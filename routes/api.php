<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\ListMateriController;
use App\Http\Controllers\PemateriAcaraController;
use App\Http\Controllers\PemateriController;
use App\Models\Pendaftar;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Crud Untuk data pemateri

Route::get('pemateris', [PemateriController::class, 'index']);
Route::post('pemateris', [PemateriController::class, 'store']);
Route::get('pemateris/{id}', [PemateriController::class, 'show']);
Route::put('pemateris/{id}', [PemateriController::class, 'update']);
Route::delete('pemateris/{id}', [PemateriController::class, 'destroy']);



//Crud untuk data pemateri di setiap acara
Route::get('acara/{acaraId}/pemateri-acara', [PemateriAcaraController::class, 'index']);
Route::post('pemateri-acara', [PemateriAcaraController::class, 'store']);
Route::delete('pemateri-acara/{id}', [PemateriAcaraController::class, 'destroy']);


//Crud Fasilitas

Route::get('fasilitas/{acaraId}', [FasilitasController::class, 'index']);
Route::post('fasilitas/{acaraId}', [FasilitasController::class, 'store']);
Route::delete('fasilitas/{fasilitas}', [FasilitasController::class, 'destroy']);

//crud Materi

Route::get('materi/{acaraId}', [ListMateriController::class, 'index']);
Route::post('materi/{acaraId}', [ListMateriController::class, 'store']);
Route::delete('materi/{listMateri}', [ListMateriController::class, 'destroy']);




//Crud User
Route::get('users', [UserController::class, 'index']);           // List all users
Route::get('users/{nik}', [UserController::class, 'show']);     // Show a specific user
Route::post('users/{user}/update', [UserController::class, 'update']);   // Update a specific user
Route::patch('users/{user}', [UserController::class, 'update']); // Alternatively, use PATCH for partial updates
Route::delete('users/{user}', [UserController::class, 'destroy']); // Delete a specific user


//Crud Acara
Route::post('acaras', [AcaraController::class, 'store']);          // Create a new event
Route::post('acaras/{acara}', [AcaraController::class, 'update']);  // Update a specific event
Route::patch('acaras/{acara}', [AcaraController::class, 'update']); // Alternatively, use PATCH for partial updates
Route::delete('acaras/{acara}', [AcaraController::class, 'destroy']); // Delete a specific event
Route::post('acaras/{id}/activate', [AcaraController::class, 'activate']);
Route::post('acaras/{id}/deactivate', [AcaraController::class, 'deactivate']);

//Crud Pendaftar
Route::get('pendaftar', [PendaftarController::class, 'index']);         // List all registrations
Route::get('pendaftar/{pendaftar}', [PendaftarController::class, 'show']); // Show a specific registration
Route::get('acaras/{acaraId}/pendaftar', [PendaftarController::class, 'indexByAcara']);
Route::post('pendaftar/{pendaftar}', [PendaftarController::class, 'update']); // Update a specific registration
Route::post('pendaftar/{id}/konfirmasi-bayar', [PendaftarController::class, 'konfirmasiBayar']);

//pendapatan
Route::get('pendapatan/{acara}', [AcaraController::class, 'pendapatanByAcara']);    // Show a specific event




// Users


Route::post('users', [UserController::class, 'store']);          // Create a new user
Route::post('pendaftar', [PendaftarController::class, 'store']);        // Create a new registration
Route::get('acaras', [AcaraController::class, 'index']);           // List all events
Route::get('acaras/{acara}', [AcaraController::class, 'show']);    // Show a specific event
Route::post('/notification',[PendaftarController::class,'notificationCallback']);