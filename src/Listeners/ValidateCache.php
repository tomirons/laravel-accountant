<?php

namespace TomIrons\Accountant\Listeners;

use TomIrons\Accountant\Jobs\PutToCache;
use TomIrons\Accountant\Events\CacheRefreshStarted;

class ValidateCache
{
    /**
     * Handle the event.
     *
     * @param CacheRefreshStarted $event
     * @return void
     */
    public function handle(CacheRefreshStarted $event)
    {
        collect($event->types)
            ->reject(function ($type) {
                return cache()->has('accountant.' . $type);
            })
            ->each(function ($type) use ($event) {
                dispatch(new PutToCache($type, $event->types))->onQueue(config('accountant.queue'));
            });
    }
}
