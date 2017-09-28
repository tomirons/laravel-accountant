<?php

namespace TomIrons\Accountant\Clients;

use Illuminate\Support\Collection;
use TomIrons\Accountant\Client;
use Stripe\Invoice;

class Subscription extends Client
{
    /**
     * Available Stripe methods.
     *
     * @var array
     */
    protected $methods = [
        'all', 'retrieve',
    ];

    /**
     * Gets the name of the Stripe Client name.
     *
     * @return string
     */
    public function getClientName(): string
    {
        return 'Subscription';
    }

    /**
     * Return a collection of invoices for the subscription.
     *
     * @param $id
     * @return Collection
     */
    public function invoices($id)
    {
        return new Collection(Invoice::all([
            'subscription' => $id
        ])->data);
    }
}