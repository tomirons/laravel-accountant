<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CustomerController extends Controller
{
    /**
     * Return all customers.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $customers = $this->factory->customer->all()
            ->currentPage($request->get('page', 1))
            ->paginate($request->url(), $request->query());

        foreach ($customers as $customer) {
            $customer->card = (new Collection($customer->sources->data))->first();
        }

        return view('accountant::customers.index', compact('customers'));
    }

    /**
     * Show information about a specific customer.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $customer = $this->factory->customer->retrieve($id);
        $customer->cards = new Collection($customer->sources->data);
        $customer->subscriptions = new Collection($customer->subscriptions->data);
        $customer->invoices = new Collection($customer->invoices()->data);

        return view('accountant::customers.show', compact('customer'));
    }
}