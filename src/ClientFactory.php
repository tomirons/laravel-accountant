<?php

namespace TomIrons\Accountant;

use Illuminate\Contracts\Foundation\Application;

class ClientFactory
{
    /**
     * Instance of the application.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new instance of the factory.
     *
     * @param $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Magic method for retrieving a client.
     *
     * @param $method
     * @return mixed
     */
    public function __get($method)
    {
        $class = __NAMESPACE__ . '\\Clients\\' . ucfirst($method);

        return $this->app->make($class);
    }

}