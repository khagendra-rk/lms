<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Index\IndexController;
use App\Http\Controllers\Borrow\BorrowController;
use App\Http\Controllers\Faculty\FacultyController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//User Routes
Route::apiResource('/users', UserController::class);

//Faculty Routes
Route::apiResource('/faculties', FacultyController::class);

//Student Routes
Route::apiResource('/students', StudentController::class);

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
});

// Book CRUD
Route::apiResource('/books', BookController::class);

//Index Routes
// Route::apiResource('/indices', IndexController::class);

//Borrow Routes
Route::apiResource('/borrows', BorrowController::class);
