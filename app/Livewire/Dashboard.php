<?php

namespace App\Livewire;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Registration;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $role = $user->role->role_name ?? 'Guest';

        $stats = [
            'total_users' => User::count(),
            'total_poly' => Polyclinic::count(),
            'total_role' => Role::count(),
            'total_patients' => Patient::count(),

            // Antrean dihitung dari tabel registrations berdasarkan status 'waiting_doctor'
            'waiting_records' => Registration::where('status', 'waiting_doctor')
                ->when($role === 'Dokter' && $user->poly_id, function ($query) use ($user) {
                    $query->where('poly_id', $user->poly_id);
                })->count(),

            // Total rekam medis/pemeriksaan yang telah selesai
            'completed_records' => MedicalRecord::when($role === 'Dokter', function ($query) use ($user) {
                    $query->where('doctor_id', $user->id);
                })->count(),
        ];

        return view('livewire.dashboard', compact('stats', 'role'));
    }
}
