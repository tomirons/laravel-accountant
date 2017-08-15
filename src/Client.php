<?php

namespace TomIrons\Accountant;

use TomIrons\Accountant\Paginator;
use Illuminate\Support\Collection;
use Stripe\Balance;
use Stripe\BalanceTransaction;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use LogicException;

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
     * @return Client
     */
    public function balance()
    {
        return $this->setClass(Balance::retrieve());
    }

    /**
     * Retrieve all charges.
     *
     * @return Client
     */
    public function charges()
    {
        return $this->setClass(Charge::all([
            'limit' => $this->limit(),
            'ending_before' => $this->endPoint(),
            'starting_after' => $this->startPoint()
        ]));
    }

    /**
     * Retrieve all customers.
     *
     * @return Client
     */
    public function customers()
    {
        return $this->setClass(Customer::all([
            'limit' => $this->limit(),
            'ending_before' => $this->endPoint(),
            'starting_after' => $this->startPoint()
        ]));
    }

    /**
     * Retrieve recent transactions.
     *
     * @return Client
     */
    public function transactions()
    {
        return $this->setClass(BalanceTransaction::all([
            'limit' => $this->limit(),
            'ending_before' => $this->endPoint(),
            'starting_after' => $this->startPoint()
        ]));
    }

    /**
     * Paginate the results.
     *
     * @return StripePaginator
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
                'query' => request()->query()
            ]
        );
    }

    /**
     * Set the class for the client.
     *
     * @param $class
     * @return $this
     */
    protected function setClass($class)
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
     * @param $old
     * @param $new
     */
    private function setPoints($before, $after)
    {
        session()->put('accountant.api.ending_before', $before);
        session()->put('accountant.api.starting_after', $after);
    }
}