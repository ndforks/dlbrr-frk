<?php

use App\Http\Controllers\Accountancy\AccountancyIndex;
use App\Http\Controllers\Accountancy\JournalAccountancy;
use App\Http\Controllers\Adherents\ListAdherents;
use App\Http\Controllers\Adherents\ShowAdherents;
use App\Http\Controllers\Admin\AdminIndex;
use App\Http\Controllers\Admin\ModulesAdmin;
use App\Http\Controllers\Admin\SystemAdmin;
use App\Http\Controllers\Asterisk\WrapperController as AsteriskWrapper;
use App\Http\Controllers\Asset\ListAsset;
use App\Http\Controllers\Asset\ShowAsset;
use App\Http\Controllers\Barcode\CodeInitController;
use App\Http\Controllers\Barcode\PrintSheetController;
use App\Http\Controllers\Bom\ListBom;
use App\Http\Controllers\Bom\ShowBom;
use App\Http\Controllers\Bookcal\BookcalIndex;
use App\Http\Controllers\Bookcal\CalendarBookcal;
use App\Http\Controllers\Bookmarks\BookmarksIndex;
use App\Http\Controllers\Bookmarks\ShowBookmarks;
use App\Http\Controllers\Categories\CategoriesIndex;
use App\Http\Controllers\Categories\ShowCategories;
use App\Http\Controllers\Collab\CollabController;
use App\Http\Controllers\Comm\Propal\ListPropal;
use App\Http\Controllers\Comm\Propal\ShowPropal;
use App\Http\Controllers\Commande\ListCommande;
use App\Http\Controllers\Commande\ShowCommande;
use App\Http\Controllers\Compta\Bank\ListBank;
use App\Http\Controllers\Compta\Bank\ShowBank;
use App\Http\Controllers\Compta\Facture\ListFacture;
use App\Http\Controllers\Compta\Facture\ShowFacture;
use App\Http\Controllers\Contact\AgendaController as ContactAgenda;
use App\Http\Controllers\Contact\ConsumptionController as ContactConsumption;
use App\Http\Controllers\Contact\DocumentsController as ContactDocuments;
use App\Http\Controllers\Contact\InfoController as ContactInfo;
use App\Http\Controllers\Contact\ListContacts;
use App\Http\Controllers\Contact\MessagingController as ContactMessaging;
use App\Http\Controllers\Contact\NotesController as ContactNotes;
use App\Http\Controllers\Contact\PersoController as ContactPerso;
use App\Http\Controllers\Contact\ProjectsController as ContactProjects;
use App\Http\Controllers\Contact\ShowContact;
use App\Http\Controllers\Contact\VCardController as ContactVCard;
use App\Http\Controllers\Contrat\ListContrat;
use App\Http\Controllers\Contrat\ShowContrat;
use App\Http\Controllers\Cron\CronCardController;
use App\Http\Controllers\Cron\CronInfoController;
use App\Http\Controllers\Cron\CronListController;
use App\Http\Controllers\Don\ListDon;
use App\Http\Controllers\Don\ShowDon;
use App\Http\Controllers\Ecm\AutoIndexEcm;
use App\Http\Controllers\Ecm\EcmIndex;
use App\Http\Controllers\EventOrganization\EventOrganizationIndex;
use App\Http\Controllers\EventOrganization\ShowConferenceOrBoothEventOrganization;
use App\Http\Controllers\Expedition\ListExpedition;
use App\Http\Controllers\Expedition\ShowExpedition;
use App\Http\Controllers\ExpenseReport\ListExpenseReport;
use App\Http\Controllers\ExpenseReport\ShowExpenseReport;
use App\Http\Controllers\Fichinter\ListFichinter;
use App\Http\Controllers\Fichinter\ShowFichinter;
use App\Http\Controllers\Fourn\Commande\ShowCommande as FournShowCommande;
use App\Http\Controllers\Fourn\Facture\ShowFacture as FournShowFacture;
use App\Http\Controllers\Fourn\FournIndex;
use App\Http\Controllers\Fourn\ShowFourn;
use App\Http\Controllers\Holiday\ListHoliday;
use App\Http\Controllers\Holiday\ShowHoliday;
use App\Http\Controllers\Home;
use App\Http\Controllers\Hrm\EmployeeHrm;
use App\Http\Controllers\Hrm\HrmIndex;
use App\Http\Controllers\Hrm\PositionHrm;
use App\Http\Controllers\Loan\ListLoan;
use App\Http\Controllers\Loan\ShowLoan;
use App\Http\Controllers\Mrp\ListManufacturingOrders;
use App\Http\Controllers\Mrp\MrpIndex;
use App\Http\Controllers\Mrp\ShowManufacturingOrder;
use App\Http\Controllers\Product\ListProduct;
use App\Http\Controllers\Product\ShowProduct;
use App\Http\Controllers\Product\Stock\MovementStock;
use App\Http\Controllers\Product\Stock\ShowStock;
use App\Http\Controllers\Product\Stock\StockIndex;
use App\Http\Controllers\Projet\ListProjet;
use App\Http\Controllers\Projet\ShowProjet;
use App\Http\Controllers\Societe\ConsumptionController as SocieteConsumption;
use App\Http\Controllers\Societe\ContactsController as SocieteContacts;
use App\Http\Controllers\Societe\DocumentsController as SocieteDocuments;
use App\Http\Controllers\Societe\ListSociete;
use App\Http\Controllers\Societe\MessagingController as SocieteMessaging;
use App\Http\Controllers\Societe\NotesController as SocieteNotes;
use App\Http\Controllers\Societe\PricesController as SocietePrices;
use App\Http\Controllers\Societe\ProjectsController as SocieteProjects;
use App\Http\Controllers\Societe\ShowSociete;
use App\Http\Controllers\Societe\VCardController as SocieteVCard;
use App\Http\Controllers\SupplierProposal\ListSupplierProposal;
use App\Http\Controllers\SupplierProposal\ShowSupplierProposal;
use App\Http\Controllers\Ticket\ListTicket;
use App\Http\Controllers\Ticket\ShowTicket;
use App\Http\Controllers\User\ListUsers;
use App\Http\Controllers\User\ShowUser;
use App\Http\Controllers\Variants\ListVariants;
use App\Http\Controllers\Variants\ShowVariants;
use App\Http\Controllers\Website\PageWebsite;
use App\Http\Controllers\Website\WebsiteIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Home route - redirect to Dolibarr index
Route::get('/', Home::class);

