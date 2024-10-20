<?php

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
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
        if (str_starts_with($centralDomain, 'www.')) {
            // Remove 'www.' prefix
            $centralDomain = substr($centralDomain, 4);
        }
    } else {
        // Handle the case where the host is not found (e.g., in a local development environment)
        $centralDomain = 'unknown';
    }

    return Inertia::render('Welcome', [
        'centralDomain' => $centralDomain,
    ]);
});

Route::get('/create-tenant-local', function () {
    $tenant = Tenant::create([
                                 'id' => (string)Str::uuid(),
                                 'data' => [
                                     'db_name' => 'tenant_1'
                                 ]
                             ]);

    // Assign 'localhost' as the tenant domain for local testing
    $tenant->domains()->create([
                                   'domain' => 'localhost'
                               ]);

    // Initialize the tenant and run migrations
    tenancy()->initialize($tenant);
    Artisan::call('tenants:migrate');

    return response()->json(['message' => 'Tenant created with localhost as domain!']);
});

Route::post('/create-tenant', function () {
    $tenantId = (string)Str::uuid(); //
    $tenant = Tenant::create([
                                 'id'   => $tenantId,
                                 'data' => [
                                     'db_name' => 'tenant_' . $tenantId // Example of setting tenant-specific DB
                                 ],
                             ]);

    // Assign a domain like `tenant1.localhost`
    $tenant->domains()->create([
                                   'domain' => $tenantId . '.localhost',
                               ]);

    // Configure tenant-specific database and run migrations
    tenancy()->initialize($tenant);
    Artisan::call('tenants:migrate --seed'); // Run tenant migrations

    return response()->json(['message' => 'Tenant created!']);
});