<?php

namespace TomIrons\Accountant;

class Accountant
{
    /**
     * Format a stripe currency amount into a readable string.
     *
     * @param int $number
     * @param int $decimals
     * @return string
     */
    public static function formatAmount(int $number, int $decimals = 2)
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
    public static function formatDate(int $timestamp, string $format = 'Y/m/d h:i:s')
    {
        return \Carbon\Carbon::createFromTimestamp($timestamp)->format($format);
    }
}