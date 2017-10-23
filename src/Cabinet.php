<?php

namespace TomIrons\Accountant;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use TomIrons\Accountant\Events\CacheRefreshStarted;
use Facades\TomIrons\Accountant\Accountant;
use BadMethodCallException;

class Cabinet
{
    /**
     * Instance of the cache driver.
     *
     * @var Repository
     */
    public $driver;

    /**
     * Array of object types.
     *
     * @var array
     */
    public $types = ['charge', 'customer', 'invoice', 'subscription'];

    /**
     * Start date.
     *
     * @var Carbon
     */
    protected $start;

    /**
     * End date.
     *
     * @var Carbon
     */
    protected $end;

    /**
     * Create a new cabinet instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->driver = Cache::driver(config('accountant.config.driver', 'file'));

        $this->validate();
    }

    /**
     * Check the cache if each type exists, fire the refresh event if it doesn't.
     *
     * @return void
     */
    protected function validate()
    {
        if (!Accountant::isCacheRefreshing()) {
            foreach ($this->types as $type) {
                if (!$this->driver->has('accountant.'.$type)) {
                    event(new CacheRefreshStarted($this->types));
                    break;
                }
            }
        }
    }

    /**
     * Delete all data from the cache.
     *
     * @return void
     */
    public function empty()
    {
        $this->driver->deleteMultiple(array_map(function ($type) {
            return 'accountant.'.$type;
        }, $this->types));
    }

    /**
     * Set the start and end dates using the session or default values.
     *
     * @return $this
     */
    public function setDates()
    {
        $this->start = session('accountant.start', Carbon::now()->subMonth());
        $this->end = session('accountant.end', Carbon::now());

        return $this;
    }

    /**
     * Return the collection of a specific type from the cache.
     *
     * @param string $type
     * @return Collection
     */
    public function search($type)
    {
        return collect($this->driver->get('accountant.'.$type))
            ->where('created', '>=', $this->start->startOfDay()->getTimestamp())
            ->where('created', '<=', $this->end->endOfDay()->getTimestamp());
    }

    /**
     * Get collection of days to use for the chart depending on the start/end dates.
     *
     * @return Collection
     */
    protected function days()
    {
        if ($this->start->gt($this->end)) {
            return null;
        }

        $interval = CarbonInterval::day();
        $periods = new DatePeriod($this->start->copy(), $interval, $this->end->copy());

        foreach ($periods as $period) {
            $days[] = Carbon::createFromDate($period->format('Y'), $period->format('m'), $period->format('d'));
        }

        return collect($days);
    }

    /**
     * Get collection of months to use for the chart depending on the start/end dates.
     *
     * @return Collection
     */
    protected function months()
    {
        if ($this->start->gt($this->end)) {
            return null;
        }

        $interval = CarbonInterval::month();
        $periods = new DatePeriod($this->start->copy()->startOfMonth(), $interval, $this->end->copy()->endOfMonth());

        foreach ($periods as $period) {
            $months[] = $period->format('M Y');
        }

        return collect($months);
    }

    /**
     * Return array of data for the gross chart.
     *
     * @return array
     */
    protected function grossData()
    {
        $charges = $this->filterUnuccessfulCharges();

        return $this->days()->mapToGroups(function ($day) use ($charges) {
            return [$day->format('M Y') => $this->sumChargesOnDay($charges, $day)];
        });
    }

    /**
     * Return array of data for the payments chart.
     *
     * @return array
     */
    protected function paymentsData()
    {
        $charges = $this->filterUnuccessfulCharges();

        return $this->days()->mapToGroups(function ($day) use ($charges) {
            return [$day->format('M Y') => $charges->filter(function ($charge) use ($day) {
                return $charge->created >= $day->startOfDay()->getTimestamp()
                    && $charge->created <= $day->endOfDay()->getTimestamp();
            })->count()];
        });
    }

