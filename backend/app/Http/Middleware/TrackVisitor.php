<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageVisit;
use Stevebauman\Location\Facades\Location;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Don't track debug, assets, api, livewire, or admin routes
        if ($request->is('_debugbar*') || $request->is('admin*') || $request->is('api*') || $request->is('livewire*') || $request->is('build*')) {
            return $next($request);
        }

        $session = $request->session();

        // 1. UTM Parameters
        $utms = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        $utmData = [];
        foreach ($utms as $utm) {
            if ($request->has($utm)) {
                $utmData[$utm] = $request->query($utm);
                $session->put($utm, $request->query($utm));
            } elseif ($session->has($utm)) {
                $utmData[$utm] = $session->get($utm);
            } else {
                $utmData[$utm] = null;
            }
        }

        // 2. Referrer URL & Source
        $referrerUrl = $request->headers->get('referer');
        $referrerSource = 'direct';

        if ($referrerUrl) {
            $session->put('referrer_url', $referrerUrl);
            $host = parse_url($referrerUrl, PHP_URL_HOST);
            if ($host) {
                $host = strtolower($host);
                if (str_contains($host, 'linkedin.com')) {
                    $referrerSource = 'linkedin';
                } elseif (str_contains($host, 'google.com')) {
                    $referrerSource = 'google';
                } elseif (str_contains($host, 'facebook.com')) {
                    $referrerSource = 'facebook';
                } elseif (str_contains($host, 'instagram.com')) {
                    $referrerSource = 'instagram';
                } elseif (str_contains($host, 'youtube.com')) {
                    $referrerSource = 'youtube';
                } else {
                    $referrerSource = 'referral';
                }
            }
            $session->put('referrer_source', $referrerSource);
        } elseif ($session->has('referrer_url')) {
            $referrerUrl = $session->get('referrer_url');
            $referrerSource = $session->get('referrer_source', 'referral');
        }

        // If UTM source is present, override direct referrer
        if (!empty($utmData['utm_source'])) {
            $referrerSource = $utmData['utm_source'];
        }

        // 3. Geolocation
        $ip = $request->ip();
        // Skip local loopback IPs for geolocation lookup
        $country = null;
        $city = null;
        
        if ($ip && $ip !== '127.0.0.1' && $ip !== '::1' && !str_starts_with($ip, '192.168.') && !str_starts_with($ip, '10.')) {
            if ($position = Location::get($ip)) {
                $country = $position->countryName;
                $city = $position->cityName;
                $session->put('geo_country', $country);
                $session->put('geo_city', $city);
            }
        } else {
            $country = $session->get('geo_country');
            $city = $session->get('geo_city');
        }

        // 4. Log Visit in DB
        try {
            PageVisit::create([
                'ip_address' => $ip,
                'url' => $request->fullUrl(),
                'referrer_url' => $referrerUrl,
                'referrer_source' => $referrerSource,
                'utm_source' => $utmData['utm_source'],
                'utm_medium' => $utmData['utm_medium'],
                'utm_campaign' => $utmData['utm_campaign'],
                'utm_term' => $utmData['utm_term'],
                'utm_content' => $utmData['utm_content'],
                'country' => $country,
                'city' => $city,
            ]);
        } catch (\Exception $e) {
            // Log warning or fail silently so tracking doesn't crash the request
            report($e);
        }

        return $next($request);
    }
}
