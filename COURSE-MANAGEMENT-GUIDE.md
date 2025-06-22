# SkillLearn Course Management System - Complete Guide

## Overview
SkillLearn telah sepenuhnya direfactor dari sistem berbasis video menjadi sistem berbasis course yang komprehensif. Admin kini dapat mengelola course dengan mudah melalui interface yang intuitif.

## 🎯 Fitur Utama Course Management

### 1. Course CRUD Management
- **Create Course**: Membuat course baru dengan nama, deskripsi, kategori, level, dan gambar
- **Read Course**: Melihat daftar course dengan statistik lengkap
- **Update Course**: Edit informasi course
- **Delete Course**: Hapus course beserta semua kontennya
- **Toggle Status**: Aktifkan/nonaktifkan course

### 2. Section Management
- **Tambah Section**: Organisir course dalam beberapa bagian
- **Edit Section**: Update nama dan deskripsi section
- **Hapus Section**: Hapus section beserta semua videonya
- **Urutan Section**: Mengatur urutan pembelajaran

### 3. Video Management dalam Course
- **Tambah Video ke Section**: Pilih video dari library dan tambahkan ke section tertentu
- **Atur Urutan Video**: Menentukan urutan video dalam section
- **Catatan Admin**: Tambahkan catatan khusus untuk setiap video
- **Durasi Custom**: Set durasi video yang berbeda dari aslinya
- **Hapus Video**: Hapus video dari course tanpa menghapus video asli

### 4. Quick Review System
- **Review Setelah Video**: Ringkasan atau poin penting setelah video tertentu
- **Review Setelah Section**: Evaluasi setelah menyelesaikan section
- **Review Tengah Course**: Review strategis di tengah-tengah course
- **HTML Content**: Mendukung formatting HTML untuk konten yang kaya

### 5. Quiz Management System
- **Quiz Setelah Video**: Quiz pendek setelah video tertentu
- **Quiz Setelah Section**: Evaluasi pemahaman section
- **Quiz Akhir Course**: Quiz komprehensif di akhir course
- **Format JSON**: Quiz dalam format JSON yang fleksibel
- **Durasi Custom**: Set waktu pengerjaan quiz
- **Statistik Quiz**: Lihat rata-rata skor dan jumlah peserta

## 🏗️ Struktur Admin Interface

### Halaman Utama Admin (`/admin/courses`)
- Dashboard admin dengan quick actions
- Link cepat untuk membuat course baru
- Link ke management course
- Statistik real-time

### Daftar Course (`/admin/courses`)
- Tabel course dengan informasi lengkap
- Filter dan pencarian
- Quick actions (view, edit, delete)
- Pagination untuk performance

### Detail Course (`/admin/courses/{id}`)
- Informasi course lengkap
- Accordion sections dengan daftar video
- Quick actions untuk menambah content
- Statistik course real-time

### Course Creation (`/admin/courses/create`)
- Form lengkap untuk membuat course
- Upload gambar course
- Validasi data
- Preview gambar

### Course Editing (`/admin/courses/{id}/edit`)
- Form edit dengan data pre-filled
- Update gambar course
- Validasi perubahan

### Quiz Management (`/admin/courses/{id}/quizzes`)
- Daftar quiz dalam course
- Tambah/edit/hapus quiz
- Statistik peserta quiz
- Format JSON untuk pertanyaan

## 🔗 API Endpoints

### Course Management
```
GET     /admin/courses              - List all courses
GET     /admin/courses/create       - Show create form
POST    /admin/courses              - Store new course
GET     /admin/courses/{id}         - Show course details
GET     /admin/courses/{id}/edit    - Show edit form
PUT     /admin/courses/{id}         - Update course
DELETE  /admin/courses/{id}         - Delete course
POST    /admin/courses/{id}/toggle-status - Toggle active status
```

### Section Management
```
POST    /admin/courses/{courseId}/sections           - Add section
DELETE  /admin/courses/{courseId}/sections/{id}      - Remove section
```

### Video Management
```
POST    /admin/courses/{courseId}/videos             - Add video to section
DELETE  /admin/courses/{courseId}/videos/{id}        - Remove video from course
POST    /admin/courses/{courseId}/videos/reorder     - Reorder videos
```

### Quick Review Management
```
POST    /admin/courses/{courseId}/reviews            - Add quick review
```

### Quiz Management
```
GET     /admin/courses/{courseId}/quizzes            - List quizzes
POST    /admin/courses/{courseId}/quizzes            - Create quiz
PUT     /admin/courses/quiz/{quizId}                 - Update quiz
DELETE  /admin/courses/quiz/{quizId}                 - Delete quiz
```

## 🗃️ Database Schema

### Courses Table
- `course_id` (Primary Key)
- `nama_course` (Course Name)
- `deskripsi_course` (Description)
- `level` (pemula/menengah/lanjut)
- `kategori_kategori_id` (Foreign Key)
- `gambar_course` (Image Path)
- `total_video` (Calculated)
- `total_durasi_menit` (Calculated)
- `is_active` (Boolean)

### Course Sections Table
- `section_id` (Primary Key)
- `course_id` (Foreign Key)
- `nama_section` (Section Name)
- `deskripsi_section` (Description)
- `urutan_section` (Order)

