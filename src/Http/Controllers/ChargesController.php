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
    public function index(Request $request, Charge $charge)
    {
        return $charge->all()
            ->currentPage($request->get('page', 1))
            ->paginate($request->url(), $request->query());
    }
}
