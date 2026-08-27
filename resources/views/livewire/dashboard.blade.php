<div class="px-8 py-4">
    <!-- Header Dashboard -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>.
        </p>
    </div>

    <!-- WIDGET CARD STATISTIK BERDASARKAN ROLE -->
    @if($role === 'Admin')
        <!-- GRID ADMIN (3 Kolom per baris, 6 kartu simetris) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Total Pengguna</h5>
                <p class="text-5xl font-extrabold text-white dark:text-blue-400">{{ $stats['total_users'] }}</p>
            </div>

            <div class="bg-purple-600 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Total Poliklinik</h5>
                <p class="text-5xl font-extrabold text-white dark:text-purple-400">{{ $stats['total_poly'] }}</p>
            </div>

            <div class="bg-cyan-600 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Total Role User</h5>
                <p class="text-5xl font-extrabold text-white dark:text-cyan-400">{{ $stats['total_role'] }}</p>
            </div>

            <div class="bg-green-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Data Pasien</h5>
                <p class="text-5xl font-extrabold text-white dark:text-green-400">{{ $stats['total_patients'] }}</p>
            </div>

            <div class="bg-teal-600 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Data Registrasi</h5>
                <p class="text-5xl font-extrabold text-white dark:text-teal-400">{{ $stats['total_registrations'] ?? 0 }}</p>
            </div>

            <div class="bg-indigo-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Pemeriksaan Selesai</h5>
                <p class="text-5xl font-extrabold text-white dark:text-emerald-400">{{ $stats['completed_records'] }}</p>
            </div>
        </div>

    @elseif($role === 'Perawat')
        <!-- GRID PERAWAT (3 Kolom pas 1 baris) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-green-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Data Pasien</h5>
                <p class="text-5xl font-extrabold text-white dark:text-green-400">{{ $stats['total_patients'] }}</p>
            </div>

            <div class="bg-teal-600 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Data Registrasi</h5>
                <p class="text-5xl font-extrabold text-white dark:text-teal-400">{{ $stats['total_registrations'] ?? 0 }}</p>
            </div>

            <div class="bg-yellow-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Antrean Dokter</h5>
                <p class="text-5xl font-extrabold text-white dark:text-amber-400">{{ $stats['waiting_records'] }}</p>
            </div>
        </div>

    @elseif($role === 'Dokter')
        <!-- GRID DOKTER (2 Kolom pas 1 baris) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-teal-600 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Data Registrasi</h5>
                <p class="text-5xl font-extrabold text-white dark:text-teal-400">{{ $stats['total_registrations'] ?? 0 }}</p>
            </div>

            <div class="bg-yellow-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Antrean Dokter</h5>
                <p class="text-5xl font-extrabold text-white dark:text-amber-400">{{ $stats['waiting_records'] }}</p>
            </div>

            <div class="bg-indigo-500 block p-6 rounded-lg shadow-xs dark:bg-gray-800 dark:border dark:border-gray-700">
                <h5 class="mb-3 text-xl font-semibold text-white">Pemeriksaan Selesai</h5>
                <p class="text-5xl font-extrabold text-white dark:text-emerald-400">{{ $stats['completed_records'] }}</p>
            </div>
        </div>
    @endif

    <!-- CONTAINER GRAFIK -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- CHART TREN KUNJUNGAN PASIEN -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Grafik Kunjungan Pasien (7 Hari Terakhir)</h3>
            <div class="relative h-72">
                <canvas id="patientVisitsChart"></canvas>
            </div>
        </div>

        <!-- CHART PASIEN PER POLI -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pasien per Poliklinik</h3>
            <div class="relative h-72 flex justify-center"><canvas id="polyDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:navigated', function () {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#9CA3AF' : '#4B5563';
        const borderColor = isDarkMode ? '#374151' : '#E5E7EB';

        // --- TREN KUNJUNGAN PASIEN ---
        const canvasVisits = document.getElementById('patientVisitsChart');
        if (canvasVisits) {
            let existingChartVisits = Chart.getChart(canvasVisits);
            if (existingChartVisits) existingChartVisits.destroy();

            new Chart(canvasVisits.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($visitDates),
                    datasets: [{
                        label: 'Jumlah Pasien',
                        data: @json($visitTotals),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#2563EB',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: textColor }, grid: { display: false } },
                        y: { ticks: { color: textColor, precision: 0 }, grid: { color: borderColor }, beginAtZero: true }
                    }
                }
            });
        }

        // --- PASIEN PER POLIKLINIK ---
        const canvasPoly = document.getElementById('polyDistributionChart');
        if (canvasPoly) {
            let existingChartPoly = Chart.getChart(canvasPoly);
            if (existingChartPoly) existingChartPoly.destroy();

            new Chart(canvasPoly.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($polyNames),
                    datasets: [{
                        data: @json($polyCounts),
                        backgroundColor: ['#3B82F6', '#A855F7', '#06B6D4', '#10B981', '#F59E0B'],
                        borderWidth: 2,
                        borderColor: isDarkMode ? '#1F2937' : '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: textColor, padding: 15 } }
                    }
                }
            });
        }
    });
</script>
