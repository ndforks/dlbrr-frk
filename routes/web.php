<?php

use App\Http\Controllers\Accountancy\AccountancyIndex;
use App\Http\Controllers\Accountancy\JournalAccountancy;
use App\Http\Controllers\Adherents\AdherentsIndex;
use App\Http\Controllers\Adherents\ListAdherents;
use App\Http\Controllers\Adherents\ShowAdherents;
use App\Http\Controllers\Admin\AdminIndex;
use App\Http\Controllers\Admin\ModulesAdmin;
use App\Http\Controllers\Admin\SystemAdmin;
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
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', UserIndex::class)->name('index');
    Route::get('/{id?}', ShowUser::class)->name('show');
    Route::get('/list', ListUsers::class)->name('list');
});

// Product/Service Routes
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', ProductIndex::class)->name('index');
    Route::get('/{id?}', ShowProduct::class)->name('show');
    Route::get('/list', ListProduct::class)->name('list');
});

// Customer/Prospect Routes (Societe)
Route::prefix('societe')->name('societe.')->group(function () {
    Route::get('/', SocieteIndex::class)->name('index');
    Route::get('/{id?}', ShowSociete::class)->name('show');
    Route::get('/list', ListSociete::class)->name('list');
});

// Contact Routes
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', ContactIndex::class)->name('index');
    Route::get('/{id?}', ShowContact::class)->name('show');
    Route::get('/list', ListContacts::class)->name('list');
});

// Invoice Routes (Facture)
Route::prefix('compta/facture')->name('facture.')->group(function () {
    Route::get('/', FactureIndex::class)->name('index');
    Route::get('/{id?}', ShowFacture::class)->name('show');
    Route::get('/list', ListFacture::class)->name('list');
});

// Order Routes (Commande)
Route::prefix('commande')->name('commande.')->group(function () {
    Route::get('/', CommandeIndex::class)->name('index');
    Route::get('/{id?}', ShowCommande::class)->name('show');
    Route::get('/list', ListCommande::class)->name('list');
});

// Proposal Routes (Propal)
Route::prefix('comm/propal')->name('propal.')->group(function () {
    Route::get('/', PropalIndex::class)->name('index');
    Route::get('/{id?}', ShowPropal::class)->name('show');
    Route::get('/list', ListPropal::class)->name('list');
});

// Project Routes
Route::prefix('projet')->name('projet.')->group(function () {
    Route::get('/', ProjetIndex::class)->name('index');
    Route::get('/{id?}', ShowProjet::class)->name('show');
    Route::get('/list', ListProjet::class)->name('list');
});

// Ticket Routes
Route::prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', TicketIndex::class)->name('index');
    Route::get('/{id?}', ShowTicket::class)->name('show');
    Route::get('/list', ListTicket::class)->name('list');
});

// Supplier Routes (Fourn)
Route::prefix('fourn')->name('fourn.')->group(function () {
    Route::get('/', FournIndex::class)->name('index');
    Route::get('/{id?}', ShowFourn::class)->name('show');
    Route::prefix('commande')->name('commande.')->group(function () {
        Route::get('/', FournCommandeIndex::class)->name('index');
        Route::get('/{id?}', FournShowCommande::class)->name('show');
    });
    Route::prefix('facture')->name('facture.')->group(function () {
        Route::get('/', FournFactureIndex::class)->name('index');
        Route::get('/{id?}', FournShowFacture::class)->name('show');
    });
});

// Expedition/Shipping Routes
Route::prefix('expedition')->name('expedition.')->group(function () {
    Route::get('/', ExpeditionIndex::class)->name('index');
    Route::get('/{id?}', ShowExpedition::class)->name('show');
    Route::get('/list', ListExpedition::class)->name('list');
});

// Contract Routes
Route::prefix('contrat')->name('contrat.')->group(function () {
    Route::get('/', ContratIndex::class)->name('index');
    Route::get('/{id?}', ShowContrat::class)->name('show');
    Route::get('/list', ListContrat::class)->name('list');
});

// Intervention Routes
Route::prefix('fichinter')->name('fichinter.')->group(function () {
    Route::get('/', FichinterIndex::class)->name('index');
    Route::get('/{id?}', ShowFichinter::class)->name('show');
    Route::get('/list', ListFichinter::class)->name('list');
});

// Member Routes (Adherents)
Route::prefix('adherents')->name('adherents.')->group(function () {
    Route::get('/', AdherentsIndex::class)->name('index');
    Route::get('/{id?}', ShowAdherents::class)->name('show');
    Route::get('/list', ListAdherents::class)->name('list');
});

// Donation Routes
Route::prefix('don')->name('don.')->group(function () {
    Route::get('/', DonIndex::class)->name('index');
    Route::get('/{id?}', ShowDon::class)->name('show');
    Route::get('/list', ListDon::class)->name('list');
});

// Bank Account Routes
Route::prefix('compta/bank')->name('bank.')->group(function () {
    Route::get('/', BankIndex::class)->name('index');
    Route::get('/{id?}', ShowBank::class)->name('show');
    Route::get('/list', ListBank::class)->name('list');
});

