<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\MemberLoginController;
use App\Http\Controllers\BinaryPinController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\MemberWalletController;
use App\Http\Controllers\PaymentWithdrawController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberUpgradeController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TopAchieverController;
use App\Http\Controllers\changePassword;


use App\Http\Controllers\PageController;
use App\Http\Controllers\LegalDocumentController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClosingController;
use App\Http\Controllers\CalculationController ;
use App\Http\Controllers\StockistController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RechargeWalletController;
use App\Http\Controllers\MemberRechargeController;
use App\Http\Controllers\RepurchaseController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\MonthlyClosingController;
use App\Http\Controllers\LevelIncomeController;
use App\Http\Controllers\UplineIncomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TopAchiverController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\MemberRegisterController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\AdminTopupPinController;

use App\Http\Controllers\Member\UtilityController;
use App\Http\Controllers\Member\RechargeController;
use App\Http\Controllers\RewardIncomeController;
use App\Http\Controllers\MemberTopupController;
use App\Http\Controllers\SalaryIncomeController;
use App\Http\Controllers\Admin\AdminIncomeController;
use App\Http\Controllers\MemberWithdrawController;
use App\Http\Controllers\AdminWithdrawController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AdminForgotPasswordController;

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


// Route::view('/', 'front.home')->name('home');
// Route::view('/about', 'front.pages.about')->name('about');
// Route::view('/services', 'front.pages.services')->name('services');
// Route::view('/legal', 'front.pages.legal')->name('legal');
// Route::view('/contact', 'front.pages.contact')->name('contact');

Route::view('/', 'front.pages.home')->name('front.home');
Route::view('/about', 'front.pages.about')->name('front.about');
Route::view('/legal', 'front.pages.legal')->name('front.legal');
Route::view('/vision', 'front.pages.vision')->name('front.vision');
Route::view('/services', 'front.pages.service')->name('front.services');
Route::view('/contactus', 'front.pages.contact')->name('front.contactus');



// Route::get('/', [HomeController::class, 'home'])->name('home');
// Route::get('/about', [PageController::class, 'about'])->name('about');
// Route::get('/property', [PageController::class, 'property'])->name('property');
// Route::get('/product', [PageController::class, 'product'])->name('product');

// Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Route::get('/legal', [LegalDocumentController::class, 'index'])->name('legal.index');
// Route::get('/legal/download/{id}', [LegalDocumentController::class, 'download'])->name('legal.download');






// Admin Routes

Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); 
        })->name('admin.dashboard');
    });
});


Route::prefix('admin')->group(function () {
    Route::get('/forgot-password', [AdminForgotPasswordController::class, 'showRequestForm'])->name('admin.forgot');
    Route::post('/forgot-password', [AdminForgotPasswordController::class, 'sendResetLink'])->name('admin.forgot.send');
    Route::get('/reset-password', [AdminForgotPasswordController::class, 'showResetForm'])->name('admin.reset');
    Route::post('/reset-password', [AdminForgotPasswordController::class, 'resetPassword'])->name('admin.reset.update');
});

Route::get('/admin/members/active', [AdminMemberController::class, 'active'])->name('admin.members.active');
Route::get('/admin/members/inactive', [AdminMemberController::class, 'inactive'])->name('admin.members.inactive');
Route::post('/admin/members/toggle-status/{id}', [AdminMemberController::class, 'toggleStatus'])->name('admin.members.toggleStatus');
Route::get('/admin/member/view/{show_mem_id}', [AdminMemberController::class, 'view'])->name('admin.member.view');

Route::get('/admin/member/edit/{show_mem_id}', [AdminMemberController::class, 'edit'])->name('admin.member.edit');
Route::put('/admin/member/update/{show_mem_id}', [AdminMemberController::class, 'update'])->name('admin.member.update');
Route::get('/admin/members/join-date', [AdminMemberController::class, 'byJoinDate'])->name('admin.members.byjoindate');
Route::get('/admin/members/index', [AdminMemberController::class, 'index'])->name('admin.members.index');


// Top-up Pin Management

