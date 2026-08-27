<?php

use App\Livewire\Admin\PolyclinicManagement;
use App\Livewire\Admin\RoleManagement;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\MedicalRecordManagement;
use App\Livewire\PatientManagement;
use App\Livewire\RegistrationManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', Login::class)->name('login');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(['can:manage-master-data'])->group(function () {
        Route::get('/roles', RoleManagement::class)->name('roles');
        Route::get('/polyclinics', PolyclinicManagement::class)->name('polyclinics');
        Route::get('/users', UserManagement::class)->name('users');
    });

    Route::middleware(['can:manage-patients'])->group(function () {
        Route::get('/patients', PatientManagement::class)->name('patients');
    });

    Route::middleware(['can:view-registrations'])->group(function () {
        Route::get('/registrations', RegistrationManagement::class)->name('registrations');
    });

    Route::middleware(['can:manage-medical-records'])->group(function () {
        Route::get('/medical-records', MedicalRecordManagement::class)->name('medical-records');
    });

});
