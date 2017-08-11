<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use TomIrons\Accountant\Client;

class Controller extends BaseController
{
    /**
     * Instance of the Client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