// Admin Routes
Route::prefix('admin/managepin')->group(function () {
    Route::get('/create', [AdminTopupPinController::class, 'create'])->name('admin.topuppin.create');
    Route::post('/store', [AdminTopupPinController::class, 'store'])->name('admin.topuppin.store');
    Route::get('/used', [AdminTopupPinController::class, 'used'])->name('admin.topuppin.used');
    Route::get('/unused', [AdminTopupPinController::class, 'unused'])->name('admin.topuppin.unused');
    Route::delete('/delete/{id}', [AdminTopupPinController::class, 'deletePin'])->name('admin.topuppin.delete');
});

Route::get('/admin/member-name/{member_id}', [AdminMemberController::class, 'getMemberName']);
Route::middleware('auth:member')->get('/member/get-name/{member_id}', [MemberController::class, 'getMemberName']);


// routes/web.php
Route::prefix('admin/income')->middleware(['auth:admin'])->name('admin.income.')->group(function () {
    Route::get('/daily', [AdminIncomeController::class, 'daily'])->name('daily');
    Route::get('/direct', [AdminIncomeController::class, 'direct'])->name('direct');
    Route::get('/matching', [AdminIncomeController::class, 'matching'])->name('matching');
    Route::get('/salary', [AdminIncomeController::class, 'salary'])->name('salary');
    Route::get('/reward', [AdminIncomeController::class, 'reward'])->name('reward');
});

Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // Special Offer Routes
    Route::get('/special-offer', [App\Http\Controllers\Admin\SpecialOfferController::class, 'index'])->name('admin.special.offer.index');
    Route::post('/special-offer/process', [App\Http\Controllers\Admin\SpecialOfferController::class, 'process'])->name('admin.special.offer.process');
    Route::get('/special-offer/report', [App\Http\Controllers\Admin\SpecialOfferController::class, 'report'])->name('admin.special.offer.report');
});
Route::prefix('member')->middleware(['auth:member'])->group(function () {
    // ... existing routes
    Route::get('/monsoon-offer', [App\Http\Controllers\Member\MonsoonOfferController::class, 'index'])->name('member.monsoon.offer');
});


// Member Routes
Route::prefix('member/topup')->middleware('auth:member')->group(function () {
    Route::get('/pin', [MemberTopupController::class, 'topupPin'])->name('member.topup.pin');
    Route::post('/process', [MemberTopupController::class, 'processTopup'])->name('member.topup.process');
    Route::get('/used', [MemberTopupController::class, 'usedPins'])->name('member.topup.used');
});


// Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
// Route::post('/admin/login', [AdminLoginController::class, 'login']);
// Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');


    Route::get('/manage-joining-package', [ProductController::class, 'manageJoiningPackage'])->name('manage.joining.package');

    Route::get('/closing-report', [ClosingController::class, 'index'])->name('closing.index');
    Route::get('/closing/add', [ClosingController::class, 'add'])->name('closing.add');
    Route::get('/closing/details/{closdate}', [ClosingController::class, 'showDetails'])->name('closing.details');

    Route::get('/Calculation', [CalculationController ::class, 'index'])->name('Calculation');

    Route::get('/manage-members', [MemberController::class, 'manageMembers'])->name('manageMembers');
    Route::post('/manage-members', [MemberController::class, 'manageMembers']);


    Route::get('/member', [MemberController::class, 'manage'])->name('member');
    // Route::post('/manage-member', [MemberController::class, 'manage'])->name('member');;
    Route::post('/member/status', [MemberController::class, 'updateStatus'])->name('member.post');

    Route::get('/stockist', [StockistController::class, 'index'])->name('stockist.index');
    Route::post('/stockist/status', [MemberController::class, 'index'])->name('stockist.post');

    Route::get('/stockist/message/{memId}', [StockistController::class, 'showMessage'])->name('stockist.message');


    Route::get('/manage-kyc', [KycController::class, 'index'])->name('manage.kyc');
    Route::post('/manage-kyc', [KycController::class, 'update'])->name('manage.kyc.update');

    Route::get('/send-message', [MessageController::class, 'showForm'])->name('send.message.form');
    Route::post('/send-message', [MessageController::class, 'sendMessage'])->name('send.message.send');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/delete/{orderId}', [OrderController::class, 'deleteOrder'])->name('orders.delete');
    Route::post('/orders/cancel/{orderId}', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/update', [OrderController::class, 'updateOrders'])->name('orders.update');


    Route::get('/monthly-closing', [MonthlyClosingController::class, 'index'])->name('monthly_closing.index');
    Route::post('/monthly-closing', [MonthlyClosingController::class, 'store'])->name('monthly_closing.store');
    Route::get('/manageClosingReport', [MonthlyClosingController::class, 'manageClosingReport'])->name('manageClosingReport');
    Route::get('/oldlist', [MonthlyClosingController::class, 'oldlist'])->name('oldlist');
    Route::get('/sponsorincomelist', [MonthlyClosingController::class, 'SponsorIncomeList'])->name('sponsorincomelist');
    Route::post('/manage-stockist', [MonthlyClosingController::class, 'index'])->name('manage-stockist.search');
    Route::get('/mngpayment', [MonthlyClosingController::class, 'mngpayment'])->name('mngpayment');

    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');

    Route::get('/level-income', [LevelIncomeController::class, 'index'])->name('level.income');
    Route::post('/level-income/search', [LevelIncomeController::class, 'search'])->name('level.income.search');
    Route::get('/silver-club', [LevelIncomeController::class, 'silverclub'])->name('silver.club');
    Route::get('/upline-income', [UplineIncomeController::class, 'index'])->name('upline.income');
    Route::get('/down-income', [UplineIncomeController::class, 'downlineInc'])->name('downlineinc');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); // List all categories
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create'); // Show create form
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); // Store a new category
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update'); // Update the category
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // Delete the category

    Route::get('stock', [CategoryController::class, 'stock'])->name('stock');
    Route::delete('stock/{id}', [CategoryController::class, 'delete'])->name('stock.delete');

    Route::get('rewards', [RewardController::class, 'reward'])->name('rewards.index');
    Route::delete('rewards/{id}', [RewardController::class, 'destroy'])->name('rewards.destroy');



// Route for listing all news
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// Route for showing the create news form
Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');

// Route for storing a new news entry
Route::post('/news', [NewsController::class, 'store'])->name('news.store');

// Route for showing the edit news form
Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');

// Route for updating a news entry
Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');

// Route for deleting a news entry
Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');



