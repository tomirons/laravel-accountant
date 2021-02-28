<?php

namespace TomIrons\Accountant;

use BadMethodCallException;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Stripe\Stripe;

abstract class Client
{
    /**
     * Current data object.
     *
     * @var object
     */
    protected $data;

    /**
     * Current page/.
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
     * Call method on the stripe class if it doesn't exist.
     *
     * @param $method
     * @param null $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (! $this->methods || in_array($method, $this->methods)) {
            return $this->getStripeClass()::$method(...$args);
        }

        throw new BadMethodCallException("Method [{$method}] doesn't exist or is not in the list of allowed methods.");
    }

    /**
     * Gets the name of the Stripe Client name.
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
        return app('Stripe\\'.Str::studly($this->getClientName()));
    }

    /**
     * Set the data for the pagination.
     *
     * @param array $params
     * @return $this
     */
    public function list(array $params = [])
    {
        $this->data = $this->getStripeClass()::all(array_merge($params, [
            'limit' => $this->limit(),
            'ending_before' => $this->end(),
            'starting_after' => $this->start(),
        ]));

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
        if ($this->data->object !== 'list' || ! is_array($this->data->data)) {
            throw new LogicException("Object must be a 'list' in order to paginate.");
        }

        $collection = new Collection($this->data->data);

        $this->points($collection->first()->id, $collection->last()->id);

        return new Paginator(
            $collection,
            $this->limit(),
            $this->currentPage(),
            compact('path', 'query') + [
                'morePages' => $this->getStripeClass()->all([
                    'starting_after' => $collection->last()->id,
                ])->has_more,
            ]
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
            return session()->get('accountant.api.'.$start);
        }

        session()->put([
            'accountant.api.start' => $end,
            'accountant.api.end' => $start,
        ]);
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
