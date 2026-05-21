<?php

namespace App\Services;

use App\Models\SiteContent;
use Illuminate\Support\Facades\Cache;

class ContentService
{
    /**
     * Retrieve a site content value by page, section, and key.
     */
    public static function get(string $keyPath, ?string $default = null): ?string
    {
        // Parse "page.section.key" format
        $parts = explode('.', $keyPath);
        if (count($parts) !== 3) {
            return $default;
        }

        [$page, $section, $key] = $parts;

        $cacheKey = "cms_content_{$page}_{$section}_{$key}";

        return Cache::rememberForever($cacheKey, function () use ($page, $section, $key, $default) {
            $content = SiteContent::where('page', $page)
                ->where('section', $section)
                ->where('key', $key)
                ->first();

            return $content ? $content->value : $default;
        });
    }

    /**
     * Clear the cache for a specific content block.
     */
    public static function clearCache(string $page, string $section, string $key): void
    {
        Cache::forget("cms_content_{$page}_{$section}_{$key}");
    }
}
