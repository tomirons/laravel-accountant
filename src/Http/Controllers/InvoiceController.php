<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InvoiceController extends Controller
{
    /**
     * Return all invoices.
     *
     * @param Request $request
     * @param null $type
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $type = null, $id = null)
    {
        $parameters = $type && $id ? [$type => $id] : [];
        $invoices = $this->factory->invoice->list($parameters)
            ->currentPage($request->get('page', 1))
            ->paginate($request->url(), $request->query());


        return view('accountant::invoices.index', compact('invoices'));
    }

    /**
     * Show information about a specific invoice.
     *
     * @param $id
     */
    public function show($id)
    {
        $invoice = $this->factory->invoice->retrieve($id);
        $invoice->customer = $this->factory->customer->retrieve($invoice->customer);
        $invoice->items = new Collection($invoice->lines->all()->data);

        return view('accountant::invoices.show', compact('invoice'));
    }
}