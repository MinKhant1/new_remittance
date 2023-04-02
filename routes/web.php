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

Route::get('/viewpdfinward/{id}', [PdfController::class, 'viewpdf']);

Route::get('/downloadpdfinward/{id}', [PdfController::class, 'downloadpdfinward']);

Route::get('/download/{file}', [InwardTransactionController::class, 'download']);

Route::get('/viewpdfoutward/{id}', [PdfController_2::class, 'viewpdf']);

Route::get('/download/{file}', [OutwardTransactionController::class, 'download']);




//Inward Transaction
Route::get('/inwardtransaction', [InwardTransactionController::class, 'inwardtransaction']);

Route::get('/addinwardtransaction', [InwardTransactionController::class, 'addinwardtransaction']);

Route::post('/saveinwardtransaction', [InwardTransactionController::class, 'saveinwardtransaction']);

Route::get('/editinwardtransaction/{id}', [InwardTransactionController::class, 'editinwardtransaction']);

Route::post('/updateinwardtransaction', [InwardTransactionController::class, 'updateinwardtransaction']);

Route::get('/deleteinwardtransaction/{id}', [InwardTransactionController::class, 'deleteinwardtransaction']);

Route::get('/approveinwardtransaction', [InwardTransactionController::class, 'approveinwardtransaction']);

Route::get('/inward', [InwardTransactionController::class, 'inward']);

Route::post('/searchinward', [InwardTransactionController::class, 'searchinward']);
Route::post('/inwardtransactionwithdate', [InwardTransactionController::class, 'inwardtransactionwithdate']);
Route::post('/outwardtransactionwithdate', [OutwardTransactionController::class, 'outwardtransactionwithdate']);

Route::post('/inwardwithbranch', [InwardTransactionController::class, 'inwardwithbranch']);
Route::post('/outwardwithbranch', [OutwardTransactionController::class, 'outwardwithbranch']);

Route::get('/totalinward', [InwardTransactionController::class, 'totalinward']);

Route::post('/totalinwardwithdate', [InwardTransactionController::class, 'totalinwardwithdate']);

Route::get('/approveinward/{id}', [InwardTransactionController::class, 'approveinward']);

Route::get('/unapproveinward/{id}', [InwardTransactionController::class, 'unapproveinward']);

Route::get('/exportexcelinward', [InwardTransactionController::class, 'exportexcelinward']);

Route::get('/exportexcelinwardtotal', [InwardTransactionController::class, 'exportexceltotalinward']);

// Route::get('/inward', [InwardTransactionController::class, 'searchwithdate']);


//Outward Transaction
Route::get('/outwardtransaction', [OutwardTransactionController::class, 'outwardtransaction']);

Route::get('/addoutwardtransaction', [OutwardTransactionController::class, 'addoutwardtransaction']);

Route::post('/saveoutwardtransaction', [OutwardTransactionController::class, 'saveoutwardtransaction']);

Route::get('/editoutwardtransaction/{id}', [OutwardTransactionController::class, 'editoutwardtransaction']);

Route::post('/updateoutwardtransaction', [OutwardTransactionController::class, 'updateoutwardtransaction']);

Route::get('/deleteoutwardtransaction/{id}', [OutwardTransactionController::class, 'deleteoutwardtransaction']);

Route::get('/exportexceloutward', [OutwardTransactionController::class, 'exportexceloutward']);

Route::get('/approveoutwardtransaction', [OutwardTransactionController::class, 'approveoutwardtransaction']);

Route::get('/approveoutward/{id}', [OutwardTransactionController::class, 'approveoutward']);

Route::get('/unapproveoutward/{id}', [OutwardTransactionController::class, 'unapproveoutward']);

Route::post('/totaloutwardwithdate', [OutwardTransactionController::class, 'totaloutwardwithdate']);

Route::post('/searchoutward', [OutwardTransactionController::class, 'searchoutward']);

Route::get('/exportexceloutwardtotal', [OutwardTransactionController::class, 'exportexceloutwardtotal']);

Route::get('/exportexcelinoutwardtotal', [OutwardTransactionController::class, 'exportexcelinoutwardtotal']);



//Outward Transation Approve
Route::get('/outwardtransactionapprove', [OutwardTransactionController::class, 'outwardtransactionapprove']);


//Outward Transaction report
Route::get('/outwardtransactionreport', [OutwardTransactionController::class, 'outwardtransactionreport']);
Route::get('/totaloutward', [OutwardTransactionController::class, 'totaloutward']);

//totalinwardoutward
Route::get('totalinwardoutward',[OutwardTransactionController::class,'totalinwardoutward']);
Route::post('/totalinwardoutwardwithdate', [OutwardTransactionController::class, 'totalinwardoutwardwithdate']);


//SETUPDATA

//Company
//Company
Route::get('/company', [CompanyController::class, 'company']);

Route::get('/addcompany', [CompanyController::class, 'addcompany']);

Route::post('/savecompany', [CompanyController::class, 'savecompany']);

Route::get('/editcompany/{id}', [CompanyController::class, 'editcompany']);

Route::post('/updatecompany', [CompanyController::class, 'updatecompany']);

Route::get('/deletecompany/{id}', [CompanyController::class, 'deletecompany']);



//Branch
Route::get('/branch', [BranchController::class, 'branch']);

Route::get('/addbranch', [BranchController::class, 'addbranch']);

