<?php

namespace TomIrons\Accountant\Events;

use Illuminate\Filesystem\Filesystem;
use TomIrons\Accountant\Cabinet;

class CacheRefreshStarted
{
    /**
     * Instance of the filesystem.
     *
     * @var Filesystem
     */
    public $filesystem;

    /**
     * List of types
     *
     * @var array
     */
    public $types;

    /**
     * Create a new event instance.
     *
     * @param array $types
     * @return void
     */
    public function __construct($types)
    {
        $this->types = $types;
        $this->filesystem = new Filesystem;
    }
}