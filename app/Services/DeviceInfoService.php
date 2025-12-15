<?php

// app/Services/DeviceInfoService.php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class DeviceInfoService
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function getDeviceInfo(): array
    {
        // Get browser info
        $browser = $this->agent->browser().' '.$this->agent->version($this->agent->browser());

        // Get location using IP
        $position = Location::get(Request::ip());
        $location = $position ? $position->cityName.', '.$position->countryName : 'Unknown';

        return [
            'browser' => $browser,
            'location' => $location
        ];
    }
}
