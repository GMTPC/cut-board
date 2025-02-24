<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\MainmenuController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WipController;
use App\Models\AmountNg;
use App\Models\Wipbarcode;
use App\Models\Brand;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
Route::get('/mainmenu', [MainmenuController::class, 'mainmenu'])->name('mainmenu');
Route::middleware('auth')->group(function () {
    Route::get('/settings', [UserSettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/delete', [UserSettingsController::class, 'destroy'])->name('settings.delete');

});
Route::get('/manufacture/L{line}', [MainmenuController::class, 'manufacture'])->name('manufacture');
Route::post('/save-emp-group/{line}', [EmployeeController::class, 'saveEmpGroup'])->name('saveEmpGroup');

Route::post('/egstatus/toggle', [EmployeeController::class, 'toggleStatus'])->name('toggleStatus');


Route::get('/linecut', [MainmenuController::class, 'line3cut'])->name('line3cut');
Route::middleware('auth')->group(function () {
    Route::get('/settings', [UserSettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/delete', [UserSettingsController::class, 'destroy'])->name('settings.delete');

});

Route::post('forgot-password', [PasswordResetLinkController::class, 'resetemail'])->name('resetemail');

Route::post('/employees/line3', [EmployeeController::class, 'employeesaveline3'])->name('employees.save.line3');
Route::post('/employees/line2', [EmployeeController::class, 'employeesaveline2'])->name('employees.save.line2');
Route::post('/employees/line1', [EmployeeController::class, 'employeesaveline1'])->name('employees.save.line1');
Route::get('/showemployees/line3', [EmployeeController::class, 'employeeshow'])->name('employees.show');
Route::put('/employees/{id}', [EmployeeController::class, 'updateemployee'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'deleteemployee'])->name('employees.delete');
Route::get('/user/name', function () {
    return response()->json(['name' => Auth::user()->name]);
})->name('user.name');
Route::get('/warehousenk', [MainmenuController::class, 'warehousenk'])->name('warehouse.nk');
Route::get('/warehouseby', [MainmenuController::class, 'warehouseby'])->name('warehouse.by');
Route::get('/warehousebp', [MainmenuController::class, 'warehousebp'])->name('warehouse.bp');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
Route::post('/employees/save-multiple', [EmployeeController::class, 'saveMultiple'])->name('employees.save.multiple');
Route::get('/warehousebp', [MainmenuController::class, 'warehousebp'])->name('warehouse.bp');
Route::post('/workprocess', [MainmenuController::class, 'workgroup'])->name('workgroup.start');
Route::get('/production/datawip/L{line}/{id}', [MainmenuController::class, 'datawip'])->name('datawip');
Route::post('/delete-workprocess/{id}', [MainmenuController::class, 'deleteWorkProcess'])->name('deleteWorkProcess');
Route::post('/save-employees', [EmployeeController::class, 'saveEmployees'])->name('save-employees');
Route::delete('/deleteemp/{id}', [EmployeeController::class, 'delete'])->name('delete.employee');


Route::get('/getemp/{line}', [EmployeeController::class, 'getEmpGroups'])->name('line.getEmpGroups');
Route::get('/get-barcode/{line}/{id}', [WipController::class, 'getBarcode']);
Route::put('/update-empgroup/{id}', [WipController::class, 'updateEmpGroup'])->name('update.empgroup');
Route::post('/insert-barcode/L/{line}/{work_id}', [WipController::class, 'insertWip'])->name('insertWip');
Route::post('/addng', [WipController::class, 'addng'])->name('addng');
Route::put('/editwipamg/{id}', [WipController::class, 'editwipamg'])->name('editwipamg');
Route::delete('/deleteline1wip/{work_id}/{id}', [WipController::class, 'deleteWipLine1'])->name('deletewipline1');


Route::get('/brandlist', [MainmenuController::class, 'BrandLis'])->name('brandlist');
Route::post('/outfgcode/{line}/{work_id}', [WipController::class, 'outfgcode'])->name('outfgcode');
Route::get('/tagwipqc/{line}/{work_id}/{brd_id}', [WipController::class, 'tagwipqc'])->name('tagwipqc');
Route::get('/tagwipnn/{line}/{work_id}/{brd_id}', [WipController::class, 'tagwipnn'])->name('tagwipnn');
Route::get('/production/tagfg/{line}/{work_id}/{brd_id}', [WipController::class, 'tagfg'])->name('tagfg');
Route::get('/tagfn/{line}/{work_id}/{brd_id}', [WipController::class, 'tagfn'])->name('tagfn');
Route::put('/wip/editbrand/{brd_id}', [WipController::class, 'editbrand'])->name('wip.editbrand');
Route::post('/wip/deletebrand/{brd_id}', [WipController::class, 'deletebrand'])->name('deletebrand');
Route::get('/production/taghd/{line}/{work_id}', [WipController::class, 'taghd'])->name('taghd');
Route::post('/endprocess/{line}/{work_id}', [WipController::class, 'endprocess'])->name('endprocess');
Route::get('/check-sku/{skuCode}', [WipController::class, 'checkSku']);



Route::get('/get-wip-barcode/{wip_id}', function ($wip_id) {
    Log::info("📌 กำลังดึงข้อมูล WIP Barcode สำหรับ WIP ID: " . $wip_id);

    // ✅ แก้ไขให้ใช้ `wip_id` แทน `wip_working_id`
    $barcode = Wipbarcode::where('wip_id', $wip_id)->pluck('wip_barcode')->first();

    if (!$barcode) {
        Log::info("🚨 ไม่พบข้อมูล WIP Barcode สำหรับ WIP ID: " . $wip_id);
        return response()->json(['error' => 'ไม่พบข้อมูลบาร์โค้ด'], 404);
    }

    Log::info("✅ พบ WIP Barcode: " . $barcode);
    return response()->json(['barcode' => $barcode]);
});



Route::get('/get-amount-ng/{wip_id}', function ($wip_id) {
    Log::info("📌 กำลังดึงข้อมูล amg_amount สำหรับ WIP ID: " . $wip_id);

    $totalAmount = AmountNg::where('amg_wip_id', $wip_id)->sum('amg_amount');

    if ($totalAmount === 0) {
        Log::info("🚨 ไม่พบข้อมูล amg_amount สำหรับ WIP ID: " . $wip_id);
        return Response::json(['status' => 'error', 'error' => 'Not Found'], 404);
    }

    Log::info("✅ พบข้อมูล amg_amount รวม: " . $totalAmount);
    return Response::json(['status' => 'success', 'amg_amount' => $totalAmount]);
});

Route::get('/qrcodeinterface/{qrcode}', [WipController::class, 'qrcodeinterface'])->name('qrcodeinterface');
Route::post('/insertcheckcsvqrcode', [WipController::class, 'insertcheckcsvqrcode'])->name('insertcheckcsvqrcode');
Route::post('/insertcheckcsvqrcodewithdefect', [WipController::class, 'insertcheckcsvqrcodewithdefect'])->name('insertcheckcsvqrcodewithdefect');


Route::get('/get-brd-status/{brd_lot}', function ($brd_lot) {
    $status = Brand::where('brd_lot', $brd_lot)->value('brd_status');
    return response()->json(['brd_lot' => $brd_lot, 'brd_status' => $status]);
});

Route::get('/check-duplicate-barcode/{barcode}', [WipController::class, 'checkDuplicateBarcode']);

require __DIR__.'/auth.php';
