# Skillearn: Solusi Pembelajaran Online Berbasis Kurasi Video Terarah untuk Peningkatan Berbagai Skill secara Gratis

**Kelompok A2**
* Zahra Hilmy Ghaida - 222410101073
* Veny Ramadhani Arifin - 232410101026
* Mohammad Raihan R - 232410101059
* Laura Febi Nurdiana - 232410101097

**Program Studi Sistem Informasi**
**Fakultas Ilmu Komputer**
**Universitas Jember**
**2025**

---

### 1. Latar Belakang Masalah

Dalam bidang pendidikan era digital saat ini, akses terhadap sumber belajar sangat melimpah, namun tidak semua orang dapat memanfaatkannya secara efektif. Banyak individu, atau pelajar bahkan masyarakat umum, merasa kebingungan dan kesulitan dalam mencari materi pembelajaran yang tepat, terstruktur, dan sesuai dengan kebutuhan keterampilan mereka.  Meskipun banyak akses yang menyediakan banyak video pembelajaran di internet, tantangan dalam aksesnya tersebut adalah kurasi konten, di mana banyaknya jumlah video yang diunggah setiap detik menyulitkan pengguna untuk menemukan informasi yang relevan dan terpercaya.  Selain itu, konten-konten tersebut sudah dipublikasi secara tidak terorganisir dan tidak memberikan alur panduan belajar yang jelas.  Masalah lainnya adalah banyak platform pembelajaran daring yang mengenakan biaya berlangganan, sehingga menghambat akses masyarakat berpenghasilan rendah atau pelajar dari daerah terpencil.  Akibatnya, potensi peningkatan keterampilan secara mandiri menjadi terhambat.

 Mengetahui permasalahan tersebut, diperlukan strategi kurasi konten yang efektif untuk memastikan bahwa informasi yang disampaikan terstruktur dan jelas.  Oleh karena itu, Skillearn hadir sebagai solusi berbasis web yang menyediakan kurasi video pembelajaran secara terarah dan gratis untuk diakses agar siapa pun dapat belajar secara sistematis tanpa harus bingung memilih sumber atau terbebani oleh biaya.  Skillearn kami rancang dengan menyajikan video-video pembelajaran yang relevan dengan bidang yang dipilih dan disajikan secara urut sehingga pengguna khususnya pemula tidak kesulitan untuk belajar mulai dari awal sekali.

---

### 2. Rumusan Masalah

*  Bagaimana membangun sebuah platform pembelajaran online yang dapat mengkurasi dan menyusun video edukasi secara terarah?
*  Bagaimana menyusun sistem navigasi materi yang membantu pengguna belajar dari dasar hingga tingkat lanjut dalam berbagai bidang keterampilan?
*  Bagaimana mendesain tampilan antarmuka yang *user-friendly* agar proses belajar menjadi lebih nyaman dan efisien?

---

### 3. Tujuan Proyek

**Tujuan Umum:**
*  Mengembangkan platform pembelajaran online berbasis web yang menyediakan kurasi video edukasi secara terstruktur dan gratis untuk mendukung peningkatan berbagai keterampilan masyarakat secara mandiri.
*  Mengembangkan materi yang ternavigasi untuk membantu pengguna belajar dari dasar hingga tingkat lanjut dalam berbagai bidang keterampilan.

**Tujuan Khusus:**
*  Membangun sistem berbasis Laravel yang mampu menampilkan daftar video pembelajaran yang dikurasi berdasarkan kategori dan tingkat kesulitan.
*  Menyediakan antarmuka yang sederhana dan mudah diakses oleh pengguna dari berbagai latar belakang.
*  Menyusun alur pembelajaran dalam bentuk modul atau jalur belajar yang jelas dan berurutan.
*  Menjamin seluruh konten yang ditampilkan bersumber dari materi terbuka dan legal.

---

### 4. Studi Pustaka / Tinjauan Teknologi (Untuk Konteks Tambahan AI)

