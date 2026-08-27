<?php

namespace App\Livewire;

use App\Models\MedicalRecord;
use App\Models\Registration;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Medical Record')]
class MedicalRecordManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $registration_id, $complaints, $diagnosis, $treatment, $prescriptions;
    public $selected_record_id, $selectedRegistration;

    public $isExamineOpen = false;
    public $isEditOpen = false;
    public $isDeleteOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $waitingRegistrations = Registration::where('status', 'waiting_doctor')
            ->where('poly_id', $user->poly_id)
            ->with(['patient', 'polyclinic'])
            ->get();

        $medicalRecords = MedicalRecord::where('doctor_id', $user->id)
            ->when($this->search, function ($query) {
                $query->whereHas('registration.patient', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['registration.patient', 'doctor'])
            ->latest()
            ->paginate(10);

        return view('livewire.medical-record-management', [
            'waitingRegistrations' => $waitingRegistrations,
            'medicalRecords' => $medicalRecords,
        ]);
    }

    public function openExamineModal($registrationId)
    {
        $this->selectedRegistration = Registration::with(['patient', 'polyclinic'])->findOrFail($registrationId);
        $this->registration_id = $this->selectedRegistration->id;
        $this->resetInputsExceptRegistration();
        $this->isExamineOpen = true;
    }

    public function saveMedicalRecord()
    {
        $this->authorize('manage-medical-records');

        $this->validate([
            'registration_id' => 'required|exists:registrations,id',
            'complaints' => 'required|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescriptions' => 'nullable|string',
        ]);

        MedicalRecord::create([
            'registration_id' => $this->registration_id,
            'doctor_id' => auth()->id(),
            'complaints' => $this->complaints,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'prescriptions' => $this->prescriptions,
        ]);

        Registration::where('id', $this->registration_id)->update(['status' => 'completed']);

        session()->flash('message', 'Pemeriksaan rekam medis berhasil disimpan.');
        $this->resetInputs();
    }

    public function openEditModal($id)
    {
        $record = MedicalRecord::with('registration.patient')->findOrFail($id);
        $this->selected_record_id = $record->id;
        $this->complaints = $record->complaints;
        $this->diagnosis = $record->diagnosis;
        $this->treatment = $record->treatment;
        $this->prescriptions = $record->prescriptions;
        $this->isEditOpen = true;
    }

    public function updateMedicalRecord()
    {
        $this->authorize('manage-medical-records');

        $this->validate([
            'complaints' => 'required|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescriptions' => 'nullable|string',
        ]);

        $record = MedicalRecord::findOrFail($this->selected_record_id);
        $record->update([
            'complaints' => $this->complaints,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'prescriptions' => $this->prescriptions,
        ]);

        session()->flash('message', 'Rekam medis berhasil diperbarui.');
        $this->resetInputs();
    }

    public function openDeleteModal($id)
    {
        $this->selected_record_id = $id;
        $this->isDeleteOpen = true;
    }

    public function deleteMedicalRecord()
    {
        $this->authorize('manage-medical-records');

        MedicalRecord::findOrFail($this->selected_record_id)->delete();

        session()->flash('message', 'Rekam medis berhasil dihapus.');
        $this->resetInputs();
    }

    private function resetInputsExceptRegistration()
    {
        $this->complaints = '';
        $this->diagnosis = '';
        $this->treatment = '';
        $this->prescriptions = '';
    }

    public function resetInputs()
    {
        $this->registration_id = null;
        $this->selected_record_id = null;
        $this->selectedRegistration = null;
        $this->complaints = '';
        $this->diagnosis = '';
        $this->treatment = '';
        $this->prescriptions = '';
        $this->isExamineOpen = false;
        $this->isEditOpen = false;
        $this->isDeleteOpen = false;
    }
}
