<?php

namespace TomIrons\Accountant\Clients;

use Psy\Util\Str;
use TomIrons\Accountant\Client;
use Stripe\Charge as StripeCharge;
use TomIrons\Accountant\Contracts\Client as ClientContract;
use TomIrons\Accountant\Contracts\Deleteable;
use TomIrons\Accountant\Contracts\Listable;

class Charge extends Client implements ClientContract, Listable
{
    /**
     * Get all of the charges.
     *
     * @return $this
     */
    public function all()
    {
        return $this->class(StripeCharge::all([
            'limit' => $this->limit(),
            'ending_before' => $this->end(),
            'starting_after' => $this->start(),
        ]));
    }

    /**
     * Return a charge object.
     *
     * @param $id
     * @return StripeCharge
     */
    public function retrieve($id)
    {
        return StripeCharge::retrieve($id);
    }

    /**
     * Create a new charge.
     *
     * @param $data
     */
    public function create($data)
    {
        return StripeCharge::create($data);
    }

    /**
     * Update an existing charge.
     *
     * @param $id
     * @param $data
     * @return StripeCharge
     */
    public function update($id, $data)
    {
        return StripeCharge::update($id, $data);
    }
}