* **Kurasi Konten dalam Platform Pembelajaran:** Wahyuni et al.  (2022) menjelaskan bahwa kurasi konten adalah proses memilih, menyusun, dan merekomendasikan materi yang relevan dan berkualitas kepada pengguna.  Dalam konteks pembelajaran daring, kurasi video terarah dapat meningkatkan efektivitas belajar karena materi yang disajikan sesuai dengan kebutuhan keterampilan yang ingin dicapai oleh peserta didik.  Dengan adanya kurasi, pengguna dapat lebih fokus pada materi yang relevan tanpa harus memilah konten secara mandiri.
*  **Pembelajaran Daring Berbasis Video:** Pratama dan Hamidah (2020) menyatakan bahwa pembelajaran daring merupakan metode pembelajaran yang memanfaatkan teknologi internet untuk menyampaikan materi ajar tanpa tatap muka langsung[cite: 22].
 Salah satu media yang banyak digunakan dalam pembelajaran daring adalah video, karena mampu menyajikan materi secara visual dan audio, sehingga dapat meningkatkan daya serap peserta didik serta fleksibilitas dalam waktu dan tempat belajar[cite: 23]. Oktaviani et al.  (2021) menemukan bahwa penggunaan video pembelajaran daring berbasis aplikasi KineMaster mampu meningkatkan pemahaman siswa terhadap materi pelajaran matematika secara signifikan.
 Hal ini sejalan dengan temuan Sitorus dan Siagian (2020) yang menyatakan bahwa video pembelajaran online dapat meningkatkan keterampilan menyanyi solo siswa melalui visualisasi praktik yang ditampilkan secara sistematis dan menarik.
*  **Pengembangan Soft Skill Melalui Pembelajaran Daring:** Simamarta (2021) mengungkapkan bahwa selain peningkatan aspek kognitif, pembelajaran daring juga berperan dalam pengembangan *soft skill* peserta didik.
 Menurutnya, proses pembelajaran secara online dapat mendorong siswa untuk meningkatkan keterampilan komunikasi, kemampuan berpikir kritis, serta adaptasi terhadap perkembangan teknologi digital.  Pembelajaran berbasis video yang dikurasi secara terarah menjadi salah satu pendekatan yang dinilai efektif dalam pengembangan *soft skill* tersebut.
*  **Efektivitas Video Youtube sebagai Media Pembelajaran:** Wulandari (2021) menunjukkan bahwa video pembelajaran melalui platform Youtube mampu meningkatkan hasil belajar mahasiswa pendidikan teknologi informasi secara signifikan.
 Keunggulan Youtube terletak pada kemudahan akses, beragam konten, serta fleksibilitas waktu belajar.
 Hal ini menjadi salah satu pertimbangan dalam pengembangan platform Skillearn, yang memanfaatkan video terkurasi dari berbagai platform gratis untuk menunjang proses belajar berbasis *skill*.
*  **Kerangka Konsep Pengembangan Skillearn:** Berdasarkan kajian pustaka, dapat disimpulkan bahwa video pembelajaran daring efektif dalam meningkatkan keterampilan, baik *hard skill* maupun *soft skill*[cite: 32, 33].
 Pratama dan Hamidah (2020) serta Simarmata (2021) menekankan pentingnya media video dalam proses pembelajaran daring[cite: 33]. Selain itu, Wahyuni et al.
 (2022) dan Wulandari (2021) menyatakan bahwa proses kurasi video yang dilakukan secara sistematis dan terarah memungkinkan pengguna memilih berbagai kategori *skill* sesuai minat dan kebutuhan secara fleksibel.

---

### 5. Spesifikasi Proyek

#### 5.1 Fungsi Utama Website

 Skillearn merupakan sebuah platform pembelajaran online berbasis kurasi video yang menyediakan berbagai jalur pembelajaran *skill* secara gratis.
 Fungsi utama dari website ini adalah sebagai media edukasi yang dirancang untuk menyajikan pembelajaran secara runtut bagi pengguna dalam memilih jalur pembelajaran sesuai minat, mengakses video-video pembelajaran yang telah dikurasi, serta memantau *progress* belajar yang dicapai.

*  **Fitur Opsional (Jika Waktu Memungkinkan):** Platform ini juga dilengkapi dengan fitur *chatbot* yang dapat membantu pengguna dalam mencari video rekomendasi atau menjawab pertanyaan seputar materi yang tersedia.

#### 5.2 Target Pengguna

Target pengguna dari website Skillearn terdiri atas dua kategori, yaitu:

