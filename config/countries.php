<?php
// File: `config/countries.php`
// Returns country data and generates a FlagCDN URL per country using lowercase ISO code.
$countries = [
    ['name' => 'Nigeria', 'code' => 'NG', 'dial_code' => '+234'],
    ['name' => 'Ghana', 'code' => 'GH', 'dial_code' => '+233'],
    ['name' => 'Kenya', 'code' => 'KE', 'dial_code' => '+254'],
    ['name' => 'Cameroon', 'code' => 'CM', 'dial_code' => '+237'],
    ['name' => 'Philippines', 'code' => 'PH', 'dial_code' => '+63'],
    ['name' => 'Zimbabwe', 'code' => 'ZW', 'dial_code' => '+263'],
    ['name' => 'Togo', 'code' => 'TG', 'dial_code' => '+228'],
    ['name' => 'Benin', 'code' => 'BJ', 'dial_code' => '+229'],
];

// Build flag URL using FlagCDN 80x60 PNGs
foreach ($countries as &$c) {
    $c['flag'] = 'https://flagcdn.com/80x60/'.strtolower($c['code']).'.png';
}
unset($c);

return $countries;
