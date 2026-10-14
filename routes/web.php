<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Levels\Index as LevelsIndex;
use App\Livewire\Admin\Modules\Index as ModulesIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Game\PatternFixer;
use App\Livewire\Game\RobotPath;
use App\Livewire\Parent\Dashboard as ParentDashboard;
use App\Livewire\Settings\Page as SettingsPage;
use App\Livewire\Student\Dashboard as StudentDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ---------- Public ----------
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/privacy', 'legal.privacy')->name('privacy');

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

// ---------- Guest only ----------
Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
});

// ---------- Logout ----------
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

// ---------- Authenticated ----------
Route::middleware(['auth'])->group(function () {

    // Settings (all logged-in users)
    Route::get('/settings', SettingsPage::class)->name('settings');

    // Parent
    Route::middleware('role:parent')->group(function () {
        Route::get('/parent', ParentDashboard::class)->name('parent.dashboard');
    });

    // Student
    Route::middleware('role:student')->group(function () {
        Route::get('/student', StudentDashboard::class)->name('student.dashboard');
        Route::get('/play/robot/{level}', RobotPath::class)->name('play.robot');
        Route::get('/play/pattern/{level}', PatternFixer::class)->name('play.pattern');
    });

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/admin/modules', ModulesIndex::class)->name('admin.modules');
        Route::get('/admin/levels', LevelsIndex::class)->name('admin.levels');
    });

});