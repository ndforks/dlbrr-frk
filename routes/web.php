<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Home route - redirect to Dolibarr index
Route::get('/', function () {
    return redirect('/htdocs/index.php');
});

// User Management Routes
Route::prefix('user')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/user/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/user/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/user/list.php'));
});

// Product/Service Routes
Route::prefix('product')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/product/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/product/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/product/list.php'));
});

// Customer/Prospect Routes (Societe)
Route::prefix('societe')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/societe/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/societe/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/societe/list.php'));
});

// Contact Routes
Route::prefix('contact')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/contact/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/contact/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/contact/list.php'));
});

// Invoice Routes (Facture)
Route::prefix('compta/facture')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/compta/facture/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/compta/facture/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/compta/facture/list.php'));
});

// Order Routes (Commande)
Route::prefix('commande')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/commande/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/commande/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/commande/list.php'));
});

// Proposal Routes (Propal)
Route::prefix('comm/propal')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/comm/propal/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/comm/propal/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/comm/propal/list.php'));
});

// Project Routes
Route::prefix('projet')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/projet/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/projet/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/projet/list.php'));
});

// Ticket Routes
Route::prefix('ticket')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/ticket/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/ticket/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/ticket/list.php'));
});

// Supplier Routes (Fourn)
Route::prefix('fourn')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/fourn/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/fourn/card.php'));
    Route::prefix('commande')->group(function () {
        Route::get('/', fn() => redirect('/htdocs/fourn/commande/index.php'));
        Route::get('/card.php', fn() => redirect('/htdocs/fourn/commande/card.php'));
    });
    Route::prefix('facture')->group(function () {
        Route::get('/', fn() => redirect('/htdocs/fourn/facture/index.php'));
        Route::get('/card.php', fn() => redirect('/htdocs/fourn/facture/card.php'));
    });
});

// Expedition/Shipping Routes
Route::prefix('expedition')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/expedition/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/expedition/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/expedition/list.php'));
});

// Contract Routes
Route::prefix('contrat')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/contrat/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/contrat/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/contrat/list.php'));
});

// Intervention Routes
Route::prefix('fichinter')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/fichinter/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/fichinter/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/fichinter/list.php'));
});

// Member Routes (Adherents)
Route::prefix('adherents')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/adherents/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/adherents/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/adherents/list.php'));
});

// Donation Routes
Route::prefix('don')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/don/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/don/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/don/list.php'));
});

// Bank Account Routes
Route::prefix('compta/bank')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/compta/bank/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/compta/bank/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/compta/bank/list.php'));
});

// Expense Report Routes
Route::prefix('expensereport')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/expensereport/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/expensereport/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/expensereport/list.php'));
});

// Holiday/Leave Routes
Route::prefix('holiday')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/holiday/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/holiday/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/holiday/list.php'));
});

// HR Management Routes
Route::prefix('hrm')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/hrm/index.php'));
    Route::get('/employee.php', fn() => redirect('/htdocs/hrm/employee.php'));
    Route::get('/position.php', fn() => redirect('/htdocs/hrm/position.php'));
});

// Asset Management Routes
Route::prefix('asset')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/asset/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/asset/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/asset/list.php'));
});

// BOM (Bill of Materials) Routes
Route::prefix('bom')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/bom/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/bom/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/bom/list.php'));
});

// Manufacturing Order Routes
Route::prefix('mrp')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/mrp/index.php'));
    Route::get('/mo_card.php', fn() => redirect('/htdocs/mrp/mo_card.php'));
    Route::get('/mo_list.php', fn() => redirect('/htdocs/mrp/mo_list.php'));
});

// Stock/Warehouse Routes
Route::prefix('product/stock')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/product/stock/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/product/stock/card.php'));
    Route::get('/mouvement.php', fn() => redirect('/htdocs/product/stock/mouvement.php'));
});

// Category Routes
Route::prefix('categories')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/categories/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/categories/card.php'));
});

// Bookmarks Routes
Route::prefix('bookmarks')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/bookmarks/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/bookmarks/card.php'));
});

// Accounting Routes
Route::prefix('accountancy')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/accountancy/index.php'));
    Route::get('/journal/', fn() => redirect('/htdocs/accountancy/journal/index.php'));
});

// ECM (Document Management) Routes
Route::prefix('ecm')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/ecm/index.php'));
    Route::get('/index_auto.php', fn() => redirect('/htdocs/ecm/index_auto.php'));
});

// Event Organization Routes
Route::prefix('eventorganization')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/eventorganization/index.php'));
    Route::get('/conferenceorbooth_card.php', fn() => redirect('/htdocs/eventorganization/conferenceorbooth_card.php'));
});

// Booking/Calendar Routes
Route::prefix('bookcal')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/bookcal/index.php'));
    Route::get('/calendar.php', fn() => redirect('/htdocs/bookcal/calendar.php'));
});

// Loan Routes
Route::prefix('loan')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/loan/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/loan/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/loan/list.php'));
});

// Supplier Proposal Routes
Route::prefix('supplier_proposal')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/supplier_proposal/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/supplier_proposal/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/supplier_proposal/list.php'));
});

// Variants Routes
Route::prefix('variants')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/variants/index.php'));
    Route::get('/card.php', fn() => redirect('/htdocs/variants/card.php'));
    Route::get('/list.php', fn() => redirect('/htdocs/variants/list.php'));
});

// Website Builder Routes
Route::prefix('website')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/website/index.php'));
    Route::get('/page.php', fn() => redirect('/htdocs/website/page.php'));
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', fn() => redirect('/htdocs/admin/index.php'));
    Route::get('/system/', fn() => redirect('/htdocs/admin/system/index.php'));
    Route::get('/modules.php', fn() => redirect('/htdocs/admin/modules.php'));
});

// API Routes
Route::prefix('api')->group(function () {
    Route::any('/{path?}', fn($path = '') => redirect('/htdocs/api/index.php/' . $path))
        ->where('path', '.*');
});

// Fallback route to handle all other Dolibarr requests
Route::fallback(function (Request $request) {
    $path = $request->path();
    
    // Map the request to the htdocs directory in public
    $htdocsPath = public_path('htdocs/' . $path);
    
    // Check if it's a PHP file
    if (str_ends_with($path, '.php')) {
        if (file_exists($htdocsPath)) {
            chdir(dirname($htdocsPath));
            ob_start();
            include $htdocsPath;
            $content = ob_get_clean();
            return response($content);
        }
    }
    
    // Check if it's a directory with an index.php
    if (is_dir($htdocsPath) && file_exists($htdocsPath . '/index.php')) {
        chdir($htdocsPath);
        ob_start();
        include $htdocsPath . '/index.php';
        $content = ob_get_clean();
        return response($content);
    }
    
    // Check if it's a static file
    if (file_exists($htdocsPath) && is_file($htdocsPath)) {
        return response()->file($htdocsPath);
    }
    
    // Default to 404
    abort(404);
});
