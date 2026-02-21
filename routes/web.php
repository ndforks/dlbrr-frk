<?php

use App\Http\Controllers\Admin\AdminIndex;
use App\Http\Controllers\Admin\ModulesAdmin;
use App\Http\Controllers\Admin\SystemAdmin;
use App\Http\Controllers\Accountancy\AccountancyIndex;
use App\Http\Controllers\Accountancy\JournalAccountancy;
use App\Http\Controllers\Adherents\AdherentsIndex;
use App\Http\Controllers\Adherents\ListAdherents;
use App\Http\Controllers\Adherents\ShowAdherents;
use App\Http\Controllers\Asset\AssetIndex;
use App\Http\Controllers\Asset\ListAsset;
use App\Http\Controllers\Asset\ShowAsset;
use App\Http\Controllers\Bom\BomIndex;
use App\Http\Controllers\Bom\ListBom;
use App\Http\Controllers\Bom\ShowBom;
use App\Http\Controllers\Bookcal\BookcalIndex;
use App\Http\Controllers\Bookcal\CalendarBookcal;
use App\Http\Controllers\Bookmarks\BookmarksIndex;
use App\Http\Controllers\Bookmarks\ShowBookmarks;
use App\Http\Controllers\Categories\CategoriesIndex;
use App\Http\Controllers\Categories\ShowCategories;
use App\Http\Controllers\Comm\Propal\ListPropal;
use App\Http\Controllers\Comm\Propal\PropalIndex;
use App\Http\Controllers\Comm\Propal\ShowPropal;
use App\Http\Controllers\Commande\CommandeIndex;
use App\Http\Controllers\Commande\ListCommande;
use App\Http\Controllers\Commande\ShowCommande;
use App\Http\Controllers\Compta\Bank\BankIndex;
use App\Http\Controllers\Compta\Bank\ListBank;
use App\Http\Controllers\Compta\Bank\ShowBank;
use App\Http\Controllers\Compta\Facture\FactureIndex;
use App\Http\Controllers\Compta\Facture\ListFacture;
use App\Http\Controllers\Compta\Facture\ShowFacture;
use App\Http\Controllers\Contact\ContactIndex;
use App\Http\Controllers\Contact\ListContacts;
use App\Http\Controllers\Contact\ShowContact;
use App\Http\Controllers\Contrat\ContratIndex;
use App\Http\Controllers\Contrat\ListContrat;
use App\Http\Controllers\Contrat\ShowContrat;
use App\Http\Controllers\Don\DonIndex;
use App\Http\Controllers\Don\ListDon;
use App\Http\Controllers\Don\ShowDon;
use App\Http\Controllers\Ecm\AutoIndexEcm;
use App\Http\Controllers\Ecm\EcmIndex;
use App\Http\Controllers\EventOrganization\EventOrganizationIndex;
use App\Http\Controllers\EventOrganization\ShowConferenceOrBoothEventOrganization;
use App\Http\Controllers\Expedition\ExpeditionIndex;
use App\Http\Controllers\Expedition\ListExpedition;
use App\Http\Controllers\Expedition\ShowExpedition;
use App\Http\Controllers\ExpenseReport\ExpenseReportIndex;
use App\Http\Controllers\ExpenseReport\ListExpenseReport;
use App\Http\Controllers\ExpenseReport\ShowExpenseReport;
use App\Http\Controllers\Fichinter\FichinterIndex;
use App\Http\Controllers\Fichinter\ListFichinter;
use App\Http\Controllers\Fichinter\ShowFichinter;
use App\Http\Controllers\Fourn\Commande\CommandeIndex as FournCommandeIndex;
use App\Http\Controllers\Fourn\Commande\ShowCommande as FournShowCommande;
use App\Http\Controllers\Fourn\Facture\FactureIndex as FournFactureIndex;
use App\Http\Controllers\Fourn\Facture\ShowFacture as FournShowFacture;
use App\Http\Controllers\Fourn\FournIndex;
use App\Http\Controllers\Fourn\ShowFourn;
use App\Http\Controllers\Holiday\HolidayIndex;
use App\Http\Controllers\Holiday\ListHoliday;
use App\Http\Controllers\Holiday\ShowHoliday;
use App\Http\Controllers\Home;
use App\Http\Controllers\Hrm\EmployeeHrm;
use App\Http\Controllers\Hrm\HrmIndex;
use App\Http\Controllers\Hrm\PositionHrm;
use App\Http\Controllers\Loan\ListLoan;
use App\Http\Controllers\Loan\LoanIndex;
use App\Http\Controllers\Loan\ShowLoan;
use App\Http\Controllers\Mrp\ListManufacturingOrders;
use App\Http\Controllers\Mrp\MrpIndex;
use App\Http\Controllers\Mrp\ShowManufacturingOrder;
use App\Http\Controllers\Product\ListProduct;
use App\Http\Controllers\Product\ProductIndex;
use App\Http\Controllers\Product\ShowProduct;
use App\Http\Controllers\Product\Stock\MovementStock;
use App\Http\Controllers\Product\Stock\ShowStock;
use App\Http\Controllers\Product\Stock\StockIndex;
use App\Http\Controllers\Projet\ListProjet;
use App\Http\Controllers\Projet\ProjetIndex;
use App\Http\Controllers\Projet\ShowProjet;
use App\Http\Controllers\Societe\ListSociete;
use App\Http\Controllers\Societe\ShowSociete;
use App\Http\Controllers\Societe\SocieteIndex;
use App\Http\Controllers\SupplierProposal\ListSupplierProposal;
use App\Http\Controllers\SupplierProposal\ShowSupplierProposal;
use App\Http\Controllers\SupplierProposal\SupplierProposalIndex;
use App\Http\Controllers\Ticket\ListTicket;
use App\Http\Controllers\Ticket\ShowTicket;
use App\Http\Controllers\Ticket\TicketIndex;
use App\Http\Controllers\User\ListUsers;
use App\Http\Controllers\User\ShowUser;
use App\Http\Controllers\User\UserIndex;
use App\Http\Controllers\Variants\ListVariants;
use App\Http\Controllers\Variants\ShowVariants;
use App\Http\Controllers\Variants\VariantsIndex;
use App\Http\Controllers\Website\PageWebsite;
use App\Http\Controllers\Website\WebsiteIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Home route - redirect to Dolibarr index
Route::get('/', Home::class);

