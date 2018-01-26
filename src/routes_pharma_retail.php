 <?php

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;

$routes = new Routing\RouteCollection();

$routes->add('default_route', new Routing\Route('/', array(
  '_controller' => 'User\\Controller\\LoginController::indexAction',
)));
$routes->add('login', new Routing\Route('/login', array(
  '_controller' => 'User\\Controller\\LoginController::indexAction',
)));
$routes->add('forgot_password', new Routing\Route('/forgot-password', array(
  '_controller' => 'User\\Controller\\LoginController::forgotPasswordAction',
)));
$routes->add('reset_password', new Routing\Route('/reset-password', array(
  '_controller' => 'User\\Controller\\LoginController::resetPasswordAction',
)));
$routes->add('send_otp', new Routing\Route('/send-otp', array(
  '_controller' => 'User\\Controller\\LoginController::sendOTPAction',
)));
$routes->add('logout', new Routing\Route('/logout', array(
  '_controller' => 'User\\Controller\\LoginController::logoutAction',
)));
$routes->add('me', new Routing\Route('/me', array(
  '_controller' => 'User\\Controller\\UserController::editProfileAction',
)));
$routes->add('dashboard', new Routing\Route('/dashboard', array(
  '_controller' => 'User\\Controller\\DashBoardController::indexAction',
)));

// medicines management
$routes->add('medicines_list', new Routing\Route('/medicines/list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Products\\Controller\\ProductsController::listMedicines',
    'pageNo' => 1,
    'perPage' => 100,
)));
$routes->add('medicines_update', new Routing\Route('/medicines/update/{itemCode}', array(
    '_controller' => 'PharmaRetail\\Products\\Controller\\ProductsController::createMedicines',
    'itemCode' => null
)));
$routes->add('medicines_create', new Routing\Route('/medicines/create', array(
    '_controller' => 'PharmaRetail\\Products\\Controller\\ProductsController::createMedicines',
    'itemCode' => null
)));

$routes->add('categories_list', new Routing\Route('/categories/list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Categories\\Controller\\CategoriesController::listCategories',
    'pageNo' => 1,
    'perPage' => 100,
)));

// supplier management
$routes->add('suppliers_create', new Routing\Route('/suppliers/create', array(
    '_controller' => 'PharmaRetail\\Suppliers\\Controller\\SupplierController::supplierCreateAction',
)));
$routes->add('suppliers_update', new Routing\Route('/suppliers/update/{supplierCode}', array(
    '_controller' => 'PharmaRetail\\Suppliers\\Controller\\SupplierController::supplierCreateAction',
    'supplierCode' => null,
)));
$routes->add('suppliers_delete', new Routing\Route('/suppliers/remove/{supplierCode}', array(
    '_controller' => 'PharmaRetail\\Suppliers\\Controller\\SupplierController::supplierRemoveAction',
    'supplierCode' => null,    
)));
$routes->add('suppliers_view', new Routing\Route('/suppliers/view/{supplierCode}', array(
    '_controller' => 'PharmaRetail\\Suppliers\\Controller\\SupplierController::supplierViewAction',
    'supplierCode' => null,    
)));
$routes->add('suppliers_list', new Routing\Route('/suppliers/list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Suppliers\\Controller\\SupplierController::suppliersListAction',
    'pageNo' => 1,
    'perPage' => 50,
)));

// doctor routes
$routes->add('doctors_create', new Routing\Route('/doctors/create', array(
    '_controller' => 'PharmaRetail\\Doctors\\Controller\\DoctorsController::doctorCreateAction',
)));
$routes->add('doctors_update', new Routing\Route('/doctors/update/{doctorCode}', array(
    '_controller' => 'PharmaRetail\\Doctors\\Controller\\DoctorsController::doctorCreateAction',
    'doctorCode' => null,
)));
$routes->add('doctors_delete', new Routing\Route('/doctors/remove/{doctorCode}', array(
    '_controller' => 'PharmaRetail\\Doctors\\Controller\\DoctorsController::doctorRemoveAction',
    'doctorCode' => null,    
)));
$routes->add('doctors_view', new Routing\Route('/doctors/view/{doctorCode}', array(
    '_controller' => 'PharmaRetail\\Doctors\\Controller\\DoctorsController::doctorViewAction',
    'doctorCode' => null,    
)));
$routes->add('doctors_list', new Routing\Route('/doctors/list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Doctors\\Controller\\DoctorsController::doctorListAction',
    'pageNo' => 1,
    'perPage' => 50,
)));