// Asterisk click-to-dial wrapper
Route::get('/asterisk/wrapper', AsteriskWrapper::class)->name('asterisk.wrapper');

// Barcode mass initialization
Route::match(['get', 'post'], '/barcode/codeinit', CodeInitController::class)->name('barcode.codeinit');

// Barcode printsheet
Route::match(['get', 'post'], '/barcode/printsheet', PrintSheetController::class)->name('barcode.printsheet');

// Collab - Collaborative document editing
Route::match(['get', 'post'], '/collab', CollabController::class)->name('collab.index');

// Cron jobs management
Route::match(['get', 'post'], '/cron/list', CronListController::class)->name('cron.list');
Route::match(['get', 'post'], '/cron/card', CronCardController::class)->name('cron.card');
Route::get('/cron/info', CronInfoController::class)->name('cron.info');

// User Management Routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', ListUsers::class)->name('list');
    Route::get('/{id}', ShowUser::class)->name('show');
});

// Product/Service Routes
Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', ListProduct::class)->name('list');
    Route::get('/{id}', ShowProduct::class)->name('show');
});

// Customer/Prospect Routes (Societe)
Route::prefix('societe')->name('societe.')->group(function () {
    Route::get('/', ListSociete::class)->name('list');
    Route::get('/{id}', ShowSociete::class)->name('show');
    Route::get('/{id}/projects', SocieteProjects::class)->name('projects');
    Route::match(['get', 'post'], '/{id}/notes', SocieteNotes::class)->name('notes');
    Route::get('/{id}/documents', SocieteDocuments::class)->name('documents');
    Route::get('/{id}/contacts', SocieteContacts::class)->name('contacts');
    Route::get('/{id}/consumption', SocieteConsumption::class)->name('consumption');
    Route::get('/{id}/prices', SocietePrices::class)->name('prices');
    Route::get('/{id}/messaging', SocieteMessaging::class)->name('messaging');
    Route::get('/{id}/vcard', SocieteVCard::class)->name('vcard');
});

