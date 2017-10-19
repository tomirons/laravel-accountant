<?php

namespace TomIrons\Accountant\Listeners;

use TomIrons\Accountant\Events\CacheRefreshStarted;

class CreateRefreshFile
{
    /**
     * Handle the event.
     *
     * @param CacheRefreshStarted $event
     * @return void
     */
    public function handle(CacheRefreshStarted $event)
    {
        $path = storage_path('laravel-accountant');

        if (! $event->filesystem->exists($path)) {
            $event->filesystem->makeDirectory($path);
        }

        $event->filesystem->put($path.'/refresh', null);
    }
}