*  **Admin:** Pihak yang bertugas mengelola konten video pembelajaran, mengatur jalur kurasi, memantau statistik penggunaan, mengelola *database chatbot*, serta menangani *feedback* dari pengguna.
*  **Customer/User:** Masyarakat umum atau individu yang ingin mempelajari berbagai keterampilan secara mandiri dan gratis melalui video-video pembelajaran yang tersedia di platform.

#### 5.3 Fitur yang Akan Dikembangkan

**A. Untuk Pengguna (Customer/User):**
*  **Registrasi:** Fitur bagi Customer untuk membuat akun baru dan masuk ke sistem.
*  **Login:** Melakukan autentikasi untuk masuk ke sistem.
* **Fitur Pembelajaran:**
    *  Customer dapat melihat video pembelajaran sesuai kurasi yang dipilih.
    *  Customer dapat memilih pembelajaran berdasarkan kategori *skill*.
    *  Customer dapat memberikan *feedback* terhadap video.
    *  Customer dapat menyimpan video ke daftar favorit (*bookmark*).
*  **Fitur *Progress* Belajar:** Customer dapat melihat *progress* belajar yang telah dicapai di masing-masing kurasi.
* **Fitur Profil:**
    *  Melihat data profil.
    *  Mengubah data profil.
*  **Logout:** Keluar dari sistem dan mengakhiri sesi pengguna.

**B. Untuk Admin:**
*  **Login:** Fitur untuk melakukan autentikasi dan akses ke *dashboard* admin.
* **Fitur Pembelajaran:**
    *  Admin dapat melihat video.
    *  Admin dapat menambah dan mengelompokkan video pembelajaran.
    *  Admin dapat mengupdate detail video pembelajaran.
    *  Admin dapat menghapus video dari platform.
    *  Admin dapat melihat daftar *feedback* yang diberikan oleh *customer*.
    *  Admin dapat membalas *feedback* yang diberikan oleh *customer*.
    *  Admin dapat menghapus *feedback*.
*  **Fitur Report:** Admin dapat melihat informasi total pengguna platform untuk kebutuhan bisnis.
* **Fitur Profil:**
    *  Melihat data profil.
    *  Mengubah data profil.
*  **Fitur Logout:** Keluar dari sistem dan mengakhiri sesi admin.

---

### 6. Desain Sistem

Berikut adalah diagram sistem yang menjadi acuan utama dalam pengembangan Skillearn. Pastikan Anda telah menempatkan semua file gambar diagram ini di folder `docs/diagrams/` di *root* proyek Anda.

#### 6.1 Use Case Diagram
 ![Use Case Diagram Skillearn](docs/diagrams/use_case_skillearn.png)

#### 6.2 ERD (Entity Relationship Diagram)
 ![ERD Skillearn](docs/diagrams/erd_skillearn.png)

#### 6.3 PDM (Physical Data Model)
 ![PDM Skillearn](docs/diagrams/pdm_skillearn.png)

#### 6.4 Activity Diagrams

##### Registrasi (Pelanggan)
 ![Activity Diagram Registrasi Pelanggan](docs/diagrams/ad_registrasi_pelanggan.png)

##### Login (Admin)
 ![Activity Diagram Login Admin](docs/diagrams/ad_login_admin.png)

##### Login (Pelanggan)
 ![Activity Diagram Login Pelanggan](docs/diagrams/ad_login_pelanggan.png)

##### Melihat Video Pembelajaran (Admin)
 ![Activity Diagram Melihat Video Pembelajaran Admin](docs/diagrams/ad_melihat_video_pembelajaran_admin.png)

##### Melihat Video Pembelajaran (Pelanggan)
 ![Activity Diagram Melihat Video Pembelajaran Pelanggan](docs/diagrams/ad_melihat_video_pembelajaran_pelanggan.png)

##### Menambah dan Mengelompokkan Video Pembelajaran (Admin)
 ![Activity Diagram Menambah dan Mengelompokkan Video Pembelajaran Admin](docs/diagrams/ad_menambah_mengelompokkan_video_admin.png)

##### Mengupdate Detail Video Pembelajaran (Admin)
 ![Activity Diagram Mengupdate Detail Video Pembelajaran Admin](docs/diagrams/ad_mengupdate_detail_video_admin.png)

##### Menghapus Video Pembelajaran (Admin)
 ![Activity Diagram Menghapus Video Pembelajaran Admin](docs/diagrams/ad_menghapus_video_admin.png)

