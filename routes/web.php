<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\MainmenuController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\EmployeeController;


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

Route::post('/workprocess/start', [MainmenuController::class, 'startWork'])->name('workprocess.start');
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

require __DIR__.'/auth.php';
