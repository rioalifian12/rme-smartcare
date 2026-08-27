# RME SmartCare

**RME SmartCare** adalah aplikasi Rekam Medis Elektronik (RME) dan manajemen klinik berbasis **Laravel** dan **Livewire**. Aplikasi ini menyediakan fitur manajemen master data, manajemen pasien, pendaftaran pasien, dan rekam medis dengan kontrol akses berbasis peran (_Role-Based Access Control_).

## Fitur Utama

- ** Sistem Autentikasi**: Fitur Login/Logout menggunakan komponen interaktif Livewire.
- ** Dashboard**: Visualisasi ringkasan aktivitas dan data penting aplikasi.
- ** Kontrol Akses (Permission)**: Hak akses ketat menggunakan Middleware & Gate (`manage-master-data`, `manage-patients`, `view-registrations`, `manage-medical-records`).
- ** Master Data**:
    - **Manajemen Roles**: Pengaturan role dan permission pengguna.
    - **Manajemen Polyclinics**: Pengelolaan data poliklinik/poli.
    - **Manajemen Users**: Pengelolaan data pengguna/staf.
- ** Manajemen Pasien**: Registrasi dan pendataan informasi pasien.
- ** Manajemen Pendaftaran**: Pengelolaan antrean dan pendaftaran pasien ke unit/poli.
- ** Manajemen Rekam Medis**: Pencatatan dan histori rekam medis pasien oleh tenaga medis.

## Stack Teknologi

- **Backend**: Laravel 13 (PHP 8.3+)
- **Frontend**: TailwindCSS 4.3 + Flowbite 4.0
- **Frontend Interaktif**: Livewire
- **Build Tool**: Vite 8.0
- **Database**: MySQL
- **Chart Library**: Chart.js 4.5
- **UI Components**: Blade Templates & Component Library

## Requirements

- **PHP**: ^8.2 atau lebih tinggi
- **Composer**: Untuk mengelola dependencies PHP
- **Node.js & npm**: Untuk mengelola dependencies frontend (Vite/Mix)
- **Database**: MySQL

## Instalasi & Setup

#### 1. Clone & Dependencies

```bash
git clone <repository-url>
cd rme-smartcare
composer install
npm install
```

#### 2. Environment Setup

```bash
# Linux / macOS
cp .env.example .env

# Windows PowerShell
Copy-Item .env.example .env

# Generate Key
php artisan key:generate
```

#### 3. Database Migration

```bash
php artisan migrate
# Optional: Jika ingin menjalankan seeder
php artisan db:seed
```

#### 4. Build Frontend Assets

```bash
npm install
npm run build
```

## Menjalankan Aplikasi

### Development Mode

Jalankan perintah berikut pada terminal terpisah:

```bash
composer run dev
```

Akses aplikasi di browser melalui: **http://localhost:8000**

> **Catatan**: Konfigurasi default credentials / admin user disesuaikan dengan prosedur database seeder yang Anda gunakan.

## Struktur & Lokasi File Important

```
rme-smartcare/
├── app/
│   ├── Livewire/             # Komponen interaktif UI Livewire
│   └── Models/               # Data Models (User, Role, Polyclinic, Patient, Registration, MedicalRecord)
├── database/
│   └── migrations/           # Skema tabel database utama
└── routes/
    └── web.php               # Routing utama & middleware permission
```

## User Roles & Permissions

Hak akses dan rute diatur pada `routes/web.php` menggunakan middleware `auth` dan gate `can`:

- `manage-master-data`: Akses pengelolaan Roles, Polyclinics, dan Users.
- `manage-patients`: Akses pengelolaan data pasien.
- `view-registrations`: Akses melihat dan mengelola pendaftaran pasien.
- `manage-medical-records`: Akses pengisian dan pengelolaan rekam medis pasien.

## Data Models Utama

- **User**: Menyimpan data akun staf/pengguna aplikasi.
- **Role**: Menyimpan daftar peran dan hak akses (permissions).
- **Polyclinic**: Data unit layanan/poliklinik.
- **Patient**: Data master identitas pasien.
- **Registration**: Data pendaftaran pasien ke poliklinik tujuan.
- **MedicalRecord**: Catatan hasil rekam medis pasien.

## Troubleshooting

### Migration Errors

```bash
# Reset database secara keseluruhan
php artisan migrate:fresh
```

### Permission Errors

```bash
# Sesuaikan izin akses folder storage & cache (Linux/macOS)
chmod -R 775 storage bootstrap/cache
```

### Dependencies Issues

```bash
# Clear composer cache & reinstall
composer clear-cache
composer install

# Clear npm cache & reinstall
npm cache clean --force
npm install
```

## Kontribusi

Jika Anda ingin berkontribusi:

1. Buat branch baru untuk fitur/perbaikan (`git checkout -b feature/nama-fitur`).
2. Lakukan _commit_ perubahan Anda.
3. Ajukan _Pull Request_ (PR) dengan penjelasan lengkap mengenai perubahan yang dibuat.

## Lisensi

Proyek ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
