<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\InwardController;
use App\Http\Controllers\TransmaxlimitController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangerateController;
use App\Http\Controllers\OutwardTransactionController;
use App\Http\Controllers\InwardTransactionController;
use App\Http\Controllers\InwardTransactionApproveController;
use App\Http\Controllers\PurposeOfTrans;
use App\Http\Controllers\PurposeOfTransController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PdfController_2;

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
Auth::routes();

//ADMIN
// Route::middleware(['auth', 'user-access:admin'])->group(function () {
// Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
// });

//Dashboard
// Route::get('/', [InwardTransactionController::class, 'dashboardtotal']);


//DAILYTRANSACTION
// Route::get('/showoutwardslip', [PdfController::class, 'showoutwardslip']);

Route::get('/viewpdfinward/{id}', [PdfController::class, 'viewpdf'])->middleware('user_middleware');

Route::get('/downloadpdfinward/{id}', [PdfController::class, 'downloadpdfinward'])->middleware('user_middleware');

Route::get('/download/{file}', [InwardTransactionController::class, 'download'])->middleware('user_middleware');

Route::get('/viewpdfoutward/{id}', [PdfController_2::class, 'viewpdf'])->middleware('user_middleware');

Route::get('/download/{file}', [OutwardTransactionController::class, 'download'])->middleware('user_middleware');




//Inward Transaction
Route::get('/inwardtransaction', [InwardTransactionController::class, 'inwardtransaction'])->middleware('user_middleware');

Route::get('/addinwardtransaction', [InwardTransactionController::class, 'addinwardtransaction'])->middleware('user_middleware');

Route::post('/saveinwardtransaction', [InwardTransactionController::class, 'saveinwardtransaction']);

Route::get('/editinwardtransaction/{id}', [InwardTransactionController::class, 'editinwardtransaction']);

Route::post('/updateinwardtransaction', [InwardTransactionController::class, 'updateinwardtransaction'])->middleware('user_middleware');

Route::get('/deleteinwardtransaction/{id}', [InwardTransactionController::class, 'deleteinwardtransaction']);

Route::get('/approveinwardtransaction', [InwardTransactionController::class, 'approveinwardtransaction'])->middleware('user_middleware');

Route::get('/inward', [InwardTransactionController::class, 'inward'])->middleware('user_middleware');

Route::post('/searchinward', [InwardTransactionController::class, 'searchinward']);
Route::post('/inwardtransactionwithdate', [InwardTransactionController::class, 'inwardtransactionwithdate']);
Route::post('/outwardtransactionwithdate', [OutwardTransactionController::class, 'outwardtransactionwithdate']);

Route::post('/inwardwithbranch', [InwardTransactionController::class, 'inwardwithbranch']);
Route::post('/outwardwithbranch', [OutwardTransactionController::class, 'outwardwithbranch']);

Route::get('/totalinward', [InwardTransactionController::class, 'totalinward'])->middleware('user_middleware');

Route::post('/totalinwardwithdate', [InwardTransactionController::class, 'totalinwardwithdate']);

Route::get('/approveinward/{id}', [InwardTransactionController::class, 'approveinward'])->middleware('user_middleware');

Route::get('/unapproveinward/{id}', [InwardTransactionController::class, 'unapproveinward'])->middleware('user_middleware');

Route::get('/exportexcelinward', [InwardTransactionController::class, 'exportexcelinward'])->middleware('user_middleware');

Route::get('/exportexcelinwardtotal', [InwardTransactionController::class, 'exportexceltotalinward'])->middleware('user_middleware');

// Route::get('/inward', [InwardTransactionController::class, 'searchwithdate']);


//Outward Transaction
Route::get('/outwardtransaction', [OutwardTransactionController::class, 'outwardtransaction'])->middleware('user_middleware');

Route::get('/addoutwardtransaction', [OutwardTransactionController::class, 'addoutwardtransaction'])->middleware('user_middleware');

Route::post('/saveoutwardtransaction', [OutwardTransactionController::class, 'saveoutwardtransaction']);

Route::get('/editoutwardtransaction/{id}', [OutwardTransactionController::class, 'editoutwardtransaction'])->middleware('user_middleware');

Route::post('/updateoutwardtransaction', [OutwardTransactionController::class, 'updateoutwardtransaction']);

Route::get('/deleteoutwardtransaction/{id}', [OutwardTransactionController::class, 'deleteoutwardtransaction'])->middleware('user_middleware');