Route::get('/achivers', [TopAchiverController::class, 'index'])->name('achivers.index');
Route::post('/achivers', [TopAchiverController::class, 'store'])->name('achivers.store');
Route::delete('/achivers/{id}', [TopAchiverController::class, 'destroy'])->name('achivers.destroy');

Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/change-password', [UserController::class, 'changePassword'])->name('password.update');



















    Route::get('/manage-orders', [OrderController::class, 'manage'])->name('manage.orders');
    Route::post('/manage-orders', [OrderController::class, 'processOrders'])->name('process.orders');
    Route::get('/manage-orders/cancel/{id}', [OrderController::class, 'manageOrder'])->name('cancel.order');
    Route::get('/manage-orders/delete/{id}', [OrderController::class, 'newdeleteOrder'])->name('delete.order');

    Route::get('/manage-payment', [RechargeWalletController::class, 'index'])->name('manage.payment');
    Route::post('/manage-payment', [RechargeWalletController::class, 'index']);
    Route::get('/member-recharge', [MemberRechargeController::class, 'index'])->name('member.recharge.index');

    Route::get('/repurchase', [RepurchaseController::class, 'index'])->name('repurchase');
    Route::get('/globle', [RepurchaseController::class, 'globle'])->name('globle');
    Route::get('/active', [RepurchaseController::class, 'active'])->name('active');
    Route::get('/income-report', [IncomeReportController::class, 'index'])->name('income.report');
    Route::get('/leadership', [RepurchaseController::class, 'leader'])->name('leadership');
    Route::get('/executivedirector', [RepurchaseController::class, 'executivedirector'])->name('executivedirector');
    Route::get('/platinumdirector', [RepurchaseController::class, 'platinum_director'])->name('platinumdirector');
    Route::get('/crowndirector', [RepurchaseController::class, 'crowndirector'])->name('crowndirector');
    Route::get('/crownambessador', [RepurchaseController::class, 'crownambessador'])->name('crownambessador');
    Route::get('/royal_corwn_ambb', [RepurchaseController::class, 'royal_corwn_ambb'])->name('royal_corwn_ambb');





    // Route::get('/managePin', [BinaryPinController::class, 'index'])->name('managePin');
    // Route::post('/binarypin/search', [BinaryPinController::class, 'search'])->name('binarypin.search');
    // Route::get('/usedbinarypin', [BinaryPinController::class, 'usedbinarypin'])->name('usetopuppin');
    // Route::get('/unused-topup-pin', [BinaryPinController::class, 'unusedTopupPin'])->name('unusedTopupPin');
    // Route::get('/pins/create', [PinController::class, 'create'])->name('pins.create');
    // Route::post('/pins/store', [PinController::class, 'store'])->name('pins.store');
    // Route::post('/pins/generate', [PinController::class, 'generatePins'])->name('pins.generate');
    // Route::get('/matchinpair', [BinaryPinController::class, 'matchinpair'])->name('matchinpair');

    // Route::get('/member/wallet', [MemberWalletController::class, 'index'])->name('member.wallet');
    // Route::post('/member/wallet/deduct', [MemberWalletController::class, 'deductAmount'])->name('member.wallet.deduct');
    // Route::get('/payment/withdraw', [PaymentWithdrawController::class, 'index'])->name('payment.withdraw.index');
    // Route::get('/payment/withdraw/delete/{id}', [PaymentWithdrawController::class, 'delete'])->name('payment.withdraw.delete');
    // Route::get('/payment/withdraw/report/{id}', [PaymentWithdrawController::class, 'reportUpdate'])->name('payment.withdraw.report');
    // Route::get('/manage-member', [MemberController::class, 'index'])->name('manage.member');
    // Route::post('/manage-member/update-status', [MemberController::class, 'updateStatus'])->name('manage.member.update.status');
    // Route::post('/manage-member/show-today', [MemberController::class, 'showToday'])->name('manage.member.show.today');

    // Route::get('free/members', [MemberController::class, 'freemember'])->name('free.members');
    // Route::post('members/status', [MemberController::class, 'freeupdateStatus'])->name('members.updateStatus');
    // Route::post('members/showtoday', [MemberController::class, 'freeupdateShowToday'])->name('members.updateShowToday');
    // Route::post('members/upgrade', [MemberController::class, 'freeupgrade'])->name('members.upgrade');

//     Route::get('/send-message', [MemberController::class, 'showForm'])->name('send.message.form');
// Route::post('/send-message', [MemberController::class, 'sendMessages'])->name('send.message');

// Route::get('/member-upgrade', [MemberUpgradeController::class, 'index'])->name('member.upgrade.index');
// Route::patch('/member-upgrade/{id}', [MemberUpgradeController::class, 'upgrade'])->name('member.upgrade');
// Route::patch('/member-free-upgrade/{id}', [MemberUpgradeController::class, 'freeUpgrade'])->name('member.freeUpgrade');

// Route::get('/add-member', [MemberUpgradeController::class, 'addmember'])->name('add.member');

// Route::post('/upgrade-member', [MemberUpgradeController::class, 'upgradeMember'])->name('upgrade.member');

// Route::get('/single-leg-payout', [MemberUpgradeController::class, 'singleLegPayout'])->name('single_leg_payout');
// Route::post('/single-leg-payout', [MemberUpgradeController::class, 'singleLegPayout']);

// Route::get('/lap-pay-list', [MemberUpgradeController::class, 'leg'])->name('lap.pay.list');
// Route::post('/lap-pay-list', [MemberUpgradeController::class, 'searchPayout'])->name('payout.search');

// Route::get('/level-list', [MemberUpgradeController::class, 'leval'])->name('leval.list');

// Route::get('paymentwithdrawList', [PaymentWithdrawController::class, 'list'])->name('paymentwithdrawList');