// inward entry.
$routes->add('inward_entry', new Routing\Route('/inward-entry', array(
    '_controller' => 'PharmaRetail\\Inward\\Controller\\InwardController::inwardEntryAction',
)));
$routes->add('inward_entry_update', new Routing\Route('/inward-entry/update/{purchaseCode}', array(
    '_controller' => 'PharmaRetail\\Inward\\Controller\\InwardController::inwardEntryUpdateAction',
    'purchaseCode' => null,
)));
$routes->add('inward_entry_view', new Routing\Route('/inward-entry/view/{purchaseCode}', array(
    '_controller' => 'PharmaRetail\\Inward\\Controller\\InwardController::inwardEntryViewAction',
    'purchaseCode' => null,
)));
$routes->add('purchases_list', new Routing\Route('/purchase/list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Purchases\\Controller\\PurchasesController::purchaseListAction',
    'pageNo' => 1,
    'perPage' => 100,    
)));

// grn routes
$routes->add('grn_create_new', new Routing\Route('/grn/create', array(
    '_controller' => 'PharmaRetail\\Grn\\Controller\\GrnControllerNew::grnEntryCreateAction',
)));
$routes->add('grn_view', new Routing\Route('/grn/view/{grnCode}', array(
    '_controller' => 'PharmaRetail\\Grn\\Controller\\GrnController::grnViewAction',
    'grnCode' => null,
)));
$routes->add('grn_list', new Routing\Route('/grn/list', array(
    '_controller' => 'PharmaRetail\\Grn\\Controller\\GrnController::grnListAction',
)));

// sales routes
$routes->add('sales_entry', new Routing\Route('/sales/entry', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesController::salesEntryAction',
)));
$routes->add('sales_entry_landing_cost', new Routing\Route('/sales-entry-with/landing-cost', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesControllerAddOn::salesEntryAction',
)));
$routes->add('sales_entry_gst', new Routing\Route('/sales-gst/entry', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesControllerGst::salesEntryGstAction',
)));
$routes->add('sales_update', new Routing\Route('/sales/update/{salesCode}', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesController::salesEntryAction',
    'salesCode' => null,
)));
$routes->add('sales_view', new Routing\Route('/sales/view/{salesCode}', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesController::salesViewAction',
    'salesCode' => null,
)));
$routes->add('sales_remove', new Routing\Route('/sales/remove/{salesCode}', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesController::salesRemoveAction',
    'salesCode' => null,
)));
$routes->add('sales_list', new Routing\Route('/sales/list/{pageNo}', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesController::salesListAction',
    'pageNo' => null,
)));
$routes->add('sales_bill_search', new Routing\Route('/sales/search-bills', array(
    '_controller' => 'PharmaRetail\\Sales\\Controller\\SalesController::saleBillsSearchAction',
)));

// sales return routes
$routes->add('sales_return_entry', new Routing\Route('/sales-return/entry/{salesCode}', array(
    '_controller' => 'PharmaRetail\\SalesReturns\\Controller\\SalesReturnsController::salesReturnEntryAction',
    'salesCode' => null,
)));
$routes->add('sales_return_view', new Routing\Route('/sales-return/view/{salesCode}/{salesReturnCode}', array(
    '_controller' => 'PharmaRetail\\SalesReturns\\Controller\\SalesReturnsController::salesReturnViewAction',
    'salesCode' => null,
    'salesReturnCode' => null,
)));
$routes->add('sales_return_list', new Routing\Route('/sales-return/list', array(
    '_controller' => 'PharmaRetail\\SalesReturns\\Controller\\SalesReturnsController::salesReturnListAction',
)));

// opening balances
$routes->add('openings_list', new Routing\Route('/opbal/list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Openings\\Controller\\OpeningsController::opBalListAction',
    'pageNo' => null,
    'perPage' => null,    
)));
$routes->add('openings_add', new Routing\Route('/opbal/add', array(
    '_controller' => 'PharmaRetail\\Openings\\Controller\\OpeningsController::opBalCreateAction',    
)));
$routes->add('openings_update', new Routing\Route('/opbal/update/{opCode}', array(
    '_controller' => 'PharmaRetail\\Openings\\Controller\\OpeningsController::opBalCreateAction',
    'opCode' => null,
)));