Route::get('/exportexceloutward', [OutwardTransactionController::class, 'exportexceloutward'])->middleware('user_middleware');

Route::get('/approveoutwardtransaction', [OutwardTransactionController::class, 'approveoutwardtransaction'])->middleware('user_middleware');

Route::get('/approveoutward/{id}', [OutwardTransactionController::class, 'approveoutward'])->middleware('user_middleware');

Route::get('/unapproveoutward/{id}', [OutwardTransactionController::class, 'unapproveoutward'])->middleware('user_middleware');

Route::post('/totaloutwardwithdate', [OutwardTransactionController::class, 'totaloutwardwithdate']);

Route::post('/searchoutward', [OutwardTransactionController::class, 'searchoutward']);

Route::get('/exportexceloutwardtotal', [OutwardTransactionController::class, 'exportexceloutwardtotal'])->middleware('user_middleware');

Route::get('/exportexcelinoutwardtotal', [OutwardTransactionController::class, 'exportexcelinoutwardtotal'])->middleware('user_middleware');



//Outward Transation Approve
Route::get('/outwardtransactionapprove', [OutwardTransactionController::class, 'outwardtransactionapprove'])->middleware('user_middleware');


//Outward Transaction report
Route::get('/outwardtransactionreport', [OutwardTransactionController::class, 'outwardtransactionreport'])->middleware('user_middleware');
Route::get('/totaloutward', [OutwardTransactionController::class, 'totaloutward'])->middleware('user_middleware');

//totalinwardoutward
Route::get('totalinwardoutward',[OutwardTransactionController::class,'totalinwardoutward'])->middleware('user_middleware');
Route::post('/totalinwardoutwardwithdate', [OutwardTransactionController::class, 'totalinwardoutwardwithdate']);


//SETUPDATA

//Company
//Company
Route::get('/company', [CompanyController::class, 'company'])->middleware('user_middleware');

Route::get('/addcompany', [CompanyController::class, 'addcompany'])->middleware('user_middleware');

Route::post('/savecompany', [CompanyController::class, 'savecompany']);

Route::get('/editcompany/{id}', [CompanyController::class, 'editcompany'])->middleware('user_middleware');

Route::post('/updatecompany', [CompanyController::class, 'updatecompany']);

Route::get('/deletecompany/{id}', [CompanyController::class, 'deletecompany'])->middleware('user_middleware');



//Branch
Route::get('/branch', [BranchController::class, 'branch'])->middleware('user_middleware');

Route::get('/addbranch', [BranchController::class, 'addbranch'])->middleware('user_middleware');

Route::post('/savebranch', [BranchController::class, 'savebranch']);

Route::get('/editbranch/{id}', [BranchController::class, 'editbranch'])->middleware('user_middleware');

Route::post('/updatebranch', [BranchController::class, 'updatebranch']);

Route::get('/deletebranch/{id}', [BranchController::class, 'deletebranch'])->middleware('user_middleware');


//Country

Route::get('/country',[CountryController::class,'country'])->middleware('user_middleware');

Route::get('/addcountry',[CountryController::class,'addcountry'])->middleware('user_middleware');

Route::post('/savecountry', [CountryController::class, 'savecountry']);

Route::get('/editcountry/{id}', [CountryController::class, 'editcountry'])->middleware('user_middleware');

Route::post('/updatecountry', [CountryController::class, 'updatecountry']);

Route::get('/deletecountry/{id}', [CountryController::class, 'deletecountry'])->middleware('user_middleware');


//Currency

Route::get('/currency',[CurrencyController::class,'currency'])->middleware('user_middleware');

Route::get('/addcurrency',[CurrencyController::class,'addcurrency'])->middleware('user_middleware');

Route::post('/savecurrency', [CurrencyController::class, 'savecurrency']);

Route::get('/editcurrency/{id}', [CurrencyController::class, 'editcurrency'])->middleware('user_middleware');

Route::post('/updatecurrency', [CurrencyController::class, 'updatecurrency']);

Route::get('/deletecurrency/{id}', [CurrencyController::class, 'deletecurrency'])->middleware('user_middleware');


//Purpose of Trans
Route::get('/purposeoftrans',[PurposeOfTransController::class,'purposeoftrans'])->middleware('user_middleware');

Route::get('/addpurposeoftrans',[PurposeOfTransController::class,'addpurposeoftrans'])->middleware('user_middleware');

Route::post('/savepurposeoftrans', [PurposeOfTransController::class, 'savepurposeoftrans']);