Route::post('/savebranch', [BranchController::class, 'savebranch']);

Route::get('/editbranch/{id}', [BranchController::class, 'editbranch']);

Route::post('/updatebranch', [BranchController::class, 'updatebranch']);

Route::get('/deletebranch/{id}', [BranchController::class, 'deletebranch']);


//Country

Route::get('/country',[CountryController::class,'country']);

Route::get('/addcountry',[CountryController::class,'addcountry']);

Route::post('/savecountry', [CountryController::class, 'savecountry']);

Route::get('/editcountry/{id}', [CountryController::class, 'editcountry']);

Route::post('/updatecountry', [CountryController::class, 'updatecountry']);

Route::get('/deletecountry/{id}', [CountryController::class, 'deletecountry']);


//Currency

Route::get('/currency',[CurrencyController::class,'currency']);

Route::get('/addcurrency',[CurrencyController::class,'addcurrency']);

Route::post('/savecurrency', [CurrencyController::class, 'savecurrency']);

Route::get('/editcurrency/{id}', [CurrencyController::class, 'editcurrency']);

Route::post('/updatecurrency', [CurrencyController::class, 'updatecurrency']);

Route::get('/deletecurrency/{id}', [CurrencyController::class, 'deletecurrency']);


//Purpose of Trans
Route::get('/purposeoftrans',[PurposeOfTransController::class,'purposeoftrans']);

Route::get('/addpurposeoftrans',[PurposeOfTransController::class,'addpurposeoftrans']);

Route::post('/savepurposeoftrans', [PurposeOfTransController::class, 'savepurposeoftrans']);

Route::get('/editpurposeoftrans/{id}', [PurposeOfTransController::class, 'editpurposeoftrans']);

Route::post('/updatepurposeoftrans', [PurposeOfTransController::class, 'updatepurposeoftrans']);

Route::get('/deletepurposeoftrans/{id}', [PurposeOfTransController::class, 'deletepurposeoftrans']);





//Trans-max-limit
Route::get('/transmaxlimit', [TransmaxlimitController::class, 'transmaxlimit']);

Route::get('/addtransmaxlimit', [TransmaxlimitController::class, 'addtransmaxlimit']);

Route::post('/savetransmaxlimit', [TransmaxlimitController::class, 'savetransmaxlimit']);

Route::get('/edittransmaxlimit/{id}', [TransmaxlimitController::class, 'edittransmaxlimit']);

Route::post('/updatetransmaxlimit', [TransmaxlimitController::class, 'updatetransmaxlimit']);

Route::get('/deletetransmaxlimit/{id}', [TransmaxlimitController::class, 'deletetransmaxlimit']);



//BlackList
Route::get('/blacklist', [BlacklistController::class, 'blacklist']);

Route::get('/addblacklist', [BlacklistController::class, 'addblacklist']);

Route::post('/saveblacklist', [BlacklistController::class, 'saveblacklist']);

Route::get('/editblacklist/{id}', [BlacklistController::class, 'editblacklist']);

Route::post('/updateblacklist', [BlacklistController::class, 'updateblacklist']);

Route::get('/deleteblacklist/{id}', [BlacklistController::class, 'deleteblacklist']);



//ExchangeRate
Route::get('/exchangerate', [ExchangerateController::class, 'exchangerate']);

Route::get('/addexchangerate', [ExchangerateController::class, 'addexchangerate']);

Route::post('/saveexchangerate', [ExchangerateController::class, 'saveexchangerate']);

Route::get('/editexchangerate/{id}', [ExchangerateController::class, 'editexchangerate']);

Route::post('/updateexchangerate', [ExchangerateController::class, 'updateexchangerate']);

Route::get('/deleteexchangerate/{id}', [ExchangerateController::class, 'deleteexchangerate']);



//Registraion
Route::get('/login', [UserController::class, 'login'])->name('login');

Route::get('/signup', [UserController::class, 'signup']);

Route::post('/createaccount', [UserController::class, 'createaccount']);

Route::post('/accessaccount', [UserController::class, 'accessaccount']);

Route::get('/logout', [UserController::class, 'logout']);



//User
Route::get('/user', [UserController::class, 'user']);

Route::get('/usermanual', [UserController::class, 'usermanual']);

Route::get('/adduser', [UserController::class, 'adduser']);

Route::post('/saveuser', [UserController::class, 'saveuser']);

Route::get('/edituser/{id}', [UserController::class, 'edituser']);

Route::post('/updateuser', [UserController::class, 'updateuser']);

Route::get('/deleteuser/{id}', [UserController::class, 'deleteuser']);


//Role
Route::get('/role', [RoleController::class, 'role']);

Route::get('/addrole', [RoleController::class, 'addrole']);

Route::post('/saverole', [RoleController::class, 'saverole']);

Route::get('/editrole/{id}', [RoleController::class, 'editrole']);

Route::post('/updaterole', [RoleController::class, 'updaterole']);

Route::get('/deleterole/{id}', [RoleController::class, 'deleterole']);


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/dailywithdate', [App\Http\Controllers\HomeController::class, 'dailywithdate'])->name('home');
Route::post('/monthlywithdate', [App\Http\Controllers\HomeController::class, 'monthlywithdate'])->name('home');
Route::post('/yearlywithdate', [App\Http\Controllers\HomeController::class, 'yearlywithdate'])->name('home');
