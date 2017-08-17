<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Http\Request;
use TomIrons\Accountant\Clients\Charge;

class ChargesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Charge $charge)
    {
        $this->client = $charge;
    }

    /**
     * Return all charges.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->client->all()
            ->currentPage($request->get('page', 1))
            ->paginate($request->url(), $request->query());
    }

    /**
     * Show information about a charge.
     *
     * @param Request $request
     * @return \Stripe\Charge
     */
    public function show($id)
    {
        return $this->client->retrieve($id);
    }
}
