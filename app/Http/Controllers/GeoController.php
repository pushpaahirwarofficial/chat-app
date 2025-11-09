<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class GeoController extends Controller
{
    public function show(Request $request)
    {
        // Test with Google DNS IP
        $ip = '8.8.8.8';  

        $position = \Stevebauman\Location\Facades\Location::get($ip);

        dd($position);
}

}
