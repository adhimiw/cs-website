<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PageVisit;

class ReferrerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_tracks_referrer_and_utm_parameters(): void
    {
        $payload = [
            'url' => 'http://localhost:5173/our-services',
            'referrer' => 'http://localhost:8000/test_referrer.html',
            'utm_source' => 'test_ref_html',
            'utm_medium' => 'banner',
            'utm_campaign' => 'uat_check',
        ];

        $response = $this->postJson('/api/track-visit', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('page_visits', [
            'url' => 'http://localhost:5173/our-services',
            'referrer_url' => 'http://localhost:8000/test_referrer.html',
            'referrer_source' => 'test_ref_html',
            'utm_source' => 'test_ref_html',
            'utm_medium' => 'banner',
            'utm_campaign' => 'uat_check',
        ]);
    }
}