// Route::get('/pending-list', [PaymentWithdrawController::class, 'index'])->name('pending.list');
// Route::get('/balance-list', [PaymentWithdrawController::class, 'currentBalance'])->name('balance.list');

// Route::get('manage/rewards', [RewardController::class, 'index'])->name('manage.rewards');
// Route::delete('admin/rewards/{id}', [RewardController::class, 'destroy'])->name('rewards.destroy');
//     // Other admin routes that require authentication

// Route::get('admin/news', [NewsController::class, 'index'])->name('news.index');
// Route::get('admin/news/create', [NewsController::class, 'create'])->name('news.create');
// Route::post('admin/news', [NewsController::class, 'store'])->name('news.store');
// Route::get('admin/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
// Route::put('admin/news/{id}', [NewsController::class, 'update'])->name('news.update');
// Route::delete('admin/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');

// Route::get('achievers', [TopAchieverController::class, 'index'])->name('achievers');
// Route::post('admin/achievers', [TopAchieverController::class, 'store'])->name('achievers.store');
// Route::delete('admin/achievers/{id}', [TopAchieverController::class, 'destroy'])->name('achievers.destroy');

// Route::get('/payment-withdraw-status', [PaymentWithdrawController::class, 'paymentwithdraw'])->name('payment.withdraw');
// Route::post('admin/payment-withdraw-status', [PaymentWithdrawController::class, 'update'])->name('withdraw.update');
// // Route::post('/change-password', [changePassword::class, 'changePassword'])->name('change.password');
// // Route::get('/change-password', [changePassword::class, 'index'])->name('change.pass');
// Route::get('/leval-info', [PaymentWithdrawController::class, 'levalinfo'])->name('levalinfo');
// Route::post('/update-user-levels', [PaymentWithdrawController::class, 'updateUserLevels'])->name('update.user.levels');


});

// Member Routes
Route::get('/member/login', [MemberLoginController::class, 'showLoginForm'])->name('member.login');
Route::post('/member/login', [MemberLoginController::class, 'login']);
Route::post('/member/logout', [MemberLoginController::class, 'logout'])->name('member.logout');

// Route::middleware(['auth:member'])->group(function () {
//     Route::get('/member/dashboard', function () {
//         return view('member.dashboard');
//     })->name('member.dashboard');
//     // Other member routes that require authentication
// });

Route::get('/register', [MemberController::class, 'showRegisterForm'])->name('member.register');
Route::post('/register', [MemberController::class, 'register'])->name('member.register.submit');
Route::post('/get-sponsor-name', [MemberController::class, 'getSponsorName'])->name('get.sponsor.name');

Route::get('/member/register/success', function () {
    $showMemId = session('show_mem_id');
    $memberName = session('name');
    $memberEmail = session('emailid');

    if (!$showMemId) {
        return redirect()->route('member.register');
    }

    return view('member.member-register-success', compact('showMemId', 'memberName', 'memberEmail'));
})->name('member.register.success');

// Route::get('/register', [MemberRegisterController::class, 'showForm'])->name('member.register');
// Route::post('/register', [MemberRegisterController::class, 'register'])->name('member.register.submit');
// Route::post('/get-sponsor-name', [MemberRegisterController::class, 'getSponsorName']);






Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('login-as-member/{show_mem_id}', [App\Http\Controllers\AdminMemberController::class, 'loginAsMember'])->name('loginAsMember');
});




Route::prefix('member')->middleware('auth:member')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');

    // Profile
    Route::get('/profile', [MemberController::class, 'showProfile'])->name('member.profile');
    Route::get('/profile/edit', [MemberController::class, 'editBasic'])->name('member.profile.edit');
    Route::post('/profile/update', [MemberController::class, 'updateBasic'])->name('member.profile.update');

    // Network
    Route::get('/direct-associates', [MemberController::class, 'directAssociates'])->name('member.directAssociates');
    Route::get('/associate-network', [MemberController::class, 'associateNetwork'])->name('member.associateNetwork');
   Route::get('/tree', [MemberController::class, 'treeView'])->name('member.tree');