##### Menyimpan Video Ke Daftar Favorit (Pelanggan)
 ![Activity Diagram Menyimpan Video Ke Daftar Favorit Pelanggan](docs/diagrams/ad_menyimpan_video_favorit_pelanggan.png)

##### Melihat Progress Belajar (Pelanggan)
 ![Activity Diagram Melihat Progress Belajar Pelanggan](docs/diagrams/ad_melihat_progress_belajar_pelanggan.png)

##### Melihat Total Pengguna Platform (Admin)
 ![Activity Diagram Melihat Total Pengguna Platform Admin](docs/diagrams/ad_melihat_total_pengguna_admin.png)

##### Melihat Feedback (Admin)
 ![Activity Diagram Melihat Feedback Admin](docs/diagrams/ad_melihat_feedback_admin.png)

##### Melihat Feedback (Pelanggan)
 ![Activity Diagram Melihat Feedback Pelanggan](docs/diagrams/ad_melihat_feedback_pelanggan.png)

##### Membuat Feedback (Admin)
 ![Activity Diagram Membuat Feedback Admin](docs/diagrams/ad_membuat_feedback_admin.png)

##### Membuat Feedback (Pelanggan)
 ![Activity Diagram Membuat Feedback Pelanggan](docs/diagrams/ad_membuat_feedback_pelanggan.png)

##### Menghapus Feedback (Admin)
 ![Activity Diagram Menghapus Feedback Admin](docs/diagrams/ad_menghapus_feedback_admin.png)

##### Menghapus Feedback (Pelanggan)
 ![Activity Diagram Menghapus Feedback Pelanggan](docs/diagrams/ad_menghapus_feedback_pelanggan.png)

##### Logout (Admin)
 ![Activity Diagram Logout Admin](docs/diagrams/ad_logout_admin.png)

##### Logout (Pelanggan)
 ![Activity Diagram Logout Pelanggan](docs/diagrams/ad_logout_pelanggan.png)

---

### 7. Teknis & Teknologi

* **Framework Frontend:** Direkomendasikan **React.js** atau **Vue.js** untuk membangun antarmuka pengguna yang dinamis dan modular.
* **Framework Backend:** **Laravel** (PHP) untuk membangun sistem *backend* yang mampu menampilkan daftar video pembelajaran yang dikurasi berdasarkan kategori dan tingkat kesulitan.
* **Database:** Berdasarkan ERD dan PDM yang dilampirkan, struktur database akan mencakup tabel `users`, `akun`, `feedback`, `kategori`, `vidio`, dan `bookmark`. Pilih **MySQL** .
    * **Tabel `users`**: `nama_lengkap` (VARCHAR 100), `no_telepon` (VARCHAR 12), `users_ID` (PRIMARY KEY).
    * **Tabel `akun`**: `username` (VARCHAR 84), `password` (VARCHAR 84), `email` (VARCHAR 84), `role` (VARCHAR 2), `no_telepon` (VARCHAR 12), `akun_ID` (PRIMARY KEY). Relasi dengan `users`.
    * **Tabel `feedback`**: `tanggal` (DATE), `pesan` (CLOB), `balasan` (CLOB), `feedback_ID` (PRIMARY KEY), `vidio_vidio_ID` (FOREIGN KEY).
    * **Tabel `kategori`**: `kategori` (VARCHAR 20), `kategori_ID` (PRIMARY KEY).
    * **Tabel `vidio`**: `nama` (VARCHAR 120), `deskripsi` (CLOB), `url` (CLOB), `gambar` (CLOB), `jumlah_tayang` (INTEGER), `vidio_ID` (PRIMARY KEY), `kategori_kategori_ID` (FOREIGN KEY).
    * **Tabel `bookmark`**: `akun_username` (VARCHAR 84), `akun_email` (VARCHAR 84), `bookmark_ID` (PRIMARY KEY), `vidio_vidio_ID` (FOREIGN KEY).
* **Styling:** Pilih salah satu yang paling sesuai untuk website pembelajaran:
    * **Tailwind CSS** (disarankan untuk fleksibilitas dan pengembangan cepat)
