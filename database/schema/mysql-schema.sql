/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `bookmark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookmark` (
  `bookmark_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `users_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`bookmark_id`),
  UNIQUE KEY `bookmark_users_id_course_id_unique` (`users_id`,`course_id`),
  KEY `bookmark_course_id_foreign` (`course_id`),
  CONSTRAINT `bookmark_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `bookmark_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `course_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_sections` (
  `section_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_section` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_section` text COLLATE utf8mb4_unicode_ci,
  `urutan_section` int NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`section_id`),
  KEY `course_sections_course_id_foreign` (`course_id`),
  CONSTRAINT `course_sections_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `course_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_videos` (
  `course_video_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `section_id` bigint unsigned NOT NULL,
  `vidio_vidio_id` bigint unsigned NOT NULL,
  `urutan_video` int NOT NULL,
  `durasi_menit` int NOT NULL DEFAULT '0',
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`course_video_id`),
  UNIQUE KEY `course_videos_section_id_urutan_video_unique` (`section_id`,`urutan_video`),
  KEY `course_videos_course_id_foreign` (`course_id`),
  KEY `course_videos_vidio_vidio_id_foreign` (`vidio_vidio_id`),
  CONSTRAINT `course_videos_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `course_videos_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `course_sections` (`section_id`) ON DELETE CASCADE,
  CONSTRAINT `course_videos_vidio_vidio_id_foreign` FOREIGN KEY (`vidio_vidio_id`) REFERENCES `vidio` (`vidio_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `course_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_course` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_course` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar_course` text COLLATE utf8mb4_unicode_ci,
  `level` enum('pemula','menengah','lanjut') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pemula',
  `total_durasi_menit` int NOT NULL DEFAULT '0',
  `total_video` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `kategori_kategori_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`course_id`),
  KEY `courses_kategori_kategori_id_foreign` (`kategori_kategori_id`),
  CONSTRAINT `courses_kategori_kategori_id_foreign` FOREIGN KEY (`kategori_kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `feedback_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `balasan` text COLLATE utf8mb4_unicode_ci,
  `vidio_vidio_id` bigint unsigned NOT NULL,
  `users_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `feedback_vidio_vidio_id_foreign` (`vidio_vidio_id`),
  KEY `feedback_users_id_foreign` (`users_id`),
  CONSTRAINT `feedback_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`) ON DELETE CASCADE,
  CONSTRAINT `feedback_vidio_vidio_id_foreign` FOREIGN KEY (`vidio_vidio_id`) REFERENCES `vidio` (`vidio_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori` (
  `kategori_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kategori_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `quick_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quick_reviews` (
  `review_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul_review` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten_review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_review` enum('setelah_video','setelah_section','tengah_course') COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `section_id` bigint unsigned DEFAULT NULL,
  `vidio_vidio_id` bigint unsigned DEFAULT NULL,
  `urutan_review` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `quick_reviews_course_id_foreign` (`course_id`),
  KEY `quick_reviews_section_id_foreign` (`section_id`),
  KEY `quick_reviews_vidio_vidio_id_foreign` (`vidio_vidio_id`),
  CONSTRAINT `quick_reviews_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `quick_reviews_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `course_sections` (`section_id`) ON DELETE CASCADE,
  CONSTRAINT `quick_reviews_vidio_vidio_id_foreign` FOREIGN KEY (`vidio_vidio_id`) REFERENCES `vidio` (`vidio_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `riwayat_tonton`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_tonton` (
  `id_riwayat_tonton` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pengguna` bigint unsigned NOT NULL,
  `id_video` bigint unsigned NOT NULL,
  `waktu_ditonton` timestamp NOT NULL,
  `durasi_tonton` int NOT NULL DEFAULT '0',
  `persentase_progress` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_riwayat_tonton`),
  UNIQUE KEY `unique_pengguna_video_tanggal` (`id_pengguna`,`id_video`,`waktu_ditonton`),
  KEY `riwayat_tonton_id_video_foreign` (`id_video`),
  CONSTRAINT `riwayat_tonton_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`users_id`) ON DELETE CASCADE,
  CONSTRAINT `riwayat_tonton_id_video_foreign` FOREIGN KEY (`id_video`) REFERENCES `vidio` (`vidio_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_course_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_course_progress` (
  `progress_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `current_section_id` bigint unsigned DEFAULT NULL,
  `current_video_id` bigint unsigned DEFAULT NULL,
  `videos_completed` int NOT NULL DEFAULT '0',
  `total_videos` int NOT NULL,
  `progress_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `status` enum('not_started','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_started',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`progress_id`),
  UNIQUE KEY `user_course_progress_user_id_course_id_unique` (`user_id`,`course_id`),
  KEY `user_course_progress_course_id_foreign` (`course_id`),
  KEY `user_course_progress_current_section_id_foreign` (`current_section_id`),
  KEY `user_course_progress_current_video_id_foreign` (`current_video_id`),
  CONSTRAINT `user_course_progress_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `user_course_progress_current_section_id_foreign` FOREIGN KEY (`current_section_id`) REFERENCES `course_sections` (`section_id`) ON DELETE SET NULL,
  CONSTRAINT `user_course_progress_current_video_id_foreign` FOREIGN KEY (`current_video_id`) REFERENCES `vidio` (`vidio_id`) ON DELETE SET NULL,
  CONSTRAINT `user_course_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_video_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_video_progress` (
  `video_progress_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `vidio_vidio_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `watch_time_seconds` int NOT NULL DEFAULT '0',
  `total_duration_seconds` int DEFAULT NULL,
  `completion_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `first_watched_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`video_progress_id`),
  UNIQUE KEY `user_video_progress_user_id_vidio_vidio_id_course_id_unique` (`user_id`,`vidio_vidio_id`,`course_id`),
  KEY `user_video_progress_vidio_vidio_id_foreign` (`vidio_vidio_id`),
  KEY `user_video_progress_course_id_foreign` (`course_id`),
  CONSTRAINT `user_video_progress_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `user_video_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`) ON DELETE CASCADE,
  CONSTRAINT `user_video_progress_vidio_vidio_id_foreign` FOREIGN KEY (`vidio_vidio_id`) REFERENCES `vidio` (`vidio_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `users_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(84) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(84) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(84) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('AD','CU') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CU',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `vidio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vidio` (
  `vidio_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_tayang` int NOT NULL DEFAULT '0',
  `kategori_kategori_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`vidio_id`),
  KEY `vidio_kategori_kategori_id_foreign` (`kategori_kategori_id`),
  CONSTRAINT `vidio_kategori_kategori_id_foreign` FOREIGN KEY (`kategori_kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_06_10_072657_create_kategori_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_06_10_072710_create_vidio_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_06_10_072717_create_feedback_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_06_10_072738_create_bookmark_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_06_11_030414_create_watch_history_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_06_11_081431_skillearn',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_06_16_000001_create_courses_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_06_16_000002_create_course_sections_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_06_16_000003_create_course_videos_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_06_16_000004_create_quick_reviews_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_06_16_000006_create_user_video_progress_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_06_16_000005_create_user_course_progress_table',5);