// User Management Routes
Route::prefix('user')->group(function () {
    Route::get('/', UserIndex::class);
    Route::get('/card.php', ShowUser::class);
    Route::get('/list.php', ListUsers::class);
});

// Product/Service Routes
Route::prefix('product')->group(function () {
    Route::get('/', ProductIndex::class);
    Route::get('/card.php', ShowProduct::class);
    Route::get('/list.php', ListProduct::class);
});

// Customer/Prospect Routes (Societe)
Route::prefix('societe')->group(function () {
    Route::get('/', SocieteIndex::class);
    Route::get('/card.php', ShowSociete::class);
    Route::get('/list.php', ListSociete::class);
});

// Contact Routes
Route::prefix('contact')->group(function () {
    Route::get('/', ContactIndex::class);
    Route::get('/card.php', ShowContact::class);
    Route::get('/list.php', ListContacts::class);
});

// Invoice Routes (Facture)
Route::prefix('compta/facture')->group(function () {
    Route::get('/', FactureIndex::class);
    Route::get('/card.php', ShowFacture::class);
    Route::get('/list.php', ListFacture::class);
});

// Order Routes (Commande)
Route::prefix('commande')->group(function () {
    Route::get('/', CommandeIndex::class);
    Route::get('/card.php', ShowCommande::class);
    Route::get('/list.php', ListCommande::class);
});

// Proposal Routes (Propal)
Route::prefix('comm/propal')->group(function () {
    Route::get('/', PropalIndex::class);
    Route::get('/card.php', ShowPropal::class);
    Route::get('/list.php', ListPropal::class);
});

// Project Routes
Route::prefix('projet')->group(function () {
    Route::get('/', ProjetIndex::class);
    Route::get('/card.php', ShowProjet::class);
    Route::get('/list.php', ListProjet::class);
});

// Ticket Routes
Route::prefix('ticket')->group(function () {
    Route::get('/', TicketIndex::class);
    Route::get('/card.php', ShowTicket::class);
    Route::get('/list.php', ListTicket::class);
});

// Supplier Routes (Fourn)
Route::prefix('fourn')->group(function () {
    Route::get('/', FournIndex::class);
    Route::get('/card.php', ShowFourn::class);
    Route::prefix('commande')->group(function () {
        Route::get('/', FournCommandeIndex::class);
        Route::get('/card.php', FournShowCommande::class);
    });
    Route::prefix('facture')->group(function () {
        Route::get('/', FournFactureIndex::class);
        Route::get('/card.php', FournShowFacture::class);
    });
});

// Expedition/Shipping Routes
Route::prefix('expedition')->group(function () {
    Route::get('/', ExpeditionIndex::class);
    Route::get('/card.php', ShowExpedition::class);
    Route::get('/list.php', ListExpedition::class);
});

// Contract Routes
Route::prefix('contrat')->group(function () {
    Route::get('/', ContratIndex::class);
    Route::get('/card.php', ShowContrat::class);
    Route::get('/list.php', ListContrat::class);
});

