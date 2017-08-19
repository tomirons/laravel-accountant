<?php

namespace TomIrons\Accountant;


use Illuminate\Contracts\Foundation\Application;

class ClientFactory
{
    protected $app;

    /**
     * ClientFactory constructor.
     * @param $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function __get($method)
    {
        $class = __NAMESPACE__ . '\\Clients\\' . ucfirst($method);
        return $this->app->make($class);
    }

}