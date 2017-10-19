<?php

namespace TomIrons\Accountant;

use Illuminate\Support\Facades\File;

class Accountant
{
    /**
     * Format a stripe currency amount into a readable string.
     *
     * @param int $number
     * @param int $decimals
     * @return string
     */
    public function formatAmount(int $number, int $decimals = 2)
    {
        return number_format($number / 100, $decimals);
    }

    /**
     * Format a stripe timestamp amount into a readable format.
     *
     * @param int $timestamp
     * @param string $format
     * @return string
     */
    public function formatDate(int $timestamp, string $format = 'Y/m/d h:i:s')
    {
        return \Carbon\Carbon::createFromTimestamp($timestamp)->format($format);
    }

    /**
     * Format the plan name and interval into a readable string.
     *
     * @param $plan
     * @return string
     */
    public function planToReadable($plan)
    {
        $interval = $plan->interval_count > 1 ? ' every '.$plan->interval_count.' '.str_plural($plan->interval) : '/'.$plan->interval;

        return "{$plan->name} (\${$this->formatAmount($plan->amount)}{$interval})";
    }

    /**
     * Check if the "refresh" file exists.
     *
     * @return bool
     */
    public function isCacheRefreshing()
    {
        return File::exists(storage_path('laravel-accountant/refresh'));
    }
}
