<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Testimonial;
use Filament\Tables\Columns\ImageColumn;

// Test BlogPost
$post = BlogPost::first();
if ($post) {
    $col1 = ImageColumn::make('image');
    $col1->record($post);
    echo "BlogPost raw image: " . $post->image . PHP_EOL;
    echo "BlogPost ImageColumn URL (default disk): " . $col1->getImageUrl($post->image) . PHP_EOL;
    
    $col1_disk = ImageColumn::make('image')->disk('public');
    $col1_disk->record($post);
    echo "BlogPost ImageColumn URL (public disk): " . $col1_disk->getImageUrl($post->image) . PHP_EOL;
} else {
    echo "No BlogPost found!" . PHP_EOL;
}

// Test Service
$service = Service::first();
if ($service) {
    $col2 = ImageColumn::make('image');
    $col2->record($service);
    echo "Service raw image: " . $service->image . PHP_EOL;
    echo "Service ImageColumn URL (default disk): " . $col2->getImageUrl($service->image) . PHP_EOL;
    
    $col2_disk = ImageColumn::make('image')->disk('public');
    $col2_disk->record($service);
    echo "Service ImageColumn URL (public disk): " . $col2_disk->getImageUrl($service->image) . PHP_EOL;
}

// Test Testimonial
$testimonial = Testimonial::first();
if ($testimonial) {
    $col3 = ImageColumn::make('image');
    $col3->record($testimonial);
    echo "Testimonial raw image: " . $testimonial->image . PHP_EOL;
    echo "Testimonial ImageColumn URL (default disk): " . $col3->getImageUrl($testimonial->image) . PHP_EOL;
    
    $col3_disk = ImageColumn::make('image')->disk('public');
    $col3_disk->record($testimonial);
    echo "Testimonial ImageColumn URL (public disk): " . $col3_disk->getImageUrl($testimonial->image) . PHP_EOL;
}
