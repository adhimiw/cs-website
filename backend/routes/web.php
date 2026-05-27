<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-symlink', function () {
    $target = request('target') ?: storage_path('app/public');
    $link = request('link') ?: public_path('storage');

    // Normalize paths to avoid slash mismatches
    $target = rtrim(str_replace('\\', '/', $target), '/');
    $link = rtrim(str_replace('\\', '/', $link), '/');

    if (is_link($link)) {
        $currentSymlinkTarget = rtrim(str_replace('\\', '/', readlink($link)), '/');
        if ($currentSymlinkTarget === $target) {
            return response()->json([
                'success' => true,
                'message' => "The link path [$link] is already correctly connected to [$target]."
            ]);
        }

        try {
            unlink($link);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "The link path [$link] is a symlink pointing to [$currentSymlinkTarget], but it could not be deleted to point to [$target]. Reason: " . $e->getMessage()
            ]);
        }
    } elseif (file_exists($link)) {
        if (is_dir($link)) {
            return response()->json([
                'success' => false,
                'message' => "The link path [$link] is an actual directory. Please delete or rename it manually first."
            ]);
        }
        
        try {
            unlink($link);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "The file at [$link] could not be deleted. Reason: " . $e->getMessage()
            ]);
        }
    }

    $parentDir = dirname($link);
    if (!is_dir($parentDir)) {
        mkdir($parentDir, 0755, true);
    }

    try {
        if (symlink($target, $link)) {
            return response()->json([
                'success' => true,
                'message' => "Successfully created symlink from target [$target] to link [$link]."
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Failed to create symlink from [$target] to [$link]."
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "Failed to create symlink from [$target] to [$link]. Error: " . $e->getMessage()
        ]);
    }
});
