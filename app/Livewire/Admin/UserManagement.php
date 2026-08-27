<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use App\Models\Polyclinic;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    // Properties Form User
    public $name, $email, $password, $role_id, $poly_id, $status = 'active';
    public $reset_password;
    public $selected_user_id;

    // States Modal
    public $isCreateOpen = false;
    public $isEditOpen = false;
    public $isResetOpen = false;
    public $isDeleteOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('manage-master-data');

        $users = User::with(['role', 'polyclinic'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest('id')
            ->paginate(10);

        $roles = Role::all();
        $polyclinics = Polyclinic::all();

        return view('livewire.admin.user-management', compact('users', 'roles', 'polyclinics'));
    }

    public function createUser()
    {
        $this->authorize('manage-master-data');

        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'poly_id' => 'nullable|exists:polyclinics,id',
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $this->role_id,
            'poly_id' => $this->poly_id ?: null,
            'status' => $this->status,
        ]);

        session()->flash('message', 'User berhasil ditambahkan.');
        $this->resetInputs();
    }

    public function openEditModal($id)
    {
        $user = User::findOrFail($id);
        $this->selected_user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->poly_id = $user->poly_id;
        $this->status = $user->status;
        $this->isEditOpen = true;
    }

    public function updateUser()
    {
        $this->authorize('manage-master-data');

        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $this->selected_user_id,
            'role_id' => 'required|exists:roles,id',
            'poly_id' => 'nullable|exists:polyclinics,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($this->selected_user_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'poly_id' => $this->poly_id ?: null,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Data user berhasil diperbarui.');
        $this->resetInputs();
    }

    public function openResetModal($id)
    {
        $this->selected_user_id = $id;
        $this->reset_password = '';
        $this->isResetOpen = true;
    }

    public function resetUserPassword()
    {
        $this->authorize('manage-master-data');

        $this->validate([
            'reset_password' => 'required|min:6',
        ]);

        $user = User::findOrFail($this->selected_user_id);
        $user->update([
            'password' => Hash::make($this->reset_password),
        ]);

        session()->flash('message', 'Password user berhasil direset.');
        $this->resetInputs();
    }

    public function openDeleteModal($id)
    {
        $this->selected_user_id = $id;
        $this->isDeleteOpen = true;
    }

    public function deleteUser()
    {
        $this->authorize('manage-master-data');

        User::findOrFail($this->selected_user_id)->delete();

        session()->flash('message', 'User berhasil dihapus.');
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->reset_password = '';
        $this->role_id = null;
        $this->poly_id = null;
        $this->status = 'active';
        $this->selected_user_id = null;
        $this->isCreateOpen = false;
        $this->isEditOpen = false;
        $this->isResetOpen = false;
        $this->isDeleteOpen = false;
    }
}