// Intervention Routes
Route::prefix('fichinter')->group(function () {
    Route::get('/', FichinterIndex::class);
    Route::get('/card.php', ShowFichinter::class);
    Route::get('/list.php', ListFichinter::class);
});

// Member Routes (Adherents)
Route::prefix('adherents')->group(function () {
    Route::get('/', AdherentsIndex::class);
    Route::get('/card.php', ShowAdherents::class);
    Route::get('/list.php', ListAdherents::class);
});

// Donation Routes
Route::prefix('don')->group(function () {
    Route::get('/', DonIndex::class);
    Route::get('/card.php', ShowDon::class);
    Route::get('/list.php', ListDon::class);
});

// Bank Account Routes
Route::prefix('compta/bank')->group(function () {
    Route::get('/', BankIndex::class);
    Route::get('/card.php', ShowBank::class);
    Route::get('/list.php', ListBank::class);
});

// Expense Report Routes
Route::prefix('expensereport')->group(function () {
    Route::get('/', ExpenseReportIndex::class);
    Route::get('/card.php', ShowExpenseReport::class);
    Route::get('/list.php', ListExpenseReport::class);
});

// Holiday/Leave Routes
Route::prefix('holiday')->group(function () {
    Route::get('/', HolidayIndex::class);
    Route::get('/card.php', ShowHoliday::class);
    Route::get('/list.php', ListHoliday::class);
});

// HR Management Routes
Route::prefix('hrm')->group(function () {
    Route::get('/', HrmIndex::class);
    Route::get('/employee.php', EmployeeHrm::class);
    Route::get('/position.php', PositionHrm::class);
});

// Asset Management Routes
Route::prefix('asset')->group(function () {
    Route::get('/', AssetIndex::class);
    Route::get('/card.php', ShowAsset::class);
    Route::get('/list.php', ListAsset::class);
});

// BOM (Bill of Materials) Routes
Route::prefix('bom')->group(function () {
    Route::get('/', BomIndex::class);
    Route::get('/card.php', ShowBom::class);
    Route::get('/list.php', ListBom::class);
});

// Manufacturing Order Routes
Route::prefix('mrp')->group(function () {
    Route::get('/', MrpIndex::class);
    Route::get('/mo_card.php', ShowManufacturingOrder::class);
    Route::get('/mo_list.php', ListManufacturingOrders::class);
});

// Stock/Warehouse Routes
Route::prefix('product/stock')->group(function () {
    Route::get('/', StockIndex::class);
    Route::get('/card.php', ShowStock::class);
    Route::get('/mouvement.php', MovementStock::class);
});

// Category Routes
Route::prefix('categories')->group(function () {
    Route::get('/', CategoriesIndex::class);
    Route::get('/card.php', ShowCategories::class);
});

// Bookmarks Routes
Route::prefix('bookmarks')->group(function () {
    Route::get('/', BookmarksIndex::class);
    Route::get('/card.php', ShowBookmarks::class);
});

// Accounting Routes
Route::prefix('accountancy')->group(function () {
    Route::get('/', AccountancyIndex::class);
    Route::get('/journal/', JournalAccountancy::class);
});

// ECM (Document Management) Routes
Route::prefix('ecm')->group(function () {
    Route::get('/', EcmIndex::class);
    Route::get('/index_auto.php', AutoIndexEcm::class);
});

// Event Organization Routes
Route::prefix('eventorganization')->group(function () {
    Route::get('/', EventOrganizationIndex::class);
    Route::get('/conferenceorbooth_card.php', ShowConferenceOrBoothEventOrganization::class);
});

// Booking/Calendar Routes
Route::prefix('bookcal')->group(function () {
    Route::get('/', BookcalIndex::class);
    Route::get('/calendar.php', CalendarBookcal::class);
});

// Loan Routes
Route::prefix('loan')->group(function () {
    Route::get('/', LoanIndex::class);
    Route::get('/card.php', ShowLoan::class);
    Route::get('/list.php', ListLoan::class);
});

// Supplier Proposal Routes
Route::prefix('supplier_proposal')->group(function () {
    Route::get('/', SupplierProposalIndex::class);
    Route::get('/card.php', ShowSupplierProposal::class);
    Route::get('/list.php', ListSupplierProposal::class);
});

// Variants Routes
Route::prefix('variants')->group(function () {
    Route::get('/', VariantsIndex::class);
    Route::get('/card.php', ShowVariants::class);
    Route::get('/list.php', ListVariants::class);
});

// Website Builder Routes
Route::prefix('website')->group(function () {
    Route::get('/', WebsiteIndex::class);
    Route::get('/page.php', PageWebsite::class);
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', AdminIndex::class);
    Route::get('/system/', SystemAdmin::class);
    Route::get('/modules.php', ModulesAdmin::class);
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