// inventory
$routes->add('qty_available', new Routing\Route('/inventory/available-qty/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::availableQtyList',
    'pageNo' => null,
    'perPage' => null,
)));
$routes->add('item_track', new Routing\Route('/inventory/track-item', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::trackItem',   
)));
$routes->add('item_qty_available', new Routing\Route('/inventory/search-medicines', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::searchItem',   
)));
$routes->add('add_stock_adjustment', new Routing\Route('/inventory/stock-adjustment', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::addStockAdjustment',   
)));
$routes->add('stock_adjustment_list', new Routing\Route('/inventory/stock-adjustments-list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::getAllStockAdjustments',
    'pageNo' => null,
    'perPage' => null,
)));
$routes->add('trash_expired_items', new Routing\Route('/inventory/trash-expired-items/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::trashExpiredItems',
    'pageNo' => null,
    'perPage' => null,    
)));
$routes->add('add_item_threshold', new Routing\Route('/inventory/item-threshold-add', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::itemThresholdAdd',   
)));
$routes->add('update_item_threshold', new Routing\Route('/inventory/item-threshold-update/{thrCode}', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::itemThresholdUpdate',
    'thrCode' => null,      
)));
$routes->add('del_item_threshold', new Routing\Route('/inventory/item-threshold-delete', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::itemThresholdDelete',
    'thrCode' => null,      
)));
$routes->add('list_item_threshold', new Routing\Route('/inventory/item-threshold-list/{pageNo}', array(
    '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::itemThresholdList',
    'pageNo' => 1,
)));

// async calls
$routes->add('async', new Routing\Route('/async/{apiString}', array(
    '_controller' => 'PharmaRetail\\Async\\Controller\\AsyncController::asyncRequestAction',
    'apiString' => null,
)));

// customer routes
$routes->add('patients_create', new Routing\Route('/patients/create', array(
    '_controller' => 'PharmaRetail\\Customers\\Controller\\CustomersController::customerCreateAction',
)));
$routes->add('patients_update', new Routing\Route('/patients/update/{regCode}', array(
    '_controller' => 'PharmaRetail\\Customers\\Controller\\CustomersController::customerUpdateAction',
    'regCode' => null,
)));
$routes->add('patients_view', new Routing\Route('/patients/view/{custCode}', array(
    '_controller' => 'PharmaRetail\\Customers\\Controller\\CustomersController::customerViewAction',
    'custCode' => null,
)));
$routes->add('patients_list', new Routing\Route('/patients/list/{pageNo}/{perPage}', array(
    '_controller' => 'PharmaRetail\\Customers\\Controller\\CustomersController::customerListAction',
    'pageNo' => null,
    'perPage' => null,
)));

// supplier opening balance
$routes->add('fin_supp_opbal_create', new Routing\Route('/fin/supp-opbal/create', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\FinSuppOpBalController::supplierOpBalCreateAction',
)));
$routes->add('fin_supp_opbal_update', new Routing\Route('/fin/supp-opbal/update/{opBalCode}', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\FinSuppOpBalController::supplierOpBalUpdateAction',
    'opBalCode' => null,
)));
$routes->add('fin_supp_opbal_list', new Routing\Route('/fin/supp-opbal/list', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\FinSuppOpBalController::supplierOpBalListAction',
)));

// supplier outstanding
$routes->add('fin_supp_billwise_outstanding', new Routing\Route('/fin/billwise-outstanding', array(
  '_controller' => 'PharmaRetail\\Finance\\Controller\\FinSuppOpBalController::supplierBillwiseOsAction',
)));
$routes->add('fin_supp_ason_outstanding', new Routing\Route('/fin/supp-outstanding-ason', array(
  '_controller' => 'PharmaRetail\\Finance\\Controller\\FinSuppOpBalController::supplierBillwiseAsonAction',
)));
$routes->add('fin_supp_ledger', new Routing\Route('/fin/supplier-ledger', array(
  '_controller' => 'PharmaRetail\\Finance\\Controller\\FinSuppOpBalController::supplierLedger',
)));

