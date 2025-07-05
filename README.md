<div align="center">

# 🎓 SkillLearn
### Platform Pembelajaran Online Berbasis Kurasi Video Terarah

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Vite](https://img.shields.io/badge/Vite-6.3+-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![MIT License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](https://choosealicense.com/licenses/mit/)
[![Development Status](https://img.shields.io/badge/Status-Active%20Development-brightgreen?style=for-the-badge)]()

**Solusi pembelajaran gratis dengan kurasi video berkualitas tinggi untuk meningkatkan berbagai skill secara efektif dan terstruktur**

[🚀 Live Demo](#-demo--screenshots) • [📋 Fitur](#-fitur-utama) • [🛠️ Instalasi](#️-instalasi--setup) • [📖 Dokumentasi](#-dokumentasi) • [👥 Tim](#-tim-pengembang)

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

# Install Node dependencies and build assets
npm install
npm run build
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

# Create database
mysql -u root -p
CREATE DATABASE skillearn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit

# Run migrations dan seeders
php artisan migrate --seed

# Create storage symlink
php artisan storage:link
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

#### 🎥 Video Management
```
GET    /api/videos                    # List all videos with filters
GET    /api/videos/{id}               # Get specific video details
POST   /api/videos/{id}/view          # Track video view
GET    /api/videos/category/{category} # Videos by category
```

#### 📚 Course Management
```
GET    /api/courses                   # List all courses
GET    /api/courses/{id}              # Get course with sections
POST   /api/courses/{id}/enroll       # Enroll in course
GET    /api/courses/{id}/progress     # Get user progress
```

#### 🔖 User Interactions
```
POST   /api/bookmarks                 # Toggle bookmark
DELETE /api/bookmarks/{id}            # Remove bookmark
GET    /api/user/bookmarks            # Get user bookmarks
POST   /api/feedbacks                 # Submit feedback
GET    /api/user/progress             # Get learning progress
```

#### 🧪 Quiz System
```
GET    /api/quiz/{id}                 # Get quiz data
POST   /api/quiz/{id}/submit          # Submit quiz answers
GET    /api/quiz/{id}/result          # Get quiz results
GET    /api/user/quiz-history         # Get user quiz history
```

#### 📊 Dashboard & Analytics
```
GET    /api/dashboard                 # Dashboard data
GET    /api/admin/analytics           # Admin analytics
GET    /api/admin/users               # User management
GET    /api/admin/content-stats       # Content statistics
```

### 📚 Guides & Tutorials
- [📖 User Guide](docs/USER-GUIDE.md) - Panduan lengkap untuk pengguna
- [🔨 Admin Guide](docs/ADMIN-GUIDE.md) - Panduan administrasi platform
- [🧪 Quiz Builder Guide](docs/QUIZ-BUILDER-GUIDE.md) - Cara membuat kuis interaktif
- [🎭 Demo Script](docs/demo-script.md) - Script untuk presentasi
- [📊 Presentation Guide](docs/presentation-guide.md) - Panduan presentasi lengkap
- [🏗️ Architecture Documentation](docs/architecture-diagrams.md) - Dokumentasi arsitektur sistem
- [📚 Course System Implementation](docs/COURSE-SYSTEM-IMPLEMENTATION.md) - Implementasi sistem kursus
- [📈 Learning Progress System](docs/LEARNING-PROGRESS-SYSTEM.md) - Sistem tracking progress
- [⚡ AJAX Explanation](docs/ajax-explanation.md) - Penjelasan implementasi AJAX
- [🧪 End-to-End Test Checklist](docs/END-TO-END-TEST-CHECKLIST.md) - Checklist testing

### 🔗 External Documentation
- [Laravel 10.x Documentation](https://laravel.com/docs/10.x)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [Vite Documentation](https://vitejs.dev/guide/)
- [SweetAlert2 Documentation](https://sweetalert2.github.io/)

---

## ⚙️ Konfigurasi

### 🗄️ Database Setup
1. **Buat Database**
```sql
CREATE DATABASE skillearn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **Konfigurasi Environment**
```bash
# Update .env file
APP_NAME="SkillLearn"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skillearn
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 📁 Storage Configuration
```bash
# Create storage symlink
php artisan storage:link

# Set proper permissions (Linux/Mac)
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 🔧 Additional Settings
```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔧 Development

### 🛠️ Development Tools
```bash
# Watch for file changes
npm run dev

# Run Laravel development server
php artisan serve

# Run both simultaneously (recommended)
# Terminal 1:
npm run dev
# Terminal 2:
php artisan serve
```

### 🗃️ Database Management
```bash
# Create new migration
php artisan make:migration create_table_name

# Create model with migration
php artisan make:model ModelName -m

# Create controller
php artisan make:controller ControllerName

# Refresh database with seeders
php artisan migrate:refresh --seed
```

### 🎨 Frontend Development
```bash
# Install new package
npm install package-name

# Build for production
npm run build

# Check for outdated packages
npm outdated
```

### 🔍 Debugging Tools
```bash
# Enable debug mode
# Set APP_DEBUG=true in .env

# View logs
tail -f storage/logs/laravel.log

# Database queries debugging
# Add to .env: DB_LOG_QUERIES=true
```

---

## 🧪 Testing

### 🧪 Menjalankan Tests
```bash
# PHP Unit Tests
php artisan test

# Feature Tests dengan coverage
php artisan test --filter=Feature --coverage

# Unit Tests only
php artisan test --filter=Unit

# Specific test file
php artisan test tests/Feature/AuthTest.php

# Run tests with detailed output
php artisan test --verbose
```

### 📋 Test Coverage
- ✅ **Authentication Tests** - Login, register, logout functionality
- ✅ **API Endpoints Tests** - All REST API endpoints
- ✅ **Database Relationship Tests** - Model relationships and constraints
- ✅ **Security Tests** - CSRF, authorization, input validation
- ✅ **Feature Integration Tests** - End-to-end user workflows
- ✅ **Quiz System Tests** - Quiz creation, submission, grading
- ✅ **Bookmark System Tests** - AJAX bookmark functionality
- ✅ **Progress Tracking Tests** - Learning progress calculations

### 🔧 Testing Environment Setup
```bash
# Create testing database
CREATE DATABASE skillearn_testing;

# Copy environment for testing
cp .env .env.testing

# Update .env.testing
APP_ENV=testing
DB_DATABASE=skillearn_testing

# Run migrations for testing
php artisan migrate --env=testing
```

---

## 🚀 Deployment

### 🌐 Production Deployment

#### 📦 Shared Hosting (cPanel)
```bash
# 1. Upload files ke public_html atau subdirectory
# 2. Update .env for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Generate key and cache
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Run migrations
php artisan migrate --force
```

#### 🐳 Docker Deployment
```dockerfile
# Dockerfile example
FROM php:8.1-fpm

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

EXPOSE 9000
CMD ["php-fpm"]
```

#### ☁️ VPS/Cloud Deployment
```bash
# Using Laravel Forge, DigitalOcean, or AWS
# 1. Setup server with PHP 8.1+, Nginx/Apache, MySQL
# 2. Clone repository
git clone https://github.com/yourusername/SkillLearn-PWEB.git

# 3. Configure web server
# Nginx configuration example in docs/deployment/nginx.conf
# Apache configuration example in docs/deployment/.htaccess
```

### 🔒 Security Checklist
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use HTTPS with SSL certificate
- [ ] Configure proper file permissions
- [ ] Enable CSRF protection
- [ ] Setup rate limiting
- [ ] Regular database backups
- [ ] Update dependencies regularly

---

## ❓ FAQ

### 🤔 Pertanyaan Umum

**Q: Apakah SkillLearn gratis untuk digunakan?**
A: Ya, SkillLearn 100% gratis untuk semua pengguna. Anda dapat mengakses semua fitur tanpa biaya apapun.

**Q: Bagaimana cara menambahkan video baru?**
A: Login sebagai admin, masuk ke dashboard admin, pilih "Video Management" dan klik "Tambah Video Baru".

**Q: Apakah bisa menggunakan database selain MySQL?**
A: Ya, Laravel mendukung PostgreSQL, SQLite, dan SQL Server. Ubah konfigurasi di file `.env`.

**Q: Bagaimana cara backup data?**
A: Gunakan command `php artisan backup:run` atau setup automated backup melalui cron job.

**Q: Apakah mendukung multiple bahasa?**
A: Saat ini hanya mendukung Bahasa Indonesia, namun struktur code sudah siap untuk internationalization.

### 🔧 Technical FAQ

**Q: Error "Class not found" setelah menambah model baru?**
A: Jalankan `composer dump-autoload` untuk memperbarui autoloader.

**Q: Vite tidak bisa compile assets?**
A: Pastikan Node.js versi 16+ terinstall dan jalankan `npm install` ulang.

**Q: Database connection error?**
A: Periksa konfigurasi database di `.env` dan pastikan service MySQL running.

---

## 🐛 Troubleshooting

### ⚠️ Common Issues

#### 1. **500 Internal Server Error**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Common solutions:
chmod -R 755 storage bootstrap/cache
php artisan config:clear
php artisan cache:clear
```

#### 2. **CSRF Token Mismatch**
```bash
# Clear browser cache and cookies
# Or add to exceptions in VerifyCsrfToken middleware
```

#### 3. **File Upload Issues**
```bash
# Check PHP limits in php.ini
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300

# Check storage permissions
sudo chmod -R 775 storage/app/public
php artisan storage:link
```

#### 4. **Database Migration Errors**
```bash
# Rollback and retry
php artisan migrate:rollback
php artisan migrate

# Fresh migration (careful: data loss!)
php artisan migrate:fresh --seed
```

#### 5. **Assets Not Loading**
```bash
# Rebuild assets
npm run build

# Check public/build directory exists
# Verify Vite configuration in vite.config.js
```

### 🆘 Getting Help
- 📧 Email: [skilllearn.dev@gmail.com](mailto:skilllearn.dev@gmail.com)
- 🐛 Issues: [GitHub Issues](https://github.com/rab781/SkillLearn-PWEB/issues)
- 📚 Docs: [Documentation](docs/)
- 💬 Discussions: [GitHub Discussions](https://github.com/rab781/SkillLearn-PWEB/discussions)

---

## 📝 Changelog

### 🚀 Version 1.0.0 (Current)
- ✅ **Initial Release** - Core platform functionality
- ✅ **User Authentication** - Registration, login, logout system
- ✅ **Video Management** - CRUD operations for videos
- ✅ **Course System** - Multi-section course structure
- ✅ **Quiz Builder** - Interactive quiz creation and management
- ✅ **Progress Tracking** - Real-time learning progress monitoring
- ✅ **Bookmark System** - AJAX-powered bookmark functionality
- ✅ **Feedback System** - Video rating and review system
- ✅ **Admin Dashboard** - Comprehensive admin management panel
- ✅ **Responsive Design** - Mobile-first responsive interface
- ✅ **Security Features** - CSRF protection, input validation

### 🔮 Upcoming Features (Roadmap)
- 🔄 **Multi-language Support** - Internationalization (i18n)
- 📱 **Progressive Web App** - PWA capabilities
- 🔔 **Push Notifications** - Real-time notifications
- 💬 **Discussion Forums** - Community discussion features
- 🎯 **Learning Paths** - Structured learning journeys
- 📊 **Advanced Analytics** - Detailed learning analytics
- 🎨 **Theme Customization** - Dark/light mode toggle
- 🔍 **Advanced Search** - Full-text search with filters
- 📱 **Mobile App** - React Native mobile application
- 🤖 **AI Recommendations** - Machine learning content suggestions

### 🐛 Bug Fixes & Improvements
- 🔧 **Performance Optimization** - Database query optimization
- 🔧 **UI/UX Improvements** - Enhanced user interface
- 🔧 **Security Enhancements** - Additional security measures
- 🔧 **Code Refactoring** - Clean code practices implementation

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari komunitas! Berikut cara berkontribusi:

### 🚀 Cara Berkontribusi

1. **Fork** repository ini
2. **Create** feature branch: `git checkout -b feature/AmazingFeature`
3. **Commit** perubahan: `git commit -m 'Add some AmazingFeature'`
4. **Push** ke branch: `git push origin feature/AmazingFeature`
5. **Submit** Pull Request

### 📝 Guidelines
- Ikuti PSR-12 coding standards untuk PHP
- Gunakan conventional commits untuk commit messages
- Tulis tests untuk fitur baru
- Update dokumentasi jika diperlukan
- Pastikan semua tests passing sebelum submit PR
- Gunakan descriptive commit messages

### 🏷️ Conventional Commits
```
feat: add new user authentication system
fix: resolve video upload issue
docs: update API documentation
style: format code according to PSR-12
refactor: restructure course controller
test: add unit tests for quiz system
chore: update dependencies
```

### 🐛 Bug Reports
Gunakan [GitHub Issues](https://github.com/rab781/SkillLearn-PWEB/issues) untuk melaporkan bug dengan template:
- **Environment**: OS, PHP version, Laravel version
- **Steps to reproduce**: Langkah-langkah untuk reproduksi bug
- **Expected behavior**: Perilaku yang diharapkan
- **Actual behavior**: Perilaku yang terjadi
- **Screenshots**: Jika applicable

### 💡 Feature Requests
Untuk request fitur baru, gunakan [GitHub Discussions](https://github.com/rab781/SkillLearn-PWEB/discussions) dengan:
- **Problem description**: Masalah yang ingin dipecahkan
- **Proposed solution**: Solusi yang diusulkan
- **Alternative solutions**: Alternatif solusi lain
- **Use cases**: Contoh penggunaan

---

## 👥 Tim Pengembang

<div align="center">

### 🏆 Kelompok A2 - Sistem Informasi
**Universitas Jember • 2025**

| Role | Name | NIM | GitHub | Email |
|------|------|-----|--------|-------|
| 👑 **Team Lead & Backend** | Mohammad Raihan R | 232410101059 | [@rab781](https://github.com/rab781) | raihan@student.unej.ac.id |
| 🎨 **UI/UX Designer** | Zahra Hilmy Ghaida | 222410101073 | [@zahra-hilmy](https://github.com/zahra-hilmy) | zahra@student.unej.ac.id |
| 💻 **Frontend Developer** | Veny Ramadhani Arifin | 232410101026 | [@veny-ramadhani](https://github.com/veny-ramadhani) | veny@student.unej.ac.id |
| 🗄️ **Backend Developer** | Laura Febi Nurdiana | 232410101097 | [@laura-febi](https://github.com/laura-febi) | laura@student.unej.ac.id |

### 📊 Kontribusi Tim
- **Mohammad Raihan R**: Project architecture, backend development, database design, team coordination
- **Zahra Hilmy Ghaida**: UI/UX design, user research, design system, prototyping
- **Veny Ramadhani Arifin**: Frontend implementation, responsive design, JavaScript development
- **Laura Febi Nurdiana**: Backend features, API development, testing, security implementation

</div>

---

## 📄 Lisensi

This project is licensed under the MIT License - see the [`LICENSE`](LICENSE) file for details.

**Copyright © 2025 Kelompok A2 - Universitas Jember**

### 📜 MIT License Summary
- ✅ **Commercial use** - Dapat digunakan untuk kepentingan komersial
- ✅ **Modification** - Dapat dimodifikasi sesuai kebutuhan
- ✅ **Distribution** - Dapat didistribusikan
- ✅ **Private use** - Dapat digunakan secara private
- ⚠️ **Include license** - Harus menyertakan license notice
- ❌ **No warranty** - Tanpa garansi dari pengembang

---

## 🙏 Acknowledgments

### 💻 Technologies & Frameworks
- **[Laravel Framework](https://laravel.com)** - The PHP framework for web artisans
- **[TailwindCSS](https://tailwindcss.com)** - A utility-first CSS framework for rapid UI development
- **[Vite](https://vitejs.dev)** - Next generation frontend tooling
- **[SweetAlert2](https://sweetalert2.github.io)** - Beautiful, responsive, customizable alerts
- **[Font Awesome](https://fontawesome.com)** - The web's most popular icon set and toolkit

### 🏫 Academic Support
- **[Universitas Jember](https://unej.ac.id)** - Academic guidance and institutional support
- **Fakultas Ilmu Komputer** - Department support and resources
- **Program Studi Sistem Informasi** - Curriculum guidance and mentorship

### 🌟 Special Thanks
- **Dosen Pembimbing** - Academic supervision and guidance
- **Open Source Community** - For amazing tools and libraries
- **Beta Testers** - For valuable feedback and testing
- **Stack Overflow Community** - For development support and solutions

### 📚 Learning Resources
- **[Laravel Documentation](https://laravel.com/docs)** - Comprehensive framework documentation
- **[MDN Web Docs](https://developer.mozilla.org)** - Web technologies documentation
- **[TailwindCSS Documentation](https://tailwindcss.com/docs)** - CSS framework guide
- **[PHP.net](https://php.net)** - Official PHP documentation

---

<div align="center">

### 🌟 Dukung Project Ini!

Jika project ini membantu Anda dalam pembelajaran atau pengembangan, silakan berikan ⭐ **star** di repository ini!

### 📈 Project Statistics
![GitHub stars](https://img.shields.io/github/stars/rab781/SkillLearn-PWEB?style=social)
![GitHub forks](https://img.shields.io/github/forks/rab781/SkillLearn-PWEB?style=social)
![GitHub issues](https://img.shields.io/github/issues/rab781/SkillLearn-PWEB)
![GitHub license](https://img.shields.io/github/license/rab781/SkillLearn-PWEB)

**Made with ❤️ by Kelompok A2 - Universitas Jember**

[⬆ Kembali ke atas](#-skilllearn)

</div>

---

<div align="center">
  
**📧 Contact**: [skilllearn.dev@gmail.com](mailto:skilllearn.dev@gmail.com) | **🌐 Website**: [skillearn.com](https://skillearn.com)

**📱 Social Media**: [LinkedIn](https://linkedin.com/company/skillearn) | [Instagram](https://instagram.com/skillearn.official) | [Twitter](https://twitter.com/skillearn_dev)

*"Belajar tanpa batas, skill tanpa henti"* 🚀

### 🔗 Quick Links
[Documentation](docs/) | [API Reference](docs/api.md) | [Contributing](CONTRIBUTING.md) | [Security Policy](SECURITY.md) | [Changelog](CHANGELOG.md) | [Issues](https://github.com/rab781/SkillLearn-PWEB/issues) | [Discussions](https://github.com/rab781/SkillLearn-PWEB/discussions)

</div>
