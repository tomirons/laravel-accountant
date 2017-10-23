<?php

namespace TomIrons\Accountant\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display catch all view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('accountant::dashboard')->with([
            'stats' => $this->data(new Request),
        ]);
    }

    /**
     * Refresh all data.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refresh()
    {
        $this->cabinet->empty();

        return redirect()->back();
    }

    /**
     * Generate array based on the start and end date.
     *
     * @param Request $request
     * @return array
     */
    public function data(Request $request)
    {
        if ($request->has(['start', 'end'])) {
            session()->put([
                'accountant.start' => Carbon::createFromTimestamp($request->get('start')),
                'accountant.end' => Carbon::createFromTimestamp($request->get('end')),
            ]);
        }

        return $this->cabinet->setDates()->generate();
    }

    /**
     * Return the dates for the date filter.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'start' => session()->get('accountant.start', Carbon::now()->subMonth())->format('m/d/Y'),
            'end' => session()->get('accountant.end', Carbon::now())->format('m/d/Y')
        ];
    }
}
