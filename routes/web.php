<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard as AdminDashboard;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
});
Route::get('/lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['id', 'en'])) {
        abort(400);
    }
    session(['locale' => $locale]);

    if (auth()->check()) {
        auth()->user()->update(['language' => $locale]);
    }

    return back();
})->name('lang.switch');

Route::view('/privacy', 'legal.privacy')->name('privacy');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

use App\Livewire\Parent\Dashboard as ParentDashboard;
use App\Livewire\Student\Dashboard as StudentDashboard;

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:parent')->group(function () {
        Route::get('/parent', ParentDashboard::class)->name('parent.dashboard');
    });

    Route::middleware('role:student')->group(function () {
        Route::get('/student', StudentDashboard::class)->name('student.dashboard');
    });

    Route::middleware('role:admin')->group(function () {
        Route::view('/admin', 'placeholder')->name('admin.dashboard');
    });
});