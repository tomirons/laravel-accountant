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
     * Get all of the customers.
     *
     * @return $this
     */
    public function all()
    {
        return $this->class(StripeCustomer::all([
            'limit' => $this->limit(),
            'ending_before' => $this->end(),
            'starting_after' => $this->start(),
        ]));
    }

    /**
     * Return a customer object.
     *
     * @param $id
     * @return StripeCustomer
     */
    public function retrieve($id)
    {
        return StripeCustomer::retrieve($id);
    }

    /**
     * Create a new customer.
     *
     * @param $data
     * @return StripeCustomer
     */
    public function create($data)
    {
        return StripeCustomer::create($data);
    }

    /**
     * Update an existing customer.
     *
     * @param $id
     * @param $data
     * @return StripeCustomer
     */
    public function update($id, $data)
    {
        return StripeCustomer::update($id, $data);
    }

    /**
     * Delete a customer.
     * @param $id
     * @return StripeCustomer
     */
    public function delete($id)
    {
        return StripeCustomer::retrieve($id)->delete();
    }
}