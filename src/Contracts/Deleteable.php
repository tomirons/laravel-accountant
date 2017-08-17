<?php

namespace TomIrons\Accountant\Contracts;

interface Deleteable
{
    /**
     * Delete an object.
     *
     * @param $id
     * @return $this
     */
    public function delete($id);
}