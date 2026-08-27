<?php

namespace App\Livewire\Admin;

use App\Models\Polyclinic;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Polyclinic')]
class PolyclinicManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $poly_name;
    public $selected_poly_id;

    public $isCreateOpen = false;
    public $isEditOpen = false;
    public $isDeleteOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Proteksi Otorisasi Gate RBAC
        $this->authorize('manage-master-data');

        $polyclinics = Polyclinic::when($this->search, function ($q) {
            $q->where('poly_name', 'like', '%' . $this->search . '%');
        })->latest('id')->paginate(10);

        return view('livewire.admin.polyclinic-management', compact('polyclinics'));
    }

    public function createPolyclinic()
    {
        $this->authorize('manage-master-data');

        $this->validate([
            'poly_name' => 'required|string|max:100|unique:polyclinics,poly_name',
        ]);

        Polyclinic::create([
            'poly_name' => $this->poly_name,
        ]);

        session()->flash('message', 'Poliklinik berhasil ditambahkan.');
        $this->resetInputs();
    }

    public function openEditModal($id)
    {
        $poly = Polyclinic::findOrFail($id);
        $this->selected_poly_id = $poly->id;
        $this->poly_name = $poly->poly_name;
        $this->isEditOpen = true;
    }

    public function updatePolyclinic()
    {
        $this->authorize('manage-master-data');

        $this->validate([
            'poly_name' => 'required|string|max:100|unique:polyclinics,poly_name,' . $this->selected_poly_id,
        ]);

        $poly = Polyclinic::findOrFail($this->selected_poly_id);
        $poly->update([
            'poly_name' => $this->poly_name,
        ]);

        session()->flash('message', 'Poliklinik berhasil diperbarui.');
        $this->resetInputs();
    }

    public function openDeleteModal($id)
    {
        $this->selected_poly_id = $id;
        $this->isDeleteOpen = true;
    }

    public function deletePolyclinic()
    {
        $this->authorize('manage-master-data');

        Polyclinic::findOrFail($this->selected_poly_id)->delete();

        session()->flash('message', 'Poliklinik berhasil dihapus.');
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->poly_name = '';
        $this->selected_poly_id = null;
        $this->isCreateOpen = false;
        $this->isEditOpen = false;
        $this->isDeleteOpen = false;
    }
}
