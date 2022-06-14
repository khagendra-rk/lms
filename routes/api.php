<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Index\IndexController;
use App\Http\Controllers\Borrow\BorrowController;
use App\Http\Controllers\Faculty\FacultyController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::controller(AuthController::class)->group(function () {
        Route::get('/user', 'user');
        Route::post('/logout', 'logout');
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });

    // Bookings
    Route::controller(BookingController::class)->middleware(['booking'])->group(function () {
        Route::get('/bookings', 'index');
        Route::post('/bookings', 'store');
        Route::delete('/bookings/{borrow}', 'cancel');
    });
    Route::middleware(['role:admin,librarian'])->group(function () {

        //User Routes
        Route::apiResource('/users', UserController::class);

        //Faculty Routes
        Route::apiResource('/faculties', FacultyController::class);

        //Role Routes
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermission'])->name('roles.permissions');
        Route::apiResource('/roles', RoleController::class);

        //Permission Routes
        Route::apiResource('/permissions', PermissionController::class);

        //Teacher Routes
        Route::apiResource('/teachers', TeacherController::class);

        //Book Routes
        Route::controller(BookController::class)->prefix('/books/{book}/')->group(function () {
            Route::get('indices', 'bookIndices');
            Route::post('indices', 'addIndex');
            Route::put('indices/{index}', 'updateIndex');
            Route::delete('indices/{index}', 'destroyIndex');
            Route::post('rangeindices', 'addRangeIndex');
            Route::post('listindices', 'addListIndex');
            Route::post('quantityindices', 'addQuantityIndex');
            Route::get('faculties', 'faculties');
            Route::post('faculties', 'removeFaculty');
            Route::post('addfaculties', 'addFaculty');
        });

        // Book CRUD
        Route::apiResource('/books', BookController::class);

        //Borrow Routes
        Route::get('/borrows/{borrow}/returns', [BorrowController::class, 'return'])->name('borrows.return');
        Route::put('/returnindex', [BorrowController::class, 'returnIndex'])->name('borrows.returnindex');
        Route::put('/borrows/checkborrows', [BorrowController::class, 'checkBorrow'])->name('borrows.checkborrow');
        Route::apiResource('/borrows', BorrowController::class);
    });

    //Student Routes    
    Route::controller(StudentController::class)->prefix('/students/{student}/')->group(function () {
        Route::post('/bookings', 'bookingRequest');
        Route::get('/documents', 'documents');
        Route::post('/documents', 'storeDocument');
        Route::get('/documents/{document}', 'showDocument');
        Route::put('/documents/{document}', 'updateDocument');
        Route::patch('/documents/{document}', 'updateDocument');
        Route::delete('/documents/{document}', 'destroyDocument');
    });
    Route::apiResource('/students', StudentController::class);
});