// Contact Routes
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', ListContacts::class)->name('list');
    Route::get('/{id}', ShowContact::class)->name('show');
    Route::get('/{id}/projects', ContactProjects::class)->name('projects');
    Route::match(['get', 'post'], '/{id}/notes', ContactNotes::class)->name('notes');
    Route::get('/{id}/documents', ContactDocuments::class)->name('documents');
    Route::get('/{id}/agenda', ContactAgenda::class)->name('agenda');
    Route::get('/{id}/consumption', ContactConsumption::class)->name('consumption');
    Route::get('/{id}/info', ContactInfo::class)->name('info');
    Route::match(['get', 'post'], '/{id}/perso', ContactPerso::class)->name('perso');
    Route::get('/{id}/messaging', ContactMessaging::class)->name('messaging');
    Route::get('/{id}/vcard', ContactVCard::class)->name('vcard');
});

// Invoice Routes (Facture)
Route::prefix('compta/facture')->name('facture.')->group(function () {
    Route::get('/', ListFacture::class)->name('list');
    Route::get('/{id}', ShowFacture::class)->name('show');
});

// Order Routes (Commande)
Route::prefix('commande')->name('commande.')->group(function () {
    Route::get('/', ListCommande::class)->name('list');
    Route::get('/{id}', ShowCommande::class)->name('show');
});

// Proposal Routes (Propal)
Route::prefix('comm/propal')->name('propal.')->group(function () {
    Route::get('/', ListPropal::class)->name('list');
    Route::get('/{id}', ShowPropal::class)->name('show');
});

// Project Routes
Route::prefix('projet')->name('projet.')->group(function () {
    Route::get('/', ListProjet::class)->name('list');
    Route::get('/{id}', ShowProjet::class)->name('show');
});

// Ticket Routes
Route::prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', ListTicket::class)->name('list');
    Route::get('/{id}', ShowTicket::class)->name('show');
});

// Supplier Routes (Fourn)
Route::prefix('fourn')->name('fourn.')->group(function () {
    Route::get('/', FournIndex::class)->name('index');
    Route::get('/{id}', ShowFourn::class)->name('show');
    Route::prefix('commande')->name('commande.')->group(function () {
        Route::get('/{id}', FournShowCommande::class)->name('show');
    });
    Route::prefix('facture')->name('facture.')->group(function () {
        Route::get('/{id}', FournShowFacture::class)->name('show');
    });
});

// Expedition/Shipping Routes
Route::prefix('expedition')->name('expedition.')->group(function () {
    Route::get('/', ListExpedition::class)->name('list');
    Route::get('/{id}', ShowExpedition::class)->name('show');
});

// Contract Routes
Route::prefix('contrat')->name('contrat.')->group(function () {
    Route::get('/', ListContrat::class)->name('list');
    Route::get('/{id}', ShowContrat::class)->name('show');
});

// Intervention Routes
Route::prefix('fichinter')->name('fichinter.')->group(function () {
    Route::get('/', ListFichinter::class)->name('list');
    Route::get('/{id}', ShowFichinter::class)->name('show');
});

