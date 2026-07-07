# Sistem Manajemen Inventaris - PT Telkomsel

Sistem Manajemen Inventaris berbasis web yang dirancang khusus untuk mengelola data aset, kategori barang, serta log aktivitas transaksi peminjaman barang di lingkungan kantor PT Telkomsel. Aplikasi ini dibangun menggunakan framework **Laravel 13**, **Tailwind CSS**, database **PostgreSQL**, dan didukung oleh ekosistem kontainerisasi **Docker**.

---

## 🔗 Link Demo & Hosting

*   **Link Live Demo (Railway)**: [https://sistem-manajemen-inventaris-production.up.railway.app](https://sistem-manajemen-inventaris-production.up.railway.app)

---

## 📁 Informasi Struktur Folder Proyek

Untuk mempermudah penelaahan kode, berikut adalah struktur pohon folder utama proyek ini beserta keterangannya:

```text
Sistem Manajemen Inventaris/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                     # Controller bawaan Laravel Breeze untuk Auth
│   │   │   ├── BorrowingController.php   # Manajemen transaksi & pengembalian barang
│   │   │   ├── CategoryController.php    # Manajemen kategori produk (CRUD & inline edit)
│   │   │   ├── DashboardController.php   # Statistik dasbor & visualisasi Chart.js
│   │   │   ├── ProductController.php     # CRUD produk master, pencarian, & filter
│   │   │   ├── ProfileController.php     # Pengaturan profil pengguna
│   │   │   └── ReportController.php      # Ekspor Laporan ke PDF dan Excel/CSV
│   │   └── Middleware/
│   │       └── RoleMiddleware.php        # Middleware kustom untuk pembatasan peran/role
│   └── Models/
│       ├── Borrowing.php                 # Representasi tabel transaksi peminjaman
│       ├── BorrowingDetail.php           # Relasi detail peminjaman & kuantitas barang
│       ├── Category.php                  # Model kategori barang
│       ├── Product.php                   # Model aset/barang master
│       ├── Role.php                      # Model peran (Admin, Staff, Manager)
│       └── User.php                      # Model data pengguna
├── bootstrap/
│   └── app.php                           # Registrasi middleware 'role' & exception JSON
├── config/                               # Konfigurasi aplikasi Laravel
├── database/
│   ├── database_dump.sql                 # SQL dump untuk restore database PostgreSQL
│   ├── factories/                        # Pabrik data tiruan (fake data factories)
│   ├── migrations/                       # DDL skema database (8 file migrasi)
│   └── seeders/                          # Pengisi data awal (seeder) pengguna & barang
├── resources/
│   ├── css/                              # File styling Tailwind CSS
│   ├── js/                               # Logika JavaScript frontend & grafik Chart.js
│   └── views/                            # Template antarmuka Blade PHP
├── routes/
│   ├── api.php                           # Endpoint REST API Publik
│   └── web.php                           # Rute halaman web & kontrol otorisasi middleware
├── tests/
│   ├── Feature/                          # Uji integrasi fitur (Auth, CRUD, PDF/Excel)
│   └── Unit/                             # Uji fungsionalitas unit kecil
├── docker-compose.yml                    # Konfigurasi orkestrasi container Docker
└── Dockerfile                            # Konfigurasi build image PHP 8.4 Alpine
```

---

## 🛠️ Fitur Utama & Fitur Bonus

### Fitur Fungsional Utama:
1.  **Manajemen Kategori Barang (CRUD)**: Pembuatan, pembaruan inline (tanpa reload halaman), pencarian, dan penghapusan aman (mencegah penghapusan jika kategori masih memiliki produk aktif).
2.  **Manajemen Barang/Aset Master (CRUD)**: Pencatatan aset inventaris lengkap dengan kode barang otomatis, detail spesifikasi, pencarian, dan penyaringan berdasarkan kategori, kondisi, dan status stok.
3.  **Transaksi Peminjaman & Pengembalian Barang**:
    *   Pengurangan stok secara realtime saat pencatatan transaksi peminjaman baru.
    *   Pengembalian stok secara otomatis saat barang ditandai telah dikembalikan (*returned*).
    *   Didukung **Database Transactions** (`DB::transaction`) untuk menjaga konsistensi dan integritas data secara atomik.
4.  **Sistem Otorisasi Multi-Role**: Pembagian hak akses berbasis Role-based Access Control (RBAC) menggunakan Gates dan Middleware khusus:
    *   **Admin & Staff**: Akses penuh untuk mengelola Kategori, Barang, dan Transaksi Peminjaman.
    *   **Manager**: Akses khusus untuk melihat dasbor laporan visual, mengunduh data laporan (PDF/Excel), serta memantau log transaksi.

### Fitur Tambahan (Bonus Fitur):
1.  **Dasbor Interaktif & Notifikasi**:
    *   Pemberitahuan otomatis (Alerts) untuk produk dengan stok habis (0) atau stok menipis (<= 5).
    *   Visualisasi grafik tren bulanan peminjaman menggunakan library Chart.js.
2.  **Ekspor Laporan (PDF & Excel/CSV)**:
    *   **Ekspor PDF**: Laporan siap cetak berformat PDF premium menggunakan package `laravel-dompdf`.
    *   **Ekspor Excel**: Unduhan CSV terenkode BOM UTF-8 agar selaras saat dibuka langsung melalui Microsoft Excel.
3.  **REST API Publik**: Menyediakan endpoint JSON untuk pertukaran data inventaris dan transaksi.
4.  **Upload Foto Barang**: Mendukung unggahan foto fisik barang yang terintegrasi aman dengan Laravel Storage.
5.  **Toggle Dark Mode**: Switcher tema gelap/terang dinamis dengan retensi preferensi pengguna berbasis `localStorage`.

---

## 🖥️ Persyaratan Sistem (System Requirements)

Pastikan perangkat lunak berikut terinstal di komputer Anda sebelum memulai instalasi:
*   [Docker Desktop](https://www.docker.com/products/docker-desktop/) (pastikan Docker Daemon berjalan aktif)
*   [Node.js](https://nodejs.org/) (versi 18 ke atas)

---

## 🚀 Cara Instalasi & Menjalankan Proyek

Ikuti langkah-langkah di bawah ini untuk menyiapkan dan menjalankan proyek inventaris di komputer lokal Anda:

### Langkah 1: Clone Repositori
Clone proyek ini dari repositori GitHub ke komputer Anda:
```bash
git clone <url-repositori-github>
cd "Sistem Manajemen Inventaris"
```

### Langkah 2: Konfigurasi Environment File
Salin file konfigurasi `.env.example` menjadi `.env` di direktori utama:
```bash
cp .env.example .env
```
*(Catatan: Konfigurasi database di dalam `.env.example` telah disesuaikan secara bawaan menggunakan PostgreSQL dengan konfigurasi jaringan Docker).*

### Langkah 3: Siapkan Jaringan Docker & Container Database
1.  Buat jaringan eksternal Docker bernama `postgresql_default` (diperlukan oleh konfigurasi compose):
    ```bash
    docker network create postgresql_default
    ```
2.  *Jika Anda belum memiliki container database PostgreSQL*, Anda dapat menjalankan database PostgreSQL yang kompatibel dengan perintah berikut:
    ```bash
    docker run --name postgres-db --network postgresql_default -e POSTGRES_DB=telkomsel_inventory -e POSTGRES_USER=harun -e POSTGRES_PASSWORD=sandi -p 5432:5432 -d postgres:latest
    ```
3.  Nyalakan container aplikasi utama:
    ```bash
    docker-compose up -d
    ```
    *Docker secara otomatis mengunduh image PHP 8.4 Alpine, menyambungkan container aplikasi ke database PostgreSQL, serta mengekspos port aplikasi.*

### Langkah 4: Hubungkan Storage Link
Jalankan perintah berikut di dalam container untuk menghubungkan symlink folder penyimpanan gambar barang agar dapat diakses publik:
```bash
docker-compose exec app php artisan storage:link
```

### Langkah 5: Jalankan Migrasi & Database Seeder
Lakukan migrasi struktur tabel database beserta pengisian data uji coba awal (users, roles, categories, products, dan borrowings):
```bash
docker-compose exec app php artisan migrate --seed
```

### Langkah 6: Jalankan Kompilasi Aset Frontend
Jalankan perintah pemasangan pustaka frontend dan server dev Vite secara lokal pada komputer host Anda:
```bash
npm install
npm run dev
```

### Langkah 7: Akses Aplikasi
Buka web browser dan kunjungi alamat berikut:
*   Situs Web Utama: **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Akun Login Uji Coba (Testing Accounts)

Gunakan daftar akun di bawah ini untuk menguji hak akses masing-masing peran:

| Peran (Role) | Email Karyawan | Kata Sandi (Password) | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@telkomsel.com` | `password` | Mengelola Kategori, Barang, dan Transaksi Peminjaman |
| **Staff** | `staff@telkomsel.com` | `password` | Mengelola Kategori, Barang, dan Transaksi Peminjaman |
| **Manager** | `manager@telkomsel.com` | `password` | Melihat Laporan (PDF/Excel) & Dasbor Visual |

---

## 🔌 Dokumentasi REST API

Semua data respon disajikan dalam format JSON. Endpoint ini bersifat publik untuk pertukaran data inventaris eksternal.

### 1. Dapatkan Semua Produk
*   **Endpoint**: `GET /api/products`
*   **Deskripsi**: Mengambil data seluruh barang inventaris lengkap beserta relasi kategori masing-masing.
*   **Contoh Respons (JSON)**:
    ```json
    {
        "status": "success",
        "data": [
            {
                "id": 1,
                "code": "BRG-202607060001",
                "name": "MacBook Pro M3",
                "category_id": 1,
                "stock": 10,
                "storage_location": "Ruang Aset lt.3",
                "condition": "Baik",
                "image_path": "products/macbook-pro.jpg",
                "created_at": "2026-07-06T12:00:00.000000Z",
                "updated_at": "2026-07-06T12:00:00.000000Z",
                "category": {
                    "id": 1,
                    "name": "Elektronik & Gadget",
                    "created_at": "2026-07-06T12:00:00.000000Z",
                    "updated_at": "2026-07-06T12:00:00.000000Z"
                }
            }
        ]
    }
    ```

### 2. Dapatkan Detail Satu Produk
*   **Endpoint**: `GET /api/products/{id}`
*   **Deskripsi**: Mengambil data rincian satu barang berdasarkan ID produk.
*   **Contoh Respons (JSON)**:
    ```json
    {
        "status": "success",
        "data": {
            "id": 1,
            "code": "BRG-202607060001",
            "name": "MacBook Pro M3",
            "category_id": 1,
            "stock": 10,
            "storage_location": "Ruang Aset lt.3",
            "condition": "Baik",
            "image_path": "products/macbook-pro.jpg",
            "created_at": "2026-07-06T12:00:00.000000Z",
            "updated_at": "2026-07-06T12:00:00.000000Z",
            "category": {
                "id": 1,
                "name": "Elektronik & Gadget",
                "created_at": "2026-07-06T12:00:00.000000Z",
                "updated_at": "2026-07-06T12:00:00.000000Z"
            }
        }
    }
    ```

### 3. Dapatkan Log Riwayat Peminjaman
*   **Endpoint**: `GET /api/borrowings`
*   **Deskripsi**: Mengambil riwayat log transaksi peminjaman barang beserta relasi nama peminjam, status barang, kuantitas, dan detail waktu.
*   **Contoh Respons (JSON)**:
    ```json
    {
        "status": "success",
        "data": [
            {
                "id": 1,
                "borrower_name": "Harun Sanjaya",
                "borrow_date": "2026-07-06T00:00:00.000000Z",
                "return_date": null,
                "status": "Borrowed",
                "created_at": "2026-07-06T12:15:00.000000Z",
                "updated_at": "2026-07-06T12:15:00.000000Z",
                "products": [
                    {
                        "id": 1,
                        "code": "BRG-202607060001",
                        "name": "MacBook Pro M3",
                        "pivot": {
                            "borrowing_id": 1,
                            "product_id": 1,
                            "quantity": 1
                        }
                    }
                ]
            }
        ]
    }
    ```

---

## 🧪 Hasil Unit & Feature Testing

Proyek ini telah dilengkapi dengan rangkaian pengujian otomatis (*Automated Test Suite*) untuk memverifikasi kebenaran logika otorisasi peran (RBAC), integritas transaksi peminjaman, manajemen stok, serta fitur ekspor laporan.

### Perintah Menjalankan Test
Jalankan pengujian menggunakan PHPUnit di dalam container Docker:
```bash
docker-compose exec app php artisan test
```

### Hasil Log Pengujian (Output)
Berikut adalah hasil running test suite lokal yang menunjukkan kesuksesan 100% kelulusan pengujian:

```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                    0.01s  

   PASS  Tests\Feature\ApiTest
  ✓ api products returns success and correct schema                      0.43s  
  ✓ api single product returns correct details                           0.24s  
  ✓ api borrowings ordered by latest                                     0.19s  

   PASS  Tests\Feature\Auth\AuthenticationTest
  ✓ login screen can be rendered                                         1.09s  
  ✓ users can authenticate using the login screen                        2.19s  
  ✓ users can not authenticate with invalid password                     0.55s  
  ✓ users can logout                                                     0.22s  

   PASS  Tests\Feature\Auth\EmailVerificationTest
  ✓ email verification screen can be rendered                            0.26s  
  ✓ email can be verified                                                0.48s  
  ✓ email is not verified with invalid hash                              0.36s  

   PASS  Tests\Feature\Auth\PasswordConfirmationTest
  ✓ confirm password screen can be rendered                              0.37s  
  ✓ password can be confirmed                                            0.19s  
  ✓ password is not confirmed with invalid password                      0.47s  

   PASS  Tests\Feature\Auth\PasswordResetTest
  ✓ reset password link screen can be rendered                           0.34s  
  ✓ reset password link can be requested                                 2.08s  
  ✓ reset password screen can be rendered                                0.55s  
  ✓ password can be reset with valid token                               0.73s  

   PASS  Tests\Feature\Auth\PasswordUpdateTest
  ✓ password can be updated                                              0.23s  
  ✓ correct password must be provided to update password                 0.21s  

   PASS  Tests\Feature\Auth\RegistrationTest
  ✓ registration screen can be rendered                                  0.25s  
  ✓ new users can register                                               0.26s  

   PASS  Tests\Feature\BorrowingTest
  ✓ borrowing index page can be rendered                                 0.76s  
  ✓ admin can view borrowing create form                                 0.37s  
  ✓ admin can create borrowing                                           0.29s  
  ✓ cannot borrow more than available stock                              0.19s  
  ✓ borrower name is required                                            0.20s  
  ✓ admin can return borrowed item                                       0.28s  
  ✓ cannot return already returned item                                  0.18s  
  ✓ admin can delete borrowing                                           0.19s  
  ✓ borrowing index can filter by status                                 0.38s  
  ✓ borrowing index can search by borrower                               0.39s  
  ✓ borrowing show page displays details                                 0.57s  

   PASS  Tests\Feature\CategoryTest
  ✓ category index displays categories                                   0.42s  
  ✓ admin can create category                                            0.19s  
  ✓ category name must be unique                                         0.20s  
  ✓ category name is required                                            0.20s  
  ✓ admin can update category                                            0.23s  
  ✓ cannot delete category with products                                 0.22s  
  ✓ can delete empty category                                            0.22s  
  ✓ category index has pagination                                        0.47s  

   PASS  Tests\Feature\DashboardTest
  ✓ dashboard can be rendered                                            0.41s  
  ✓ dashboard displays total products                                    0.38s  
  ✓ dashboard displays available stock                                   0.38s  
  ✓ dashboard shows low stock alert                                      0.38s  
  ✓ dashboard shows out of stock alert                                   0.39s  
  ✓ dashboard shows chart                                                0.38s  
  ✓ dashboard shows recent borrowings                                    0.38s  
  ✓ dashboard accessible by all roles                                    0.61s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                        0.24s  

   PASS  Tests\Feature\ProductTest
  ✓ product index page can be rendered                                   0.66s  
  ✓ admin can view product create form                                   0.65s  
  ✓ admin can create product                                             0.19s  
  ✓ product code must be unique                                          0.20s  
  ✓ validation fails for invalid condition                               0.20s  
  ✓ admin can update product                                             0.19s  
  ✓ cannot delete product with active borrowing                          0.18s  
  ✓ can delete product without borrowing                                 0.19s  
  ✓ product show page displays details                                   0.57s  
  ✓ product search by name                                               0.44s  
  ✓ product search by code                                               0.42s  
  ✓ product filter by category                                           0.40s  
  ✓ product filter by stock status low                                   0.39s  
  ✓ product filter by condition                                          0.39s  
  ✓ product upload image                                                 1.04s  

   PASS  Tests\Feature\ProfileTest
  ✓ profile page is displayed                                            1.69s  
  ✓ profile information can be updated                                   0.25s  
  ✓ email verification status is unchanged when the email address is un… 0.20s  
  ✓ user can delete their account                                        0.20s  
  ✓ correct password must be provided to delete account                  0.23s  

   PASS  Tests\Feature\ReportTest
  ✓ report index can be rendered                                         0.42s  
  ✓ report index shows inventory table                                   0.39s  
  ✓ report index shows borrowing log                                     0.40s  
  ✓ report pdf can be downloaded                                         2.35s  
  ✓ report excel can be downloaded                                       0.22s  
  ✓ report excel contains correct headers                                0.19s  
  ✓ report excel contains product data                                   0.33s  
  ✓ manager can access reports page                                      0.35s  

   PASS  Tests\Feature\RoleTest
  ✓ admin can access category index                                      0.44s  
  ✓ staff can access category index                                      0.43s  
  ✓ manager cannot access category index                                 0.24s  
  ✓ admin can store category                                             0.20s  
  ✓ manager cannot store category                                        0.24s  
  ✓ admin can access product create                                      0.38s  
  ✓ manager cannot access product create                                 0.23s  
  ✓ all roles can view product index                                     0.69s  
  ✓ admin can access borrowing create                                    0.39s  
  ✓ manager cannot access borrowing create                               0.24s  
  ✓ all roles can view borrowing index                                   0.67s  
  ✓ admin can access reports                                             0.51s  
  ✓ manager can access reports                                           0.47s  
  ✓ staff cannot access reports                                          0.32s  
  ✓ guest is redirected to login                                         0.21s  

  Tests:    100 passed (338 assertions)
  Duration: 82.88s
```

---

## 🗃️ File Database (.sql)

File dump database PostgreSQL tersedia di:
```text
database/database_dump.sql
```

File ini berisi seluruh struktur tabel (DDL) beserta data seed awal (roles, users, categories, products, borrowings) yang dapat digunakan untuk restore database secara mandiri:

```bash
# Restore ke database PostgreSQL lokal / VPS Anda
PGPASSWORD=<password> psql -h <host> -U <user> -d <database> < database/database_dump.sql
```

---

## ☁️ Deployment ke Railway

Proyek ini telah dikonfigurasi sepenuhnya untuk dideploy langsung ke **Railway** menggunakan arsitektur web server produksi **Nginx + PHP-FPM + Supervisor**.

### Fitur Cloud Deployment:
1. **Dynamic Port Binding**: Skrip startup `start.sh` mendeteksi port dinamis yang diberikan Railway (`$PORT`) dan menyunting konfigurasi Nginx secara otomatis agar mendengarkan port yang tepat (serta port cadangan `8000`).
2. **Automated Migrations & Conditional Seeding**: Setiap kontainer berjalan, sistem otomatis mengeksekusi migrasi database (`php artisan migrate --force`). Seeding (`php artisan db:seed`) hanya akan dijalankan jika database kosong untuk menghindari kegagalan entri ganda.
3. **Environment Forwarding**: Konfigurasi PHP-FPM diatur dengan `clear_env = no` untuk meneruskan seluruh variabel lingkungan dari Railway (seperti database credentials dan `APP_KEY`) ke Laravel di runtime.
4. **SSL/TLS & HTTPS Force**: Otorisasi HTTPS dipaksakan secara otomatis di lingkungan produksi melalui `URL::forceScheme('https')` pada `AppServiceProvider.php` guna menghindari masalah *Mixed Content* di peramban (browser).

### Langkah Men-deploy Mandiri ke Railway:
1. Hubungkan repositori GitHub Anda ke proyek baru di Railway.
2. Tambahkan layanan **PostgreSQL** di Railway.
3. Sambungkan variabel lingkungan di layanan web Anda dengan PostgreSQL (gunakan sintaks referensi Railway seperti `DB_HOST=${{Postgres.PGHOST}}`, dsb).
4. Pastikan kolom **Start Command** di tab **Settings** layanan web Anda di Railway dikosongkan (agar Railway membaca instruksi `start.sh` dari `Dockerfile` kita).
5. Railway akan mendeteksi `Dockerfile`, mem-build image berbasis Alpine Linux, lalu menjalankan server produksi secara instan.
