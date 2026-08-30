/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: 103.173.78.7    Database: beasiswa
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB-ubu2404-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `program_studis`
--

DROP TABLE IF EXISTS `program_studis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_studis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `idprodi` varchar(50) NOT NULL,
  `singkatan` varchar(50) DEFAULT NULL,
  `urut` int(11) DEFAULT NULL,
  `fakultas_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sevima_prodi_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_studis_idprodi_unique` (`idprodi`),
  UNIQUE KEY `program_studis_unique` (`sevima_prodi_id`),
  KEY `program_studis_fakultas_id_foreign` (`fakultas_id`),
  CONSTRAINT `program_studis_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_studis`
--

LOCK TABLES `program_studis` WRITE;
/*!40000 ALTER TABLE `program_studis` DISABLE KEYS */;
INSERT INTO `program_studis` VALUES
(1,'Pendidikan Agama Islam','PAI','PAI',1,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',86208),
(2,'Pendidikan Bahasa Arab','PBA','PBA',2,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',88204),
(3,'Manajemen Pendidikan Islam','KI','MPI',3,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',86231),
(4,'Pendidikan Guru Madrasah Ibtidaiyah','PGMI','PGMI',4,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',86232),
(5,'Pendidikan Islam Anak Usia Dini','PGRA','PIAUD',5,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',86207),
(6,'Tadris Bahasa Inggris','BING','TBI',6,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',88203),
(7,'Tadris IPA','IPA','TIPA',7,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',84206),
(8,'Tadris Fisika','FSK','TFSK',8,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',84203),
(9,'Tadris Biologi','BLG','TBLG',9,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',84205),
(10,'Tadris Matematika','MTK','TMTK',10,1,'2025-08-19 05:14:19','2025-08-19 05:14:19',84202),
(11,'Hukum Keluarga Islam (Ahwal Syakhshiyyah)','AS','AS',1,2,'2025-08-19 05:14:19','2025-08-19 05:14:19',74230),
(12,'Hukum Ekonomi Syariah (Mua\'malah)','MU','HES',2,2,'2025-08-19 05:14:19','2025-08-19 05:14:19',74234),
(13,'Hukum Tatanegara (Siyasah Syar\'iyyah)','HTN','HTN',3,2,'2025-08-19 05:14:19','2025-08-19 05:14:19',74235),
(14,'Komunikasi dan Penyiaran Islam','KPI','KPI',1,3,'2025-08-19 05:14:19','2025-08-19 05:14:19',70233),
(15,'Bimbingan Penyuluhan Islam','BPI','BPI',2,3,'2025-08-19 05:14:19','2025-08-19 05:14:19',70232),
(16,'Manajemen Dakwah','MD','MD',3,3,'2025-08-19 05:14:19','2025-08-19 05:14:19',70230),
(17,'Ilmu Al-Qur\'an dan Tafsir','IQT','IQT',4,3,'2025-08-19 05:14:19','2025-08-19 05:14:19',76231),
(18,'Ekonomi Syariah','EI','ESY',1,4,'2025-08-19 05:14:19','2025-08-19 05:14:19',60202),
(19,'Perbankan Syariah','PBS','PBS',2,4,'2025-08-19 05:14:19','2025-08-19 05:14:19',61206),
(20,'Manajemen Bisnis Syariah','MBS','MBS',3,4,'2025-08-19 05:14:19','2025-08-19 05:14:19',61205),
(21,'Manajemen Pendidikan Islam S2','MPI','MPI',1,5,NULL,NULL,NULL),
(22,'Pendidikan Agama Islam S2','PAIS','PAI',2,5,NULL,NULL,NULL),
(23,'Ekonomi Syariah S2','ESY','ESY',3,5,NULL,NULL,NULL),
(24,'Pendidikan Bahasa Arab S2','PBAS','PBA',4,5,NULL,NULL,NULL),
(25,'Hukum Keluarga Islam (Ahwal Syakhshiyyah) S2','HI','HKI',5,5,NULL,NULL,NULL),
(26,'Pendidikan Agama Islam S3','PAIS3','PAI',6,5,NULL,NULL,NULL);
/*!40000 ALTER TABLE `program_studis` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-08-30 15:02:06
