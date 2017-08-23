<?php

namespace TomIrons\Accountant\Clients;


use TomIrons\Accountant\Client;

class Balance extends Client
{
    /**
     * Name of the stripe class to retrieve.
     *
     * @var string
     */
    protected $name = 'Balance';

    /**
     * Gets the name of the Stripe Client name
     * @return string
     */
    function getClientName(): string
    {
        return 'Balance';
    }
}