Route::get('/left-network', [MemberController::class, 'leftNetwork'])->name('member.left_network');
Route::get('/right-network', [MemberController::class, 'rightNetwork'])->name('member.right_network');
Route::get('/member/tree/load/{memid}', [MemberController::class, 'ajaxTreeNode']);
Route::get('/member/details/{memid}', [MemberController::class, 'getMemberDetails'])->name('member.details');
    // Member Details
    Route::get('/details/{memid}', [MemberController::class, 'getMemberDetails'])->name('member.details');

    // Income Routes
    Route::get('/withdrawals/{memid}', [MemberController::class, 'memberWithdrawals'])->name('member.withdrawals');
 Route::get('/daily-income', [MemberController::class, 'memberDailyIncome'])
    ->name('member.daily.income');
   Route::get('/direct-payment', [MemberController::class, 'memberDirectPayment'])
    ->name('member.direct.payment');
       Route::get('history/{id}', [MemberController::class, 'viewHistory'])->name('member.history.view');

   Route::get('/matching-income', [MemberController::class, 'memberMatchingIncome'])
    ->name('member.matching.income');
    Route::get('/reward-income}', [RewardIncomeController::class, 'memberRewardIncome'])->name('member.reward.income');
});
Route::middleware(['auth:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('salary-income', [SalaryIncomeController::class, 'memberSalaryIncome'])->name('salary.income');
    Route::get('payment-history', [MemberController::class, 'paymentHistory'])->name('payment-history');

});

Route::middleware(['auth:member'])->group(function () {

    // Show bank form (only if not already submitted)
    Route::get('/member/bank-details/form', [MemberController::class, 'showBankForm'])
         ->name('member.bank.form');
    
    // Store bank details
    Route::post('/member/bank-details/store', [MemberController::class, 'storeBankDetails'])
         ->name('member.bank.store');
    
    // View submitted bank details (read-only)
    Route::get('/member/bank-details/view', [MemberController::class, 'viewBankDetails'])
         ->name('member.bank.view');
    
    // Optional: Check if member has bank details (for AJAX calls)
    Route::get('/member/bank-details/check', [MemberController::class, 'hasBankDetails'])
         ->name('member.bank.check');
});




// Route::middleware(['auth:member'])->group(function () {
//     Route::get('/member/dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');
// });
// Route::middleware(['auth:member'])->group(function () {
//     Route::get('/member/profile', [MemberController::class, 'showProfile'])->name('member.profile');
// });

// Route::prefix('member')->middleware('auth:member')->group(function () {
//    // Route::get('/profile', [MemberController::class, 'showProfile'])->name('member.profile');
//     Route::get('/profile/edit', [MemberController::class, 'editBasic'])->name('member.profile.edit');
//     Route::post('/profile/update', [MemberController::class, 'updateBasic'])->name('member.profile.update');

//     // Only for admins
//     //Route::get('/{id}/bank-details/edit', [AdminController::class, 'editBankDetails'])->name('admin.member.bank.edit');
//    // Route::post('/{id}/bank-details/update', [AdminController::class, 'updateBankDetails'])->name('admin.member.bank.update');
// });
// Route::get('/member/direct-associates', [MemberController::class, 'directAssociates'])->middleware('auth:member')->name('member.directAssociates');

// Route::get('/member/associate-network', [MemberController::class, 'associateNetwork'])->middleware('auth:member')->name('member.associateNetwork');

// Route::get('/member/tree', [MemberController::class, 'treeView'])->name('member.tree');
    
 
// Route::get('/member/details/{memid}', [MemberController::class, 'getMemberDetails'])->name('member_details');


// Route::get('/member/left-network', [MemberController::class, 'leftNetwork'])->name('member.left_network');
// Route::get('/member/right-network', [MemberController::class, 'rightNetwork'])->name('member.right_network');

// Route::get('/member/withdrawals/{memid}', [MemberController::class, 'memberWithdrawals'])->name('member.withdrawals');


// // Member Daily Income Route
// Route::get('/member/daily-income/{memid}', [MemberController::class, 'memberDailyIncome'])->name('member.daily.income');

