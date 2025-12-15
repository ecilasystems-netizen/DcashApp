<?php

use App\Models\CompanyBankAccount;

if (!function_exists('format_number_short')) {
    function format_number_short($number)
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1).'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1).'K';
        }
        return $number;
    }
}

if (!function_exists('get_browser_info')) {
    function get_browser_info()
    {
        $agent = new Jenssegers\Agent\Agent();
        return $agent->browser().' '.$agent->version($agent->browser());
    }
}

if (!function_exists('getBankname')) {
    function getBankname($id)
    {
        $banks = CompanyBankAccount::find($id);
        return $banks->bank_name ?? 'Unknown Bank';
    }
}
// Add more helper functions as needed
