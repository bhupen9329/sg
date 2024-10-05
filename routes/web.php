<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InwardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\EmaailSettingController;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\OutwardController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\WareHouseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ValuationController;
use App\Http\Controllers\ManualMatching;
use App\Models\CompanySetting;
use App\Models\SubCategory;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });




Route::get('/', function () {
    return view('auth.login');
})->name('login');


Auth::routes();

Route::post('/login', [LoginController::class, 'login']);
Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);



    // dashboard Route
    Route::get('/dashboard', [HomeController::class, 'dashboard_index'])->name('dashboard');
    Route::post('/save-notes', [HomeController::class, 'save_notes'])->name('save_notes');
    Route::post('/base-price-store', [HomeController::class, 'base_price_store'])->name('base-price.store');
    Route::post('/get-category-data', [HomeController::class, 'get_category_data'])->name('get_category_data');

    Route::get('/logout', [HomeController::class, 'logout']);
    Route::get('/users_profile/{user_id}', [ProfileController::class, 'index'])->name('users_profile');
    Route::post('/profile-update/{user_id}', [ProfileController::class, 'update'])->name('profile_update');
    Route::post('/password-update/{user_id}', [ProfileController::class, 'password_reset'])->name('password.update');

    // .........................................buyers/suppliers.............................................
    Route::get('/buyers_create', [CompanyController::class, 'create'])->name('buyers.create');
    Route::post('/buyers_store', [CompanyController::class, 'store'])->name('buyers.store');
    Route::get('/buyers_edit/{id}', [CompanyController::class, 'edit'])->name('buyers.edit');
    Route::post('/buyers_update/{id}', [CompanyController::class, 'update'])->name('buyers.update');
    Route::delete('/buyers_delete/{id}', [CompanyController::class, 'delete'])->name('buyers.destroy');
    Route::get('/buyers', [CompanyController::class, 'index'])->name('buyers.index');
    Route::get('/buyers_show/{id}', [CompanyController::class, 'show'])->name('buyers.show');
    Route::post('/get_check_buyer_supplier_name', [CompanyController::class, 'get_check_buyer_supplier_name']);
    Route::post('/get_buyer_supplier_name_edit', [CompanyController::class, 'get_buyer_supplier_name_edit']);






    // .........................................Purchase.............................................
    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.index');
    Route::post('/purchase-create', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/save-purchase-order', [PurchaseController::class, 'store'])->name('save.purchase-order');
    Route::get('/purchase-edit/{id}', [PurchaseController::class, 'edit'])->name('purchase.edit');
    Route::post('/purchase-update', [PurchaseController::class, 'update'])->name('purchase.update');
    Route::delete('/purchase-delete/{id}', [PurchaseController::class, 'delete'])->name('purchase.destroy');
    Route::get('/purchase-show/{id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/po-partial-received-save', [PurchaseController::class, 'partial_receive_save'])->name('po-partial-receive.save');
    Route::post('/po-partial-closed-save', [PurchaseController::class, 'partial_closed_save'])->name('po-partial-closed.save');
    Route::post('/total-closed', [PurchaseController::class, 'total_closed'])->name('total.closed');
    Route::post('/get_received_quantity', [PurchaseController::class, 'get_received_quantity']);
    Route::post('/get_received_qty', [PurchaseController::class, 'get_received_qty']);
    Route::post('/update-partial-received-quantity', [PurchaseController::class, 'update_partial_received_quantity']);
    Route::get('/back', [PurchaseController::class, 'back']);

    // .................................Warehouse Route Start..................................................

    Route::get('/warehouse', [WareHouseController::class, 'index'])->name('warehouse.index');
    Route::get('/warehouse-create', [WareHouseController::class, 'create'])->name('warehouse.create');
    Route::post('/warehouse-store', [WareHouseController::class, 'store'])->name('warehouse.store');
    Route::get('/warehouse-show/{id}', [WareHouseController::class, 'show'])->name('warehouse.show');
    Route::get('/warehouse-edit/{id}', [WareHouseController::class, 'edit'])->name('warehouse.edit');
    Route::post('/warehouse-update/{id}', [WareHouseController::class, 'update'])->name('warehouse.update');
    Route::delete('/warehouse-delete/{id}', [WareHouseController::class, 'delete'])->name('warehouse.destroy');
    Route::post('/get_city_list', [WareHouseController::class, 'get_city_list'])->name('get_city_list');
 

    // ............................................ Company Email............................................
    Route::get('/email', [EmailSettingController::class, 'index'])->name('email.create');
    Route::post('emails-edit', [EmailSettingController::class, 'update'])->name('emails.update');


    // ............................................ Company Setting............................................
    Route::get('/company-setting', [CompanySettingController::class, 'index'])->name('setting.company_create');
    Route::post('company-edit', [CompanySettingController::class, 'update'])->name('setting.company_update');

    // ............................................ Shortage Setting............................................
    Route::get('/shortage-setting', [CompanySettingController::class, 'shortage_index'])->name('setting.shortage_create');
    Route::post('shortage-edit', [CompanySettingController::class, 'shortage_update'])->name('setting.shortage_update');

    // ............................................ GST Setting............................................
    Route::get('/gst-setting', [CompanySettingController::class, 'gstindex'])->name('setting.gst');
    Route::post('/gst-setting-store', [CompanySettingController::class, 'gst_store'])->name('setting_gst.store');
    Route::get('/gst-setting-delete/{id}', [CompanySettingController::class, 'gst_setting_delete'])->name('gst_setting.destroy');
    Route::post('/gst-setting-update', [CompanySettingController::class, 'gst_update'])->name('setting_gst.update');
    Route::post('/get_gst_details', [CompanySettingController::class, 'get_gst_details'])->name('get_gst_details');





    Route::post('gst-edit', [CompanySettingController::class, 'gstupdate'])->name('setting.gst_update');

    // ............................................ Category............................................
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category-create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('-store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category-edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/category-update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category-delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
    Route::post('/get_category_name', [CategoryController::class, 'get_category_name']);
    Route::post('/get_category_name_edit', [CategoryController::class, 'get_category_name_edit']);

    // ............................................ Sub Category Controller............................................
    Route::get('/subcategory-create', [SubCategoryController::class, 'create'])->name('subcategory.create');
    Route::post('subcategory-store', [SubCategoryController::class, 'store'])->name('subcategory.store');
    Route::get('/subcategory', [SubCategoryController::class, 'index'])->name('subcategory.index');
    Route::delete('/subcategory-delete/{id}', [SubCategoryController::class, 'delete'])->name('subcategory.delete');
    Route::get('/subcategory-edit/{id}', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
    Route::post('/subcategory-update/{id}', [SubCategoryController::class, 'update'])->name('subcategory.update');
    Route::post('/get_providers_details', [SubCategoryController::class, 'get_providers_details']);
    Route::post('/get_sub_category_name', [SubCategoryController::class, 'get_sub_category_name']);
    Route::post('/get_sub_category_name_edit', [SubCategoryController::class, 'get_sub_category_name_edit']);



    // ............................................ Sales............................................
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales-create/', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales-create-quotation', [SalesController::class, 'create_quotation'])->name('sales_quotation.create');
    Route::post('/get_subcategory_list', [SubCategoryController::class, 'get_subcategory_list'])->name('get_subcategory_list');
    Route::post('/get_subcategory_details', [SubCategoryController::class, 'get_subcategory_details'])->name('get_subcategory_details');
    Route::post('sales-store', [SalesController::class, 'store'])->name('sales.store');
    Route::post('sales-quotation-store', [SalesController::class, 'store_quotation'])->name('sales_quotation.store');
    Route::get('sales-edit/{id}', [SalesController::class, 'edit'])->name('sales.edit');
    Route::post('sales-update/{id}', [SalesController::class, 'update'])->name('sales.update');
    Route::get('sales-show/{id}', [SalesController::class, 'show'])->name('sales.show');
    Route::delete('/sales-delete/{id}', [SalesController::class, 'delete'])->name('sales.destroy');
    Route::post('/sales-closed', [SalesController::class, 'close'])->name('sales.closed');
    Route::get('/get_quotation/{id}', [SalesController::class, 'get_quotation_details']);
    Route::get('/get_sales_order/{id}', [SalesController::class, 'get_sales_details']);
    Route::get('/sales_order_pdf/{id}', [PdfController::class, 'sales_order_pdf'])->name('sales.pdf');
    Route::post('/get-so-remark-for-modal', [SalesController::class, 'get_remark']);
    Route::post('/sales/closed', [SalesController::class, 'closeSales'])->name('sales.closed');
    Route::post('/get-so-remark-for-modal', [SalesController::class, 'getSoRemarkForModal']);




    // ............................................Sub Category............................................
    Route::get('/subcategory-create', [SubCategoryController::class, 'create'])->name('subcategory.create');
    Route::post('subcategory-store', [SubCategoryController::class, 'store'])->name('subcategory.store');

    // ............................................ Quotation............................................
    Route::get('/quotation', [QuotationController::class, 'index'])->name('quotation.index');
    Route::post('/quotation-create', [QuotationController::class, 'create'])->name('quotation.create');
    Route::post('/quotation-store', [QuotationController::class, 'store'])->name('quotation.store');
    Route::get('/quotation-show/{id}', [QuotationController::class, 'show'])->name('quotation.show');
    Route::get('/quotation-edit/{id}', [QuotationController::class, 'edit'])->name('quotation.edit');
    Route::post('/quotation-update/{id}', [QuotationController::class, 'update'])->name('quotation.update');
    Route::get('/quotation-delete/{id}', [QuotationController::class, 'delete'])->name('quotation.destroy');
    Route::get('/quotation_pdf/{qt_id}', [PdfController::class, 'quotation_pdf'])->name('quotation.pdf');
    Route::post('/get_sub_category', [QuotationController::class, 'get_sub_category']);
    Route::post('/get_sub_category_list', [QuotationController::class, 'get_sub_category_list']);
    Route::post('/get_email_details', [QuotationController::class, 'get_email_details'])->name('get_email_details');
    Route::post('/send_email', [QuotationController::class, 'send_email'])->name('quotation.send_email');

 
    // ............................................ Inward............................................
    Route::get('/inward', [InwardController::class, 'index'])->name('inward.index');
    Route::post('/inward-create', [InwardController::class, 'create'])->name('inward.create');
    Route::post('/Purchase-inward-create', [InwardController::class, 'Purchase_inward_create'])->name('Purchase-inward.create');
    Route::post('/inward-store', [InwardController::class, 'store'])->name('inward.save');
    Route::get('/inward-edit/{id}', [InwardController::class, 'edit'])->name('inward.edit');
    Route::post('/inward-update/{id}', [InwardController::class, 'update'])->name('inward.update');
    Route::post('/inward-approve/{id}', [InwardController::class, 'approve'])->name('inward.approve');
    Route::delete('/inward-delete/{id}', [InwardController::class, 'delete'])->name('inward.destroy');
    Route::post('/change-credit-note-status', [InwardController::class, 'change_credit_note_status'])->name('change_credit_note.status');
    Route::post('/get_subcategory_list', [SubCategoryController::class, 'get_subcategory_list'])->name('get_subcategory_list');
    Route::post('/get_subcategory_details', [SubCategoryController::class, 'get_subcategory_details'])->name('get_subcategory_details');
    Route::post('/get_po_number_for_supplier', [InwardController::class, 'get_po_number_for_supplier']);
    Route::post('/get_current_quantity_list_form_po', [InwardController::class, 'current_quantity_form_po']);

 

 
    // ............................................ Outward............................................
    Route::get('/outward', [OutwardController::class, 'index'])->name('outward.index');
    Route::post('/outward-create', [OutwardController::class, 'create'])->name('outward.create');
    Route::post('/outward-store', [OutwardController::class, 'store'])->name('outward.store');
    Route::get('/outward-edit/{id}', [OutwardController::class, 'edit'])->name('outward.edit');
    Route::post('/outward-soupdate/{id}', [OutwardController::class, 'soupdate'])->name('outward.soupdate');
    Route::post('/outward-update/{id}', [OutwardController::class, 'update'])->name('outward.update');
    Route::get('/outward-approve/{id}', [OutwardController::class, 'approve'])->name('outward.approve');
    Route::get('/outward-delete/{id}', [OutwardController::class, 'delete'])->name('outward.delete');
    Route::post('/outward-create-sales', [OutwardController::class, 'create_so'])->name('outward_sales.create');
    Route::post('/outward-store-sales', [OutwardController::class, 'store_so'])->name('outward_sales.store');
    Route::get('/outward-edit-sales/{id}', [OutwardController::class, 'edit_so'])->name('outward_sales.edit');
    Route::post('/outward-update-sales/{id}', [OutwardController::class, 'update_so'])->name('outward_sales.update');
    Route::post('/outward-bill', [OutwardController::class, 'bill'])->name('outward.bill');
    Route::post('/get_current_quantity_list_from_virtual_store', [OutwardController::class, 'virtual_store']);
    Route::post('/get_so_number', [OutwardController::class, 'so_number']);




    // ............................................ Stocks ............................................
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock-create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock-store', [StockController::class, 'store'])->name('stock.save');
    Route::post('/get_current_quantity_list', [StockController::class, 'get_current_quantity_list']);
    Route::post('/get_reserved_details', [StockController::class, 'get_reserved_details']);
    Route::post('/get_value_for_virtual_store', [StockController::class, 'value_for_virtual_store']);


    // ............................................ Stock Adjustment​............................................
    Route::get('/stock-adjustment​', [StockAdjustmentController::class, 'index'])->name('adjustment.index');
    Route::post('/stock-adjustment​-create', [StockAdjustmentController::class, 'create'])->name('stock-adjustment​.create');
    Route::post('/stock-adjustment​-store', [StockAdjustmentController::class, 'store'])->name('stock-adjustment​.save');
    Route::get('/stock-adjustment​-show/{id}', [StockAdjustmentController::class, 'show'])->name('stock-adjustment​.show');
    Route::get('/stock-adjustment​-edit/{id}', [StockAdjustmentController::class, 'edit'])->name('stock-adjustment​.edit');
    Route::get('/stock-adjustment​-update/{id}', [StockAdjustmentController::class, 'update'])->name('stock-adjustment​.update');
    Route::delete('/stock-adjustment​-delete/{id}', [StockAdjustmentController::class, 'delete'])->name('stock-adjustment​.destroy');
    Route::post('/get_current_qty', [StockAdjustmentController::class, 'checkQuantity'])->name('get_current_qty');
    Route::post('/get_Category_details', [StockAdjustmentController::class, 'Categorydetails']);




 

    //...............................................Reports............................................................................
    Route::get('/po-report', [ReportController::class, 'po_report'])->name('po_report');
    Route::post('/report-po', [ReportController::class, 'get_po_report'])->name('get_po_report');

    Route::get('/so-report', [ReportController::class, 'so_report'])->name('so_report');
    Route::post('/report-so', [ReportController::class, 'get_so_report'])->name('get_so_report');

    Route::get('/inward-report', [ReportController::class, 'inward_report'])->name('inward_report');
    Route::post('/report-inward', [ReportController::class, 'get_inward_report'])->name('get_inward_report');

    Route::get('/quotation-report', [ReportController::class, 'quotationso_report'])->name('quotation_report');
    Route::post('/report-quotation', [ReportController::class, 'get_quotationso_report'])->name('get_quotation_report');

    Route::get('/outward-report', [ReportController::class, 'outward_report'])->name('outward_report');
    Route::post('/report-outward', [ReportController::class, 'get_outward_report'])->name('get_outward_report');          

    Route::get('/stock-report', [ReportController::class, 'stock_report'])->name('stock_report');
    Route::post('/report-stock', [ReportController::class, 'get_stock_report'])->name('get_stock_report');
 

    Route::get('/top-selling-report', [ReportController::class, 'top_selling_report'])->name('top_selling_report');
    Route::post('/report-top_selling', [ReportController::class, 'get_top_selling_report'])->name('get_top_selling_report');

    // Route::get('/quotation-execution-report', [ReportController::class, 'quotation_execution_report'])->name('quotation_execution_report');
    // Route::post('/report-quotation-execution', [ReportController::class, 'get_quotation_execution_report'])->name('get_quotation_execution_report');

    Route::get('/stock-transaction-report', [ReportController::class, 'stock_transaction_report'])->name('stock_transaction_report');
    Route::post('/report-transaction', [ReportController::class, 'get_stock_transaction_report'])->name('get_stock_transaction_report');


    Route::get('/ageing-report', [ReportController::class, 'ageing_report'])->name('ageing_report');
    Route::post('/ageing-report-get', [ReportController::class, 'get_ageing_report'])->name('ageing_report_get');

    

    Route::get('/lifo-report', [ReportController::class, 'lifo_report'])->name('lifo_report');
    
    Route::get('/show_lifo', [ReportController::class, 'showLIFOReport'])->name('show.lifo');
    Route::get('/calculate_fifo', [ReportController::class, 'calculateFIFO'])->name('inventory.fifo');
    Route::get('/calculate_average', [ReportController::class, 'calculateAverageCost'])->name('inventory.average');


    // .........................................Transactions.............................................
    Route::get('/stock_transaction', [TransactionController::class, 'index'])->name('stock_transaction.index');

    Route::get('/increment-age', [StockController::class, 'incrementAge']);

    Route::get('subcategory-export', [SubCategoryController::class, 'export'])->name('subcategory_export');
    Route::post('/subcategory-import', [SubCategoryController::class, 'import'])->name('subcategory_import');


        //...............................................Inventory Valuation............................................................................

        Route::get('/inventory_valuation', [ValuationController::class, 'index'])->name('inventory_valuation.index');
        Route::get('/calculate_lifo', [ValuationController::class, 'calculateLIFO'])->name('inventory.lifo');
        Route::get('/show_lifo_report', [ValuationController::class, 'showLifoReport'])->name('show.lifo');
        Route::get('/show_fifo_report', [ValuationController::class, 'showFifoReport'])->name('show.fifo');
        Route::get('/show_average_report', [ValuationController::class, 'showAverageReport'])->name('show.average');
        Route::post('/store_inventory', [ValuationController::class, 'store_inventory'])->name('store_inventory');
        Route::get('/inventory/filter', [ValuationController::class, 'filter'])->name('inventory.filter');
        // Route::get('/valuation', [ValuationController::class, 'valuation'])->name('inventory.valuation');
        Route::post('/inventory_valuation/get_inventory_list', [ValuationController::class, 'get_inventory_list'])->name('get_inventory_list');
        Route::post('/inventory/valuation-data', [ValuationController::class, 'getValuationData'])->name('inventory.getValuationData');
        Route::get('/transaction-details', [ValuationController::class, 'getTransactionDetails'])->name('inventory_valuation.valuation');
        Route::get('/position-report', [ValuationController::class, 'getPositionReport'])->name('position.report');

        // .................................................................................................................................................
        Route::get('/manual_matching', [ManualMatching::class, 'index'])->name('manual.matching');
});