Route::get('/editpurposeoftrans/{id}', [PurposeOfTransController::class, 'editpurposeoftrans'])->middleware('user_middleware');

Route::post('/updatepurposeoftrans', [PurposeOfTransController::class, 'updatepurposeoftrans']);

Route::get('/deletepurposeoftrans/{id}', [PurposeOfTransController::class, 'deletepurposeoftrans'])->middleware('user_middleware');





//Trans-max-limit
Route::get('/transmaxlimit', [TransmaxlimitController::class, 'transmaxlimit'])->middleware('user_middleware');

Route::get('/addtransmaxlimit', [TransmaxlimitController::class, 'addtransmaxlimit'])->middleware('user_middleware');

Route::post('/savetransmaxlimit', [TransmaxlimitController::class, 'savetransmaxlimit']);

Route::get('/edittransmaxlimit/{id}', [TransmaxlimitController::class, 'edittransmaxlimit'])->middleware('user_middleware');

Route::post('/updatetransmaxlimit', [TransmaxlimitController::class, 'updatetransmaxlimit']);

Route::get('/deletetransmaxlimit/{id}', [TransmaxlimitController::class, 'deletetransmaxlimit'])->middleware('user_middleware');



//BlackList
Route::get('/blacklist', [BlacklistController::class, 'blacklist'])->middleware('user_middleware');

Route::get('/addblacklist', [BlacklistController::class, 'addblacklist'])->middleware('user_middleware');

Route::post('/saveblacklist', [BlacklistController::class, 'saveblacklist']);

Route::get('/editblacklist/{id}', [BlacklistController::class, 'editblacklist'])->middleware('user_middleware');

Route::post('/updateblacklist', [BlacklistController::class, 'updateblacklist']);

Route::get('/deleteblacklist/{id}', [BlacklistController::class, 'deleteblacklist'])->middleware('user_middleware');



//ExchangeRate
Route::get('/exchangerate', [ExchangerateController::class, 'exchangerate'])->middleware('user_middleware');

Route::get('/addexchangerate', [ExchangerateController::class, 'addexchangerate'])->middleware('user_middleware');

Route::post('/saveexchangerate', [ExchangerateController::class, 'saveexchangerate']);

Route::get('/editexchangerate/{id}', [ExchangerateController::class, 'editexchangerate'])->middleware('user_middleware');

Route::post('/updateexchangerate', [ExchangerateController::class, 'updateexchangerate']);

Route::get('/deleteexchangerate/{id}', [ExchangerateController::class, 'deleteexchangerate'])->middleware('user_middleware');



//Registraion
Route::get('/login', [UserController::class, 'login'])->name('login');

Route::get('/signup', [UserController::class, 'signup']);

Route::post('/createaccount', [UserController::class, 'createaccount']);

Route::post('/accessaccount', [UserController::class, 'accessaccount']);

Route::get('/logout', [UserController::class, 'logout']);


//Customer List
Route::get('/customer_list', [UserController::class, 'customer_list']);




//User
Route::get('/user', [UserController::class, 'user'])->middleware('user_middleware');

Route::get('/usermanual', [UserController::class, 'usermanual'])->middleware('user_middleware');

Route::get('/adduser', [UserController::class, 'adduser'])->middleware('user_middleware');

Route::post('/saveuser', [UserController::class, 'saveuser']);

Route::get('/edituser/{id}', [UserController::class, 'edituser'])->middleware('user_middleware');

Route::post('/updateuser', [UserController::class, 'updateuser']);

Route::get('/deleteuser/{id}', [UserController::class, 'deleteuser'])->middleware('user_middleware');


//Role
Route::get('/role', [RoleController::class, 'role'])->middleware('user_middleware');

Route::get('/addrole', [RoleController::class, 'addrole'])->middleware('user_middleware');

Route::post('/saverole', [RoleController::class, 'saverole']);

Route::get('/editrole/{id}', [RoleController::class, 'editrole'])->middleware('user_middleware');

Route::post('/updaterole', [RoleController::class, 'updaterole']);

Route::get('/deleterole/{id}', [RoleController::class, 'deleterole'])->middleware('user_middleware');


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('user_middleware');
Route::post('/dailywithdate', [App\Http\Controllers\HomeController::class, 'dailywithdate'])->name('home');
Route::post('/monthlywithdate', [App\Http\Controllers\HomeController::class, 'monthlywithdate'])->name('home');
Route::post('/yearlywithdate', [App\Http\Controllers\HomeController::class, 'yearlywithdate'])->name('home');
