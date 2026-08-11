<?php

use Opcodes\LogViewer\LogFile;
use Opcodes\LogViewer\LogIndex;

return [

    /*
    |--------------------------------------------------------------------------
    | Log Viewer Route
    |--------------------------------------------------------------------------
    | Log Viewer will be available at this URL: /log-viewer
    |
    */

    'route_path' => 'log-viewer',

    'route_middleware' => [
        'web',
        // Add 'auth' below if you want only logged-in users to access it
        // 'auth',
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Viewer Authorization
    |--------------------------------------------------------------------------
    | This callback determines who can access the Log Viewer. It runs
    | before any route middleware. By default, only local requests are allowed.
    | On production, customize this to check admin roles.
    |
    */

    'api_only' => false,

    'auth' => [
        'guard' => null,
    ],

    'authorization' => function (Illuminate\Http\Request $request) {
        // Allow logged-in admins and managers
        $user = $request->user();
        if ($user && $user->role) {
            return in_array($user->role->name, ['admin', 'super_admin', 'manager']);
        }
        // Allow local access (127.0.0.1)
        return in_array($request->ip(), ['127.0.0.1', '::1']);
    },

    /*
    |--------------------------------------------------------------------------
    | Log Files
    |--------------------------------------------------------------------------
    */

    'include_files' => [storage_path('logs/*.log')],

    'exclude_files' => [],

    'shorter_stack_trace_excludes' => [
        '/vendor/laravel/framework',
        '/vendor/barryvdh',
        '/vendor/psr',
    ],

    'max_log_size_formatted' => '200 MB',

    'chunk_size_in_mb' => 25,

    'early_access_chunk_size_in_kb' => 100,

    'lazy_scan_chunk_size_in_mb' => 5,

    'cache_store' => env('LOG_VIEWER_CACHE_DRIVER', 'file'),

    'log_index_chunk_size' => 200,

    'theme' => 'dark',

];
