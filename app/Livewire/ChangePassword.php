<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Change Password')]
class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function updatePassword()
    {
        // 1. Validasi Inputan
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|different:current_password',
            'new_password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal harus berisi 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.different' => 'Password baru harus berbeda dengan password saat ini.',
        ]);

        $user = auth()->user();

        // 2. Validasi Keamanan: Pastikan password lama yang dimasukkan sesuai dengan database
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini yang Anda masukkan salah.');
            return;
        }

        // 3. Update Password Baru ke Database
        /** @var \App\Models\User $user */
        $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        // 4. Bersihkan Form dan Berikan Pesan Sukses
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('message', 'Password Anda berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