// Expense Report Routes
Route::prefix('expensereport')->name('expensereport.')->group(function () {
    Route::get('/', ExpenseReportIndex::class)->name('index');
    Route::get('/{id?}', ShowExpenseReport::class)->name('show');
    Route::get('/list', ListExpenseReport::class)->name('list');
});

// Holiday/Leave Routes
Route::prefix('holiday')->name('holiday.')->group(function () {
    Route::get('/', HolidayIndex::class)->name('index');
    Route::get('/{id?}', ShowHoliday::class)->name('show');
    Route::get('/list', ListHoliday::class)->name('list');
});

// HR Management Routes
Route::prefix('hrm')->name('hrm.')->group(function () {
    Route::get('/', HrmIndex::class)->name('index');
    Route::get('/employee', EmployeeHrm::class)->name('employee');
    Route::get('/position', PositionHrm::class)->name('position');
});

// Asset Management Routes
Route::prefix('asset')->name('asset.')->group(function () {
    Route::get('/', AssetIndex::class)->name('index');
    Route::get('/{id?}', ShowAsset::class)->name('show');
    Route::get('/list', ListAsset::class)->name('list');
});

// BOM (Bill of Materials) Routes
Route::prefix('bom')->name('bom.')->group(function () {
    Route::get('/', BomIndex::class)->name('index');
    Route::get('/{id?}', ShowBom::class)->name('show');
    Route::get('/list', ListBom::class)->name('list');
});

// Manufacturing Order Routes
Route::prefix('mrp')->name('mrp.')->group(function () {
    Route::get('/', MrpIndex::class)->name('index');
    Route::get('/mo/{id?}', ShowManufacturingOrder::class)->name('show');
    Route::get('/mo/list', ListManufacturingOrders::class)->name('list');
});

// Stock/Warehouse Routes
Route::prefix('product/stock')->name('stock.')->group(function () {
    Route::get('/', StockIndex::class)->name('index');
    Route::get('/{id?}', ShowStock::class)->name('show');
    Route::get('/mouvement', MovementStock::class)->name('mouvement');
});

// Category Routes
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', CategoriesIndex::class)->name('index');
    Route::get('/{id?}', ShowCategories::class)->name('show');
});

// Bookmarks Routes
Route::prefix('bookmarks')->name('bookmarks.')->group(function () {
    Route::get('/', BookmarksIndex::class)->name('index');
    Route::get('/{id?}', ShowBookmarks::class)->name('show');
});

// Accounting Routes
Route::prefix('accountancy')->name('accountancy.')->group(function () {
    Route::get('/', AccountancyIndex::class)->name('index');
    Route::get('/journal', JournalAccountancy::class)->name('journal');
});

// ECM (Document Management) Routes
Route::prefix('ecm')->name('ecm.')->group(function () {
    Route::get('/', EcmIndex::class)->name('index');
    Route::get('/auto', AutoIndexEcm::class)->name('auto');
});

// Event Organization Routes
Route::prefix('eventorganization')->name('eventorganization.')->group(function () {
    Route::get('/', EventOrganizationIndex::class)->name('index');
    Route::get('/conferenceorbooth/{id?}', ShowConferenceOrBoothEventOrganization::class)->name('conferenceorbooth');
});

// Booking/Calendar Routes
Route::prefix('bookcal')->name('bookcal.')->group(function () {
    Route::get('/', BookcalIndex::class)->name('index');
    Route::get('/calendar', CalendarBookcal::class)->name('calendar');
});

// Loan Routes
Route::prefix('loan')->name('loan.')->group(function () {
    Route::get('/', LoanIndex::class)->name('index');
    Route::get('/{id?}', ShowLoan::class)->name('show');
    Route::get('/list', ListLoan::class)->name('list');
});

// Supplier Proposal Routes
Route::prefix('supplier_proposal')->name('supplier_proposal.')->group(function () {
    Route::get('/', SupplierProposalIndex::class)->name('index');
    Route::get('/{id?}', ShowSupplierProposal::class)->name('show');
    Route::get('/list', ListSupplierProposal::class)->name('list');
});

// Variants Routes
Route::prefix('variants')->name('variants.')->group(function () {
    Route::get('/', VariantsIndex::class)->name('index');
    Route::get('/{id?}', ShowVariants::class)->name('show');
    Route::get('/list', ListVariants::class)->name('list');
});

// Website Builder Routes
Route::prefix('website')->name('website.')->group(function () {
    Route::get('/', WebsiteIndex::class)->name('index');
    Route::get('/page', PageWebsite::class)->name('page');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminIndex::class)->name('index');
    Route::get('/system', SystemAdmin::class)->name('system');
    Route::get('/modules', ModulesAdmin::class)->name('modules');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::any('/{path?}', fn ($path = '') => redirect('/htdocs/api/index.php/'.$path))
        ->where('path', '.*');
});

// Fallback route to handle all other Dolibarr requests
Route::fallback(function (Request $request) {
    $path = $request->path();

    // Map the request to the htdocs directory in public
    $htdocsPath = public_path('htdocs/'.$path);

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
    if (is_dir($htdocsPath) && file_exists($htdocsPath.'/index.php')) {
        chdir($htdocsPath);
        ob_start();
        include $htdocsPath.'/index.php';
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
