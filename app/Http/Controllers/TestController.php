<?php

namespace App\Http\Controllers;

use App\Models\NigerianBank;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function index()
    {
        try {

//            $response = Http::get('https://nigerianbanks.xyz/');

            if ($response->successful()) {
                // $banks = $response->json();
//                $banks = json_decode(file_get_contents(base_path('/public/with-logo.json')), true);

                if (!is_array($banks)) {
                    Log::error('Failed to decode banks JSON or file not found.');
                    return response()->json(['error' => 'Failed to load banks data.'], 500);
                }

                foreach ($banks as $bankData) {
                    NigerianBank::updateOrCreate(
                        ['slug' => $bankData['slug']],
                        [
                            'code' => $bankData['code'],
                            'name' => $bankData['name'],
                            'logo' => $bankData['logo'],
                        ]
                    );
                }

                return response()->json(['message' => 'Nigerian banks have been successfully fetched and stored.']);
            }

            Log::error('Failed to fetch Nigerian banks API.',
                ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Failed to fetch data from the banks API.'], 502);

        } catch (\Exception $e) {
            Log::error('Error fetching/storing Nigerian banks: '.$e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
