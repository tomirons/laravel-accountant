<?php

namespace TomIrons\Accountant;

use Stripe\Stripe;
use LogicException;
use Illuminate\Support\Collection;

abstract class Client
{
    /**
     * Current data object.
     *
     * @var object
     */
    protected $class;

    /**
     * Current page/
     *
     * @var int
     */
    protected $currentPage;

    /**
     * Create a new Client instance.
     *
     * @return void
     */
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.key'));
    }

    /**
     * Call the corresponding method on the client, if we're calling the 'retrieve' method then pass through the object id.
     *
     * @param $method
     * @param null $args
     * @return mixed
     */
    public function __call($method, $args = null)
    {
        if ($method == 'retrieve') {
            return $this->getStripeClass()::$method($args[0]);
        }

        return $this->getStripeClass()::$method($args);
    }

    /**
     * Gets the name of the Stripe Client name
     * @return string
     */
    abstract public function getClientName(): string;

    /**
     * Return the stripe class for the client.
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getStripeClass()
    {
        return app('Stripe\\' . ucfirst($this->getClientName()));
    }

    /**
     * Set the class for the pagination.
     *
     * @return $this
     */
    public function all()
    {
        $this->class = $this->getStripeClass()::all([
            'limit' => $this->limit(),
            'ending_before' => $this->end(),
            'starting_after' => $this->start(),
        ]);

        return $this;
    }

    /**
     * Paginate the results.
     *
     * @param string $path
     * @param string $query
     * @return Paginator
     */
    public function paginate($path, $query): Paginator
    {
        if ($this->class->object !== 'list' || ! is_array($this->class->data)) {
            throw new LogicException("Object must be a 'list' in order to paginate.");
        }

        $collection = new Collection($this->class->data);

        $this->points($collection->first()->id, $collection->last()->id);

        return new Paginator(
            $collection,
            $this->limit(),
            $this->currentPage(),
            compact('path', 'query')
        );
    }

    /**
     * Get the 'starting_after' value for the API call.
     *
     * @return string
     */
    protected function start()
    {
        return request('start', null);
    }

    /**
     * Get the 'ending_before' value for the API call.
     *
     * @return string
     */
    protected function end()
    {
        return request('end', null);
    }

    /**
     * Get the number of items shown per page.
     *
     * @return int
     */
    protected function limit(): int
    {
        return config('accountant.pagination.limit', 10);
    }

    /**
     * Get / set the start and end points.
     *
     * @param string $start
     * @param string|null $end
     */
    protected function points($start, $end = null)
    {
        if (str_contains($start, ['start', 'end'])) {
            return session()->get('accountant.api.' . $start);
        }

        session()->put('accountant.api.start', $end);
        session()->put('accountant.api.end', $start);
    }

    /**
     * Get / set the current page.
     *
     * @param string|int $page
     * @return $this
     */
    public function currentPage($page = null)
    {
        if (is_null($page)) {
            return $this->currentPage;
        }

        $this->currentPage = $page;

        return $this;
    }
}
