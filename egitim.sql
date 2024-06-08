-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: db
-- Üretim Zamanı: 08 Haz 2024, 23:21:05
-- Sunucu sürümü: 10.5.25-MariaDB-ubu2004
-- PHP Sürümü: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `egitim`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ci_sessions`
--

CREATE TABLE `ci_sessions` (
							   `id` varchar(40) NOT NULL,
							   `ip_address` varchar(45) NOT NULL,
							   `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
							   `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `courses`
--

CREATE TABLE `courses` (
						   `id` bigint(20) UNSIGNED NOT NULL,
						   `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `courses`
--

INSERT INTO `courses` (`id`, `name`) VALUES
										 (1, 'Türkçe'),
										 (2, 'Coğrafya');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `grades`
--

CREATE TABLE `grades` (
						  `id` bigint(20) UNSIGNED NOT NULL,
						  `student_id` int(11) NOT NULL,
						  `course_id` int(11) NOT NULL,
						  `midterm` double(5,2) DEFAULT NULL,
						  `final` double(5,2) DEFAULT NULL,
						  `average` double(5,2) DEFAULT NULL,
						  `letter` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `course_id`, `midterm`, `final`, `average`, `letter`) VALUES
																									(1, 9, 1, 40.00, NULL, NULL, NULL),
																									(12, 9, 2, 50.00, 80.00, 68.00, 'BB');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `student_courses`
--

CREATE TABLE `student_courses` (
								   `id` bigint(20) UNSIGNED NOT NULL,
								   `student_id` bigint(20) UNSIGNED NOT NULL,
								   `course_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `student_courses`
--

INSERT INTO `student_courses` (`id`, `student_id`, `course_id`) VALUES
																	(1, 9, 1),
																	(2, 7, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `teacher_courses`
--

CREATE TABLE `teacher_courses` (
								   `id` bigint(20) UNSIGNED NOT NULL,
								   `teacher_id` int(11) DEFAULT NULL,
								   `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `teacher_courses`
--

INSERT INTO `teacher_courses` (`id`, `teacher_id`, `course_id`) VALUES
																	(1, 1, 2),
																	(2, 4, 2),
																	(3, 4, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
						 `id` bigint(20) UNSIGNED NOT NULL,
						 `name` varchar(30) NOT NULL,
						 `surname` varchar(60) NOT NULL,
						 `username` varchar(255) NOT NULL DEFAULT '',
						 `email` varchar(255) NOT NULL DEFAULT '',
						 `password` varchar(255) NOT NULL DEFAULT '',
						 `created_at` datetime NOT NULL,
						 `updated_at` datetime DEFAULT NULL,
						 `activation_key` varchar(255) NOT NULL,
						 `is_confirmed` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
						 `is_deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
						 `role` enum('teacher','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `username`, `email`, `password`, `created_at`, `updated_at`, `activation_key`, `is_confirmed`, `is_deleted`, `role`) VALUES
																																									   (2, '', '', 'admin', 'admin@gmail.com', '$2y$10$mRv12E2X5T/Kux6WD0isf.YSCqHeEFncKV74TkPY2Qycsp5eSRf6O', '2024-06-07 15:16:04', NULL, '', 1, 0, 'teacher'),
																																									   (4, 'new', 'user', 'newUser1', 'user1@example.com', '$2y$10$ljik1CyJB7JGnH8ORarrx.MDBcMVplN/1gcpRrTZbQOUc/jB5Uo.C', '2024-06-07 15:25:55', NULL, '', 1, 0, 'teacher'),
																																									   (7, 'Sezgin', 'Sevinç', 'sezgin', 'sezginex@gmail.com', '$2y$10$u.M.xuw6Hz9cgukl8q3BVuWbS9YDC5kVGOmxBp6WZSTpyy3YbuCe2', '2024-06-07 19:38:39', NULL, 'e4e5ee2777eb7b49cb0d73db5a766da0', 1, 0, 'student'),
																																									   (9, 'Murat', 'Karay', 'ahmet_kar', 'ahmet@mail.com', '$2y$10$Ieoc0gQ.pGcsRgo23tJyW.GsnDWaqc/IyYUPawfpv1m2jRf467psq', '2024-06-08 14:23:57', NULL, 'e5499eb2ceec93d616bfd83a98f14be8', 0, 0, 'student'),
																																									   (12, 'Hasan', 'Toplu', 'hassan', 'hasan@mail.com', '$2y$10$9ST3zthhvWHpKKZQ5vXR0esrt8szSphJUx6AeWzymKGBNBsRQSToK', '2024-06-08 23:02:21', NULL, 'a49f756e8e0157f0b4617c717feb2cef', 0, 0, 'teacher');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `ci_sessions`
--
ALTER TABLE `ci_sessions`
	ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Tablo için indeksler `courses`
--
ALTER TABLE `courses`
	ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `grades`
--
ALTER TABLE `grades`
	ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`course_id`);

--
-- Tablo için indeksler `student_courses`
--
ALTER TABLE `student_courses`
	ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `teacher_courses`
--
ALTER TABLE `teacher_courses`
	ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
	ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `courses`
--
ALTER TABLE `courses`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `grades`
--
ALTER TABLE `grades`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `teacher_courses`
--
ALTER TABLE `teacher_courses`
	MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
	MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
