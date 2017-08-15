<?php

namespace TomIrons\Accountant\Http\Controllers;

use TomIrons\Accountant\Client;
use Illuminate\Routing\Controller as BaseController;

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
