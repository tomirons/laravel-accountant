<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Http\Request;
use TomIrons\Accountant\Clients\Charge;

class ChargesController extends Controller
{
    /**
     * Return all charges.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $charges = $this->factory->charge->all()
            ->currentPage($request->get('page', 1))
            ->paginate($request->url(), $request->query());

        return view('accountant::charges.index', compact('charges'));
    }

    /**
     * Show information about a charge.
     *
     * @param Request $request
     * @return \Stripe\Charge
     */
    public function show($id)
    {
        $charge = $this->factory->charge->retrieve($id);
        $balance = $this->factory->balance_transaction->retrieve($charge->balance_transaction);

        return view('accountant::charges.show', compact('charge', 'balance'));
    }
}
