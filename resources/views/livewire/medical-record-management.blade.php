<section class="w-full mx-auto bg-gray-50 dark:bg-gray-900 px-4 sm:px-6 lg:px-8 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pemeriksaan Dokter (Rekam Medis)</h1>
    </div>

    @if (session()->has('message'))
        <div class="w-xs p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-300 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-base overflow-hidden">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white p-4">Antrean Pasien</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">No. RM</th>
                            <th class="px-4 py-3">Nama Pasien</th>
                            <th class="px-4 py-3">Poli Tujuan</th>
                            <th class="px-4 py-3">Tanda Vital</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($waitingRegistrations as $w)
                        <tr class="border-b border-gray-300 dark:border-gray-700">
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $w->patient->medical_record_number }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $w->patient->name }}</td>
                            <td class="px-4 py-3">{{ $w->polyclinic->poly_name }}</td>
                            <td class="px-4 py-3 text-xs">
                                TD: {{ $w->systole ?? '-' }}/{{ $w->diastole ?? '-' }} |
                                Temp: {{ $w->temperature ? $w->temperature.'°C' : '-' }} |
                                BB: {{ $w->weight ? $w->weight.'kg' : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="openExamineModal({{ $w->id }})" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                                    Periksa
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada antrean pasien saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIWAYAT PEMERIKSAAN MEDIS -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-base overflow-hidden">
            <div class="flex flex-col sm:flex-row items-center justify-between p-4 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Rekam Medis</h2>
                <div class="w-full sm:w-1/2 mt-2 sm:mt-0">
                    <input type="text" wire:model.live.debounce.300ms="search" class="px-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Cari riwayat rekam medis pasien...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Pasien</th>
                            <th class="px-4 py-3">Dokter Pemeriksa</th>
                            <th class="px-4 py-3">Keluhan</th>
                            <th class="px-4 py-3">Diagnosis</th>
                            <th class="px-4 py-3">Resep</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicalRecords as $rec)
                        <tr class="border-b border-gray-300 dark:border-gray-700">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-semibold dark:text-white">{{ $rec->registration->patient->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $rec->doctor->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">{{ $rec->complaints }}</td>
                            <td class="px-4 py-3 font-medium dark:text-white">{{ $rec->diagnosis ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $rec->prescriptions ?? '-' }}</td>
                            <td class="px-4 py-3 flex items-center space-x-2">
                                <button wire:click="openEditModal({{ $rec->id }})" class="flex items-center justify-center text-warning hover:text-warning-strong p-1.5 focus:outline-none cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m16.475 5.408l2.117 2.117m-.756-3.982L12.109 9.27a2.1 2.1 0 0 0-.58 1.082L11 13l2.648-.53c.41-.082.786-.283 1.082-.579l5.727-5.727a1.853 1.853 0 1 0-2.621-2.621"/><path d="M19 15v3a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h3"/></g></svg>
                                </button>
                                <button wire:click="openDeleteModal({{ $rec->id }})" class="flex items-center justify-center text-danger hover:text-danger-strong p-1.5 focus:outline-none cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024"><path fill="currentColor" d="M360 184h-8c4.4 0 8-3.6 8-8zh304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32M731.3 840H292.7l-24.2-512h487z"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat rekam medis.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $medicalRecords->links() }}
            </div>
        </div>

    </div>

    <!-- Modal Rekam Medis -->
    @if($isExamineOpen && $selectedRegistration)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
        <div class="relative w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 p-4 sm:p-5">
                <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-gray-600">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Input Rekam Medis</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pasien: {{ $selectedRegistration->patient->name }} ({{ $selectedRegistration->patient->medical_record_number }})</p>
                    </div>
                    <button type="button" wire:click="resetInputs" class="text-gray-400 hover:bg-gray-200 text-sm px-1 rounded-full dark:hover:bg-gray-600 cursor-pointer">✕</button>
                </div>

                <form wire:submit.prevent="saveMedicalRecord" class="space-y-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Keluhan Utama (Complaints) *</label>
                        <textarea wire:model="complaints" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2" required></textarea>
                        @error('complaints') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Diagnosis</label>
                        <textarea wire:model="diagnosis" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Tindakan (Treatment)</label>
                        <textarea wire:model="treatment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Resep Obat (Prescriptions)</label>
                        <textarea wire:model="prescriptions" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-2 border-t dark:border-gray-600">
                        <button type="button" wire:click="resetInputs" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Batal</button>
                        <button type="submit" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Simpan Rekam Medis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Rekam Medis -->
    @if($isEditOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
        <div class="relative w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 p-4 sm:p-5">
                <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Rekam Medis</h3>
                    <button type="button" wire:click="resetInputs" class="text-gray-400 hover:bg-gray-200 text-sm px-1 rounded-full dark:hover:bg-gray-600 cursor-pointer">✕</button>
                </div>
                <form wire:submit.prevent="updateMedicalRecord" class="space-y-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Keluhan Utama (Complaints) *</label>
                        <textarea wire:model="complaints" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2" required></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Diagnosis</label>
                        <textarea wire:model="diagnosis" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Tindakan (Treatment)</label>
                        <textarea wire:model="treatment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Resep Obat (Prescriptions)</label>
                        <textarea wire:model="prescriptions" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-base block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-2 border-t dark:border-gray-600">
                        <button type="button" wire:click="resetInputs" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Batal</button>
                        <button type="submit" class="text-white bg-warning box-border border border-transparent hover:bg-warning-strong focus:ring-4 focus:ring-warning-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Hapus -->
    @if($isDeleteOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <svg class="text-gray-900 dark:text-white w-11 h-11 mb-3.5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <p class="mb-4 text-gray-900 dark:text-white">Apakah Anda yakin ingin menghapus rekam medis ini?</p>
                <div class="flex justify-center items-center space-x-4">
                    <button type="button" wire:click="resetInputs" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Batal</button>
                    <button type="button" wire:click="deleteMedicalRecord" class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
