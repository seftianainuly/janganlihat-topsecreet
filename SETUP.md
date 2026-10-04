# 🚀 Setup GadgetHub — Futuristic Cyber-Tech Gadget Hub

## Langkah Setup (Jalankan Sekali)

### 1. Start MySQL (XAMPP)
Buka **XAMPP Control Panel** → klik **Start** di baris **MySQL**.

### 2. Buat Database
Buka browser → http://localhost/phpmyadmin → buat database baru: **`gadgethub`**

Pastikan database sudah dibuat sebelum menjalankan migrasi. Di phpMyAdmin, buka tab **SQL** lalu jalankan:
```sql
CREATE DATABASE IF NOT EXISTS gadgethub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Atau via command (setelah MySQL berjalan dan MySQL CLI tersedia):
```bash
C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -e "CREATE DATABASE IF NOT EXISTS gadgethub;"
```

### 3. Jalankan Migration + Seeder
```bash
php artisan migrate --force
php artisan db:seed
```

### 4. Build Assets
```bash
npm run build
# atau untuk development:
npm run dev
```

### 5. Jalankan Server
```bash
php artisan serve
```

Akses: **http://127.0.0.1:8000**

---

## Kredensial Admin (Default)
- **Email**: `admin@gadgethub.com`
- **Password**: `password123`
- **Login URL**: http://127.0.0.1:8000/login

---

## Struktur Halaman
| URL | Keterangan |
|-----|-----------|
| `/` | Landing Page |
| `/blog` | Daftar Artikel Publik |
| `/blog/{slug}` | Detail Artikel |
| `/login` | Login Admin |
| `/admin/blogs` | CMS - Daftar Blog |
| `/admin/blogs/create` | CMS - Tambah Artikel |
| `/admin/blogs/{id}/edit` | CMS - Edit Artikel |
