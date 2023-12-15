<?php

namespace App\Http\Controllers;

/**
 * The MapsController class extends Laravel's base Controller class.
 * It handles the redirection to mapping services based on the specified type (OpenStreetMap or Google Maps) and a provided address.
 */
class MapsController extends Controller
{
    /**
     * The redirectToMaps method constructs a URL for either OpenStreetMap or Google Maps based on the specified $type 
     * and the provided $address. It then redirects the user to the generated map URL.
     */
    public function redirectToMaps($type, $address)
    {
        $url = "https://www.";
        $url = ($type == 'osm') ? $url . "openstreetmap.org/search?query=" : $url . "google.com/maps/search/?api=1&query=";
        $url = $url . urlencode($address);
        return redirect()->away($url);
    }
}