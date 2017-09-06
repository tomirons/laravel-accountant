<?php

namespace TomIrons\Accountant\Clients;

use TomIrons\Accountant\Client;

class Customer extends Client
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
     * @return string
     */
    public function getClientName(): string
    {
        return 'Customer';
    }
}
