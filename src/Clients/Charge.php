<?php

namespace TomIrons\Accountant\Clients;

use TomIrons\Accountant\Client;
use Stripe\Charge as StripeCharge;
use TomIrons\Accountant\Contracts\Client as ClientContract;

class Charge extends Client implements ClientContract
{
    public function all()
    {
        return $this->setClass(StripeCharge::all([
            'limit' => $this->limit(),
            'ending_before' => $this->end(),
            'starting_after' => $this->start(),
        ]));
    }

    public function create($data)
    {
        // TODO: Implement create() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function retrieve($id)
    {
        // TODO: Implement retrieve() method.
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }
}