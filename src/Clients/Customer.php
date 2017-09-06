<?php

namespace TomIrons\Accountant\Clients;

use TomIrons\Accountant\Client;
use TomIrons\Accountant\Contracts\Client as ClientContract;
use TomIrons\Accountant\Contracts\Deleteable;
use TomIrons\Accountant\Contracts\Listable;
use Stripe\Customer as StripeCustomer;

class Customer extends Client
{
    /**
     * Available Stripe methods.
     *
     * @var array
     */
    protected $methods = [
        'all', 'retrieve'
    ];

    /**
     * Gets the name of the Stripe Client name
     * @return string
     */
    public function getClientName(): string
    {
        return 'Customer';
    }
}