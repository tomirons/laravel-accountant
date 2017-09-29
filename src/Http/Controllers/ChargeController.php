<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Http\Request;
use TomIrons\Accountant\Clients\Charge;

class ChargeController extends Controller
{
    /**
     * Return all charges.
     *
     * @param Request $request
     * @param null $type
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $type = null, $id = null)
    {
        $parameters = $type && $id ? [$type => $id] : [];
        $charges = $this->factory->charge->all($parameters)
            ->currentPage($request->get('page', 1))
            ->paginate($request->url(), $request->query());

        return view('accountant::charges.index', compact('charges'));
    }

    /**
     * Show information about a charge.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $charge = $this->factory->charge->retrieve($id);

        if ($charge->balance_transaction) {
            $charge->balance = $this->factory->balance_transaction->retrieve($charge->balance_transaction);
        }

//        dd($charge);

        return view('accountant::charges.show', compact('charge'));
    }
}
