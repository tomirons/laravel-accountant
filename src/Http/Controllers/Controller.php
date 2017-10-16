<?php

namespace TomIrons\Accountant\Http\Controllers;

use TomIrons\Accountant\Cabinet;
use TomIrons\Accountant\ClientFactory;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Instance of the Cabinet.
     *
     * @var Cabinet
     */
    protected $cabinet;

    /**
     * Instance of the client.
     *
     * @var object
     */
    protected $factory;

    /**
     * Controller constructor.
     *
     * @param object $factory
     */
    public function __construct(Cabinet $cabinet, ClientFactory $factory)
    {
        $this->cabinet = $cabinet;
        $this->factory = $factory;
    }
}
