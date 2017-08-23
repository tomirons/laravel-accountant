<?php

namespace TomIrons\Accountant\Clients;

use Psy\Util\Str;
use TomIrons\Accountant\Client;
use Stripe\Charge as StripeCharge;
use TomIrons\Accountant\Contracts\Client as ClientContract;
use TomIrons\Accountant\Contracts\Deleteable;
use TomIrons\Accountant\Contracts\Listable;

class Charge extends Client
{
    /**
     * Gets the name of the Stripe Client name
     * @return string
     */
    public function getClientName(): string
    {
        return 'Charge';
    }
}