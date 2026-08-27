<?php

namespace App\Livewire;

use App\Models\Patient;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Patient')]
class PatientManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $medical_record_number, $name, $date_of_birth, $gender = 'L', $address;
    public $selected_patient_id;

    public $isCreateOpen = false;
    public $isEditOpen = false;
    public $isDeleteOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function generateMedicalRecordNumber()
    {
        do {
            $randomNumber = 'RM-' . mt_rand(100000, 999999);

        } while (Patient::where('medical_record_number', $randomNumber)->exists());

        return $randomNumber;
    }

    public function openCreateModal()
    {
        $this->resetInputs();
        $this->medical_record_number = $this->generateMedicalRecordNumber();
        $this->isCreateOpen = true;
    }

    public function render()
    {
        $this->authorize('manage-patients');

        $patients = Patient::when($this->search, function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
        })->latest('id')->paginate(10);

        return view('livewire.patient-management', compact('patients'));
    }

    public function createPatient()
    {
        $this->authorize('manage-patients');

        if (empty($this->medical_record_number)) {
            $this->medical_record_number = $this->generateMedicalRecordNumber();
        }

        $this->validate([
            'medical_record_number' => 'required|string|max:20|unique:patients,medical_record_number',
            'name' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'nullable|string',
        ]);

        Patient::create([
            'medical_record_number' => $this->medical_record_number,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'address' => $this->address,
        ]);

        session()->flash('message', 'Data pasien berhasil ditambahkan.');
        $this->resetInputs();
    }

    public function openEditModal($id)
    {
        $patient = Patient::findOrFail($id);
        $this->selected_patient_id = $patient->id;
        $this->medical_record_number = $patient->medical_record_number;
        $this->name = $patient->name;
        $this->date_of_birth = $patient->date_of_birth;
        $this->gender = $patient->gender;
        $this->address = $patient->address;
        $this->isEditOpen = true;
    }

    public function updatePatient()
    {
        $this->authorize('manage-patients');

        $this->validate([
            'medical_record_number' => 'required|string|max:20|unique:patients,medical_record_number,' . $this->selected_patient_id,
            'name' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($this->selected_patient_id);
        $patient->update([
            'medical_record_number' => $this->medical_record_number,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'address' => $this->address,
        ]);

        session()->flash('message', 'Data pasien berhasil diperbarui.');
        $this->resetInputs();
    }

    public function openDeleteModal($id)
    {
        $this->selected_patient_id = $id;
        $this->isDeleteOpen = true;
    }

    public function deletePatient()
    {
        $this->authorize('manage-patients');

        Patient::findOrFail($this->selected_patient_id)->delete();

        session()->flash('message', 'Data pasien berhasil dihapus.');
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->medical_record_number = '';
        $this->name = '';
        $this->date_of_birth = '';
        $this->gender = 'L';
        $this->address = '';
        $this->selected_patient_id = null;
        $this->isCreateOpen = false;
        $this->isEditOpen = false;
        $this->isDeleteOpen = false;
    }
}
