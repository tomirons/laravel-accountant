<?php

namespace TomIrons\Accountant\Contracts;

interface Client
{
    /**
     * Get an object.
     *
     * @param $id
     * @return $this
     */
    public function retrieve($id);

    /**
     * Create a new object.
     * @param $data
     * @return $this
     */
    public function create($data);

    /**
     * Update an object.
     *
     * @param $id
     * @param $data
     * @return $this
     */
    public function update($id, $data);
}