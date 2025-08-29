# InkTouch POS - Panduan Sistem Authentication

## Overview
Sistem InkTouch POS telah diupgrade dengan sistem authentication berbasis role menggunakan Laravel LiveWire dan Tailwind CSS. Sistem ini mendukung 2 role utama: **Admin** dan **Staff** dengan akses fitur yang berbeda.

## Fitur Utama

### 1. Authentication System
- **Login dengan LiveWire**: Interface modern dan responsif
- **Role-based Access Control**: Admin dan Staff memiliki akses berbeda
- **Session Management**: Logout otomatis dan keamanan session
- **Password Protection**: Hash password dengan bcrypt

### 2. Role & Permissions

#### Admin Role
- **Akses Penuh**: Semua fitur sistem
- **Dashboard Admin**: Dashboard khusus dengan statistik lengkap
- **Laporan**: Akses ke laporan penjualan dan export Excel
- **Log Inventaris**: Monitor perubahan stok produk
- **Manajemen Produk & Kategori**: CRUD operations
- **Transaksi**: Buat dan lihat transaksi

#### Staff Role
- **Dashboard Staff**: Dashboard sederhana untuk operasional harian
- **Manajemen Produk & Kategori**: CRUD operations
- **Transaksi**: Buat dan lihat transaksi
- **Akses Terbatas**: Tidak dapat mengakses laporan dan log inventaris

## Kredensial Default

### Admin Account
- **Email**: `admin@inktouch.com`
- **Password**: `password`
- **Role**: Admin

### Staff Account
- **Email**: `staff@inktouch.com`
- **Password**: `password`
- **Role**: Staff

## Cara Menggunakan

### 1. Login ke Sistem
1. Akses aplikasi melalui browser
2. Akan diarahkan ke halaman login secara otomatis
3. Masukkan email dan password
4. Klik "Masuk"
5. Sistem akan mengarahkan ke dashboard sesuai role

### 2. Navigation
- **Admin**: Dapat mengakses semua menu termasuk Laporan dan Log Inventaris
- **Staff**: Menu terbatas sesuai permission
- **Logout**: Klik tombol "Keluar" di pojok kanan atas

### 3. Role Indicator
- Badge role ditampilkan di navigation bar
- **Admin**: Badge merah
- **Staff**: Badge hijau

## Struktur File

### Authentication Components
```
app/Livewire/Auth/
├── Login.php          # Login component
└── Logout.php         # Logout component

resources/views/livewire/auth/
├── login.blade.php    # Login view
└── logout.blade.php   # Logout button
```

### Middleware
```
app/Http/Middleware/
└── RoleMiddleware.php # Role-based access control
```

### Models
```
app/Models/
└── User.php          # User model dengan role methods
```

### Views
```
resources/views/
├── layouts/
│   ├── app.blade.php    # Main layout dengan role-based nav
│   └── guest.blade.php  # Guest layout untuk login
├── dashboard.blade.php  # Staff dashboard
└── admin/
    └── dashboard.blade.php # Admin dashboard
```

## Database Schema

### Users Table
```sql
- id (primary key)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string, hashed)
- role (enum: 'admin', 'staff', default: 'staff')
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

## Security Features

### 1. Middleware Protection
- **auth**: Semua route protected memerlukan login
- **role**: Route tertentu memerlukan role spesifik
- **guest**: Route login hanya untuk user yang belum login

### 2. Route Protection
```php
// Protected routes
Route::middleware(['auth'])->group(function () {
    // Basic routes untuk semua authenticated users
    
    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/laporan', [ReportController::class, 'index']);
        Route::get('/inventory-logs', [InventoryLogController::class, 'index']);
    });
});
```

### 3. View-Level Protection
```blade
@auth
    @if(auth()->user()->isAdmin())
        <!-- Admin only content -->
    @endif
@endauth
```

## Customization

### Menambah Role Baru
1. Update enum di migration:
```php
$table->enum('role', ['admin', 'staff', 'manager'])->default('staff');
```

2. Tambah method di User model:
```php
public function isManager(): bool
{
    return $this->role === 'manager';
}
```

3. Update middleware dan routes sesuai kebutuhan

### Mengubah Redirect Setelah Login
Edit file `app/Livewire/Auth/Login.php`:
```php
if ($user->isAdmin()) {
    return redirect()->intended('/admin/dashboard');
} else {
    return redirect()->intended('/dashboard');
}
```

## Troubleshooting

### 1. Error 403 Unauthorized
- Pastikan user memiliki role yang sesuai
- Check middleware pada route
- Verify user login status

### 2. Redirect Loop
- Clear browser cache dan cookies
- Check route middleware configuration
- Verify session configuration

### 3. Login Gagal
- Verify kredensial di database
- Check password hash
- Ensure migration telah dijalankan

## Commands

### Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### Create New User
```bash
php artisan tinker
User::create([
    'name' => 'New User',
    'email' => 'user@example.com',
    'password' => Hash::make('password'),
    'role' => 'staff'
]);
```

## Teknologi yang Digunakan

- **Laravel 12**: Framework PHP
- **LiveWire 3**: Frontend reactivity
- **Tailwind CSS 4**: Styling framework
- **Laravel Authentication**: Built-in auth system
- **Role-based Middleware**: Custom middleware untuk authorization

## Support

Untuk pertanyaan atau masalah, silakan hubungi tim development atau buat issue di repository project.
