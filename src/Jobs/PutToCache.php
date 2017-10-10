<?php

namespace TomIrons\Accountant\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use TomIrons\Accountant\ClientFactory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
     * PushToCache constructor.
     */
    public function __construct($type)
    {
        $this->type = $type;
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
    }
}