    /**
     * Return array of data for the customers chart.
     *
     * @return array
     */
    protected function customersData()
    {
        $customers = $this->search('customer');

        return $this->days()->mapToGroups(function ($day) use ($customers) {
            return [$day->format('M Y') => $customers->filter(function ($customer) use ($day) {
                return $customer->created >= $day->startOfDay()->getTimestamp()
                    && $customer->created <= $day->endOfDay()->getTimestamp();
            })->count()];
        });
    }

    /**
     * Return array of data for the refunded chart.
     *
     * @return array
     */
    protected function refundedData()
    {
        $charges = $this->search('charge');

        return $this->days()->mapToGroups(function ($day) use ($charges) {
            return [$day->format('M Y') => $charges->filter(function ($charge) use ($day) {
                return $charge->created >= $day->startOfDay()->getTimestamp()
                    && $charge->created <= $day->endOfDay()->getTimestamp();
            })->sum(function ($charge) {
                return $charge->amount_refunded / 100;
            })];
        });
    }

    /**
     * Filter out all unsuccessful charges.
     *
     * @return Collection
     */
    protected function filterUnuccessfulCharges()
    {
        return $this->search('charge')->filter(function ($charge) {
            return $charge->paid && $charge->status == 'succeeded';
        });
    }

    /**
     * Calculate the sum of all charges for a specific day.
     *
     * @param $charges
     * @param $day
     * @return string
     */
    protected function sumChargesOnDay($charges, $day)
    {
        return Accountant::formatAmount($charges->filter(function ($charge) use ($day) {
            return $this->isChargeOnDay($charge, $day);
        })->sum->amount);
    }

    /**
     * Check if the charge was made on a specific day.
     *
     * @param $charge
     * @param $day
     * @return bool
     */
    protected function isChargeOnDay($charge, $day)
    {
        return $charge->created >= $day->startOfDay()->getTimestamp()
            && $charge->created <= $day->endOfDay()->getTimestamp();
    }

    /**
     * Return array of data for a specific chart.
     *
     * @param $name
     * @return array
     */
    protected function chartData($name)
    {
        if (!method_exists($this, $method = $name.'Data')) {
            throw new BadMethodCallException("Method [$method] doesn't exist in this class.");
        }

        return [
            'labels' => $this->months()->toArray(),
            'datasets' => [
                0 => [
                    'backgroundColor' => 'rgba(59,141,236, .3)',
                    'borderColor' => 'rgb(59,141,236)',
                    'data' => $this->$method()->map(function ($item) {
                        return number_format($item->sum(), 2);
                    })->values()
                ]
            ]
        ];
    }

    /**
     * Generate an array of data.
     *
     * @return array
     */
    public function generate()
    {
        $charges = $this->search('charge');
        $customers = $this->search('customer');
        $subscriptions = $this->search('subscription');

        return [
            'charts' => [
                'customers' => $this->chartData('customers'),
                'gross' => $this->chartData('gross'),
                'payments' => $this->chartData('payments'),
                'refunded' => $this->chartData('refunded'),
            ],
            'charges' => [
                'count' => $charges->count(),
                'refunded' => Accountant::formatAmount($charges->sum->amount_refunded),
                'successful' => $charges->filter(function ($charge) {
                    return $charge->paid && $charge->status == 'succeeded';
                })->count(),
                'total' => Accountant::formatAmount($charges->filter(function ($charge) {
                    return $charge->paid && $charge->status == 'succeeded';
                })->sum->amount),
            ],
            'customers' => [
                'count' => $customers->count(),
            ],
            'subscriptions' => [
                'revenue' => $subscriptions->filter(function ($subscription) {
                    return !in_array($subscription->status, ['canceled', 'trialing'])
                        && $subscription->ended_at == null
                        && $subscription->quantity > 0;
                })->sum(function ($subscription) {
                    return $subscription->plan->amount;
                }),
            ],
        ];
    }
}
