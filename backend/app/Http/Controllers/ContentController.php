<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $contents = SiteContent::all()->mapWithKeys(function ($item) {
            $value = $item->value;
            if ($item->type === 'image' && $value && !str_starts_with($value, 'http') && !str_starts_with($value, '/')) {
                $value = '/storage/' . $value;
            }
            return ["{$item->page}.{$item->section}.{$item->key}" => $value];
        });

        $settings = Setting::all()->mapWithKeys(function ($item) {
            return ["settings.{$item->key}" => $item->value];
        });

        $services = \App\Models\Service::orderBy('order')->get()->map(function ($service) {
            $imageUrl = $service->image;
            if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                $imageUrl = '/storage/' . $imageUrl;
            }
            return [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'image' => $imageUrl,
                'tags' => $service->tags ?? [],
                'type' => $service->type,
                'order' => $service->order,
            ];
        });

        $blogs = \App\Models\BlogPost::orderByDesc('published_at')->get()->map(function ($post) {
            $imageUrl = $post->image;
            if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                $imageUrl = '/storage/' . $imageUrl;
            }
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'image' => $imageUrl,
                'author' => $post->author,
                'published_at' => $post->published_at ? $post->published_at->toIso8601String() : null,
            ];
        });

        $testimonials = \App\Models\Testimonial::all()->map(function ($t) {
            $imageUrl = $t->image;
            if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                $imageUrl = '/storage/' . $imageUrl;
            }
            return [
                'id' => $t->id,
                'text' => $t->text,
                'author' => $t->author,
                'image' => $imageUrl,
                'rating' => $t->rating,
            ];
        });

        return response()->json([
            'contents' => $contents,
            'settings' => $settings,
            'services' => $services,
            'blogs' => $blogs,
            'testimonials' => $testimonials,
        ]);
    }

    public function trackVisit(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
            'referrer' => 'nullable|string',
            'utm_source' => 'nullable|string',
            'utm_medium' => 'nullable|string',
            'utm_campaign' => 'nullable|string',
            'utm_term' => 'nullable|string',
            'utm_content' => 'nullable|string',
        ]);

        $ip = $request->ip();

        // Referrer source parsing
        $referrerUrl = $request->input('referrer');
        $referrerSource = 'direct';

        if ($referrerUrl) {
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
        }

        // If UTM source is present, override direct referrer
        if ($request->filled('utm_source')) {
            $referrerSource = $request->input('utm_source');
        }

        // Geolocation
        $country = null;
        $city = null;
        
        if ($ip && $ip !== '127.0.0.1' && $ip !== '::1' && !str_starts_with($ip, '192.168.') && !str_starts_with($ip, '10.')) {
            try {
                if (class_exists(\Stevebauman\Location\Facades\Location::class)) {
                    if ($position = \Stevebauman\Location\Facades\Location::get($ip)) {
                        $country = $position->countryName;
                        $city = $position->cityName;
                    }
                }
            } catch (\Exception $e) {
                // Fail silently for geo-ip
            }
        }

        $visit = \App\Models\PageVisit::create([
            'ip_address' => $ip,
            'url' => $request->input('url'),
            'referrer_url' => $referrerUrl,
            'referrer_source' => $referrerSource,
            'utm_source' => $request->input('utm_source'),
            'utm_medium' => $request->input('utm_medium'),
            'utm_campaign' => $request->input('utm_campaign'),
            'utm_term' => $request->input('utm_term'),
            'utm_content' => $request->input('utm_content'),
            'country' => $country,
            'city' => $city,
        ]);

        return response()->json(['success' => true, 'id' => $visit->id]);
    }
}
