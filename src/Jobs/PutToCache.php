<?php

namespace TomIrons\Accountant\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use TomIrons\Accountant\ClientFactory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use TomIrons\Accountant\Events\CacheRefreshStopped;

class PutToCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Type of object we're interacting with.
     *
     * @var string
     */
    protected $type;

    /**
     * Array of all types.
     *
     * @var array
     */
    protected $types;

    /**
     * Create a new job instance.
     *
     * @param string $type
     * @param array $types
     * @return void
     */
    public function __construct($type, $types)
    {
        $this->type = $type;
        $this->types = $types;
    }

    /**
     * Add new collection of data to the cache.
     *
     * @return void
     */
    public function handle(ClientFactory $factory, $data = [])
    {
        $driver = Cache::driver(config('accountant.config.driver', 'file'));
        $items = $factory->{$this->type}->all();

        foreach ($items->autoPagingIterator() as $item) {
            $data[] = $item;
        }

        $driver->add('accountant.'.$this->type, $data, config('accountant.cache.time', 60));

        if ($this->finished()) {
            event(new CacheRefreshStopped);
        }
    }

    /**
     * Check if all data exists.
     *
     * @return bool
     */
    private function finished()
    {
        return empty(array_filter($this->types, function ($type) {
            return ! cache()->has('accountant.'.$type);
        }));
    }
}
