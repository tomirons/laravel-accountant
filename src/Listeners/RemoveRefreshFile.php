<?php

namespace TomIrons\Accountant\Listeners;

use Illuminate\Filesystem\Filesystem;
use TomIrons\Accountant\Events\CacheRefreshStopped;

class RemoveRefreshFile
{
    /**
     * Handle the event.
     *
     * @param CacheRefreshStopped $event
     * @return void
     */
    public function handle(CacheRefreshStopped $event)
    {
        $event->filesystem->deleteDirectory(storage_path('laravel-accountant'));
    }
}
