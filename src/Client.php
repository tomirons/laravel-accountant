<?php

namespace TomIrons\Accountant;

use Stripe\Stripe;
use LogicException;
use Illuminate\Support\Collection;
use Stripe\Charge as StripeCharge;
use Stripe\Balance as StripeBalance;
use Stripe\Customer as StripeCustomer;
use Stripe\Collection as StripeCollection;
use Stripe\BalanceTransaction as StripeTransaction;

class Client
{
    /**
     * Current data object.
     *
     * @var object
     */
    protected $class;

    /**
     * The number of items to be shown per page.
     *
     * @var int
     */
    private $limit;

    /**
     * Create a new Client instance.
     *
     * @return void
     */
    public function __construct(Stripe $stripe)
    {
        $stripe->setApiKey(config('services.stripe.key'));
        $this->limit = config('accountant.limit', 10);
    }

    /**
     * Retrieve the balance object.
     *
     * @return $this
     */
    public function balance()
    {
        return $this->setClass(StripeBalance::retrieve());
    }

    /**
     * Retrieve all charges.
     *
     * @return $this
     */
    public function charges()
    {
        return $this->setClass(StripeCharge::all([
            'limit' => $this->limit(),
            'ending_before' => $this->endPoint(),
            'starting_after' => $this->startPoint(),
        ]));
    }

    /**
     * Retrieve all customers.
     *
     * @return $this
     */
    public function customers()
    {
        return $this->setClass(StripeCustomer::all([
            'limit' => $this->limit(),
            'ending_before' => $this->endPoint(),
            'starting_after' => $this->startPoint(),
        ]));
    }

    /**
     * Retrieve recent transactions.
     *
     * @return $this
     */
    public function transactions()
    {
        return $this->setClass(StripeTransaction::all([
            'limit' => $this->limit(),
            'ending_before' => $this->endPoint(),
            'starting_after' => $this->startPoint(),
        ]));
    }

    /**
     * Paginate the results.
     *
     * @return Paginator
     */
    public function paginate()
    {
        if ($this->class->object !== 'list' || ! is_array($this->class->data)) {
            throw new LogicException("Object must be a 'list' in order to paginate.");
        }

        $collection = new Collection($this->class->data);

        $this->setPoints($collection->first()->id, $collection->last()->id);

        return new Paginator(
            $collection,
            $this->limit(),
            $this->currentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    /**
     * Set the class for the client.
     *
     * @param StripeCollection $class
     * @return $this
     */
    protected function setClass(StripeCollection $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    private function currentPage()
    {
        return (int) request('page', 1);
    }

    /**
     * Get the 'starting_after' value for the API call.
     *
     * @return string
     */
    private function startPoint()
    {
        return request('starting_after', null);
    }

    /**
     * Get the 'ending_before' value for the API call.
     *
     * @return string
     */
    private function endPoint()
    {
        return request('ending_before', null);
    }

    /**
     * Get the number of items shown per page.
     *
     * @return int
     */
    private function limit()
    {
        return $this->limit;
    }

    /**
     * Get the number of items to be retrieved from the API call.
     *
     * @return int
     */
    private function apiLimit()
    {
        return $this->limit() + 1;
    }

    /**
     * Set the previous and next starting points.
     *
     * @param string $old
     * @param string $new
     */
    private function setPoints(string $before, string $after)
    {
        session()->put('accountant.api.ending_before', $before);
        session()->put('accountant.api.starting_after', $after);
    }
}
