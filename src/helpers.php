<?php

if (! function_exists('format_amount')) {
    /**
     * Format a stripe currency amount into a readable string.
     *
     * @param int $number
     * @return string
     */
    function format_amount(int $number)
    {
        return number_format($number / 100, 2);
    }
}