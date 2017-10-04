<?php

namespace TomIrons\Accountant\Http\Controllers;

use TomIrons\Accountant\Cabinet;
use TomIrons\Accountant\ClientFactory;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Instance of the client.
     *
     * @var object
     */
    protected $factory;

    /**
     * Instance of the Cabinet.
     *
     * @var Cabinet
     */
    protected $cabinet;

    /**
     * Controller constructor.
     * @param object $factory
     */
    public function __construct(ClientFactory $factory, Cabinet $cabinet)
    {
        $this->factory = $factory;
        $this->cabinet = $cabinet;
    }
}
