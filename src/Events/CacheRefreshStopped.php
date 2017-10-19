<?php

namespace TomIrons\Accountant\Events;

use Illuminate\Filesystem\Filesystem;

class CacheRefreshStopped
{
    /**
     * Instance of the filesystem.
     *
     * @var Filesystem
     */
    public $filesystem;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }
}