// Member Routes (Adherents)
Route::prefix('adherents')->name('adherents.')->group(function () {
    Route::get('/', ListAdherents::class)->name('list');
    Route::get('/{id}', ShowAdherents::class)->name('show');
});

// Donation Routes
Route::prefix('don')->name('don.')->group(function () {
    Route::get('/', ListDon::class)->name('list');
    Route::get('/{id}', ShowDon::class)->name('show');
});

// Bank Account Routes
Route::prefix('compta/bank')->name('bank.')->group(function () {
    Route::get('/', ListBank::class)->name('list');
    Route::get('/{id}', ShowBank::class)->name('show');
});

// Expense Report Routes
Route::prefix('expensereport')->name('expensereport.')->group(function () {
    Route::get('/', ListExpenseReport::class)->name('list');
    Route::get('/{id}', ShowExpenseReport::class)->name('show');
});

// Holiday/Leave Routes
Route::prefix('holiday')->name('holiday.')->group(function () {
    Route::get('/', ListHoliday::class)->name('list');
    Route::get('/{id}', ShowHoliday::class)->name('show');
});

// HR Management Routes
Route::prefix('hrm')->name('hrm.')->group(function () {
    Route::get('/', HrmIndex::class)->name('index');
    Route::get('/employee', EmployeeHrm::class)->name('employee');
    Route::get('/position', PositionHrm::class)->name('position');
});

// Asset Management Routes
Route::prefix('asset')->name('asset.')->group(function () {
    Route::get('/', ListAsset::class)->name('list');
    Route::get('/{id}', ShowAsset::class)->name('show');
});

// BOM (Bill of Materials) Routes
Route::prefix('bom')->name('bom.')->group(function () {
    Route::get('/', ListBom::class)->name('list');
    Route::get('/{id}', ShowBom::class)->name('show');
});

// Manufacturing Order Routes
Route::prefix('mrp')->name('mrp.')->group(function () {
    Route::get('/', MrpIndex::class)->name('index');
    Route::get('/mo', ListManufacturingOrders::class)->name('list');
    Route::get('/mo/{id}', ShowManufacturingOrder::class)->name('show');
});

// Stock/Warehouse Routes
Route::prefix('product/stock')->name('stock.')->group(function () {
    Route::get('/', StockIndex::class)->name('index');
    Route::get('/{id}', ShowStock::class)->name('show');
    Route::get('/mouvement', MovementStock::class)->name('mouvement');
});

// Category Routes
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', CategoriesIndex::class)->name('index');
    Route::get('/{id}', ShowCategories::class)->name('show');
});

// Bookmarks Routes
Route::prefix('bookmarks')->name('bookmarks.')->group(function () {
    Route::get('/', BookmarksIndex::class)->name('index');
    Route::get('/{id}', ShowBookmarks::class)->name('show');
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
    Route::get('/conferenceorbooth/{id}', ShowConferenceOrBoothEventOrganization::class)->name('conferenceorbooth');
});

// Booking/Calendar Routes
Route::prefix('bookcal')->name('bookcal.')->group(function () {
    Route::get('/', BookcalIndex::class)->name('index');
    Route::get('/calendar', CalendarBookcal::class)->name('calendar');
});

// Loan Routes
Route::prefix('loan')->name('loan.')->group(function () {
    Route::get('/', ListLoan::class)->name('list');
    Route::get('/{id}', ShowLoan::class)->name('show');
});

// Supplier Proposal Routes
Route::prefix('supplier_proposal')->name('supplier_proposal.')->group(function () {
    Route::get('/', ListSupplierProposal::class)->name('list');
    Route::get('/{id}', ShowSupplierProposal::class)->name('show');
});

// Variants Routes
Route::prefix('variants')->name('variants.')->group(function () {
    Route::get('/', ListVariants::class)->name('list');
    Route::get('/{id}', ShowVariants::class)->name('show');
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
