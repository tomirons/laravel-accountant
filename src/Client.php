<?php

namespace TomIrons\Accountant;

use http\Exception\BadMethodCallException;
use Stripe\Stripe;
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
     * Available Stripe methods.
     *
     * @var array
     */
    protected $methods;

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
     * Call method on the stripe class if it doesn't exist. Since all clients will have subset of same functions
     * we don't have to define the functions of the client. This function will directly call the function on the
     * respective client.
     *
     * @param $method
     * @param null $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (in_array($method, $this->methods)) {
            return $this->getStripeClass()::$method(...$args);
        }

        throw new \BadMethodCallException("Method '{$method}' is not in the list of allowed methods.");
    }

    /**
     * Gets the name of the Stripe Client name
     *
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
        return app('Stripe\\' . studly_case($this->getClientName()));
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
