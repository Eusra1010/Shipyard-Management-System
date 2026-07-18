<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    private float $lat = 22.3569;
    private float $lon = 91.7832;

    private function apiKey(): string
    {
        return config('services.openweather.key', '');
    }

    public function getCurrentWeather(): array
    {
        return Cache::remember('weather_current', 1800, function () {
            $resp = (new Client())->get('https://api.openweathermap.org/data/2.5/weather', [
                'query'   => ['lat' => $this->lat, 'lon' => $this->lon, 'appid' => $this->apiKey(), 'units' => 'metric'],
                'timeout' => 6,
            ]);

            $d = json_decode($resp->getBody(), true);

            return [
                'temp'       => (int) round($d['main']['temp']),
                'feels_like' => (int) round($d['main']['feels_like']),
                'condition'  => ucfirst($d['weather'][0]['description']),
                'main'       => $d['weather'][0]['main'],
                'icon'       => $d['weather'][0]['icon'],
                'humidity'   => $d['main']['humidity'],
                'wind_speed' => round($d['wind']['speed'], 1),
                'fetched_at' => now()->format('H:i'),
            ];
        });
    }

    public function getForecastRisk(): array
    {
        return Cache::remember('weather_forecast', 7200, function () {
            $resp = (new Client())->get('https://api.openweathermap.org/data/2.5/forecast', [
                'query'   => ['lat' => $this->lat, 'lon' => $this->lon, 'appid' => $this->apiKey(), 'units' => 'metric', 'cnt' => 16],
                'timeout' => 6,
            ]);

            $d      = json_decode($resp->getBody(), true);
            $cutoff = now()->addHours(48)->timestamp;

            $outdoor_risk  = false;
            $earliest_risk = null;
            $risk_reason   = null;

            foreach ($d['list'] as $entry) {
                if ($entry['dt'] > $cutoff) break;

                $isRain     = ($entry['weather'][0]['main'] === 'Rain');
                $isHighWind = ($entry['wind']['speed'] > 8);

                if ($isRain || $isHighWind) {
                    $outdoor_risk = true;
                    if ($earliest_risk === null) {
                        $earliest_risk = date('D d M, H:i', $entry['dt']);
                        $risk_reason   = $isRain
                            ? 'Rain forecast'
                            : 'High wind (' . round($entry['wind']['speed'], 1) . ' m/s)';
                    }
                }
            }

            return [
                'outdoor_risk'  => $outdoor_risk,
                'earliest_risk' => $earliest_risk,
                'risk_reason'   => $risk_reason,
            ];
        });
    }

    public function getWeatherJson()
    {
        try {
            return response()->json([
                'current'       => $this->getCurrentWeather(),
                'forecast_risk' => $this->getForecastRisk(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Weather data unavailable'], 503);
        }
    }
}
