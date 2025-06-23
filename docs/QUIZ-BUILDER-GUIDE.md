# 📚 Panduan Quiz Builder - Admin SkillLearn

## 🎯 Overview
Quiz Builder adalah interface yang user-friendly untuk membuat, mengedit, dan mengelola quiz tanpa perlu format JSON kompleks. System ini telah didesain agar mudah digunakan oleh orang awam.

## 🚀 Fitur Utama

### ✨ Quiz Builder Interface
- **Visual Question Builder**: Interface drag & drop yang intuitif
- **Real-time Preview**: Melihat quiz seperti yang akan dilihat siswa
- **Auto Migration**: Konversi quiz lama format JSON ke format baru
- **Drag & Drop Reorder**: Mengubah urutan pertanyaan dengan mudah

### 🛠️ Membuat Pertanyaan Baru

1. **Akses Quiz Management**
   - Masuk ke Admin Dashboard
   - Pilih course yang ingin dikelola
   - Klik "Quiz Management" 
   - Pilih quiz yang ingin dikelola

2. **Tambah Pertanyaan**
   - Klik tombol "Tambah Pertanyaan Baru"
   - Isi form yang disediakan:
     - **Pertanyaan**: Tulis pertanyaan dengan jelas
     - **Pilihan Jawaban**: Minimal 2, maksimal 5 pilihan
     - **Jawaban Benar**: Pilih salah satu pilihan sebagai jawaban benar
     - **Bobot Nilai**: Pilih tingkat kesulitan (1-5)
   - Klik "Simpan Pertanyaan"

3. **Edit Pertanyaan**
   - Klik menu dropdown (⚙️) pada pertanyaan
   - Pilih "Edit"
   - Ubah data yang diperlukan
   - Klik "Simpan Pertanyaan"

4. **Hapus Pertanyaan**
   - Klik menu dropdown (⚙️) pada pertanyaan
   - Pilih "Hapus"
   - Konfirmasi penghapusan

5. **Mengubah Urutan**
   - Drag pertanyaan menggunakan handle (⋮⋮)
   - Drop ke posisi yang diinginkan
   - Urutan akan otomatis tersimpan

## 🔄 Migration Quiz Lama

### Cek Status Migration
1. Klik tombol "Cek Migration" di halaman quiz questions
2. System akan menampilkan status migration
3. Jika ada quiz lama, akan muncul tombol "Migrate Sekarang"

### Proses Migration
1. Klik "Migrate Sekarang" jika diperlukan
2. System akan otomatis mengkonversi format JSON lama
3. Quiz lama akan tetap berfungsi selama proses migration
4. Setelah migration selesai, semua quiz menggunakan format baru

## 📱 Tips Penggunaan

### ✅ Best Practices
- **Pertanyaan Jelas**: Tulis pertanyaan yang mudah dipahami
- **Pilihan Seimbang**: Buat pilihan jawaban yang masuk akal
- **Distribusi Bobot**: Gunakan berbagai tingkat kesulitan
- **Preview Berkala**: Gunakan fitur preview untuk mengecek quiz

### ⚠️ Hal yang Perlu Diperhatikan
- Minimal 2 pilihan jawaban per pertanyaan
- Maksimal 5 pilihan jawaban per pertanyaan
- Jawaban benar harus dipilih sebelum menyimpan
- Bobot nilai mempengaruhi skor akhir siswa

### 🎨 Interface Features
- **Color Coding**: Pilihan jawaban benar ditandai dengan warna hijau
- **Drag Handle**: Icon ⋮⋮ untuk mengubah urutan
- **Status Badge**: Menampilkan informasi bobot dan urutan
- **Responsive Design**: Berfungsi baik di desktop dan mobile

## 🔧 Troubleshooting

### Problem: Quiz lama tidak muncul
**Solution**: Gunakan fitur "Cek Migration" untuk mengkonversi quiz lama

### Problem: Pertanyaan tidak tersimpan
**Solution**: 
- Pastikan semua field wajib diisi
- Pastikan pilihan jawaban benar sudah dipilih
- Check koneksi internet

### Problem: Urutan pertanyaan tidak berubah
**Solution**: 
- Pastikan menggunakan drag handle (⋮⋮)
- Tunggu notifikasi "Urutan pertanyaan diperbarui"
- Refresh halaman jika perlu

### Problem: Preview quiz tidak muncul
**Solution**: 
- Pastikan quiz memiliki minimal 1 pertanyaan
- Check popup blocker browser
- Coba buka di tab baru

## 📞 Support
Jika mengalami kendala, hubungi tim IT atau lihat dokumentasi teknis di folder `/docs/`.

---
*Last updated: {{ date('Y-m-d') }}*
