<?php

namespace TomIrons\Accountant\Clients;

use TomIrons\Accountant\Client;
use Stripe\Charge as StripeCharge;
use TomIrons\Accountant\Contracts\Client as ClientContract;
use TomIrons\Accountant\Contracts\Deleteable;
use TomIrons\Accountant\Contracts\Listable;

class Charge extends Client implements ClientContract, Deleteable, Listable
{
    /**
     * Get all of the charges.
     *
     * @return $this
     */
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

    /**
     * Get a single charge.
     *
     * @param $id
     * @return StripeCharge
     */
    public function retrieve($id)
    {
        return StripeCharge::retrieve($id);
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}