<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

Route::get('/', 'BackHomeController@index');

Route::middleware(['auth' => 'role:super-admin|owner'])->group(function () {
    //MASTER DATA
    //-----------------------------------------------------------------------------
    Route::post('products/option', 'Web\Master\ProductController@_option');
    Route::get('products/data', 'Web\Master\ProductController@_data');
    Route::get('products/json', 'Web\Master\ProductController@_json');
    Route::resource('products', 'Web\Master\ProductController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('services/option', 'Web\Master\ServicesController@_option');
    Route::get('services/data', 'Web\Master\ServicesController@_data');
    Route::get('services/json', 'Web\Master\ServicesController@_json');
    Route::resource('services', 'Web\Master\ServicesController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('members/data', 'Web\Master\MemberController@_data');
    Route::get('members/json', 'Web\Master\MemberController@_json');
    Route::resource('members', 'Web\Master\MemberController')->except([
        'edit'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('packages/add', 'Web\Master\PackageController@create')
        ->name('packages.create'); //form no modal
    Route::post('packages/option', 'Web\Master\PackageController@_option');
    Route::get('packages/data', 'Web\Master\PackageController@_data');
    Route::get('packages/json', 'Web\Master\PackageController@_json');
    Route::resource('packages', 'Web\Master\PackageController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('discounts/add', 'Web\Master\DiscountController@create')
        ->name('discounts.create'); //form no modal
    Route::post('discounts/option', 'Web\Master\DiscountController@_option');
    Route::get('discounts/data', 'Web\Master\DiscountController@_data');
    Route::get('discounts/json', 'Web\Master\DiscountController@_json');
    Route::resource('discounts', 'Web\Master\DiscountController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('vouchers/option', 'Web\Master\VoucherController@_option');
    Route::get('vouchers/data', 'Web\Master\VoucherController@_data');
    Route::get('vouchers/json', 'Web\Master\VoucherController@_json');
    Route::resource('vouchers', 'Web\Master\VoucherController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('employees/add', 'Web\Master\EmployeeController@create')
        ->name('employees.create'); //form no modal
    Route::post('employees/option', 'Web\Master\EmployeeController@_option');
    Route::get('employees/data', 'Web\Master\EmployeeController@_data');
    Route::get('employees/json', 'Web\Master\EmployeeController@_json');
    Route::resource('employees', 'Web\Master\EmployeeController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('suppliers/add', 'Web\Master\SupplierController@create')
        ->name('suppliers.create'); //form no mdal
    Route::post('suppliers/option', 'Web\Master\SupplierController@_option');
    Route::get('suppliers/data', 'Web\Master\SupplierController@_data');
    Route::get('suppliers/json', 'Web\Master\SupplierController@_json');
    Route::resource('suppliers', 'Web\Master\SupplierController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('banks/add', 'Web\Master\BankController@create')
        ->name('banks.create'); //form no mdal
    Route::post('banks/option', 'Web\Master\BankController@_option');
    Route::get('banks/data', 'Web\Master\BankController@_data');
    Route::get('banks/json', 'Web\Master\BankController@_json');
    Route::resource('banks', 'Web\Master\BankController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('locations/add', 'Web\Master\LocationController@create')
        ->name('locations.create'); //form no mdal
    Route::post('locations/option', 'Web\Master\LocationController@_option');
    Route::get('locations/data', 'Web\Master\LocationController@_data');
    Route::get('locations/json', 'Web\Master\LocationController@_json');
    Route::resource('locations', 'Web\Master\LocationController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('rooms/add', 'Web\Master\RoomController@create')
        ->name('rooms.create'); //form no mdal
    Route::post('rooms/option', 'Web\Master\RoomController@_option');
    Route::get('rooms/data', 'Web\Master\RoomController@_data');
    Route::get('rooms/json', 'Web\Master\RoomController@_json');
    Route::resource('rooms', 'Web\Master\RoomController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('categories/add', 'Web\Master\CategoryController@create')
        ->name('categories.create'); //form no mdal
    Route::get('categories/data', 'Web\Master\CategoryController@_data');
    Route::get('categories/json', 'Web\Master\CategoryController@_json');
    Route::resource('categories', 'Web\Master\CategoryController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('branchs/add', 'Web\Master\BranchController@create')
        ->name('branchs.create'); //form no mdal
    Route::get('branchs/data', 'Web\Master\BranchController@_data');
    Route::get('branchs/json', 'Web\Master\BranchController@_json');
    Route::resource('branchs', 'Web\Master\BranchController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('slides/add', 'Web\Master\SlideController@create')
        ->name('slides.create'); //form no mdal
    Route::get('slides/data', 'Web\Master\SlideController@_data');
    Route::get('slides/json', 'Web\Master\SlideController@_json');
    Route::resource('slides', 'Web\Master\SlideController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('galleries/add', 'Web\Master\GallerysController@create')
        ->name('galleries.create'); //form no mdal
    Route::get('galleries/data', 'Web\Master\GallerysController@_data');
    Route::get('galleries/json', 'Web\Master\GallerysController@_json');
    Route::resource('galleries', 'Web\Master\GallerysController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('news/add', 'Web\Master\BeritaController@create')
        ->name('news.create'); //form no mdal
    Route::get('news/data', 'Web\Master\BeritaController@_data');
    Route::get('news/json', 'Web\Master\BeritaController@_json');
    Route::resource('news', 'Web\Master\BeritaController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('homepages/add', 'Web\Master\HomePagesController@create')
        ->name('homepages.create'); //form no mdal
    Route::get('homepages/data', 'Web\Master\HomePagesController@_data');
    Route::get('homepages/json', 'Web\Master\HomePagesController@_json');
    Route::resource('homepages', 'Web\Master\HomePagesController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('promos/add', 'Web\Master\PromoController@create')
        ->name('promos.create'); //form no mdal
    Route::post('promos/option', 'Web\Master\PromoController@_option');
    Route::get('promos/data', 'Web\Master\PromoController@_data');
    Route::get('promos/json', 'Web\Master\PromoController@_json');
    Route::resource('promos', 'Web\Master\PromoController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('brands/add', 'Web\Master\BrandController@create')
        ->name('brands.create'); //form no mdal
    Route::get('brands/data', 'Web\Master\BrandController@_data');
    Route::get('brands/json', 'Web\Master\BrandController@_json');
    Route::resource('brands', 'Web\Master\BrandController')->except([
        'edit', 'show'
    ]);
    //LAIN LAIN
    //-----------------------------------------------------------------------------
    Route::get('set/shifts/data', 'Web\Setting\ShiftController@_data');
    Route::get('set/shifts/json', 'Web\Setting\ShiftController@_json');
    Route::resource('set/shifts', 'Web\Setting\ShiftController', [
        'names' => [
            'index' => 'shifts.index',
        ]
    ])->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('calendars/shift/option', 'Web\Setting\CalendarShiftController@_option');
    Route::get('calendars/shift/json', 'Web\Setting\CalendarShiftController@_json');
    Route::put('calendars/shift', 'Web\Setting\CalendarShiftController@update');
    Route::resource('calendars/shift', 'Web\Setting\CalendarShiftController', [
        'names' => [
            'index' => 'calendars.index',
            'store' => 'calendars.store',
        ]
    ])->except([
        'edit', 'show'
    ]);
});

Route::middleware(['auth' => 'role:super-admin|finance|owner'])->group(function () {
    //MASTER DATA
    //-----------------------------------------------------------------------------
    Route::post('products/option', 'Web\Master\ProductController@_option');
    Route::get('products/data', 'Web\Master\ProductController@_data');
    Route::get('products/json', 'Web\Master\ProductController@_json');
    Route::resource('products', 'Web\Master\ProductController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('services/option', 'Web\Master\ServicesController@_option');
    Route::get('services/data', 'Web\Master\ServicesController@_data');
    Route::get('services/json', 'Web\Master\ServicesController@_json');
    Route::resource('services', 'Web\Master\ServicesController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('packages/add', 'Web\Master\PackageController@create')
        ->name('packages.create'); //form no modal
    Route::post('packages/option', 'Web\Master\PackageController@_option');
    Route::get('packages/data', 'Web\Master\PackageController@_data');
    Route::get('packages/json', 'Web\Master\PackageController@_json');
    Route::resource('packages', 'Web\Master\PackageController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('discounts/add', 'Web\Master\DiscountController@create')
        ->name('discounts.create'); //form no modal
    Route::get('discounts/data', 'Web\Master\DiscountController@_data');
    Route::get('discounts/json', 'Web\Master\DiscountController@_json');
    Route::resource('discounts', 'Web\Master\DiscountController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('vouchers/option', 'Web\Master\VoucherController@_option');
    Route::get('vouchers/data', 'Web\Master\VoucherController@_data');
    Route::get('vouchers/json', 'Web\Master\VoucherController@_json');
    Route::resource('vouchers', 'Web\Master\VoucherController')->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('employees/add', 'Web\Master\EmployeeController@create')
        ->name('employees.create'); //form no modal
    Route::post('employees/option', 'Web\Master\EmployeeController@_option');
    Route::get('employees/data', 'Web\Master\EmployeeController@_data');
    Route::get('employees/json', 'Web\Master\EmployeeController@_json');
    Route::resource('employees', 'Web\Master\EmployeeController')->except([
        'edit', 'show'
    ]);
});

Route::middleware(['auth'])->group(function () {
    //-----------------------------------------------------------------------------
    Route::post('profiles-upload', 'Web\Setting\ProfileController@_upload')
        ->name('profiles-upload');
    Route::get('profiles-detail', 'Web\Setting\ProfileController@_detail')
        ->name('profiles-detail');
    Route::resource('profiles', 'Web\Setting\ProfileController', [
        'names' => [
            'index' => 'profiles.index',
            'store' => 'profiles.store',
            'create' => 'profiles.create',
        ]
    ])->except([
        'edit', 'show', 'destroy', 'update'
    ]);
});

Route::middleware(['auth' => 'role:super-admin|finance|owner'])->group(function () {
    //-----------------------------------------------------------------------------
    Route::get('incomes/json', 'Web\Monitoring\PendapatanController@_json');
    Route::get('incomes/data', 'Web\Monitoring\PendapatanController@_data');
    Route::resource('incomes', 'Web\Monitoring\PendapatanController', [
        'names' => [
            'index' => 'incomes.index',
        ]
    ])->except([
        'edit', 'show'
    ]);

    //-----------------------------------------------------------------------------
    Route::get('sales/export', 'Web\Monitoring\PenjualanController@export')->name('export');
    Route::get('sales/json', 'Web\Monitoring\PenjualanController@_json');
    Route::get('sales/data', 'Web\Monitoring\PenjualanController@_data');
    Route::resource('sales', 'Web\Monitoring\PenjualanController', [
        'names' => [
            'index' => 'sales.index',
            'show' => 'sales.show',
        ]
    ])->except([
        'edit'
    ]);

    //-----------------------------------------------------------------------------
    Route::get('visits/json', 'Web\Monitoring\KunjunganController@_json');
    Route::get('visits/data', 'Web\Monitoring\KunjunganController@_data');
    Route::get('visits/data-kunjungan', 'Web\Monitoring\KunjunganController@_json_data');
    Route::resource('visits', 'Web\Monitoring\KunjunganController', [
        'names' => [
            'index' => 'visits.index',
        ]
    ])->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('mntrg/members/json', 'Web\Monitoring\MemberController@_json');
    Route::get('mntrg/members/data', 'Web\Monitoring\MemberController@_data');
    Route::resource('mntrg/members', 'Web\Monitoring\MemberController', [
        'names' => [
            'index' => 'mntrg.members.index',
            'show' => 'mntrg.members.show',
        ]
    ])->except([
        'edit', 'update', 'store'
    ]);
});

Route::middleware(['auth' => 'role:super-admin|owner'])->group(function () {
    //MONITORING
    //-----------------------------------------------------------------------------
    Route::get('therapists/fee/export', 'Web\Monitoring\KomisiController@export')->name('export');
    Route::get('therapists/fee/data', 'Web\Monitoring\KomisiController@_data');
    Route::get('therapists/fee/json', 'Web\Monitoring\KomisiController@_json');
    Route::resource('therapists/fee', 'Web\Monitoring\KomisiController', [
        'names' => [
            'index' => 'therapists.fee.index',
            'show' => 'therapists.fee.show',
        ]
    ]);
});

Route::middleware(['auth' => 'role:kasir|super-admin|owner'])->group(function () {
    //INFORMASI
    //-----------------------------------------------------------------------------
    Route::get('payments/info/data', 'Web\Information\PaymentController@_data');
    Route::get('payments/info/json', 'Web\Information\PaymentController@_json');
    Route::resource('payments/info', 'Web\Information\PaymentController', [
        'names' => [
            'index' => 'payments.index',
            'show' => 'payments.show',
        ]
    ])->except([
        'edit'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('members-info/data/history', 'Web\Information\MembersInformation@_data_history');
    Route::get('members-info/json/history', 'Web\Information\MembersInformation@_json_history');
    Route::get('members-info/data', 'Web\Information\MembersInformation@_data');
    Route::get('members-info/json', 'Web\Information\MembersInformation@_json');
    Route::resource('members-info', 'Web\Information\MembersInformation', [
        'names' => [
            'index' => 'members-info.index',
            'show' => 'members-info.show',
        ]
    ])->except([
        'edit'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('sales-prod-serv/data', 'Web\Information\ProdukLayananTerjualController@_data');
    Route::get('sales-prod-serv/json', 'Web\Information\ProdukLayananTerjualController@_json');
    Route::resource('sales-prod-serv', 'Web\Information\ProdukLayananTerjualController', [
        'names' => [
            'index' => 'salesprodserv.index',
        ]
    ])->except([
        'edit', 'show'
    ]);

    //TRANSAKSI
    //-----------------------------------------------------------------------------
    Route::post('set/modals/shift/option', 'Web\Transaction\SetModalController@_option');
    Route::get('set/modals/shift/data', 'Web\Transaction\SetModalController@_data');
    Route::get('set/modals/shift/json', 'Web\Transaction\SetModalController@_json');
    Route::resource('set/modals/shift', 'Web\Transaction\SetModalController', [
        'names' => [
            'index' => 'set.modals.index',
            'store' => 'set.modals.store',
            'update' => 'set.modals.update',
            'destroy' => 'set.modals.destroy',
        ]
    ])->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('trans/spends/sell/load', 'Web\Transaction\SpendController@_load');
    Route::get('trans/spends/sell/data', 'Web\Transaction\SpendController@_data');
    Route::get('trans/spends/sell/json', 'Web\Transaction\SpendController@_json');
    Route::resource('trans/spends/sell', 'Web\Transaction\SpendController', [
        'names' => [
            'index' => 'trans.spends.index',
            'store' => 'trans.spends.store',
            'update' => 'trans.spends.update',
            'destroy' => 'trans.spends.destroy',
        ]
    ])->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('trans/show/{name}', 'Web\Transaction\BuyController@_option');
    Route::post('trans/purchases/buy/supplier/explore', 'Web\Transaction\BuyController@_detail');
    Route::post('trans/purchases/buy/load', 'Web\Transaction\BuyController@_load');
    Route::put('trans/purchases/buy/checklist/{id}', 'Web\Transaction\BuyController@update_')->name('trans.purchases.checklist.update_');
    Route::post('trans/purchases/buy/checklist', 'Web\Transaction\BuyController@store_')->name('trans.purchases.checklist.store_');
    Route::get('trans/purchases/buy/data', 'Web\Transaction\BuyController@_data');
    Route::get('trans/purchases/buy/json', 'Web\Transaction\BuyController@_json');
    Route::resource('trans/purchases/buy', 'Web\Transaction\BuyController', [
        'names' => [
            'index' => 'trans.purchases.index',
            'store' => 'trans.purchases.store',
            'update' => 'trans.purchases.update',
            'update_' => 'trans.purchases.checklist.update_',
            'store_' => 'trans.purchases.checklist.store_',
            'destroy' => 'trans.purchases.destroy',
        ]
    ])->except([
        'edit', 'show'
    ]);

    //-----------------------------------------------------------------------------
    // Route::get('set/notas/prev', 'Web\Setting\NotaController@_prev')
    //     ->name('notas-prev');
    // Route::resource('set/notas', 'Web\Setting\NotaController', [
    //     'names' => [
    //         'index' => 'notas.index',
    //         'store' => 'notas.store',
    //         'update' => 'notas.update',
    //         'create' => 'notas.create'
    //     ]
    // ])->except([
    //     'edit', 'show'
    // ]);
    //-----------------------------------------------------------------------------
    Route::post('monitoring/order/send', 'Web\Monitoring\OrderController@sendPembayaran')->name('monitoring.order.sendPembayaran');
    Route::post('monitoring/order/void', 'Web\Monitoring\OrderController@voidPembayaran')->name('monitoring.order.voidPembayaran');
    Route::post('monitoring/order/print', 'Web\Monitoring\OrderController@printOrder')->name('monitoring.order.printOut');
    Route::get('monitoring/order/activ/{id}', 'Web\Monitoring\OrderController@activations')->name('monitoring.order.actived');
    Route::put('monitoring/order/buy/{id}', 'Web\Monitoring\OrderController@activations')->name('monitoring.order.pembayaran');
    Route::put('monitoring/order/periksa/{id}', 'Web\Monitoring\OrderController@periksas')->name('monitoring.order.periksa');
    Route::get('monitoring/order/reload', 'Web\Monitoring\OrderController@_reload');
    Route::get('monitoring/order/det/{id}', 'Web\Monitoring\OrderController@detTrans');
    Route::get('monitoring/order/data', 'Web\Monitoring\OrderController@_data');
    Route::get('monitoring/order/json', 'Web\Monitoring\OrderController@_json');
    Route::resource('monitoring/order', 'Web\Monitoring\OrderController', [
        'names' => [
            'index' => 'orders.index',
            'update' => 'monitoring.order.update',
        ]
    ])->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('cashiers/print', 'Payment\PaymentCashierController@printOrder')->name('cashiers.printOut');
    Route::post('cashiers/form/option', 'Payment\PaymentCashierController@_option');
    Route::post('cashiers/member/explore', 'Payment\PaymentCashierController@_detail');
    Route::get('cashiers/form-right', 'Payment\PaymentCashierController@form_order');
    Route::get('cashiers/json', 'Payment\PaymentCashierController@_json');
    Route::get('cashiers/data', 'Payment\PaymentCashierController@_data');
    Route::post('cashiers/{id}', 'Payment\PaymentCashierController@store');
    Route::resource('cashiers', 'Payment\PaymentCashierController', [
        'names' => [
            'index' => 'cashiers.index',
            'create' => 'cashiers.form.update',
            'store' => 'cashiers.form.store'
        ]
    ])->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::post('registrations/option', 'Reservation\ReservationController@_option');
    Route::post('registrations/option/group', 'Reservation\ReservationController@_option_group');
    Route::get('registrations/opt/{table}', 'Reservation\ReservationController@_opt');
    Route::get('registrations/opts/{table}', 'Reservation\ReservationController@_opts');
    Route::get('registrations/opt-terapis/{table}', 'Reservation\ReservationController@_optss');
    Route::post('registrations/member/explore', 'Reservation\ReservationController@_detail');
    Route::post('registrations/member/generate', 'Reservation\ReservationController@_gen');
    Route::resource('registrations', 'Reservation\ReservationController', [
        'names' => [
            'index' => 'registrations.index',
            'create' => 'registrations.create',
            'update' => 'registrations.update'
        ]
    ])->except([
        'edit', 'show'
    ]);
    //-----------------------------------------------------------------------------
    Route::get('stocks/json', 'Web\Transaction\StockManagementController@_json')->name('stocks.json');
    Route::get('stocks-history/json', 'Web\Transaction\StockManagementController@_json_history')->name('stocks.history-json');
    Route::get('stocks/data', 'Web\Transaction\StockManagementController@_data')->name('stocks.data');
    Route::get('stocks-history/data', 'Web\Transaction\StockManagementController@_data_history')->name('stocks.history-data');
    Route::post('trans/stock/{id}', 'Web\Transaction\StockManagementController@update')->name('stocks.update');
    Route::get('stocks/detail/{id}', 'Web\Transaction\StockManagementController@detail')->name('stocks.detail');
    Route::resource('trans/stock', 'Web\Transaction\StockManagementController', [
        'names' => [
            'index' => 'stocks.index',
            'show' => 'stocks.show',
        ]
    ])->except([
        'edit',
    ]);
});

Route::middleware(['auth' => 'role:super-admin|kasir|dokter'])->group(function () {
    //-----------------------------------------------------------------------------
    Route::post('monitoring/order/send', 'Web\Monitoring\OrderController@sendPembayaran')->name('monitoring.order.sendPembayaran');
    Route::post('monitoring/order/void', 'Web\Monitoring\OrderController@voidPembayaran')->name('monitoring.order.voidPembayaran');
    Route::post('monitoring/order/print', 'Web\Monitoring\OrderController@printOrder')->name('monitoring.order.printOut');
    Route::get('monitoring/order/activ/{id}', 'Web\Monitoring\OrderController@activations')->name('monitoring.order.actived');
    Route::put('monitoring/order/buy/{id}', 'Web\Monitoring\OrderController@activations')->name('monitoring.order.pembayaran');
    Route::put('monitoring/order/periksa/{id}', 'Web\Monitoring\OrderController@periksas')->name('monitoring.order.periksa');
    Route::get('monitoring/order/reload', 'Web\Monitoring\OrderController@_reload');
    Route::get('monitoring/order/det/{id}', 'Web\Monitoring\OrderController@detTrans');
    Route::get('monitoring/order/data', 'Web\Monitoring\OrderController@_data');
    Route::get('monitoring/order/json', 'Web\Monitoring\OrderController@_json');
    Route::resource('monitoring/order', 'Web\Monitoring\OrderController', [
        'names' => [
            'index' => 'orders.index',
            'update' => 'monitoring.order.update',
        ]
    ])->except([
        'edit', 'show'
    ]);
});

Route::middleware(['auth' => 'role:owner'])->group(function () {
    //LOAD CABANG
    //-----------------------------------------------------------------------------
    Route::post('branch/info/session/{id}', 'Web\Master\BranchController@_session')->name('session.branch');
});

Route::middleware(['auth' => 'role:super-admin|finance|owner|dokter'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/data', 'HomeController@data')->name('home.data');
});

Auth::routes();
