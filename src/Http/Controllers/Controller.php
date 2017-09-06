<?php

namespace TomIrons\Accountant\Http\Controllers;

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
     * Controller constructor.
     * @param object $factory
     */
    public function __construct(ClientFactory $factory)
    {
        $this->factory = $factory;
    }
}
