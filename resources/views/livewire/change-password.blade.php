<section class="w-full lg:w-2xl bg-gray-50 dark:bg-gray-900 px-8 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Akun</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Silakan perbarui kata sandi akun Anda secara berkala untuk menjaga keamanan data.</p>
    </div>

    @if (session()->has('message'))
        <div class="w-2xs p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-300 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-base overflow-hidden p-6 border border-gray-200 dark:border-gray-700">


        <form wire:submit.prevent="updatePassword" class="space-y-4">
            <div>
                <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Saat Ini</label>
                <input type="password" id="current_password" wire:model="current_password"
                       class="px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan password">
                @error('current_password') <span class="text-xs text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <hr class="border-gray-200 dark:border-gray-700 my-4">

            <!-- INPUT PASSWORD BARU -->
            <div>
                <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Baru</label>
                <input type="password" id="new_password" wire:model="new_password"
                       class="px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Minimal 8 karakter">
                @error('new_password') <span class="text-xs text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- KONFIRMASI PASSWORD BARU -->
            <div>
                <label for="new_password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password Baru</label>
                <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation"
                       class="px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Ulangi password baru">
                @error('new_password_confirmation') <span class="text-xs text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- TOMBOL SIMPAN -->
            <div class="flex justify-end pt-2">
                <button type="submit" class="w-full sm:w-auto text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:outline-none focus:ring-brand-medium font-medium rounded-base text-sm px-5 py-2.5 text-center cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</section>
