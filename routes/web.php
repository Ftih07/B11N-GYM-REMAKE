<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;

use App\Http\Controllers\Gym\BiinGymController;
use App\Http\Controllers\Gym\KingGymController;
use App\Http\Controllers\Gym\PaymentController;

use App\Http\Controllers\Kost\KostController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Store\PrintController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\QrCodePrintController;

Route::get('/', [HomeController::class, 'index'])->name('home'); //Home - Landing Page
Route::get('/biin-gym', [BiinGymController::class, 'index'])->name('gym.biin'); //B11N Gym
Route::get('/king-gym', [KingGymController::class, 'index'])->name('gym.king'); // K1NG Gym
Route::get('/kost-istana-merdeka-3', [KostController::class, 'index'])->name('kost'); // Kost Istana Merdeka

Route::view('/legal', 'legal')->name('legal');

// Blog Route
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('blogs.show');
});

//Store Route
Route::get('/biin-king-gym-store', [StoreController::class, 'showBiinKingGymStore'])->name('store.biin-king');

//Product Route
Route::get('product/{id}', [ProductController::class, 'show'])->name('store.product.show');

//Booking Kost Route
Route::post('/kost-istana-merdeka-3/book', [KostController::class, 'store'])->name('kost.book');

//Membership Payment Route
Route::post('/payment/upload', [PaymentController::class, 'uploadPayment']);

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

// Route Print Struk Customer
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/print/transaction/{code}', [PrintController::class, 'printStruk'])
        ->name('print.struk');
});

// Route Halaman Report Form
Route::get('/maintenance-report', [App\Http\Controllers\MaintenanceReportController::class, 'create'])->name('maintenance.create');
Route::post('/maintenance-report', [App\Http\Controllers\MaintenanceReportController::class, 'store'])->name('maintenance.store');

// Route API Dropdown (PENTING)
Route::get('/api/equipments/{gymId}', [App\Http\Controllers\MaintenanceReportController::class, 'getEquipments']);

// Route untuk melihat semua list alat (View All)
Route::get('/gym/equipments', [App\Http\Controllers\Gym\EquipmentPageController::class, 'index'])->name('gym.equipments.index');

// Route untuk melihat detail & video tutorial
Route::get('/gym/equipments/{slug}', [App\Http\Controllers\Gym\EquipmentPageController::class, 'show'])
    ->name('gym.equipments.show');

// Route untuk menampilkan halaman form
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');

// Route untuk memproses data yang dikirim (POST)
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

use App\Http\Controllers\AuthController;

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/qr-code/print/{qrCode}', QrCodePrintController::class)->name('qr-code.print');


// -----------------------------------------------//
//--------------- Membership Route ---------------//
// -----------------------------------------------//

use App\Http\Controllers\Member\AttendanceController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\ProfileController;

// Halaman Login (Tamu only)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Route Google yang tadi
    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// Halaman Dashboard Member (Harus Login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/measurements', [DashboardController::class, 'storeMeasurement'])->name('measurements.store');
});
