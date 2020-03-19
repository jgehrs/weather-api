<?php namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

/**
 * Class WeatherService
 * @desc Service class to make external API calls to retrieve current weather data, just the wind in this case
 */
class WeatherService
{
    private static $openWeatherAppId = 'a20f8c55ec43972234889ddf6e48f07f';

    /**
    * Gets wind data for a zip code
    * @param string the zip code
    *
    * @return json
    */
    public static function getByZip($zip) {

        // Check the cache first, if it's there just return it, else make the api call and cache the results
        if (Cache::has($zip)){
            return Cache::get($zip);
        }else{
            // I've tried generating multiple api keys and none of them are valid, which is why I'm hard coding the sample endpoint below
            // $endpoint = 'api.openweathermap.org/data/2.5/weather?zip=' . $zip . ',us&appid=' . $openWeatherAppId;

            $endpoint = 'https://samples.openweathermap.org/data/2.5/weather?zip=93101,us&appid=a20f8c55ec43972234889ddf6e48f07f';

            $client = new Client();
            $response = $client->request('GET', $endpoint);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            $weatherData = json_decode($body);

            // Build the response with only the data we need
            $windData['cityName'] = $weatherData->name;
            $windData['zipCode'] = $zip;
            $windData['windSpeed'] = $weatherData->wind->speed;
            $windData['windDirection'] = $weatherData->wind->deg;

            // Cache the data for 15 minutes
            Cache::put($zip, $windData, 15);

            return $windData;
        }
    }
}