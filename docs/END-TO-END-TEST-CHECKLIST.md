# 🧪 End-to-End Test Checklist - SkillLearn Quiz System

## 📋 Testing Checklist

### ✅ Customer-Facing Features

#### 🔖 Bookmark System
- [ ] Bookmark course dari halaman course detail
- [ ] Unbookmark course dari halaman course detail  
- [ ] Bookmark video dari halaman video player
- [ ] Unbookmark video dari halaman video player
- [ ] Notifikasi SweetAlert2 muncul dengan benar
- [ ] UI update tanpa reload halaman
- [ ] Status bookmark tersimpan di database

#### 💬 Feedback System  
- [ ] Submit feedback dari halaman course detail
- [ ] Feedback form validation bekerja
- [ ] Notifikasi success muncul
- [ ] Feedback tersimpan di database
- [ ] UI reset setelah submit

#### 📝 Quiz System (Customer)
- [ ] Quiz modal muncul setelah video selesai (80% watched)
- [ ] Quiz modal muncul di akhir section
- [ ] Quiz modal muncul di akhir course
- [ ] Pilihan jawaban bisa dipilih dalam modal
- [ ] Submit quiz via AJAX tanpa reload
- [ ] Hasil quiz ditampilkan dalam modal
- [ ] Skor quiz tersimpan di database
- [ ] Progress course update setelah quiz

#### 🎥 YouTube Video Tracking
- [ ] Video YouTube embed dengan benar
- [ ] Timer tracking berjalan
- [ ] Progress tersimpan setiap 30 detik
- [ ] Auto-complete saat 80% ditonton
- [ ] Database menyimpan posisi terakhir
- [ ] Resume dari posisi terakhir saat dibuka kembali

### ✅ Admin Features

#### 📊 Course Management
- [ ] Tombol "Nonaktifkan Course" styling jelas
- [ ] Tombol "Tambah Section" styling jelas
- [ ] Dialog tambah video mendukung YouTube URL
- [ ] Preview YouTube video dalam dialog
- [ ] Validasi URL YouTube bekerja
- [ ] Input manual video tetap berfungsi
- [ ] Video YouTube tersimpan ke database

#### 🎯 Quiz Builder
- [ ] Interface quiz builder muncul tanpa error
- [ ] Tambah pertanyaan baru via form
- [ ] Edit pertanyaan existing
- [ ] Hapus pertanyaan dengan konfirmasi
- [ ] Drag & drop urutan pertanyaan
- [ ] Minimal 2 pilihan jawaban enforced
- [ ] Maksimal 5 pilihan jawaban enforced
- [ ] Jawaban benar harus dipilih
- [ ] Bobot nilai tersimpan dengan benar
- [ ] Preview quiz berfungsi

#### 🔄 Quiz Migration
- [ ] Cek status migration menampilkan data benar
- [ ] Migrate quiz lama dari JSON ke format baru
- [ ] Quiz lama tetap berfungsi selama migration
- [ ] Setelah migration, quiz tampil di UI baru
- [ ] Tidak ada data yang hilang

#### 📈 Monitoring System
- [ ] Dashboard monitoring menampilkan statistik
- [ ] Detail course monitoring bekerja
- [ ] Statistik siswa akurat
- [ ] Statistik video akurat  
- [ ] Statistik quiz akurat
- [ ] Export data (jika ada) berfungsi

### ✅ Technical Tests

#### 🌐 AJAX & API
- [ ] Semua endpoint AJAX return JSON yang benar
- [ ] Error handling muncul jika request gagal
- [ ] Loading indicator muncul saat proses
- [ ] CSRF token validation bekerja
- [ ] Rate limiting tidak mengganggu UX

#### 📱 Responsive Design
- [ ] Quiz builder responsive di mobile
- [ ] Quiz modal responsive di mobile
- [ ] Dashboard admin responsive
- [ ] Video player responsive
- [ ] Feedback form responsive

#### 🔒 Security & Validation
- [ ] Form validation bekerja client-side
- [ ] Server-side validation mencegah data invalid
- [ ] Authentication middleware bekerja
- [ ] Authorization untuk admin routes
- [ ] XSS protection aktif

## 📝 Test Scenarios

### Scenario 1: Complete Learning Flow (Customer)
1. Login sebagai student
2. Browse dan pilih course
3. Bookmark course
4. Mulai menonton video pertama
5. Tonton sampai 80% (trigger auto-complete + quiz)
6. Kerjakan quiz via modal
7. Lanjut ke video berikutnya
8. Submit feedback untuk course
9. Complete semua video dalam section
10. Kerjakan quiz section
11. Complete seluruh course
12. Kerjakan quiz akhir course

### Scenario 2: Admin Quiz Management
1. Login sebagai admin
2. Pilih course untuk dikelola
3. Buat quiz baru (akhir course)
4. Tambah 3-5 pertanyaan dengan berbagai bobot
5. Preview quiz
6. Edit salah satu pertanyaan
7. Ubah urutan pertanyaan (drag & drop)
8. Cek migration status
9. Migrate quiz lama jika ada
10. Monitor progress siswa

### Scenario 3: Mixed Content Management
1. Admin tambah video YouTube baru
2. Admin buat quiz untuk video tersebut
3. Student tonton video baru
4. Student kerjakan quiz
5. Admin monitor hasil di dashboard
6. Admin edit quiz berdasarkan hasil monitoring

## 🐛 Common Issues & Solutions

### Issue: Modal tidak muncul
- Check console untuk JavaScript errors
- Pastikan SweetAlert2 loaded
- Verify AJAX endpoints

### Issue: Drag & drop tidak bekerja
- Check Sortable.js loaded
- Verify event handlers
- Test di browser berbeda

### Issue: YouTube tracking tidak akurat
- Check YouTube API loaded
- Verify player events
- Test dengan video duration berbeda

### Issue: Migration gagal
- Check database connection
- Verify JSON format quiz lama
- Check error logs

## ✅ Test Results

### Customer Features: ☐ PASS / ☐ FAIL
**Notes**: _____________________________________________

### Admin Features: ☐ PASS / ☐ FAIL  
**Notes**: _____________________________________________

### Technical Tests: ☐ PASS / ☐ FAIL
**Notes**: _____________________________________________

### Overall System: ☐ READY FOR PRODUCTION / ☐ NEEDS FIXES

---
**Tested by**: ________________  
**Date**: ________________  
**Environment**: ________________
