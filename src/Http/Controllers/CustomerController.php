<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('accountant::customers.show', compact('customer'));
    }
}