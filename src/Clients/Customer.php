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
     * Name of the stripe class to retrieve.
     *
     * @var string
     */
    protected $name = 'Customer';
}