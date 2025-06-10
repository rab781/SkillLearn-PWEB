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
*  **Pembelajaran Daring Berbasis Video:** Pratama dan Hamidah (2020) menyatakan bahwa pembelajaran daring merupakan metode pembelajaran yang memanfaatkan teknologi internet untuk menyampaikan materi ajar tanpa tatap muka langsung.
 Salah satu media yang banyak digunakan dalam pembelajaran daring adalah video, karena mampu menyajikan materi secara visual dan audio, sehingga dapat meningkatkan daya serap peserta didik serta fleksibilitas dalam waktu dan tempat belajar[cite: 23]. Oktaviani et al.  (2021) menemukan bahwa penggunaan video pembelajaran daring berbasis aplikasi KineMaster mampu meningkatkan pemahaman siswa terhadap materi pelajaran matematika secara signifikan.
 Hal ini sejalan dengan temuan Sitorus dan Siagian (2020) yang menyatakan bahwa video pembelajaran online dapat meningkatkan keterampilan menyanyi solo siswa melalui visualisasi praktik yang ditampilkan secara sistematis dan menarik.
*  **Pengembangan Soft Skill Melalui Pembelajaran Daring:** Simamarta (2021) mengungkapkan bahwa selain peningkatan aspek kognitif, pembelajaran daring juga berperan dalam pengembangan *soft skill* peserta didik.
 Menurutnya, proses pembelajaran secara online dapat mendorong siswa untuk meningkatkan keterampilan komunikasi, kemampuan berpikir kritis, serta adaptasi terhadap perkembangan teknologi digital.  Pembelajaran berbasis video yang dikurasi secara terarah menjadi salah satu pendekatan yang dinilai efektif dalam pengembangan *soft skill* tersebut.
*  **Efektivitas Video Youtube sebagai Media Pembelajaran:** Wulandari (2021) menunjukkan bahwa video pembelajaran melalui platform Youtube mampu meningkatkan hasil belajar mahasiswa pendidikan teknologi informasi secara signifikan.
 Keunggulan Youtube terletak pada kemudahan akses, beragam konten, serta fleksibilitas waktu belajar.
 Hal ini menjadi salah satu pertimbangan dalam pengembangan platform Skillearn, yang memanfaatkan video terkurasi dari berbagai platform gratis untuk menunjang proses belajar berbasis *skill*.
*  **Kerangka Konsep Pengembangan Skillearn:** Berdasarkan kajian pustaka, dapat disimpulkan bahwa video pembelajaran daring efektif dalam meningkatkan keterampilan, baik *hard skill* maupun *soft skill*.
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
