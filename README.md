<div align="center">

# 🎓 SkillLearn
### Platform Pembelajaran Online Berbasis Kurasi Video Terarah

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

**Solusi pembelajaran gratis dengan kurasi video berkualitas tinggi untuk meningkatkan berbagai skill secara efektif dan terstruktur**

[🚀 Live Demo](#-live-demo) • [📋 Fitur](#-fitur-utama) • [🛠️ Instalasi](#️-instalasi) • [📖 Dokumentasi](#-dokumentasi) • [👥 Tim](#-tim-pengembang)

</div>

---

## 🌟 Tentang SkillLearn

SkillLearn adalah platform pembelajaran online inovatif yang dirancang khusus untuk menyediakan akses gratis ke berbagai keterampilan melalui kurasi video pembelajaran yang terstruktur dan terarah. Platform ini mengatasi masalah overload informasi dengan menyajikan jalur pembelajaran yang jelas dari tingkat dasar hingga mahir.

### 🎯 Mengapa SkillLearn?

- **📚 Kurasi Berkualitas**: Video pembelajaran yang telah dipilih dan disusun secara sistematis
- **🆓 100% Gratis**: Akses penuh tanpa biaya berlangganan
- **🎨 User Experience Modern**: Interface yang intuitif dengan teknologi terdepan
- **📱 Responsive Design**: Optimal di semua perangkat
- **⚡ Performa Tinggi**: Loading cepat dengan teknologi hybrid (server-side + AJAX)

---

## ✨ Fitur Utama

### 👤 Untuk Pengguna (Learners)
- **🔐 Sistem Autentikasi**: Registrasi dan login yang aman
- **🎥 Browse & Filter**: Pencarian video berdasarkan kategori dan tingkat kesulitan
- **❤️ Bookmark System**: Simpan video favorit dengan teknologi AJAX real-time
- **📊 Progress Tracking**: Pantau kemajuan belajar di setiap kursus
- **💬 Feedback System**: Berikan review dan rating untuk setiap video
- **📈 Dashboard Personal**: Statistik pembelajaran dan riwayat tonton

### 👨‍💼 Untuk Admin
- **📹 Video Management**: CRUD lengkap untuk mengelola konten video
- **📚 Course Builder**: Sistem pembuat kursus dengan section dan modul
- **❓ Quiz Builder**: Interface visual untuk membuat kuis interaktif
- **📊 Analytics Dashboard**: Laporan pengguna dan statistik platform
- **💭 Feedback Management**: Kelola review dan tanggapan pengguna
- **🔧 Content Curation**: Tools untuk mengorganisir jalur pembelajaran

---

## 🚀 Demo & Screenshots

### 🖥️ Live Demo
- **Customer Demo**: [customer@skillearn.com](mailto:customer@skillearn.com) | `password123`
- **Admin Demo**: [admin@skillearn.com](mailto:admin@skillearn.com) | `password123`

### 📸 Preview Platform

<details>
<summary>🏠 <strong>Homepage & Landing Page</strong></summary>

> Modern landing page dengan hero section yang menarik dan penjelasan fitur lengkap

</details>

<details>
<summary>📚 <strong>Course Dashboard</strong></summary>

> Dashboard interaktif dengan real-time updates menggunakan AJAX technology

</details>

<details>
<summary>🎯 <strong>Quiz Builder Interface</strong></summary>

> Visual quiz builder dengan drag & drop functionality untuk kemudahan penggunaan admin

</details>

---

## 🛠️ Teknologi & Arsitektur

### 💻 Tech Stack
```
Backend:     Laravel 10.x + PHP 8.1+
Frontend:    Blade Templates + TailwindCSS 3.4
JavaScript:  Vanilla JS + AJAX + Fetch API
Database:    MySQL/PostgreSQL
Tools:       Vite, Composer, NPM
```

### 🏗️ Arsitektur Sistem
- **Hybrid Architecture**: Kombinasi server-side rendering dan client-side interactions
- **API-First Approach**: RESTful API untuk fleksibilitas pengembangan
- **Progressive Enhancement**: Functionality berjalan dengan atau tanpa JavaScript
- **Security First**: CSRF protection, input validation, dan role-based access control

### 🔧 Fitur Teknis Unggulan
- ⚡ **AJAX Real-time**: Bookmark, feedback, dan progress tracking tanpa page refresh
- 🎨 **Modern UI/UX**: Responsive design dengan Tailwind CSS
- 🔒 **Security**: Comprehensive protection dengan Laravel security features
- 📱 **Mobile Optimized**: Perfect experience di semua screen size
- 🚀 **Performance**: Optimized queries dan efficient data loading

---

## 🚀 Instalasi & Setup

### 📋 Prerequisites
- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Web server (Apache/Nginx) atau Laravel Valet/Herd

### ⚙️ Langkah Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/rab781/SkillLearn-PWEB.git
cd SkillLearn-PWEB
```

2. **Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

3. **Environment Setup**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

4. **Database Configuration**
```bash
# Edit .env file dengan database credentials Anda
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=skillearn
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations dan seeders
php artisan migrate --seed
```

5. **Asset Compilation**
```bash
# Development
npm run dev

# Production
npm run build
```

6. **Start Development Server**
```bash
php artisan serve
```

🎉 **Selamat!** SkillLearn sudah siap digunakan di `http://localhost:8000`

---

## 📖 Dokumentasi

### 📁 Struktur Project
```
SkillLearn-PWEB/
├── 📂 app/
│   ├── Http/Controllers/     # Controllers untuk API dan Web
│   ├── Models/              # Eloquent Models
│   └── Middleware/          # Custom Middleware
├── 📂 database/
│   ├── migrations/          # Database Migrations
│   └── seeders/            # Data Seeders
├── 📂 public/
│   ├── js/                 # Custom JavaScript
│   └── css/                # Compiled CSS
├── 📂 resources/
│   ├── views/              # Blade Templates
│   └── js/                 # Source JavaScript
├── 📂 routes/
│   ├── web.php             # Web Routes
│   └── api.php             # API Routes
└── 📂 docs/               # Project Documentation
```

### 🔗 API Endpoints
```
GET    /api/videos          # List all videos
POST   /api/bookmarks       # Toggle bookmark
GET    /api/dashboard       # Dashboard data
POST   /api/feedbacks       # Submit feedback
GET    /api/quiz/{id}       # Get quiz data
```

### 📚 Guides & Tutorials
- [📖 User Guide](docs/USER-GUIDE.md) - Panduan lengkap untuk pengguna
- [🔨 Admin Guide](docs/ADMIN-GUIDE.md) - Panduan administrasi platform
- [🧪 Quiz Builder Guide](docs/QUIZ-BUILDER-GUIDE.md) - Cara membuat kuis interaktif
- [🎭 Demo Script](docs/demo-script.md) - Script untuk presentasi
- [📊 Presentation Guide](docs/presentation-guide.md) - Panduan presentasi lengkap

---

## 🧪 Testing

### 🔬 Menjalankan Tests
```bash
# PHP Unit Tests
php artisan test

# Feature Tests
php artisan test --filter=Feature

# Unit Tests only
php artisan test --filter=Unit
```

### 📋 Test Coverage
- ✅ Authentication Tests
- ✅ API Endpoints Tests
- ✅ Database Relationship Tests
- ✅ Security Tests
- ✅ Feature Integration Tests

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari komunitas! Berikut cara berkontribusi:

1. **Fork** repository ini
2. **Create** feature branch: `git checkout -b feature/AmazingFeature`
3. **Commit** perubahan: `git commit -m 'Add some AmazingFeature'`
4. **Push** ke branch: `git push origin feature/AmazingFeature`
5. **Submit** Pull Request

### 📝 Guidelines
- Ikuti PSR-12 coding standards
- Tulis tests untuk fitur baru
- Update dokumentasi jika diperlukan
- Gunakan commit message yang deskriptif

---

## 👥 Tim Pengembang

<div align="center">

### 🏆 Kelompok A2 - Sistem Informasi
**Universitas Jember • 2025**

| Role | Name | NIM | GitHub |
|------|------|-----|--------|
| 👑 **Team Lead** | Mohammad Raihan R | 232410101059 | [@rab781](https://github.com/rab781) |
| 🎨 **UI/UX Designer** | Zahra Hilmy Ghaida | 222410101073 | - |
| 💻 **Frontend Developer** | Veny Ramadhani Arifin | 232410101026 | - |
| 🗄️ **Backend Developer** | Laura Febi Nurdiana | 232410101097 | - |

</div>

---

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

---

## 🙏 Acknowledgments

- **Laravel Framework** - The PHP framework for web artisans
- **Tailwind CSS** - A utility-first CSS framework
- **Font Awesome** - Icon library
- **SweetAlert2** - Beautiful alerts and modals
- **Universitas Jember** - Academic support and guidance

---

<div align="center">

### 🌟 Dukung Project Ini!

Jika project ini membantu Anda, silakan berikan ⭐ **star** di repository ini!

**Made with ❤️ by Kelompok A2 - Universitas Jember**

[⬆ Kembali ke atas](#-skilllearn)

</div>

---

<div align="center">
  
**📧 Contact**: [skilllearn.dev@gmail.com](mailto:skilllearn.dev@gmail.com) | **🌐 Website**: [skillearn.com](https://skillearn.com)

*"Belajar tanpa batas, skill tanpa henti"* 🚀

</div>
