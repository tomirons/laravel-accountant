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
     * The number of items to be shown per page.
     *
     * @var int
     */
    protected $limit;

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
        $this->limit = config('accountant.limit', 10);
    }

    /**
     * Call method on the stripe class if it doesn't exist.
     *
     * @param $method
     * @param null $args
     */
    public function __call($method, $args = null)
    {
        $this->getStripeClass()::$method($args);
    }

    /**
     * Gets the name of the Stripe Client name
     * @return string
     */
    abstract function getClientName(): string;

    /**
     * Return the stripe class for the client.
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getStripeClass()
    {
        return app('Stripe\\' . ucfirst($this->name));
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
        return $this->limit;
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
