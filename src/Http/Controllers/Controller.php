<?php

namespace TomIrons\Accountant\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use TomIrons\Accountant\ClientFactory;

class Controller extends BaseController
{
    /**
     * Instance of the client.
     *
     * @var Object
     */
    protected $factory;

    /**
     * Controller constructor.
     * @param Object $factory
     */
    public function __construct(ClientFactory $factory)
    {
        $this->factory = $factory;
    }
}
