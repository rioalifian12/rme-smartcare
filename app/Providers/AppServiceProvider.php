<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // CRUD Master Data: Role, Polyclinic, & User (Khusus Admin)
        Gate::define('manage-master-data', function (User $user) {
            return $user->role->role_name === 'Admin';
        });

        // CRUD Pasien (Admin & Perawat)
        Gate::define('manage-patients', function (User $user) {
            return in_array($user->role->role_name, ['Admin', 'Perawat']);
        });

        // CRUD Pendaftaran (Admin & Perawat, Read untuk Dokter)
        Gate::define('manage-registrations', function (User $user) {
            return in_array($user->role->role_name, ['Admin', 'Perawat']);
        });

        Gate::define('view-registrations', function (User $user) {
            return in_array($user->role->role_name, ['Admin', 'Perawat', 'Dokter']);
        });

        // CRUD Rekam Medis (Admin & Dokter)
        Gate::define('manage-medical-records', function (User $user) {
            return in_array($user->role->role_name, ['Admin', 'Dokter']);
        });
    }
}
