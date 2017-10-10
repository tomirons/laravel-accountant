<?php

namespace TomIrons\Accountant\Clients;

use TomIrons\Accountant\Client;

class Balance extends Client
{
    /**
     * Available Stripe methods.
     *
     * @var array
     */
    protected $methods = [
        'retrieve',
    ];

    /**
     * Gets the name of the Stripe Client name.
     *
     * @return string
     */
    public function getClientName(): string
    {
        return 'Balance';
    }
}
