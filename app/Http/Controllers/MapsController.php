<?php

namespace App\Http\Controllers;

class MapsController extends Controller
{
    public function redirectToMaps($type, $address)
    {
        $url = "https://www.";
        $url = ($type == 'osm') ? $url . "openstreetmap.org/search?query=" : $url . "google.com/maps/search/?api=1&query=";
        $url = $url . urlencode($address);
        return redirect()->away($url);
    }

}