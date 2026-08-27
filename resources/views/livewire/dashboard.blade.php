<div class="px-8 py-4">
    <!-- Header Dashboard -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard {{ $role }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- WIDGET MASTER DATA (Khusus Admin) --}}
        @can('manage-master-data')
        <!-- Card Total Pengguna -->
        <div class="bg-blue-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
            <h5 class="mb-3 text-xl font-semibold text-white dark:text-white">Total Pengguna</h5>
            <p class="text-5xl font-extrabold text-white dark:text-blue-400">{{ $stats['total_users'] }}</p>
        </div>

        <!-- Card Total Poliklinik -->
        <div class="bg-purple-600 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
            <h5 class="mb-3 text-xl font-semibold text-white dark:text-white">Total Poliklinik</h5>
            <p class="text-5xl font-extrabold text-white dark:text-purple-400">{{ $stats['total_poly'] }}</p>
        </div>

        <!-- Card Total Role -->
        <div class="bg-cyan-600 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
            <h5 class="mb-3 text-xl font-semibold text-white dark:text-white">Total Role User</h5>
            <p class="text-5xl font-extrabold text-white dark:text-cyan-400">{{ $stats['total_role'] }}</p>
        </div>
        @endcan

        {{-- WIDGET DATA PASIEN (Admin & Perawat) --}}
        @can('manage-patients')
        <div class="bg-green-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
            <h5 class="mb-3 text-xl font-semibold text-white dark:text-white">Data Pasien</h5>
            <p class="text-5xl font-extrabold text-white dark:text-green-400">{{ $stats['total_patients'] }}</p>
        </div>
        @endcan

        {{-- WIDGET ANTREAN POLIKLINIK (Admin, Perawat, & Dokter) --}}
        @can('view-registrations')
        <div class="bg-yellow-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
            <h5 class="mb-3 text-xl font-semibold text-white dark:text-white">Antrean Dokter</h5>
            <p class="text-5xl font-extrabold text-white dark:text-amber-400">{{ $stats['waiting_records'] }}</p>
        </div>
        @endcan

        {{-- WIDGET REKAM MEDIS (Admin & Dokter) --}}
        @can('manage-medical-records')
        <div class="bg-indigo-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
            <h5 class="mb-3 text-xl font-semibold text-white dark:text-white">Pemeriksaan Selesai</h5>
            <p class="text-5xl font-extrabold text-white dark:text-emerald-400">{{ $stats['completed_records'] }}</p>
        </div>
        @endcan

    </div>
</div>
