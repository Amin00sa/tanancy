<?php

use App\Http\Controllers\ProfileController;
use App\Models\Tenant;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('config', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
});
Route::get('/', function (Request $request) {
    $url = $request->url();

    // Parse the URL to extract the host (domain) name
    $parsedUrl = parse_url($url);

    if (isset($parsedUrl['host'])) {
        // The central domain is in $parsedUrl['host']
        $centralDomain = $parsedUrl['host'];
        if (substr($centralDomain, 0, 4) === 'www.') {
            // Remove 'www.' prefix
            $centralDomain = substr($centralDomain, 4);
        }
    } else {
        // Handle the case where the host is not found (e.g., in a local development environment)
        $centralDomain = 'unknown';
    }

    return Inertia::render('Welcome',[
        'centralDomain' => $centralDomain,
    ]);
});
