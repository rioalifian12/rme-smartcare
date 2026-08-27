<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Role')]
class RoleManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $role_name;
    public $selected_role_id;

    public $isCreateOpen = false;
    public $isEditOpen = false;
    public $isDeleteOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('manage-master-data');

        $roles = Role::when($this->search, function ($q) {
            $q->where('role_name', 'like', '%' . $this->search . '%');
        })->paginate(10);

        return view('livewire.admin.role-management', compact('roles'));
    }

    public function createRole()
    {
        $this->authorize('manage-master-data');

        $this->validate([
            'role_name' => 'required|string|max:50|unique:roles,role_name',
        ]);

        Role::create([
            'role_name' => $this->role_name,
        ]);

        session()->flash('message', 'Role berhasil ditambahkan.');
        $this->resetInputs();
    }

    public function openEditModal($id)
    {
        $role = Role::findOrFail($id);
        $this->selected_role_id = $role->id;
        $this->role_name = $role->role_name;
        $this->isEditOpen = true;
    }

    public function updateRole()
    {
        $this->authorize('manage-master-data');

        $this->validate([
            'role_name' => 'required|string|max:50|unique:roles,role_name,' . $this->selected_role_id,
        ]);

        $role = Role::findOrFail($this->selected_role_id);
        $role->update([
            'role_name' => $this->role_name,
        ]);

        session()->flash('message', 'Role berhasil diperbarui.');
        $this->resetInputs();
    }

    public function openDeleteModal($id)
    {
        $this->selected_role_id = $id;
        $this->isDeleteOpen = true;
    }

    public function deleteRole()
    {
        $this->authorize('manage-master-data');

        Role::findOrFail($this->selected_role_id)->delete();

        session()->flash('message', 'Role berhasil dihapus.');
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->role_name = '';
        $this->selected_role_id = null;
        $this->isCreateOpen = false;
        $this->isEditOpen = false;
        $this->isDeleteOpen = false;
    }
}
