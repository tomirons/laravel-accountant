<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Return all subscriptions.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $subscriptions = $this->factory->subscription->all()
            ->currentPage($request->get('page', 1))
            ->paginate($request->url(), $request->query());

        foreach ($subscriptions as $subscription) {
            $subscription->customer = $this->factory->customer->retrieve($subscription->customer);
        }

        return view('accountant::subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show information about a specific subscription.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $subscription = $this->factory->subscription->retrieve($id);
        $subscription->customer = $this->factory->customer->retrieve($subscription->customer);
        $subscription->invoices = collect($this->factory->invoice->objects('subscription', $id));

        return view('accountant::subscriptions.show', compact('subscription'));
    }
}