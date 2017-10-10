<?php

namespace TomIrons\Accountant;

use Carbon\Carbon;
use Facades\TomIrons\Accountant\Accountant;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use TomIrons\Accountant\Jobs\PutToCache;

class Cabinet
{
    /**
     * Instance of the cache driver.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $driver;

    /**
     * Instance of the request.
     *
     * @var Illuminate\Http\Request;
     */
    protected $request;

    /**
     * Array of object types.
     *
     * @var array
     */
    protected $types = ['balance_transaction', 'charge', 'customer', 'invoice', 'subscription'];

    /**
     * Cabinet constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->driver = Cache::driver(config('accountant.config.driver', 'file'));

        $this->validate();
    }

    /**
     * Delete and reload data for all/specific type(s).
     *
     * @param string|null $type
     * @return $this
     */
    public function refresh($type = null)
    {
        if ($type) {
            $this->refile($type);
        } else {
            foreach ($this->types as $type) {
                $this->refile($type);
            }
        }

        return $this;
    }

    /**
     * Return the collection of a specific type from the cache.
     *
     * @param string $type
     * @return Illuminate\Support\Collection
     */
    public function search($type)
    {
        $start = session('accountant.start', Carbon::now());
        $end = session('accountant.end', Carbon::now()->addWeek());

        return (new Collection($this->driver->get('accountant.' . $type)))
            ->where('created', '>=', $start->getTimestamp())
            ->where('created', '<=', $end->getTimestamp());
    }

    /**
     * Clear all/specific object type(s) from the cache.
     *
     * @param string|null $type
     * @return $this
     */
    public function empty($type = null)
    {
        if ($type) {
            $this->driver->delete('accountant.' . $type);
        } else {
            $this->driver->deleteMultiple(array_map(function ($type) {
                return 'accountant.' . $type;
            }, $this->types));
        }

        return $this;
    }

    /**
     * Dispatch job to store data.
     *
     * @param string $type
     * @return void
     */
    protected function store($type)
    {
        dispatch(new PutToCache($type));
    }

    /**
     * Clear and reload data for a specific type.
     *
     * @param $type
     * @return void
     */
    protected function refile($type)
    {
        $this->empty($type)->store($type);
    }

    /**
     * Generate an array of data.
     *
     * @return array
     */
    public function generate()
    {
        $balance = $this->search('balance_transaction');
        $charges = $this->search('charge');
        $customers = $this->search('customer');
        $subscriptions = $this->search('subscription');

        return [
            'balance' => [
                'total' => Accountant::formatAmount($balance->sum->amount)
            ],
            'charges' => [
                'count' => $charges->count(),
                'total' => Accountant::formatAmount($charges->sum->amount)
            ],
            'customers' => [
                'count' => $customers->count()
            ]
        ];
    }

    /**
     * Check the cache if each type exists, refresh if it doesn't.
     *
     * @return void
     */
    protected function validate()
    {
        foreach ($this->types as $type) {
            if (!$this->driver->has('accountant.' . $type)) {
                $this->refresh($type);
            }
        }
    }
}