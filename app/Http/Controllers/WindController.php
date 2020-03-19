<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;

/**
 * Class WindController
 * @desc WindController end point GET
 */
class WindController extends Controller
{
    /**
    * Gets wind data for a zip code
    * @param string the zip code
    *
    * @return json
    */
    public function show($zip)
    {
        return WeatherService::getByZip($zip);
    }
}