// // Direct Payment Route  
// Route::get('/member/direct-payment/{memid}', [MemberController::class, 'memberDirectPayment'])->name('member.direct.payment');

// // Matching Income Route
// Route::get('/member/matching-income/{memid}', [MemberController::class, 'memberMatchingIncome'])->name('member.matching.income');

// // Salary Income Route
// Route::get('/member/salary-income/{memid}', [MemberController::class, 'memberSalaryIncome'])->name('member.salary.income');

// // Reward Income Route
// Route::get('/member/reward-income/{memid}', [MemberController::class, 'memberRewardIncome'])->name('member.reward.income');
Route::middleware(['auth:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/payment/create', [\App\Http\Controllers\Member\MemberPaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/store', [\App\Http\Controllers\Member\MemberPaymentController::class, 'store'])->name('payment.store');
    Route::get('/payment/history', [\App\Http\Controllers\Member\MemberPaymentController::class, 'history'])->name('payment.history');

});


// Admin Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [\App\Http\Controllers\Admin\MemberPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{id}/update', [\App\Http\Controllers\Admin\MemberPaymentController::class, 'update'])->name('payments.update');
});

Route::middleware(['auth:member'])->prefix('member')->name('member.')->group(function () {
    
    Route::get('/utility', [UtilityController::class, 'index'])->name('utility');

    // Recharge Routes
    Route::get('/recharge/mobile', [RechargeController::class, 'showForm'])->name('recharge.mobile');
    Route::post('/recharge/mobile', [RechargeController::class, 'submit'])->name('recharge.mobile.submit');
    Route::get('/recharge/success', [RechargeController::class, 'success'])->name('recharge.success');
Route::get('/recharge/history', [RechargeController::class, 'history'])->name('recharge.history');

});

Route::get('/recharge/history', [RechargeController::class, 'history'])->name('recharge.history');

Route::middleware(['auth:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('reward-income', [RewardIncomeController::class, 'index'])->name('reward.index');
    
    Route::post('reward-income/withdraw/{id}', [RewardIncomeController::class, 'withdraw'])->name('reward.withdraw');
});


Route::prefix('member')->name('member.')->middleware(['auth:member'])->group(function () {
    Route::get('/withdraw', [MemberWithdrawController::class, 'index'])->name('withdraw.index');
    Route::post('/withdraw/request', [MemberWithdrawController::class, 'requestWithdraw'])->name('withdraw.request');
});

Route::prefix('admin/withdraw')->middleware(['auth:admin'])->name('admin.withdraw.')->group(function () {
    Route::get('/', [AdminWithdrawController::class, 'index'])->name('index');
    Route::post('/approve/{id}', [AdminWithdrawController::class, 'approve'])->name('approve');
    Route::post('/reject/{id}', [AdminWithdrawController::class, 'reject'])->name('reject');
    Route::put('/{id}', [AdminWithdrawController::class, 'update'])->name('update');

});

Route::prefix('admin/recharge')->middleware(['auth:admin'])->name('admin.recharge.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminRechargeController::class, 'index'])->name('index');
});
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('calculate-daily-income', [CronController::class, 'calculatedailyincome']);
     
    Route::get('direct-income', [CronController::class, 'processsponsordailyIncome']);
    Route::get('salary-income', [CronController::class, 'processsalaryincome']);
    Route::get('reward-income', [CronController::class, 'processrewardbonus']);
});


Route::prefix('admin/payment-refresh')->middleware(['auth:admin'])->name('admin.paymentrefresh.')->group(function () {
    Route::get('/', function () {
        return view('admin.payment_refresh');
    })->name('index');
});

Route::middleware(['auth:member'])->group(function () {
    Route::get('/password/change', [MemberController::class, 'editPassword'])->name('member.password.edit');
    Route::post('/password/update', [MemberController::class, 'updatePassword'])->name('member.password.update');
});


Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/payment-logs', [\App\Http\Controllers\Admin\PaymentLogController::class, 'index'])->name('payment.logs');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showRequestForm'])->name('forgot.form');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot.send');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('reset.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.update');
});
