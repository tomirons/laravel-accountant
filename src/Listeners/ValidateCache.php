<?php

namespace TomIrons\Accountant\Listeners;

use TomIrons\Accountant\Events\CacheRefreshStarted;
use TomIrons\Accountant\Jobs\PutToCache;

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
        foreach ($event->types as $type) {
            if (!cache()->has('accountant.'.$type)) {
                dispatch(new PutToCache($type, $event->types))->onQueue(config('accountant.queue'));
            }
        }
    }
}