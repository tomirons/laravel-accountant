<?php

namespace TomIrons\Accountant\Clients;

use Stripe\BalanceTransaction;
use TomIrons\Accountant\Contracts\Client as ClientContract;

class Balance implements ClientContract
{
    /**
     * Name of the stripe class to retrieve.
     *
     * @var string
     */
    protected $name = 'Balance';
}