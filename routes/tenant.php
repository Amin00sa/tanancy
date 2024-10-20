<?php

declare(strict_types=1);

use App\Http\Controllers\BankController;
use App\Http\Controllers\MarcheController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VenteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
                      'web',
                      InitializeTenancyByDomain::class,
                      PreventAccessFromCentralDomains::class,
                  ])->group(function () {
    Route::redirect('/', '/login');

    Route::get('/create/user', function () {
        \App\Models\User::create([
                                     'name'     => 'tenant_test',
                                     'password' => 12345678,
                                     'email'    => 'tenant_test@gmail.com',
                                 ]);
        return json_encode('User created successfully');
    });


    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('/suppliers', SupplierController::class);
        Route::resource('/banks', BankController::class);
        Route::resource('/marches', MarcheController::class);
        Route::resource('/purchases', PurchaseController::class);
        Route::resource('/ventes', VenteController::class);

        Route::get('/webapibanks', [BankController::class, 'fetchbanks'])->name('banks.fetch');
        Route::get('/webapisuppliers', [SupplierController::class, 'fetchsuppliers'])->name('suppliers.fetch');
        Route::get('/webapimarches', [MarcheController::class, 'fetchmarches'])->name('marches.fetch');
        Route::get('/webapipurchases', [PurchaseController::class, 'fetchpurchases'])->name('marches.fetch');
        Route::get('/webapiventes', [VenteController::class, 'fetchventes'])->name('ventes.fetch');
        Route::get('/payment/proof/{payment}', [PurchaseController::class, 'download'])->name('proof.download');
        Route::get('/vente/facture/{vente}', [VenteController::class, 'download'])->name('facture.download');

        Route::post('/client/store', [VenteController::class, 'storeclient'])->name('clients.store');
    });
    require __DIR__ . '/auth.php';
});
