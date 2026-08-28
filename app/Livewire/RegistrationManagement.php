<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Registration;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Registration')]
class RegistrationManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $patient_search = '';
    public $selected_patient_name = '';

    public $patient_id, $poly_id, $systole, $diastole, $temperature, $weight, $status = 'waiting_doctor';
    public $selected_registration_id;

    public $isCreateOpen = false;
    public $isEditOpen = false;
    public $isDeleteOpen = false;

    public function mount()
    {
        if (auth()->user()->poly_id) {
            $this->poly_id = auth()->user()->poly_id;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('view-registrations');

        $registrations = Registration::with(['patient', 'polyclinic', 'nurse'])
            ->when(auth()->user()->poly_id, function ($q) {
                $q->where('poly_id', auth()->user()->poly_id);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('patient', function ($p) {
                    $p->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('id')
            ->paginate(10);

        $searchedPatients = [];
        if (strlen($this->patient_search) >= 1) {
            $searchedPatients = Patient::where('name', 'like', '%' . $this->patient_search . '%')
                ->orWhere('medical_record_number', 'like', '%' . $this->patient_search . '%')
                ->take(5)
                ->get();
        }

        $polyclinics = Polyclinic::where('poly_name', '!=', 'Non-Nakes')
            ->where('poly_name', '!=', 'Non Nakes')
            ->get();

        return view('livewire.registration-management', compact('registrations', 'searchedPatients', 'polyclinics'));
    }

    public function selectPatient($id, $name, $rm)
    {
        $this->patient_id = $id;
        $this->selected_patient_name = $rm . ' - ' . $name;
        $this->patient_search = '';
    }

    public function createRegistration()
    {
        $this->authorize('manage-registrations');

        if (auth()->user()->poly_id) {
            $this->poly_id = auth()->user()->poly_id;
        }

        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'poly_id' => 'required|exists:polyclinics,id',
            'systole' => 'nullable|integer',
            'diastole' => 'nullable|integer',
            'temperature' => 'nullable|numeric|between:30.0,45.0',
            'weight' => 'nullable|numeric|between:1.0,300.0',
        ]);

        Registration::create([
            'patient_id' => $this->patient_id,
            'poly_id' => $this->poly_id,
            'nurse_id' => auth()->id(),
            'systole' => $this->systole ?: null,
            'diastole' => $this->diastole ?: null,
            'temperature' => $this->temperature ?: null,
            'weight' => $this->weight ?: null,
            'status' => 'waiting_doctor',
        ]);

        session()->flash('message', 'Pendaftaran poliklinik berhasil ditambahkan.');
        $this->resetInputs();
    }

    public function openEditModal($id)
    {
        $reg = Registration::with('patient')->findOrFail($id);
        $this->selected_registration_id = $reg->id;
        $this->patient_id = $reg->patient_id;
        $this->selected_patient_name = ($reg->patient->medical_record_number ?? '') . ' - ' . ($reg->patient->name ?? '');
        $this->poly_id = $reg->poly_id;
        $this->systole = $reg->systole;
        $this->diastole = $reg->diastole;
        $this->temperature = $reg->temperature;
        $this->weight = $reg->weight;
        $this->status = $reg->status;

        $this->isEditOpen = true;
    }

    public function updateRegistration()
    {
        $this->authorize('manage-registrations');

        if (auth()->user()->poly_id) {
            $this->poly_id = auth()->user()->poly_id;
        }

        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'poly_id' => 'required|exists:polyclinics,id',
            'systole' => 'nullable|integer',
            'diastole' => 'nullable|integer',
            'temperature' => 'nullable|numeric|between:30.0,45.0',
            'weight' => 'nullable|numeric|between:1.0,300.0',
            'status' => 'required|in:waiting_doctor,completed',
        ]);

        $reg = Registration::findOrFail($this->selected_registration_id);
        $reg->update([
            'patient_id' => $this->patient_id,
            'poly_id' => $this->poly_id,
            'systole' => $this->systole ?: null,
            'diastole' => $this->diastole ?: null,
            'temperature' => $this->temperature ?: null,
            'weight' => $this->weight ?: null,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Data pendaftaran berhasil diperbarui.');
        $this->resetInputs();
    }

    public function openDeleteModal($id)
    {
        $this->selected_registration_id = $id;
        $this->isDeleteOpen = true;
    }

    public function deleteRegistration()
    {
        $this->authorize('manage-registrations');

        Registration::findOrFail($this->selected_registration_id)->delete();

        session()->flash('message', 'Data pendaftaran berhasil dihapus.');
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->patient_id = null;
        $this->patient_search = '';
        $this->selected_patient_name = '';
        $this->poly_id = auth()->user()->poly_id ?? null;
        $this->systole = '';
        $this->diastole = '';
        $this->temperature = '';
        $this->weight = '';
        $this->status = 'waiting_doctor';
        $this->selected_registration_id = null;
        $this->isCreateOpen = false;
        $this->isEditOpen = false;
        $this->isDeleteOpen = false;
    }
}