// receivables
$routes->add('fin_receivables', new Routing\Route('/fin/receivables-ason', array(
  '_controller' => 'PharmaRetail\\Finance\\Controller\\ReceiptsController::receivablesListAsonAction',
)));
$routes->add('receipt_voc_create', new Routing\Route('/fin/receipt-voucher/create', array(
  '_controller' => 'PharmaRetail\\Finance\\Controller\\ReceiptsController::receiptCreateAction',
)));
$routes->add('receipt_voc_update', new Routing\Route('/fin/receipt-voucher/update/{vocNo}', array(
  '_controller' => 'PharmaRetail\\Finance\\Controller\\ReceiptsController::receiptUpdateAction',
  'vocNo' => null,
)));
$routes->add('receipt_voc_list', new Routing\Route('/fin/receipt-vouchers/{pageNo}', array(
  '_controller' => 'PharmaRetail\\Finance\\Controller\\ReceiptsController::receiptsListAction',
  'pageNo' => null,
)));

// payment vouchers
$routes->add('payment_voc_create', new Routing\Route('/fin/payment-voucher/create', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\PaymentsController::paymentCreateAction',
)));
$routes->add('payment_voc_update', new Routing\Route('/fin/payment-voucher/update/{vocNo}', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\PaymentsController::paymentUpdateAction',
    'vocNo' => null,
)));
$routes->add('payment_voc_list', new Routing\Route('/fin/payment-vouchers/{pageNo}', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\PaymentsController::paymentsListAction',
    'pageNo' => null,
)));

// banks management
$routes->add('bank_create', new Routing\Route('/fin/bank/create', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\BanksController::bankCreateAction',
)));
$routes->add('bank_update', new Routing\Route('/fin/bank/update/{bankCode}', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\BanksController::bankUpdateAction',
    'bankCode' => null,
)));
$routes->add('banks_list', new Routing\Route('/fin/bank/list', array(
    '_controller' => 'PharmaRetail\\Finance\\Controller\\BanksController::banksListAction',
)));

// users management
$routes->add('users_list', new Routing\Route('/users/list', array(
    '_controller' => 'User\\Controller\\UserController::listUsersAction',
)));
$routes->add('users_update', new Routing\Route('/users/update/{uuid}', array(
    '_controller' => 'User\\Controller\\UserController::updateUserAction',
    'uuid' => null,
)));
$routes->add('users_create', new Routing\Route('/users/create', array(
    '_controller' => 'User\\Controller\\UserController::createUserAction',
)));

// admin Options
$routes->add('adminOptions_askForBillNo', new Routing\Route('/admin-options/enter-bill-no', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsController::askForBillNo',
)));
$routes->add('adminOptions_editBusinessInfo', new Routing\Route('/admin-options/edit-business-info', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsController::editBusinessInfoAction',
)));
$routes->add('adminOptions_editSalesBill', new Routing\Route('/admin-options/edit-sales-bill', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsController::editSalesBillAction',
)));
$routes->add('adminOptions_editPO', new Routing\Route('/admin-options/edit-po', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsController::editPoAction',
)));
$routes->add('adminOptions_updateBatchQtys', new Routing\Route('/admin-options/update-batch-qtys', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsController::updateBatchQtys',
)));
$routes->add('adminOptions_deleteSaleBill', new Routing\Route('/admin-options/delete-sale-bill', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsController::deleteSaleBill',
)));
$routes->add('adminOptions_uploadInventory', new Routing\Route('/admin-options/upload-inventory', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsInvenController::uploadInventory',
)));
$routes->add('adminOptions_deleteGrn', new Routing\Route('/admin-options/deleteGrn', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsInvenController::deleteGrn',
)));
$routes->add('adminOptions_updateGrn', new Routing\Route('/admin-options/updateGrn', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsInvenController::updateGrn',
)));
$routes->add('adminOptions_askForGrnNo', new Routing\Route('/admin-options/enter-grn-no', array(
    '_controller' => 'PharmaRetail\\AdminOptions\\Controller\\AdminOptionsInvenController::askForGrnNo',
)));

// taxes management
$routes->add('add_tax', new Routing\Route('/taxes/add', array(
  '_controller' => 'PharmaRetail\\Taxes\\Controller\\TaxesController::addTax',
)));
$routes->add('update_tax', new Routing\Route('/taxes/update/{taxCode}', array(
  '_controller' => 'PharmaRetail\\Taxes\\Controller\\TaxesController::updateTax',
  'taxCode' => null,
)));
$routes->add('list_taxes', new Routing\Route('/taxes/list', array(
  '_controller' => 'PharmaRetail\\Taxes\\Controller\\TaxesController::listTaxes',
)));

