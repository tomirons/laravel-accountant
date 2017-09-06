<?php

namespace TomIrons\Accountant\Http\Controllers;

class BalanceController extends Controller
{
    /**
     * Return the Balance object.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->client->balance();
    }
}
