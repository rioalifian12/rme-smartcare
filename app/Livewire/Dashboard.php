<?php

namespace App\Livewire;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Registration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
            'total_registrations' => Registration::count(),

            'waiting_records' => Registration::where('status', 'waiting_doctor')
                ->when($role === 'Dokter' && $user->poly_id, function ($query) use ($user) {
                    $query->where('poly_id', $user->poly_id);
                })->count(),

            'completed_records' => MedicalRecord::when($role === 'Dokter', function ($query) use ($user) {
                    $query->where('doctor_id', $user->id);
                })->count(),
        ];

        // DATA KUNJUNGAN PASIEN HARIAN (7 Hari Terakhir)
        $visitData = Registration::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $visitDates = $visitData->map(fn($item) => date('d M', strtotime($item->date)))->toArray();
        $visitTotals = $visitData->pluck('total')->toArray();

        // DATA DISTRIBUSI POLIKLINIK
        $polyData = Polyclinic::where('poly_name', '!=', 'Non-Nakes')
            ->where('poly_name', '!=', 'Non Nakes')
            ->withCount('registrations')
            ->get();

        $polyNames = $polyData->pluck('poly_name')->toArray();
        $polyCounts = $polyData->pluck('registrations_count')->toArray();

        return view('livewire.dashboard', compact('stats', 'role', 'visitDates', 'visitTotals', 'polyNames', 'polyCounts'));
    }
}
