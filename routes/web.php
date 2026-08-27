<?php

use App\Livewire\Admin\PolyclinicManagement;
use App\Livewire\Admin\RoleManagement;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
// use App\Livewire\MedicalRecord\Index as MedicalRecordIndex;
// use App\Livewire\Patient\Index as PatientIndex;
// use App\Livewire\Profile\ChangePassword;
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

    // Dashboard utama (semua user login)
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // // 1. Master Data: Users, Roles, Polyclinics (Khusus Admin)
    Route::middleware(['can:manage-master-data'])->group(function () {
        Route::get('/roles', RoleManagement::class)->name('roles');
        Route::get('/polyclinics', PolyclinicManagement::class)->name('polyclinics');
        Route::get('/users', UserManagement::class)->name('users');
    });

    // // 2. Data Pasien & Pendaftaran (Admin & Perawat)
    // Route::middleware(['can:manage-patients'])->group(function () {
    //     Route::get('/patients', PatientRegistration::class)->name('patients');
    // });

    // // 3. Rekam Medis (Admin & Dokter)
    // Route::middleware(['can:manage-medical-records'])->group(function () {
    //     Route::get('/medical-records', MedicalRecordManagement::class)->name('medical-records');
    // });

});