// error page
$routes->add('error_page', new Routing\Route('/error', array(
    '_controller' => 'PharmaRetail\\User\\Controller\\DashBoardController::errorAction',
)));
$routes->add('error_page_404', new Routing\Route('/error-404', array(
    '_controller' => 'PharmaRetail\\User\\Controller\\DashBoardController::errorActionNotFound',
)));

// reports
$routes->add('report_filterOptions', new Routing\Route('/report-options/{reportName}', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsController::reportOptions',
    'reportName' => null,
)));
$routes->add('report_printSalesBill', new Routing\Route('/print-sales-bill', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsController::printSalesBill',
)));
$routes->add('report_printSalesBillGST', new Routing\Route('/print-sales-bill-gst', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesControllerMore::printSalesBillGST',
)));
$routes->add('report_printSalesBillSmall', new Routing\Route('/print-sales-bill-small', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsController::printSalesBillSmall',
)));
$routes->add('report_printSalesReturnBill', new Routing\Route('/print-sales-return-bill', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesReturnController::printSalesReturnBill',
)));
$routes->add('report_salesRegister', new Routing\Route('/sales-register', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::salesRegister',
)));
$routes->add('report_salesReturnRegister', new Routing\Route('/sales-return-register', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesReturnController::salesReturnRegister',
)));
$routes->add('report_grnRegister', new Routing\Route('/grn-register', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::grnRegister',
)));
$routes->add('report_printDaywiseSalesSummary', new Routing\Route('/sales-summary-by-month', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::printSalesSummaryByMonth',
)));
$routes->add('report_printDaySales', new Routing\Route('/day-sales-report', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::printDaySalesSummary',
)));
$routes->add('report_billSummaryPatient', new Routing\Route('/sales-summary-patient', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::patientBillSummary',
)));
$routes->add('report_printStockReport', new Routing\Route('/stock-report', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::stockReport',
)));
$routes->add('report_printStockReportNew', new Routing\Route('/stock-report-new', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::stockReportNew',
)));
$routes->add('report_printExpiryReport', new Routing\Route('/expiry-report', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::expiryReport',
)));
$routes->add('report_printItemSalesReport', new Routing\Route('/itemwise-sales-report', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::itemWiseSalesReport',
)));
$routes->add('report_printItemSalesReportByMode', new Routing\Route('/itemwise-sales-report-bymode', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::itemWiseSalesReportByMode',
)));
$routes->add('report_salesByMode', new Routing\Route('/sales-by-mode', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::salesByMode',
)));
$routes->add('report_supplierPaymentsSummary', new Routing\Route('/supplier-payments-due', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsFinanceController::supplierPaymentsDue',
)));
$routes->add('report_supplierOs', new Routing\Route('/suppliers-os-report', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsFinanceController::supplierOutstanding',
)));
$routes->add('report_stockAdjEntries', new Routing\Route('/adj-entries', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::adjEntries',
)));
$routes->add('report_sreturnsItemwise', new Routing\Route('/itemwise-sales-returns', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesReturnController::itemwiseSalesReturns',
)));
$routes->add('report_materialmovement', new Routing\Route('/material-movement', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::materialMovement',
)));
$routes->add('report_printGrn', new Routing\Route('/print-grn/{grnCode}', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsGrnController::printGrn',
    'grnCode' => null,
)));
$routes->add('report_printItemThreshold', new Routing\Route('/print-itemthr-level/{pageNo}', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::printItemthrLevel',
    'pageNo' => null,
)));
$routes->add('report_ioAnalysis', new Routing\Route('/io-analysis', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsAnalysisController::ioAnalysis',
)));
$routes->add('report_itemMaster', new Routing\Route('/item-master', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::itemMaster',
)));
$routes->add('report_payablesMonthwise', new Routing\Route('/payables-monthwise', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsFinanceController::payablesMonthwise',
)));
$routes->add('report_inventoryProfitability', new Routing\Route('/inventory-profitability', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsInventoryController::inventoryProfitability',
)));
$routes->add('report_momComparison', new Routing\Route('/mom-comparison', array(
    '_controller' => 'PharmaRetail\\Reports\\Controller\\ReportsSalesController::momComparison',
)));
$routes->add('create_products_cache', new Routing\Route('/products/cache-create', array(
  '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::createCacheAction',
)));
$routes->add('get_products_from_cache', new Routing\Route('/products/cache-get', array(
  '_controller' => 'PharmaRetail\\Inventory\\Controller\\InventoryController::getProductsFromCacheAction',
)));

return $routes;