### Course Videos Table
- `course_video_id` (Primary Key)
- `course_id` (Foreign Key)
- `section_id` (Foreign Key)
- `vidio_vidio_id` (Foreign Key to Videos)
- `urutan_video` (Order in Section)
- `durasi_menit` (Custom Duration)
- `catatan_admin` (Admin Notes)

### Quick Reviews Table
- `review_id` (Primary Key)
- `course_id` (Foreign Key)
- `section_id` (Foreign Key, Nullable)
- `vidio_vidio_id` (Foreign Key, Nullable)
- `judul_review` (Title)
- `konten_review` (HTML Content)
- `tipe_review` (setelah_video/setelah_section/tengah_course)
- `urutan_review` (Order)
- `is_active` (Boolean)

### Quizzes Table
- `quiz_id` (Primary Key)
- `course_id` (Foreign Key)
- `judul_quiz` (Quiz Title)
- `deskripsi_quiz` (Description)
- `tipe_quiz` (setelah_video/setelah_section/akhir_course)
- `durasi_menit` (Duration)
- `konten_quiz` (JSON Content)
- `is_active` (Boolean)

## 💡 Usage Examples

### 1. Membuat Course Baru
```
1. Login ke admin panel
2. Klik "Buat Course Baru" atau navigasi ke /admin/courses/create
3. Isi form dengan:
   - Nama course
   - Deskripsi lengkap
   - Pilih kategori
   - Pilih level (pemula/menengah/lanjut)
   - Upload gambar (opsional)
4. Klik "Simpan Course"
```

### 2. Menambah Konten ke Course
```
1. Buka detail course di /admin/courses/{id}
2. Tambah Section:
   - Klik "Tambah Section"
   - Isi nama dan deskripsi section
3. Tambah Video ke Section:
   - Klik "Tambah Video" pada section yang diinginkan
   - Pilih video dari dropdown
   - Set durasi dan catatan admin
4. Tambah Quick Review:
   - Klik "Tambah Quick Review"
   - Pilih tipe review
   - Isi konten dengan HTML
5. Tambah Quiz:
   - Klik "Kelola Quiz"
   - Tambah quiz baru dengan format JSON
```

### 3. Format JSON Quiz
```json
{
  "questions": [
    {
      "question": "Apa itu Laravel?",
      "options": ["Framework PHP", "Database", "Programming Language"],
      "correct": 0
    },
    {
      "question": "Siapa yang membuat Laravel?",
      "options": ["Taylor Otwell", "Mark Zuckerberg", "Linus Torvalds"],
      "correct": 0
    }
  ]
}
```

## 🔐 Authentication & Authorization

### Admin Access
- Sistem menggunakan middleware `auth` untuk semua route admin
- Admin dapat mengakses semua fitur course management
- Session-based authentication dengan Laravel

### Login Credentials (Testing)
```
Email: admin2@skilllearn.com
Password: admin123
```

## 🎨 UI/UX Features

### Responsive Design
- Bootstrap 5 untuk tampilan yang responsive
- Mobile-friendly interface
- Consistent design language

### Interactive Elements
- Modal untuk form input
- Accordion untuk course content
- Loading states dan feedback
- Confirmation dialogs untuk aksi destructive

### User Experience
- Breadcrumb navigation
- Success/error messages
- Intuitive icons dan labeling
- Drag-and-drop untuk reordering (future enhancement)

## 📊 Statistics & Analytics

### Course Statistics
- Total video dalam course
- Total durasi course (otomatis dihitung)
- Jumlah peserta terdaftar
- Progress rata-rata user

### Quiz Analytics
- Jumlah peserta quiz
- Rata-rata skor quiz
- Tingkat kelulusan

### Real-time Updates
- Statistik diupdate otomatis saat content berubah
- Live data di dashboard admin

## 🚀 Future Enhancements

### Planned Features
1. **Drag & Drop Reordering**: Visual reordering untuk video dan section
2. **Bulk Operations**: Edit multiple items sekaligus
3. **Content Templates**: Template untuk course structure
4. **Advanced Analytics**: Detailed learning analytics
5. **Role-based Access**: Different levels of admin access
6. **Content Versioning**: Track changes to course content
7. **Export/Import**: Backup dan restore course content

### Performance Optimizations
1. **Lazy Loading**: Load content on-demand
2. **Caching**: Cache course data untuk performance
3. **Image Optimization**: Automatic image compression
4. **CDN Integration**: Faster content delivery

## 📋 Testing

### Test Coverage
- Unit tests untuk models dan controllers
- Feature tests untuk admin workflows
- Browser tests untuk UI interactions

### Test Data
- Test script tersedia di `test_complete_course_management.php`
- Sample course dengan semua tipe content
- Comprehensive data untuk testing semua fitur

## 🔧 Troubleshooting

### Common Issues
1. **Images not showing**: Check storage symlink (`php artisan storage:link`)
2. **Database errors**: Run migrations (`php artisan migrate`)
3. **Permission errors**: Check file permissions for storage folder
4. **Session issues**: Clear cache (`php artisan cache:clear`)

### Debug Mode
- Enable debug mode dalam `.env` untuk development
- Check Laravel logs di `storage/logs/`
- Use browser developer tools untuk JavaScript errors

---

## 📞 Support

Untuk pertanyaan atau masalah dengan course management system, silakan check:
1. Laravel documentation
2. SkillLearn project documentation
3. Kode comments dalam controller dan model files

**System Status**: ✅ Fully Operational
**Last Updated**: June 16, 2025
**Version**: 2.0 (Course-Centric System)