* **Library Tambahan (Frontend):**
    * **SweetAlert2** (untuk notifikasi pop-up yang interaktif, misalnya untuk konfirmasi penghapusan *feedback* atau pesan "Video berhasil ditambahkan!")
    **Vue Router** jika menggunakan *framework* SPA (Single Page Application) di *frontend*.

---

### 8. Referensi Desain Tampilan Frontend


#### 8.1 Referensi Umum Tampilan (Desain Bersih dan Modern)
* **Nama Website Referensi:** [**Coursera, Udemy, Dan lain lain ]
* **URL:** [https://www.udemy.com/,https://www.coursera.org]
* **Fokus Referensi:** Perhatikan tata letak umum, skema warna, tipografi, dan *overall user experience* dari halaman utama dan halaman kategori. Inspirasi untuk *navbar*, *footer*, dan *grid layout* konten.

#### 8.2 Referensi Tampilan Halaman Detail Video
* **Nama Website Referensi:** [ YouTube, Vimeo, atau Platform Video Lain]
* **URL:** [https://www.youtube.com/watch?v=RUTV_5m4VeI&list=PLFIM0718LjIWXagluzROrA-iBY9eeUt4w]
* **Fokus Referensi:** Perhatikan bagaimana video player disematkan, posisi deskripsi video, bagian komentar/*feedback*, dan area rekomendasi video terkait. Ambil inspirasi untuk tata letak tombol *bookmark* dan *progress*.

#### 8.3 Referensi Tampilan Dashboard Admin
* **Nama Website Referensi:** [AdminLTE Demo, Laravel Nova Dashboard]
* **URL:** [ https://adminlte.io/themes/v3/index.html**]
* **Fokus Referensi:** Inspirasi untuk layout *sidebar*, *card* informasi, tabel data (video, *feedback*, *user*), dan *form* tambah/edit video. Perhatikan konsistensi *styling* dan kemudahan navigasi antar menu admin.

---

### 9. Analisis Kesesuaian Output

Setelah website selesai dibuat, AI harus menyediakan laporan analisis yang sangat detail dan terstruktur, mencakup:

* **Poin *Requirement* dari Prompt Ini:** Setiap poin dari bagian "5.3 Fitur yang Akan Dikembangkan", "Tampilan & Pengalaman Pengguna" (termasuk responsivitas dan alur pembelajaran), dan "Teknis & Teknologi" harus disebutkan kembali secara eksplisit.
* **Status Implementasi:** Untuk setiap poin, sebutkan secara jelas apakah sudah "Telah Diimplementasikan Sepenuhnya", "Sebagian Diimplementasikan", atau "Belum Diimplementasikan".
* **Penjelasan/Alasan:**
    * Jika "Telah Diimplementasikan Sepenuhnya", berikan konfirmasi singkat.
    * Jika "Sebagian Diimplementasikan" atau "Belum Diimplementasikan", berikan penjelasan rinci mengapa (misalnya, batasan AI, kompleksitas, atau asumsi yang diambil) dan bagian mana yang belum terpenuhi.
* **Kesesuaian Diagram:** Analisis seberapa akurat implementasi website merepresentasikan alur dan struktur yang digambarkan dalam Use Case, ERD, PDM, dan Activity Diagram yang disediakan. Beri catatan jika ada deviasi atau penyesuaian.
* **Kesesuaian Teknologi:** Konfirmasi penggunaan *framework* (Laravel, React/Vue), *database*, *styling* (Tailwind/Bootstrap/CSS Murni), dan *library* tambahan (SweetAlert2, Axios) sesuai dengan spesifikasi.
* **Kesesuaian Referensi Desain:** Untuk setiap halaman atau komponen utama, jelaskan seberapa akurat implementasi *frontend* (struktur HTML, *styling* CSS, tata letak elemen) mengambil inspirasi dari website referensi yang disebutkan di bagian "8. Referensi Desain Tampilan Frontend". Sebutkan bagian mana yang diadaptasi dan bagian mana yang dikustomisasi.
* **Rekomendasi:** Berikan rekomendasi konkret untuk peningkatan, penambahan fitur di masa depan (termasuk fitur *chatbot* jika belum diimplementasikan), dan area optimalisasi kinerja atau *user experience*.
* **Asumsi:** Sebutkan asumsi-asumsi yang diambil selama proses pembuatan, terutama jika ada bagian *requirement* yang kurang jelas atau membutuhkan interpretasi.
