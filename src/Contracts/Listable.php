<?php

namespace TomIrons\Accountant\Contracts;

interface Listable
{
    /**
     * Get all of the objects.
     *
     * @return $this
     */
    public function all();
}