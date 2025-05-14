# ************************************************************
# Sequel Ace SQL dump
# Version 20092
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 8.3.0)
# Database: dance&beyond
# Generation Time: 2025-05-01 12:25:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table action_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `action_events`;

CREATE TABLE `action_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actionable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actionable_id` bigint unsigned NOT NULL,
  `target_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `fields` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running',
  `exception` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `original` mediumtext COLLATE utf8mb4_unicode_ci,
  `changes` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `action_events_actionable_type_actionable_id_index` (`actionable_type`,`actionable_id`),
  KEY `action_events_target_type_target_id_index` (`target_type`,`target_id`),
  KEY `action_events_batch_id_model_type_model_id_index` (`batch_id`,`model_type`,`model_id`),
  KEY `action_events_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table addresses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `addresses`;

CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `house_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `building_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `town` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;

INSERT INTO `addresses` (`id`, `house_number`, `building_name`, `street`, `town`, `city`, `postcode`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'1',NULL,'Dance Street',NULL,'Leeds','LS1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(2,'2',NULL,'Dance Street','Town 2','Belfast','BT1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(3,'3','Building 3','Dance Street',NULL,'Manchester','M1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(4,'4',NULL,'Dance Street','Town 4','Bristol','BS1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(5,'5',NULL,'Dance Street',NULL,'Bristol','BS1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(6,'6','Building 6','Dance Street','Town 6','Birmingham','B1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(7,'7',NULL,'Dance Street',NULL,'Glasgow','G1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(8,'8',NULL,'Dance Street','Town 8','Cardiff','CF10 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(9,'9','Building 9','Dance Street',NULL,'Manchester','M1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(10,'10',NULL,'Dance Street','Town 10','London','SW1A 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(11,'11',NULL,'Dance Street',NULL,'Liverpool','L1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(12,'12','Building 12','Dance Street','Town 12','Leeds','LS1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(13,'13',NULL,'Dance Street',NULL,'Edinburgh','EH1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(14,'14',NULL,'Dance Street','Town 14','Belfast','BT1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(15,'15','Building 15','Dance Street',NULL,'Bristol','BS1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(16,'16',NULL,'Dance Street','Town 16','Edinburgh','EH1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(17,'17',NULL,'Dance Street',NULL,'Cardiff','CF10 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(18,'18','Building 18','Dance Street','Town 18','Cardiff','CF10 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(19,'19',NULL,'Dance Street',NULL,'Manchester','M1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(20,'20',NULL,'Dance Street','Town 20','Cardiff','CF10 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(21,'21','Building 21','Dance Street',NULL,'Belfast','BT1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(22,'22',NULL,'Dance Street','Town 22','Manchester','M1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(23,'23',NULL,'Dance Street',NULL,'Birmingham','B1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(24,'24','Building 24','Dance Street','Town 24','Glasgow','G1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(25,'25',NULL,'Dance Street',NULL,'Liverpool','L1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(26,'26',NULL,'Dance Street','Town 26','Cardiff','CF10 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(27,'27','Building 27','Dance Street',NULL,'Leeds','LS1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(28,'28',NULL,'Dance Street','Town 28','Glasgow','G1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(29,'29',NULL,'Dance Street',NULL,'London','SW1A 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(30,'30','Building 30','Dance Street','Town 30','Liverpool','L1 1AA',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36');

/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table brands
# ------------------------------------------------------------

DROP TABLE IF EXISTS `brands`;

CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;

INSERT INTO `brands` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Nike',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(2,'Adidas',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(3,'Puma',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(4,'Reebok',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(5,'Under Armour',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(6,'Lululemon',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(7,'Fabletics',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(8,'Capezio',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(9,'Bloch',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(10,'Grishko',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(11,'Freed of London',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(12,'Russian Pointe',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(13,'Gaynor Minden',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(14,'So Danca',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(15,'Sansha',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29');

/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table cache_locks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Ballet',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(2,'Contemporary',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(3,'Jazz',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(4,'Tap',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(5,'Hip Hop',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(6,'Ballroom',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(7,'Latin',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(8,'Folk',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(9,'Irish',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(10,'Flamenco',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(11,'Bharatanatyam',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(12,'Kathak',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(13,'Bollywood',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(14,'Accessories',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(15,'Shoes',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(16,'Costumes',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(17,'Practice Wear',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(18,'Performance Wear',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29');

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table chat_blocks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_blocks`;

CREATE TABLE `chat_blocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `chat_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_blocks_user_id_foreign` (`user_id`),
  KEY `chat_blocks_chat_id_foreign` (`chat_id`),
  CONSTRAINT `chat_blocks_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chat_blocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `chat_blocks` WRITE;
/*!40000 ALTER TABLE `chat_blocks` DISABLE KEYS */;

INSERT INTO `chat_blocks` (`id`, `user_id`, `chat_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,10,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,2,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `chat_blocks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table chat_messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat_messages`;

CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_receiver_id_foreign` (`receiver_id`),
  KEY `chat_messages_sender_id_foreign` (`sender_id`),
  KEY `chat_messages_chat_id_foreign` (`chat_id`),
  CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chat_messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;

INSERT INTO `chat_messages` (`id`, `chat_id`, `receiver_id`, `sender_id`, `message`, `is_read`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,23,17,'Can you do a discount?',1,NULL,'2025-04-25 13:11:37','2025-04-25 13:11:37'),
	(2,1,17,23,'Can I see more photos?',0,NULL,'2025-04-25 13:24:37','2025-04-25 13:24:37'),
	(3,1,17,23,'Is the price negotiable?',0,NULL,'2025-04-25 13:38:37','2025-04-25 13:38:37'),
	(4,1,23,17,'When can I pick it up?',1,NULL,'2025-04-25 13:47:37','2025-04-25 13:47:37'),
	(5,1,17,23,'What\'s the condition like?',1,NULL,'2025-04-25 13:58:37','2025-04-25 13:58:37'),
	(6,1,17,23,'When can I pick it up?',0,NULL,'2025-04-25 14:07:37','2025-04-25 14:07:37'),
	(7,1,23,17,'Is the price negotiable?',1,NULL,'2025-04-25 14:20:37','2025-04-25 14:20:37'),
	(8,1,17,23,'Thanks for your help!',0,NULL,'2025-04-25 14:21:37','2025-04-25 14:21:37'),
	(9,2,16,10,'I\'ll get back to you soon.',1,NULL,'2025-04-24 13:17:37','2025-04-24 13:17:37'),
	(10,2,10,16,'Can I see more photos?',1,NULL,'2025-04-24 14:00:37','2025-04-24 14:00:37'),
	(11,2,10,16,'What\'s the condition like?',1,NULL,'2025-04-24 14:50:37','2025-04-24 14:50:37'),
	(12,2,16,10,'How are you?',1,NULL,'2025-04-24 15:28:37','2025-04-24 15:28:37'),
	(13,2,10,16,'How long have you had it?',0,NULL,'2025-04-24 16:10:37','2025-04-24 16:10:37'),
	(14,2,16,10,'Is this still available?',0,NULL,'2025-04-24 16:12:37','2025-04-24 16:12:37'),
	(15,2,10,16,'What\'s the condition like?',0,NULL,'2025-04-24 16:41:37','2025-04-24 16:41:37'),
	(16,2,16,10,'How long have you had it?',0,NULL,'2025-04-24 17:24:37','2025-04-24 17:24:37'),
	(17,2,16,10,'Hi there!',0,NULL,'2025-04-24 18:06:37','2025-04-24 18:06:37'),
	(18,2,16,10,'I\'ll get back to you soon.',0,NULL,'2025-04-24 18:08:37','2025-04-24 18:08:37'),
	(19,3,18,8,'How are you?',1,NULL,'2025-04-29 13:02:37','2025-04-29 13:02:37'),
	(20,3,18,8,'Is the price negotiable?',1,NULL,'2025-04-29 13:42:37','2025-04-29 13:42:37'),
	(21,3,18,8,'Do you offer shipping?',1,NULL,'2025-04-29 14:12:37','2025-04-29 14:12:37'),
	(22,3,18,8,'What\'s the condition like?',0,NULL,'2025-04-29 15:10:37','2025-04-29 15:10:37'),
	(23,3,8,18,'What size is it?',0,NULL,'2025-04-29 15:24:37','2025-04-29 15:24:37'),
	(24,4,4,10,'Can you do a discount?',0,NULL,'2025-04-30 12:52:37','2025-04-30 12:52:37'),
	(25,4,4,10,'Is the price negotiable?',1,NULL,'2025-04-30 13:28:37','2025-04-30 13:28:37'),
	(26,4,10,4,'Do you offer shipping?',1,NULL,'2025-04-30 14:25:37','2025-04-30 14:25:37'),
	(27,4,10,4,'Can you do a discount?',0,NULL,'2025-04-30 14:42:37','2025-04-30 14:42:37'),
	(28,4,4,10,'Is the price negotiable?',0,NULL,'2025-04-30 15:17:37','2025-04-30 15:17:37'),
	(29,4,10,4,'What\'s the condition like?',1,NULL,'2025-04-30 15:43:37','2025-04-30 15:43:37'),
	(30,4,4,10,'Do you offer shipping?',0,NULL,'2025-04-30 16:09:37','2025-04-30 16:09:37'),
	(31,4,10,4,'Hi there!',0,NULL,'2025-04-30 17:04:37','2025-04-30 17:04:37'),
	(32,5,13,25,'Do you offer shipping?',1,NULL,'2025-04-29 13:18:37','2025-04-29 13:18:37'),
	(33,5,13,25,'Is this still available?',1,NULL,'2025-04-29 13:27:37','2025-04-29 13:27:37'),
	(34,5,13,25,'How long have you had it?',0,NULL,'2025-04-29 14:16:37','2025-04-29 14:16:37'),
	(35,5,25,13,'How long have you had it?',1,NULL,'2025-04-29 15:10:37','2025-04-29 15:10:37'),
	(36,5,25,13,'I\'m interested in your product.',1,NULL,'2025-04-29 15:49:37','2025-04-29 15:49:37'),
	(37,6,25,6,'Is this still available?',0,NULL,'2025-04-27 12:30:37','2025-04-27 12:30:37'),
	(38,6,25,6,'How long have you had it?',0,NULL,'2025-04-27 12:43:37','2025-04-27 12:43:37'),
	(39,6,25,6,'Is this still available?',0,NULL,'2025-04-27 13:03:37','2025-04-27 13:03:37'),
	(40,6,25,6,'What size is it?',1,NULL,'2025-04-27 13:59:37','2025-04-27 13:59:37'),
	(41,6,6,25,'How long have you had it?',1,NULL,'2025-04-27 14:05:37','2025-04-27 14:05:37'),
	(42,6,25,6,'What\'s the condition like?',0,NULL,'2025-04-27 14:17:37','2025-04-27 14:17:37'),
	(43,7,14,2,'How are you?',0,NULL,'2025-04-30 13:15:37','2025-04-30 13:15:37'),
	(44,7,2,14,'Can you do a discount?',1,NULL,'2025-04-30 14:04:37','2025-04-30 14:04:37'),
	(45,7,14,2,'What\'s the condition like?',0,NULL,'2025-04-30 14:11:37','2025-04-30 14:11:37'),
	(46,7,2,14,'Is this still available?',0,NULL,'2025-04-30 14:33:37','2025-04-30 14:33:37'),
	(47,7,2,14,'Does it fit true to size?',0,NULL,'2025-04-30 15:09:37','2025-04-30 15:09:37'),
	(48,8,9,7,'Hi there!',0,NULL,'2025-04-26 12:48:37','2025-04-26 12:48:37'),
	(49,8,7,9,'Thanks for your help!',0,NULL,'2025-04-26 13:30:37','2025-04-26 13:30:37'),
	(50,8,7,9,'Do you offer shipping?',0,NULL,'2025-04-26 13:31:37','2025-04-26 13:31:37'),
	(51,8,7,9,'Can I see more photos?',0,NULL,'2025-04-26 14:12:37','2025-04-26 14:12:37'),
	(52,8,7,9,'How are you?',0,NULL,'2025-04-26 14:14:37','2025-04-26 14:14:37'),
	(53,8,7,9,'Thanks for your help!',0,NULL,'2025-04-26 14:23:37','2025-04-26 14:23:37'),
	(54,8,9,7,'When can I pick it up?',1,NULL,'2025-04-26 15:22:37','2025-04-26 15:22:37'),
	(55,8,7,9,'I\'m interested in your product.',1,NULL,'2025-04-26 16:19:37','2025-04-26 16:19:37'),
	(56,9,10,25,'I\'ll get back to you soon.',0,NULL,'2025-04-28 13:19:37','2025-04-28 13:19:37'),
	(57,9,25,10,'Is this still available?',0,NULL,'2025-04-28 14:16:37','2025-04-28 14:16:37'),
	(58,9,10,25,'What\'s the condition like?',1,NULL,'2025-04-28 14:19:37','2025-04-28 14:19:37'),
	(59,10,24,6,'Can I see more photos?',1,NULL,'2025-04-25 12:48:37','2025-04-25 12:48:37'),
	(60,10,24,6,'What\'s the condition like?',1,NULL,'2025-04-25 13:37:37','2025-04-25 13:37:37'),
	(61,10,6,24,'How long have you had it?',0,NULL,'2025-04-25 14:27:37','2025-04-25 14:27:37'),
	(62,10,6,24,'How are you?',0,NULL,'2025-04-25 14:41:37','2025-04-25 14:41:37'),
	(63,10,24,6,'Can you do a discount?',1,NULL,'2025-04-25 14:57:37','2025-04-25 14:57:37');

/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table chats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chats`;

CREATE TABLE `chats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` bigint unsigned NOT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chats_buyer_id_foreign` (`buyer_id`),
  KEY `chats_seller_id_foreign` (`seller_id`),
  CONSTRAINT `chats_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chats_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `chats` WRITE;
/*!40000 ALTER TABLE `chats` DISABLE KEYS */;

INSERT INTO `chats` (`id`, `buyer_id`, `seller_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,17,23,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,10,16,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,8,18,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,4,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,13,25,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,6,25,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,14,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,9,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,10,25,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,6,24,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `chats` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table colours
# ------------------------------------------------------------

DROP TABLE IF EXISTS `colours`;

CREATE TABLE `colours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hexcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `colours` WRITE;
/*!40000 ALTER TABLE `colours` DISABLE KEYS */;

INSERT INTO `colours` (`id`, `name`, `hexcode`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Black','#000000',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(2,'White','#FFFFFF',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(3,'Red','#FF0000',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(4,'Blue','#0000FF',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(5,'Green','#008000',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(6,'Yellow','#FFFF00',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(7,'Purple','#800080',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(8,'Pink','#FFC0CB',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(9,'Orange','#FFA500',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(10,'Brown','#A52A2A',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(11,'Grey','#808080',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(12,'Navy','#000080',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(13,'Teal','#008080',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(14,'Lavender','#E6E6FA',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(15,'Beige','#F5F5DC',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29');

/*!40000 ALTER TABLE `colours` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table conditions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `conditions`;

CREATE TABLE `conditions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `conditions` WRITE;
/*!40000 ALTER TABLE `conditions` DISABLE KEYS */;

INSERT INTO `conditions` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Brand New',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(2,'Used - Very Good',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(3,'Used - Good',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29');

/*!40000 ALTER TABLE `conditions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

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



# Dump of table favourite_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favourite_products`;

CREATE TABLE `favourite_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favourite_products_user_id_foreign` (`user_id`),
  KEY `favourite_products_product_id_foreign` (`product_id`),
  CONSTRAINT `favourite_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `favourite_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `favourite_products` WRITE;
/*!40000 ALTER TABLE `favourite_products` DISABLE KEYS */;

INSERT INTO `favourite_products` (`id`, `user_id`, `product_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,16,25,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,12,17,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,21,31,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,8,16,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,24,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,7,24,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,11,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,9,45,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,12,46,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,5,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,8,24,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,4,19,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,14,19,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,17,17,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,11,35,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,2,31,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,9,27,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,9,38,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,2,36,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,3,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,1,49,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,8,33,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,3,24,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,21,45,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,7,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,15,44,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,8,20,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,12,34,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,2,33,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,24,16,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `favourite_products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table fulfillment_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fulfillment_options`;

CREATE TABLE `fulfillment_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `fulfillment_options` WRITE;
/*!40000 ALTER TABLE `fulfillment_options` DISABLE KEYS */;

INSERT INTO `fulfillment_options` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Collection','Ready to collect within 1-2 business days',NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(2,'Delivery','Delivery within 3-5 business days',NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29');

/*!40000 ALTER TABLE `fulfillment_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hiring_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hiring_details`;

CREATE TABLE `hiring_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `min_hire_days` int NOT NULL,
  `additional_fee_per_day` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hiring_details_product_id_foreign` (`product_id`),
  CONSTRAINT `hiring_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `hiring_details` WRITE;
/*!40000 ALTER TABLE `hiring_details` DISABLE KEYS */;

INSERT INTO `hiring_details` (`id`, `product_id`, `min_hire_days`, `additional_fee_per_day`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,3,17.24,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,2,3,16.12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,3,3,18.67,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,5,1,5.45,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,6,1,13.57,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,7,1,9.11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,11,3,8.2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,12,1,11.22,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,13,2,12.85,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,14,2,9.69,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,16,1,5.88,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,19,1,17.47,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,21,3,19.31,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,25,1,13.68,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,28,3,16.23,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,30,2,11.95,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,31,2,15.63,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,41,1,8.73,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,44,2,6.73,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,46,1,15.69,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,47,2,15.2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,49,1,10.32,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `hiring_details` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hiring_unavailability_days
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hiring_unavailability_days`;

CREATE TABLE `hiring_unavailability_days` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hiring_detail_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hiring_unavailability_days_hiring_detail_id_foreign` (`hiring_detail_id`),
  CONSTRAINT `hiring_unavailability_days_hiring_detail_id_foreign` FOREIGN KEY (`hiring_detail_id`) REFERENCES `hiring_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `hiring_unavailability_days` WRITE;
/*!40000 ALTER TABLE `hiring_unavailability_days` DISABLE KEYS */;

INSERT INTO `hiring_unavailability_days` (`id`, `hiring_detail_id`, `date`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,'2025-05-28',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,1,'2025-05-22',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,1,'2025-05-19',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,3,'2025-05-21',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,3,'2025-05-13',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,3,'2025-05-05',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,3,'2025-05-10',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,4,'2025-05-05',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,4,'2025-05-04',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,4,'2025-05-09',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,5,'2025-05-25',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,5,'2025-05-08',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,5,'2025-05-14',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,5,'2025-05-06',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,6,'2025-05-15',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,6,'2025-05-27',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,6,'2025-05-25',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,7,'2025-05-23',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,8,'2025-05-25',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,8,'2025-05-22',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,8,'2025-05-27',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,9,'2025-05-24',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,9,'2025-05-09',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,9,'2025-05-06',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,9,'2025-05-23',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,9,'2025-05-28',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,11,'2025-05-04',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,11,'2025-05-15',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,12,'2025-05-02',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,12,'2025-05-04',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(31,13,'2025-05-17',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(32,13,'2025-05-22',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(33,13,'2025-05-12',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(34,13,'2025-05-24',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(35,15,'2025-05-09',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(36,15,'2025-05-12',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(37,15,'2025-05-13',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,15,'2025-05-16',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,17,'2025-05-22',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,18,'2025-05-13',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,18,'2025-05-23',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,19,'2025-05-11',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(43,19,'2025-05-30',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(44,20,'2025-05-21',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(45,20,'2025-05-17',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(46,21,'2025-05-21',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(47,21,'2025-05-22',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(48,21,'2025-05-09',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(49,22,'2025-05-12',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(50,22,'2025-05-24',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(51,22,'2025-05-10',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(52,22,'2025-05-21',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `hiring_unavailability_days` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table job_batches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table media
# ------------------------------------------------------------

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'0001_01_01_000001_create_cache_table',1),
	(2,'0001_01_01_000002_create_jobs_table',1),
	(3,'2018_01_01_000000_create_action_events_table',1),
	(4,'2019_05_10_000000_add_fields_to_action_events_table',1),
	(5,'2021_08_25_193039_create_nova_notifications_table',1),
	(6,'2022_04_26_000000_add_fields_to_nova_notifications_table',1),
	(7,'2022_12_19_000000_create_field_attachments_table',1),
	(8,'2025_02_21_153901_create_personal_access_tokens_table',1),
	(9,'2025_02_24_185924_create_media_table',1),
	(10,'2025_02_24_185959_create_users_table',1),
	(11,'2025_02_24_190539_add_two_factor_columns_to_users_table',1),
	(12,'2025_02_25_064712_create_user_schools_table',1),
	(13,'2025_02_25_064827_create_chats_table',1),
	(14,'2025_02_25_065015_create_chat_messages_table',1),
	(15,'2025_02_25_065139_create_chat_blocks_table',1),
	(16,'2025_02_25_065237_create_notifications_table',1),
	(17,'2025_02_25_065420_create_addresses_table',1),
	(18,'2025_02_25_065910_create_user_addresses_table',1),
	(19,'2025_02_25_072557_create_brands_table',1),
	(20,'2025_02_25_072642_create_categories_table',1),
	(21,'2025_02_25_072706_create_conditions_table',1),
	(22,'2025_02_25_072731_create_sizes_table',1),
	(23,'2025_02_25_072736_create_colours_table',1),
	(24,'2025_02_25_073019_create_products_table',1),
	(25,'2025_02_25_075322_create_fulfillment_options_table',1),
	(26,'2025_02_25_075457_create_product_fulfillment_options_table',1),
	(27,'2025_02_25_075630_create_product_reviews_table',1),
	(28,'2025_02_25_075802_create_favourite_products_table',1),
	(29,'2025_02_25_075903_create_hiring_details_table',1),
	(30,'2025_02_25_080357_create_hiring_unavailability_days_table',1),
	(31,'2025_02_25_080503_create_product_colours_table',1),
	(32,'2025_02_25_080508_create_product_sizes_table',1),
	(33,'2025_02_25_080940_create_payment_methods_table',1),
	(34,'2025_02_25_081145_create_orders_table',1),
	(35,'2025_02_25_081808_create_order_items_table',1),
	(36,'2025_02_25_082623_create_order_item_colours_table',1),
	(37,'2025_02_25_082628_create_order_item_sizes_table',1),
	(38,'2025_02_27_141036_alter_user_schools_table_add_name_column',1),
	(39,'2025_03_05_050845_create_user_reviews_table',1),
	(40,'2025_03_24_112449_create_seller_orders_table',1),
	(41,'2025_03_25_022109_create_order_statuses_table',1),
	(42,'2025_03_25_022348_create_seller_order_statuses_table',1),
	(43,'2025_03_28_105217_create_transactions_table',1),
	(44,'2025_03_28_113036_create_order_transactions_table',1),
	(45,'2025_03_28_113328_create_payout_transactions_table',1),
	(46,'2025_03_28_113342_create_refund_transactions_table',1),
	(47,'2025_04_07_073150_create_shipping_service_providers_table',1),
	(48,'2025_04_07_073454_create_product_shipping_service_providers_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;

INSERT INTO `notifications` (`id`, `user_id`, `title`, `description`, `link`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,5,'Product Sold','Your product has been sold.','/products/1',NULL,'2025-04-26 10:19:37','2025-04-26 10:19:37'),
	(2,6,'Review Received','You have received a new review.','/reviews/1',NULL,'2025-04-23 22:19:38','2025-04-23 22:19:38'),
	(3,1,'Order Shipped','Your order has been shipped.','/orders/2',NULL,'2025-04-26 18:19:38','2025-04-26 18:19:38'),
	(4,26,'Review Received','You have received a new review.','/reviews/1',NULL,'2025-04-27 23:19:38','2025-04-27 23:19:38'),
	(5,3,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-30 05:19:38','2025-04-30 05:19:38'),
	(6,16,'Product Hired','Your product has been hired.','/products/2',NULL,'2025-04-27 09:19:38','2025-04-27 09:19:38'),
	(7,13,'Order Placed','Your order has been placed successfully.','/orders/1',NULL,'2025-04-27 10:19:38','2025-04-27 10:19:38'),
	(8,4,'Order Shipped','Your order has been shipped.','/orders/2',NULL,'2025-04-26 00:19:38','2025-04-26 00:19:38'),
	(9,1,'Review Received','You have received a new review.','/reviews/1',NULL,'2025-04-25 23:19:38','2025-04-25 23:19:38'),
	(10,20,'Order Shipped','Your order has been shipped.','/orders/2',NULL,'2025-04-23 16:19:38','2025-04-23 16:19:38'),
	(11,1,'Order Placed','Your order has been placed successfully.','/orders/1',NULL,'2025-05-01 03:19:38','2025-05-01 03:19:38'),
	(12,11,'Order Shipped','Your order has been shipped.','/orders/2',NULL,'2025-04-23 15:19:38','2025-04-23 15:19:38'),
	(13,23,'Order Delivered','Your order has been delivered.','/orders/3',NULL,'2025-04-29 02:19:38','2025-04-29 02:19:38'),
	(14,18,'Order Shipped','Your order has been shipped.','/orders/2',NULL,'2025-04-24 09:19:38','2025-04-24 09:19:38'),
	(15,23,'Order Placed','Your order has been placed successfully.','/orders/1',NULL,'2025-04-29 20:19:38','2025-04-29 20:19:38'),
	(16,23,'Review Received','You have received a new review.','/reviews/1',NULL,'2025-05-01 09:19:38','2025-05-01 09:19:38'),
	(17,5,'New Message','You have a new message.','/chats/1',NULL,'2025-04-25 08:19:38','2025-04-25 08:19:38'),
	(18,1,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-30 18:19:38','2025-04-30 18:19:38'),
	(19,3,'Product Sold','Your product has been sold.','/products/1',NULL,'2025-04-23 14:19:38','2025-04-23 14:19:38'),
	(20,8,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-28 02:19:38','2025-04-28 02:19:38'),
	(21,15,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-27 15:19:38','2025-04-27 15:19:38'),
	(22,10,'Order Placed','Your order has been placed successfully.','/orders/1',NULL,'2025-04-29 22:19:38','2025-04-29 22:19:38'),
	(23,24,'Order Delivered','Your order has been delivered.','/orders/3',NULL,'2025-04-25 12:19:38','2025-04-25 12:19:38'),
	(24,4,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-27 16:19:38','2025-04-27 16:19:38'),
	(25,8,'Product Hired','Your product has been hired.','/products/2',NULL,'2025-04-28 09:19:38','2025-04-28 09:19:38'),
	(26,4,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-25 05:19:38','2025-04-25 05:19:38'),
	(27,15,'Product Hired','Your product has been hired.','/products/2',NULL,'2025-04-23 14:19:38','2025-04-23 14:19:38'),
	(28,18,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-26 06:19:38','2025-04-26 06:19:38'),
	(29,5,'Order Delivered','Your order has been delivered.','/orders/3',NULL,'2025-05-01 05:19:38','2025-05-01 05:19:38'),
	(30,3,'Payment Received','You have received a payment.','/payments/1',NULL,'2025-04-29 00:19:38','2025-04-29 00:19:38');

/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nova_field_attachments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nova_field_attachments`;

CREATE TABLE `nova_field_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `attachable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachable_id` bigint unsigned NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nova_field_attachments_attachable_type_attachable_id_index` (`attachable_type`,`attachable_id`),
  KEY `nova_field_attachments_url_index` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table nova_notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nova_notifications`;

CREATE TABLE `nova_notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nova_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table nova_pending_field_attachments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nova_pending_field_attachments`;

CREATE TABLE `nova_pending_field_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `draft_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nova_pending_field_attachments_draft_id_index` (`draft_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table order_item_colours
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_item_colours`;

CREATE TABLE `order_item_colours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint unsigned NOT NULL,
  `colour_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_item_colours_order_item_id_foreign` (`order_item_id`),
  KEY `order_item_colours_colour_id_foreign` (`colour_id`),
  CONSTRAINT `order_item_colours_colour_id_foreign` FOREIGN KEY (`colour_id`) REFERENCES `colours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_item_colours_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_item_colours` WRITE;
/*!40000 ALTER TABLE `order_item_colours` DISABLE KEYS */;

INSERT INTO `order_item_colours` (`id`, `order_item_id`, `colour_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,2,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,3,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,4,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,5,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,6,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,7,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,8,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,9,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,10,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,11,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,12,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,13,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,14,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,15,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,16,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,17,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,18,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,19,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,20,14,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,21,13,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,22,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,23,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,24,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,25,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,26,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,27,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,28,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,29,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,30,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(31,31,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(32,32,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(33,33,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(34,34,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(35,35,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(36,36,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(37,37,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,38,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,39,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,40,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,41,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,42,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `order_item_colours` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_item_sizes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_item_sizes`;

CREATE TABLE `order_item_sizes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint unsigned NOT NULL,
  `size_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_item_sizes_order_item_id_foreign` (`order_item_id`),
  KEY `order_item_sizes_size_id_foreign` (`size_id`),
  CONSTRAINT `order_item_sizes_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_item_sizes_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_item_sizes` WRITE;
/*!40000 ALTER TABLE `order_item_sizes` DISABLE KEYS */;

INSERT INTO `order_item_sizes` (`id`, `order_item_id`, `size_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,2,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,3,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,4,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,5,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,6,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,7,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,8,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,9,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,10,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,11,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,12,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,13,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,14,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,15,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,16,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,17,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,18,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,19,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,20,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,21,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,22,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,23,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,24,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,25,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,26,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,27,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,28,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,29,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,30,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(31,31,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(32,32,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(33,33,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(34,34,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(35,35,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(36,36,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(37,37,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,38,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,39,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,40,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,41,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,42,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `order_item_sizes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_items`;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seller_order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` double NOT NULL,
  `product_snapshot` json NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_seller_order_id_foreign` (`seller_order_id`),
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_items_seller_order_id_foreign` FOREIGN KEY (`seller_order_id`) REFERENCES `seller_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;

INSERT INTO `order_items` (`id`, `seller_order_id`, `product_id`, `quantity`, `price`, `product_snapshot`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,8,3,170.69,'{\"id\": 8, \"name\": \"Reebok Dance Shorts HGmV\", \"type\": \"sale\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 170.69, \"category\": {\"id\": 15, \"name\": \"Shoes\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,2,18,1,131.2,'{\"id\": 18, \"name\": \"Bloch Pointe Shoes j9CI\", \"type\": \"sale\", \"brand\": {\"id\": 9, \"name\": \"Bloch\"}, \"price\": 131.2, \"category\": {\"id\": 16, \"name\": \"Costumes\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Perfect for beginners and professionals alike.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,3,41,1,261.95,'{\"id\": 41, \"name\": \"Gaynor Minden Dance Shorts KRng\", \"type\": \"hire\", \"brand\": {\"id\": 13, \"name\": \"Gaynor Minden\"}, \"price\": 192.11, \"category\": {\"id\": 2, \"name\": \"Contemporary\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Lightweight and breathable material.\", \"hiring_details\": {\"days\": 8, \"start_date\": \"2025-05-06\", \"additional_fee_per_day\": 8.73}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,4,33,3,127.78,'{\"id\": 33, \"name\": \"Puma Pointe Shoes w7YM\", \"type\": \"sale\", \"brand\": {\"id\": 3, \"name\": \"Puma\"}, \"price\": 127.78, \"category\": {\"id\": 9, \"name\": \"Irish\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,5,9,1,53.01,'{\"id\": 9, \"name\": \"Gaynor Minden Dance Bag GgEh\", \"type\": \"sale\", \"brand\": {\"id\": 13, \"name\": \"Gaynor Minden\"}, \"price\": 53.01, \"category\": {\"id\": 16, \"name\": \"Costumes\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Stylish and functional for all your dance needs.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,6,22,1,191.16,'{\"id\": 22, \"name\": \"So Danca Jazz Shoes EXFn\", \"type\": \"sale\", \"brand\": {\"id\": 14, \"name\": \"So Danca\"}, \"price\": 191.16, \"category\": {\"id\": 6, \"name\": \"Ballroom\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,7,3,3,93.86,'{\"id\": 3, \"name\": \"Reebok Tap Shoes slqh\", \"type\": \"hire\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 19.18, \"category\": {\"id\": 18, \"name\": \"Performance Wear\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Stylish and functional for all your dance needs.\", \"hiring_details\": {\"days\": 4, \"start_date\": \"2025-05-06\", \"additional_fee_per_day\": 18.67}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,8,30,3,205.69,'{\"id\": 30, \"name\": \"Fabletics Dance Tights 8LPZ\", \"type\": \"hire\", \"brand\": {\"id\": 7, \"name\": \"Fabletics\"}, \"price\": 169.84, \"category\": {\"id\": 2, \"name\": \"Contemporary\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Perfect for beginners and professionals alike.\", \"hiring_details\": {\"days\": 3, \"start_date\": \"2025-05-02\", \"additional_fee_per_day\": 11.95}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,9,21,1,217.19,'{\"id\": 21, \"name\": \"Sansha Dance Skirt rh5O\", \"type\": \"hire\", \"brand\": {\"id\": 15, \"name\": \"Sansha\"}, \"price\": 24.09, \"category\": {\"id\": 14, \"name\": \"Accessories\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Designed with dancers in mind for maximum comfort and flexibility.\", \"hiring_details\": {\"days\": 10, \"start_date\": \"2025-05-02\", \"additional_fee_per_day\": 19.31}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,10,50,1,131.65,'{\"id\": 50, \"name\": \"Reebok Dance Tights AIoJ\", \"type\": \"sale\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 131.65, \"category\": {\"id\": 12, \"name\": \"Kathak\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Perfect for beginners and professionals alike.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,11,9,3,53.01,'{\"id\": 9, \"name\": \"Gaynor Minden Dance Bag GgEh\", \"type\": \"sale\", \"brand\": {\"id\": 13, \"name\": \"Gaynor Minden\"}, \"price\": 53.01, \"category\": {\"id\": 16, \"name\": \"Costumes\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Stylish and functional for all your dance needs.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,12,13,2,110.59,'{\"id\": 13, \"name\": \"Puma Dance Leotard QQcS\", \"type\": \"hire\", \"brand\": {\"id\": 3, \"name\": \"Puma\"}, \"price\": 46.34, \"category\": {\"id\": 6, \"name\": \"Ballroom\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Comfortable and durable for long practice sessions.\", \"hiring_details\": {\"days\": 5, \"start_date\": \"2025-05-07\", \"additional_fee_per_day\": 12.85}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,13,22,2,191.16,'{\"id\": 22, \"name\": \"So Danca Jazz Shoes EXFn\", \"type\": \"sale\", \"brand\": {\"id\": 14, \"name\": \"So Danca\"}, \"price\": 191.16, \"category\": {\"id\": 6, \"name\": \"Ballroom\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,14,20,2,134.27,'{\"id\": 20, \"name\": \"Reebok Dance Shorts 1A5z\", \"type\": \"sale\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 134.27, \"category\": {\"id\": 11, \"name\": \"Bharatanatyam\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Perfect for beginners and professionals alike.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,15,2,3,196.34,'{\"id\": 2, \"name\": \"So Danca Jazz Shoes Yz04\", \"type\": \"hire\", \"brand\": {\"id\": 14, \"name\": \"So Danca\"}, \"price\": 115.74, \"category\": {\"id\": 13, \"name\": \"Bollywood\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"High-quality material that will last for years.\", \"hiring_details\": {\"days\": 5, \"start_date\": \"2025-05-07\", \"additional_fee_per_day\": 16.12}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,16,25,1,162.09,'{\"id\": 25, \"name\": \"Puma Dance Skirt qQtc\", \"type\": \"hire\", \"brand\": {\"id\": 3, \"name\": \"Puma\"}, \"price\": 25.29, \"category\": {\"id\": 11, \"name\": \"Bharatanatyam\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Perfect for beginners and professionals alike.\", \"hiring_details\": {\"days\": 10, \"start_date\": \"2025-05-03\", \"additional_fee_per_day\": 13.68}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,17,7,3,109.36,'{\"id\": 7, \"name\": \"Puma Practice Wear Set iPrV\", \"type\": \"hire\", \"brand\": {\"id\": 3, \"name\": \"Puma\"}, \"price\": 91.14, \"category\": {\"id\": 2, \"name\": \"Contemporary\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Lightweight and breathable material.\", \"hiring_details\": {\"days\": 2, \"start_date\": \"2025-05-04\", \"additional_fee_per_day\": 9.11}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,18,3,1,149.87,'{\"id\": 3, \"name\": \"Reebok Tap Shoes slqh\", \"type\": \"hire\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 19.18, \"category\": {\"id\": 18, \"name\": \"Performance Wear\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Stylish and functional for all your dance needs.\", \"hiring_details\": {\"days\": 7, \"start_date\": \"2025-05-07\", \"additional_fee_per_day\": 18.67}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,19,47,2,167.92,'{\"id\": 47, \"name\": \"Capezio Warm-up Booties TK2y\", \"type\": \"hire\", \"brand\": {\"id\": 8, \"name\": \"Capezio\"}, \"price\": 107.12, \"category\": {\"id\": 3, \"name\": \"Jazz\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Great for practice sessions and classes.\", \"hiring_details\": {\"days\": 4, \"start_date\": \"2025-05-02\", \"additional_fee_per_day\": 15.2}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,20,49,1,203.72,'{\"id\": 49, \"name\": \"Grishko Dance Skirt lKfp\", \"type\": \"hire\", \"brand\": {\"id\": 10, \"name\": \"Grishko\"}, \"price\": 100.52, \"category\": {\"id\": 8, \"name\": \"Folk\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Perfect for beginners and professionals alike.\", \"hiring_details\": {\"days\": 10, \"start_date\": \"2025-05-04\", \"additional_fee_per_day\": 10.32}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,21,21,3,120.64,'{\"id\": 21, \"name\": \"Sansha Dance Skirt rh5O\", \"type\": \"hire\", \"brand\": {\"id\": 15, \"name\": \"Sansha\"}, \"price\": 24.09, \"category\": {\"id\": 14, \"name\": \"Accessories\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Designed with dancers in mind for maximum comfort and flexibility.\", \"hiring_details\": {\"days\": 5, \"start_date\": \"2025-05-07\", \"additional_fee_per_day\": 19.31}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,22,25,3,52.65,'{\"id\": 25, \"name\": \"Puma Dance Skirt qQtc\", \"type\": \"hire\", \"brand\": {\"id\": 3, \"name\": \"Puma\"}, \"price\": 25.29, \"category\": {\"id\": 11, \"name\": \"Bharatanatyam\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Perfect for beginners and professionals alike.\", \"hiring_details\": {\"days\": 2, \"start_date\": \"2025-05-06\", \"additional_fee_per_day\": 13.68}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,23,49,3,121.16,'{\"id\": 49, \"name\": \"Grishko Dance Skirt lKfp\", \"type\": \"hire\", \"brand\": {\"id\": 10, \"name\": \"Grishko\"}, \"price\": 100.52, \"category\": {\"id\": 8, \"name\": \"Folk\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Perfect for beginners and professionals alike.\", \"hiring_details\": {\"days\": 2, \"start_date\": \"2025-05-05\", \"additional_fee_per_day\": 10.32}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,24,8,1,170.69,'{\"id\": 8, \"name\": \"Reebok Dance Shorts HGmV\", \"type\": \"sale\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 170.69, \"category\": {\"id\": 15, \"name\": \"Shoes\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,25,33,3,127.78,'{\"id\": 33, \"name\": \"Puma Pointe Shoes w7YM\", \"type\": \"sale\", \"brand\": {\"id\": 3, \"name\": \"Puma\"}, \"price\": 127.78, \"category\": {\"id\": 9, \"name\": \"Irish\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,26,20,2,134.27,'{\"id\": 20, \"name\": \"Reebok Dance Shorts 1A5z\", \"type\": \"sale\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 134.27, \"category\": {\"id\": 11, \"name\": \"Bharatanatyam\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Perfect for beginners and professionals alike.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,27,38,1,73.82,'{\"id\": 38, \"name\": \"Under Armour Dance Tights t9oL\", \"type\": \"sale\", \"brand\": {\"id\": 5, \"name\": \"Under Armour\"}, \"price\": 73.82, \"category\": {\"id\": 13, \"name\": \"Bollywood\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Perfect for beginners and professionals alike.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,28,22,3,191.16,'{\"id\": 22, \"name\": \"So Danca Jazz Shoes EXFn\", \"type\": \"sale\", \"brand\": {\"id\": 14, \"name\": \"So Danca\"}, \"price\": 191.16, \"category\": {\"id\": 6, \"name\": \"Ballroom\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,29,34,2,92.83,'{\"id\": 34, \"name\": \"So Danca Performance Costume 9NPP\", \"type\": \"sale\", \"brand\": {\"id\": 14, \"name\": \"So Danca\"}, \"price\": 92.83, \"category\": {\"id\": 9, \"name\": \"Irish\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Great for practice sessions and classes.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,30,35,2,90.6,'{\"id\": 35, \"name\": \"Capezio Dance Leotard oXhI\", \"type\": \"sale\", \"brand\": {\"id\": 8, \"name\": \"Capezio\"}, \"price\": 90.6, \"category\": {\"id\": 3, \"name\": \"Jazz\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Elegant design for a professional look.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(31,31,41,3,261.95,'{\"id\": 41, \"name\": \"Gaynor Minden Dance Shorts KRng\", \"type\": \"hire\", \"brand\": {\"id\": 13, \"name\": \"Gaynor Minden\"}, \"price\": 192.11, \"category\": {\"id\": 2, \"name\": \"Contemporary\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Lightweight and breathable material.\", \"hiring_details\": {\"days\": 8, \"start_date\": \"2025-05-06\", \"additional_fee_per_day\": 8.73}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(32,32,32,2,143.92,'{\"id\": 32, \"name\": \"Puma Ballet Shoes NUF7\", \"type\": \"sale\", \"brand\": {\"id\": 3, \"name\": \"Puma\"}, \"price\": 143.92, \"category\": {\"id\": 3, \"name\": \"Jazz\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Stylish and functional for all your dance needs.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(33,33,39,3,39.22,'{\"id\": 39, \"name\": \"Sansha Ballet Tutu idsB\", \"type\": \"sale\", \"brand\": {\"id\": 15, \"name\": \"Sansha\"}, \"price\": 39.22, \"category\": {\"id\": 12, \"name\": \"Kathak\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Versatile piece for various dance styles.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(34,34,41,2,244.49,'{\"id\": 41, \"name\": \"Gaynor Minden Dance Shorts KRng\", \"type\": \"hire\", \"brand\": {\"id\": 13, \"name\": \"Gaynor Minden\"}, \"price\": 192.11, \"category\": {\"id\": 2, \"name\": \"Contemporary\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Lightweight and breathable material.\", \"hiring_details\": {\"days\": 6, \"start_date\": \"2025-05-07\", \"additional_fee_per_day\": 8.73}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(35,35,11,2,105.07,'{\"id\": 11, \"name\": \"Lululemon Pointe Shoes qyVI\", \"type\": \"hire\", \"brand\": {\"id\": 6, \"name\": \"Lululemon\"}, \"price\": 31.27, \"category\": {\"id\": 7, \"name\": \"Latin\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Ideal for performances and competitions.\", \"hiring_details\": {\"days\": 9, \"start_date\": \"2025-05-02\", \"additional_fee_per_day\": 8.2}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(36,36,12,1,111.84,'{\"id\": 12, \"name\": \"Grishko Dance Top tSIZ\", \"type\": \"hire\", \"brand\": {\"id\": 10, \"name\": \"Grishko\"}, \"price\": 33.3, \"category\": {\"id\": 17, \"name\": \"Practice Wear\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Versatile piece for various dance styles.\", \"hiring_details\": {\"days\": 7, \"start_date\": \"2025-05-03\", \"additional_fee_per_day\": 11.22}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(37,37,2,3,212.46,'{\"id\": 2, \"name\": \"So Danca Jazz Shoes Yz04\", \"type\": \"hire\", \"brand\": {\"id\": 14, \"name\": \"So Danca\"}, \"price\": 115.74, \"category\": {\"id\": 13, \"name\": \"Bollywood\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"High-quality material that will last for years.\", \"hiring_details\": {\"days\": 6, \"start_date\": \"2025-05-04\", \"additional_fee_per_day\": 16.12}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,38,37,1,141.97,'{\"id\": 37, \"name\": \"Bloch Jazz Shoes wDkL\", \"type\": \"sale\", \"brand\": {\"id\": 9, \"name\": \"Bloch\"}, \"price\": 141.97, \"category\": {\"id\": 3, \"name\": \"Jazz\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"High-quality material that will last for years.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,39,20,3,134.27,'{\"id\": 20, \"name\": \"Reebok Dance Shorts 1A5z\", \"type\": \"sale\", \"brand\": {\"id\": 4, \"name\": \"Reebok\"}, \"price\": 134.27, \"category\": {\"id\": 11, \"name\": \"Bharatanatyam\"}, \"condition\": {\"id\": 2, \"name\": \"Used - Very Good\"}, \"description\": \"Perfect for beginners and professionals alike.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,40,39,2,39.22,'{\"id\": 39, \"name\": \"Sansha Ballet Tutu idsB\", \"type\": \"sale\", \"brand\": {\"id\": 15, \"name\": \"Sansha\"}, \"price\": 39.22, \"category\": {\"id\": 12, \"name\": \"Kathak\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Versatile piece for various dance styles.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,41,5,2,34.54,'{\"id\": 5, \"name\": \"Freed of London Pointe Shoes PwLc\", \"type\": \"hire\", \"brand\": {\"id\": 11, \"name\": \"Freed of London\"}, \"price\": 29.09, \"category\": {\"id\": 8, \"name\": \"Folk\"}, \"condition\": {\"id\": 3, \"name\": \"Used - Good\"}, \"description\": \"Lightweight and breathable material.\", \"hiring_details\": {\"days\": 1, \"start_date\": \"2025-05-05\", \"additional_fee_per_day\": 5.45}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,42,9,2,53.01,'{\"id\": 9, \"name\": \"Gaynor Minden Dance Bag GgEh\", \"type\": \"sale\", \"brand\": {\"id\": 13, \"name\": \"Gaynor Minden\"}, \"price\": 53.01, \"category\": {\"id\": 16, \"name\": \"Costumes\"}, \"condition\": {\"id\": 1, \"name\": \"Brand New\"}, \"description\": \"Stylish and functional for all your dance needs.\"}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_statuses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_statuses`;

CREATE TABLE `order_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_statuses` WRITE;
/*!40000 ALTER TABLE `order_statuses` DISABLE KEYS */;

INSERT INTO `order_statuses` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Pending',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(2,'Processing',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(3,'Order Confirmed',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(4,'Payment Pending',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(5,'Payment Confirmed',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(6,'Shipped',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(7,'Delivered',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(8,'Cancelled',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(9,'Disput',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(10,'Disput Resolved',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38');

/*!40000 ALTER TABLE `order_statuses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_transactions`;

CREATE TABLE `order_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_transactions_transaction_id_foreign` (`transaction_id`),
  KEY `order_transactions_order_id_foreign` (`order_id`),
  KEY `order_transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `order_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_transactions_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `payment_method_id` bigint unsigned NOT NULL,
  `amount` double NOT NULL,
  `addresses` json NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_payment_method_id_foreign` (`payment_method_id`),
  CONSTRAINT `orders_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;

INSERT INTO `orders` (`id`, `user_id`, `payment_method_id`, `amount`, `addresses`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,11,3,0,'{\"billing\": {\"id\": 5, \"city\": \"Bristol\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"BS1 1AA\", \"house_number\": \"5\", \"building_name\": null}, \"shipping\": {\"id\": 5, \"city\": \"Bristol\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"BS1 1AA\", \"house_number\": \"5\", \"building_name\": null}}',NULL,'2025-04-18 12:19:37','2025-04-18 12:19:37'),
	(2,25,5,0,'{\"billing\": {\"id\": 7, \"city\": \"Glasgow\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"G1 1AA\", \"house_number\": \"7\", \"building_name\": null}, \"shipping\": {\"id\": 7, \"city\": \"Glasgow\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"G1 1AA\", \"house_number\": \"7\", \"building_name\": null}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,5,3,0,'{\"billing\": {\"id\": 2, \"city\": \"Belfast\", \"town\": \"Town 2\", \"street\": \"Dance Street\", \"postcode\": \"BT1 1AA\", \"house_number\": \"2\", \"building_name\": null}, \"shipping\": {\"id\": 2, \"city\": \"Belfast\", \"town\": \"Town 2\", \"street\": \"Dance Street\", \"postcode\": \"BT1 1AA\", \"house_number\": \"2\", \"building_name\": null}}',NULL,'2025-04-15 12:19:37','2025-04-15 12:19:37'),
	(4,4,6,0,'{\"billing\": {\"id\": 5, \"city\": \"Bristol\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"BS1 1AA\", \"house_number\": \"5\", \"building_name\": null}, \"shipping\": {\"id\": 5, \"city\": \"Bristol\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"BS1 1AA\", \"house_number\": \"5\", \"building_name\": null}}',NULL,'2025-04-04 12:19:37','2025-04-04 12:19:37'),
	(5,3,4,0,'{\"billing\": {\"id\": 27, \"city\": \"Leeds\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"LS1 1AA\", \"house_number\": \"27\", \"building_name\": \"Building 27\"}, \"shipping\": {\"id\": 27, \"city\": \"Leeds\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"LS1 1AA\", \"house_number\": \"27\", \"building_name\": \"Building 27\"}}',NULL,'2025-04-27 12:19:37','2025-04-27 12:19:37'),
	(6,20,6,0,'{\"billing\": {\"id\": 18, \"city\": \"Cardiff\", \"town\": \"Town 18\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"18\", \"building_name\": \"Building 18\"}, \"shipping\": {\"id\": 18, \"city\": \"Cardiff\", \"town\": \"Town 18\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"18\", \"building_name\": \"Building 18\"}}',NULL,'2025-04-11 12:19:37','2025-04-11 12:19:37'),
	(7,6,3,0,'{\"billing\": {\"id\": 12, \"city\": \"Leeds\", \"town\": \"Town 12\", \"street\": \"Dance Street\", \"postcode\": \"LS1 1AA\", \"house_number\": \"12\", \"building_name\": \"Building 12\"}, \"shipping\": {\"id\": 12, \"city\": \"Leeds\", \"town\": \"Town 12\", \"street\": \"Dance Street\", \"postcode\": \"LS1 1AA\", \"house_number\": \"12\", \"building_name\": \"Building 12\"}}',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,7,2,0,'{\"billing\": {\"id\": 9, \"city\": \"Manchester\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"M1 1AA\", \"house_number\": \"9\", \"building_name\": \"Building 9\"}, \"shipping\": {\"id\": 9, \"city\": \"Manchester\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"M1 1AA\", \"house_number\": \"9\", \"building_name\": \"Building 9\"}}',NULL,'2025-04-13 12:19:37','2025-04-13 12:19:37'),
	(9,5,3,0,'{\"billing\": {\"id\": 3, \"city\": \"Manchester\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"M1 1AA\", \"house_number\": \"3\", \"building_name\": \"Building 3\"}, \"shipping\": {\"id\": 3, \"city\": \"Manchester\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"M1 1AA\", \"house_number\": \"3\", \"building_name\": \"Building 3\"}}',NULL,'2025-04-09 12:19:37','2025-04-09 12:19:37'),
	(10,2,1,0,'{\"billing\": {\"id\": 19, \"city\": \"Manchester\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"M1 1AA\", \"house_number\": \"19\", \"building_name\": null}, \"shipping\": {\"id\": 19, \"city\": \"Manchester\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"M1 1AA\", \"house_number\": \"19\", \"building_name\": null}}',NULL,'2025-04-07 12:19:37','2025-04-07 12:19:37'),
	(11,19,1,0,'{\"billing\": {\"id\": 2, \"city\": \"Belfast\", \"town\": \"Town 2\", \"street\": \"Dance Street\", \"postcode\": \"BT1 1AA\", \"house_number\": \"2\", \"building_name\": null}, \"shipping\": {\"id\": 2, \"city\": \"Belfast\", \"town\": \"Town 2\", \"street\": \"Dance Street\", \"postcode\": \"BT1 1AA\", \"house_number\": \"2\", \"building_name\": null}}',NULL,'2025-04-06 12:19:37','2025-04-06 12:19:37'),
	(12,9,3,0,'{\"billing\": {\"id\": 28, \"city\": \"Glasgow\", \"town\": \"Town 28\", \"street\": \"Dance Street\", \"postcode\": \"G1 1AA\", \"house_number\": \"28\", \"building_name\": null}, \"shipping\": {\"id\": 28, \"city\": \"Glasgow\", \"town\": \"Town 28\", \"street\": \"Dance Street\", \"postcode\": \"G1 1AA\", \"house_number\": \"28\", \"building_name\": null}}',NULL,'2025-04-08 12:19:37','2025-04-08 12:19:37'),
	(13,12,6,0,'{\"billing\": {\"id\": 18, \"city\": \"Cardiff\", \"town\": \"Town 18\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"18\", \"building_name\": \"Building 18\"}, \"shipping\": {\"id\": 18, \"city\": \"Cardiff\", \"town\": \"Town 18\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"18\", \"building_name\": \"Building 18\"}}',NULL,'2025-04-18 12:19:37','2025-04-18 12:19:37'),
	(14,8,2,0,'{\"billing\": {\"id\": 18, \"city\": \"Cardiff\", \"town\": \"Town 18\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"18\", \"building_name\": \"Building 18\"}, \"shipping\": {\"id\": 18, \"city\": \"Cardiff\", \"town\": \"Town 18\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"18\", \"building_name\": \"Building 18\"}}',NULL,'2025-04-22 12:19:37','2025-04-22 12:19:37'),
	(15,17,6,0,'{\"billing\": {\"id\": 27, \"city\": \"Leeds\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"LS1 1AA\", \"house_number\": \"27\", \"building_name\": \"Building 27\"}, \"shipping\": {\"id\": 27, \"city\": \"Leeds\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"LS1 1AA\", \"house_number\": \"27\", \"building_name\": \"Building 27\"}}',NULL,'2025-04-27 12:19:37','2025-04-27 12:19:37'),
	(16,17,5,0,'{\"billing\": {\"id\": 6, \"city\": \"Birmingham\", \"town\": \"Town 6\", \"street\": \"Dance Street\", \"postcode\": \"B1 1AA\", \"house_number\": \"6\", \"building_name\": \"Building 6\"}, \"shipping\": {\"id\": 6, \"city\": \"Birmingham\", \"town\": \"Town 6\", \"street\": \"Dance Street\", \"postcode\": \"B1 1AA\", \"house_number\": \"6\", \"building_name\": \"Building 6\"}}',NULL,'2025-04-16 12:19:37','2025-04-16 12:19:37'),
	(17,2,2,0,'{\"billing\": {\"id\": 8, \"city\": \"Cardiff\", \"town\": \"Town 8\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"8\", \"building_name\": null}, \"shipping\": {\"id\": 8, \"city\": \"Cardiff\", \"town\": \"Town 8\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"8\", \"building_name\": null}}',NULL,'2025-04-06 12:19:37','2025-04-06 12:19:37'),
	(18,15,1,0,'{\"billing\": {\"id\": 26, \"city\": \"Cardiff\", \"town\": \"Town 26\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"26\", \"building_name\": null}, \"shipping\": {\"id\": 26, \"city\": \"Cardiff\", \"town\": \"Town 26\", \"street\": \"Dance Street\", \"postcode\": \"CF10 1AA\", \"house_number\": \"26\", \"building_name\": null}}',NULL,'2025-04-15 12:19:37','2025-04-15 12:19:37'),
	(19,9,5,0,'{\"billing\": {\"id\": 5, \"city\": \"Bristol\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"BS1 1AA\", \"house_number\": \"5\", \"building_name\": null}, \"shipping\": {\"id\": 5, \"city\": \"Bristol\", \"town\": null, \"street\": \"Dance Street\", \"postcode\": \"BS1 1AA\", \"house_number\": \"5\", \"building_name\": null}}',NULL,'2025-04-13 12:19:37','2025-04-13 12:19:37'),
	(20,3,3,0,'{\"billing\": {\"id\": 2, \"city\": \"Belfast\", \"town\": \"Town 2\", \"street\": \"Dance Street\", \"postcode\": \"BT1 1AA\", \"house_number\": \"2\", \"building_name\": null}, \"shipping\": {\"id\": 2, \"city\": \"Belfast\", \"town\": \"Town 2\", \"street\": \"Dance Street\", \"postcode\": \"BT1 1AA\", \"house_number\": \"2\", \"building_name\": null}}',NULL,'2025-04-16 12:19:37','2025-04-16 12:19:37');

/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table payment_methods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payment_methods`;

CREATE TABLE `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;

INSERT INTO `payment_methods` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Credit Card','Pay with Visa, Mastercard, or American Express',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,'Debit Card','Pay directly from your bank account',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,'PayPal','Pay securely with your PayPal account',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,'Apple Pay','Quick and secure payment with Apple devices',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,'Google Pay','Fast checkout with Google Pay',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,'Bank Transfer','Direct bank transfer to seller account',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table payout_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payout_transactions`;

CREATE TABLE `payout_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `commission` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payout_transactions_transaction_id_foreign` (`transaction_id`),
  KEY `payout_transactions_seller_id_foreign` (`seller_id`),
  CONSTRAINT `payout_transactions_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payout_transactions_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table personal_access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `personal_access_tokens`;

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



# Dump of table product_colours
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_colours`;

CREATE TABLE `product_colours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `colour_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_colours_product_id_foreign` (`product_id`),
  KEY `product_colours_colour_id_foreign` (`colour_id`),
  CONSTRAINT `product_colours_colour_id_foreign` FOREIGN KEY (`colour_id`) REFERENCES `colours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_colours_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `product_colours` WRITE;
/*!40000 ALTER TABLE `product_colours` DISABLE KEYS */;

INSERT INTO `product_colours` (`id`, `product_id`, `colour_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,1,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,2,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,2,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,2,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,3,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,4,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,4,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,5,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,5,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,5,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,6,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,7,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,8,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,8,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,8,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,9,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,9,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,10,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,10,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,10,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,11,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,12,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,12,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,13,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,13,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,14,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,15,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,15,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,16,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(31,17,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(32,17,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(33,17,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(34,18,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(35,19,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(36,19,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(37,19,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,20,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,20,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,20,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,21,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,21,13,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(43,22,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(44,23,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(45,23,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(46,23,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(47,24,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(48,24,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(49,24,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(50,25,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(51,25,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(52,26,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(53,26,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(54,27,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(55,28,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(56,28,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(57,29,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(58,29,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(59,29,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(60,30,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(61,31,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(62,31,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(63,32,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(64,33,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(65,34,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(66,35,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(67,35,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(68,36,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(69,36,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(70,37,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(71,38,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(72,39,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(73,39,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(74,40,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(75,40,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(76,41,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(77,41,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(78,42,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(79,43,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(80,43,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(81,44,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(82,45,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(83,45,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(84,45,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(85,46,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(86,47,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(87,48,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(88,48,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(89,49,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(90,49,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(91,49,14,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(92,50,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `product_colours` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table product_fulfillment_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_fulfillment_options`;

CREATE TABLE `product_fulfillment_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fulfillment_option_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_fulfillment_options_fulfillment_option_id_foreign` (`fulfillment_option_id`),
  KEY `product_fulfillment_options_product_id_foreign` (`product_id`),
  CONSTRAINT `product_fulfillment_options_fulfillment_option_id_foreign` FOREIGN KEY (`fulfillment_option_id`) REFERENCES `fulfillment_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_fulfillment_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `product_fulfillment_options` WRITE;
/*!40000 ALTER TABLE `product_fulfillment_options` DISABLE KEYS */;

INSERT INTO `product_fulfillment_options` (`id`, `fulfillment_option_id`, `product_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,2,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,1,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,2,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,2,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,1,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,2,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,1,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,2,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,1,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,2,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,1,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,1,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,2,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,1,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,2,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,1,11,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,2,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,1,12,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,2,13,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,1,13,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,1,14,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,2,14,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,1,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,2,15,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,1,16,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,1,17,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,2,18,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,2,19,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,1,19,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(31,1,20,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(32,2,20,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(33,1,21,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(34,2,22,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(35,1,22,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(36,1,23,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(37,1,24,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,2,25,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,1,25,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,1,26,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,2,26,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,2,27,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(43,2,28,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(44,1,28,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(45,1,29,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(46,2,30,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(47,2,31,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(48,1,31,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(49,1,32,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(50,2,33,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(51,1,34,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(52,2,35,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(53,1,35,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(54,2,36,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(55,1,36,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(56,1,37,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(57,2,37,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(58,2,38,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(59,2,39,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(60,2,40,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(61,1,41,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(62,2,42,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(63,2,43,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(64,1,43,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(65,2,44,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(66,2,45,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(67,1,45,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(68,2,46,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(69,1,47,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(70,2,47,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(71,1,48,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(72,1,49,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(73,2,49,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(74,2,50,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(75,1,50,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `product_fulfillment_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table product_reviews
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_reviews`;

CREATE TABLE `product_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `rating` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_user_id_foreign` (`user_id`),
  KEY `product_reviews_product_id_foreign` (`product_id`),
  CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `rating`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,17,24,4.2,'Dormouse, and repeated her question. \'Why did you begin?\' The Hatter was the Duchess\'s cook. She carried the pepper-box in her life before, and she walked down the hall. After a while she was about.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(2,18,43,4.7,'Alice, so please your Majesty,\' said the Mouse. \'--I proceed. \"Edwin and Morcar, the earls of Mercia and Northumbria--\"\' \'Ugh!\' said the Duchess. An invitation from the change: and Alice was soon.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(3,4,14,4.1,'Queen\'s hedgehog just now, only it ran away when it had come back again, and did not wish to offend the Dormouse sulkily remarked, \'If you didn\'t like cats.\' \'Not like cats!\' cried the Mouse.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(4,9,12,4.6,'Dodo said, \'EVERYBODY has won, and all would change (she knew) to the end of his great wig.\' The judge, by the hedge!\' then silence, and then keep tight hold of anything, but she did so, very.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(5,22,26,2,'Queen said severely \'Who is it directed to?\' said the Dormouse; \'--well in.\' This answer so confused poor Alice, and she tried to open it; but, as the rest of my life.\' \'You are not attending!\' said.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(6,15,44,4.3,'King had said that day. \'No, no!\' said the Queen, \'Really, my dear, and that if something wasn\'t done about it in her lessons in the middle. Alice kept her eyes immediately met those of a globe of.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(7,18,15,4.8,'He looked at Alice. \'It goes on, you know,\' the Hatter said, tossing his head contemptuously. \'I dare say you\'re wondering why I don\'t care which happens!\' She ate a little pattering of feet on the.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(8,14,2,2.1,'Don\'t let him know she liked them best, For this must ever be A secret, kept from all the things get used to it as to go on. \'And so these three little sisters,\' the Dormouse went on, \'you throw.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(9,19,7,3,'I suppose it were nine o\'clock in the pool, and the small ones choked and had to fall a long sleep you\'ve had!\' \'Oh, I\'ve had such a capital one for catching mice--oh, I beg your pardon,\' said Alice.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(10,16,8,4.2,'Alice\'s elbow was pressed so closely against her foot, that there ought! And when I find a number of bathing machines in the pictures of him), while the Mock Turtle Soup is made from,\' said the Cat.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(11,26,9,2.7,'Alice, very earnestly. \'I\'ve had nothing yet,\' Alice replied in a very curious to see what I like\"!\' \'You might just as well. The twelve jurors were writing down \'stupid things!\' on their hands and.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(12,23,41,3.6,'March Hare went \'Sh! sh!\' and the bright eager eyes were nearly out of that is--\"Birds of a water-well,\' said the Cat, and vanished again. Alice waited a little, \'From the Queen. \'Sentence.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(13,1,26,4.1,'Alice could hardly hear the words:-- \'I speak severely to my right size again; and the Queen till she had never before seen a good deal worse off than before, as the other.\' As soon as it was out of.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(14,5,9,4.1,'Alice had learnt several things of this sort of circle, (\'the exact shape doesn\'t matter,\' it said,) and then at the door--I do wish I had to sing this:-- \'Beautiful Soup, so rich and green, Waiting.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(15,6,45,2.7,'King turned pale, and shut his note-book hastily. \'Consider your verdict,\' the King said, turning to the Knave of Hearts, he stole those tarts, And took them quite away!\' \'Consider your verdict,\' he.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(16,9,12,2.6,'I say again!\' repeated the Pigeon, but in a hot tureen! Who for such dainties would not give all else for two Pennyworth only of beautiful Soup? Pennyworth only of beautiful Soup? Pennyworth only of.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(17,9,28,1.7,'She had quite a large crowd collected round it: there were any tears. No, there were three gardeners who were all ornamented with hearts. Next came an angry tone, \'Why, Mary Ann, and be turned out.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(18,18,13,3.1,'OURS they had been broken to pieces. \'Please, then,\' said Alice, a little bird as soon as it spoke (it was Bill, the Lizard) could not swim. He sent them word I had it written up somewhere.\' Down.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(19,18,41,2.4,'I\'m pleased, and wag my tail when it\'s angry, and wags its tail about in the flurry of the crowd below, and there she saw maps and pictures hung upon pegs. She took down a jar from one foot to the.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(20,7,15,4.6,'Dinah, and saying \"Come up again, dear!\" I shall remember it in large letters. It was high time to be rude, so she went on. \'Would you like the look of things at all, as the White Rabbit, \'but it.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(21,6,36,2.1,'March Hare. \'Sixteenth,\' added the Gryphon, \'you first form into a graceful zigzag, and was going to do it.\' (And, as you go on? It\'s by far the most curious thing I know. Silence all round, if you.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(22,4,35,2.6,'Alice again, for really I\'m quite tired of this. I vote the young Crab, a little nervous about this; \'for it might end, you know,\' the Hatter began, in rather a hard word, I will just explain to you.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(23,1,43,1.4,'Queen. \'Can you play croquet?\' The soldiers were silent, and looked at each other for some time with the edge of her sister, as well wait, as she spoke. Alice did not get dry very soon. \'Ahem!\' said.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(24,19,44,1.2,'Alice had got so much at this, but at last turned sulky, and would only say, \'I am older than I am now? That\'ll be a very decided tone: \'tell her something about the games now.\' CHAPTER X. The.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(25,11,38,4.5,'Alice\'s, and they went up to Alice, and she put one arm out of its little eyes, but it said nothing. \'Perhaps it hasn\'t one,\' Alice ventured to remark. \'Tut, tut, child!\' said the Hatter. \'Nor I,\'.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(26,26,9,4.1,'I shan\'t! YOU do it!--That I won\'t, then!--Bill\'s to go down--Here, Bill! the master says you\'re to go on. \'And so these three little sisters--they were learning to draw, you know--\' \'But, it goes.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(27,26,21,2.5,'I grow at a reasonable pace,\' said the King. \'When did you manage to do it.\' (And, as you say it.\' \'That\'s nothing to do: once or twice, and shook itself. Then it got down off the cake. * * * * * *.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(28,14,24,3.5,'Alice and all the rest, Between yourself and me.\' \'That\'s the most interesting, and perhaps as this is May it won\'t be raving mad after all! I almost wish I\'d gone to see its meaning. \'And just as.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(29,11,39,4.1,'It\'s the most confusing thing I know. Silence all round, if you only kept on good terms with him, he\'d do almost anything you liked with the words don\'t FIT you,\' said the King. The White Rabbit.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(30,11,1,2.8,'King; \'and don\'t look at the frontispiece if you please! \"William the Conqueror, whose cause was favoured by the time she had made out the proper way of expecting nothing but the three gardeners at.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(31,10,38,1.4,'I shan\'t grow any more--As it is, I suppose?\' said Alice. \'Then it ought to be said. At last the Mock Turtle: \'nine the next, and so on; then, when you\'ve cleared all the while, and fighting for the.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(32,23,37,3.5,'Duck: \'it\'s generally a frog or a worm. The question is, what?\' The great question is, what?\' The great question certainly was, what? Alice looked all round the neck of the Mock Turtle. \'Seals.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(33,6,7,3.2,'Alice, \'I\'ve often seen them at dinn--\' she checked herself hastily, and said \'No, never\') \'--so you can find them.\' As she said to the general conclusion, that wherever you go on? It\'s by far the.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(34,2,10,2.3,'Bill, I fancy--Who\'s to go among mad people,\' Alice remarked. \'Oh, you foolish Alice!\' she answered herself. \'How can you learn lessons in here? Why, there\'s hardly enough of me left to make out.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(35,4,14,3.3,'Queen. \'I never went to school every day--\' \'I\'VE been to her, And mentioned me to introduce it.\' \'I don\'t think--\' \'Then you should say \"With what porpoise?\"\' \'Don\'t you mean \"purpose\"?\' said.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(36,16,24,2.6,'Heads below!\' (a loud crash)--\'Now, who did that?--It was Bill, I fancy--Who\'s to go down--Here, Bill! the master says you\'re to go through next walking about at the thought that SOMEBODY ought to.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(37,23,3,1.5,'I\'ll just see what this bottle does. I do so like that curious song about the same words as before, \'and things are worse than ever,\' thought the poor little thing was to eat or drink something or.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(38,15,11,3.4,'KING AND QUEEN OF HEARTS. Alice was a very melancholy voice. \'Repeat, \"YOU ARE OLD, FATHER WILLIAM,\' to the Dormouse, and repeated her question. \'Why did you ever see you again, you dear old thing!\'.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(39,5,24,1.3,'CHAPTER V. Advice from a bottle marked \'poison,\' it is to find it out, we should all have our heads cut off, you know. Please, Ma\'am, is this New Zealand or Australia?\' (and she tried to open them.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(40,13,6,3.8,'Alice began to cry again, for really I\'m quite tired of swimming about here, O Mouse!\' (Alice thought this must ever be A secret, kept from all the time it vanished quite slowly, beginning with the.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(41,22,22,3.2,'I\'d only been the right size, that it felt quite strange at first; but she did not like to be executed for having missed their turns, and she jumped up in spite of all her coaxing. Hardly knowing.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(42,6,5,3.3,'Alice thoughtfully: \'but then--I shouldn\'t be hungry for it, she found this a very fine day!\' said a whiting to a farmer, you know, upon the other birds tittered audibly. \'What I was going to turn.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(43,10,47,4.3,'As she said to Alice. \'Only a thimble,\' said Alice indignantly. \'Ah! then yours wasn\'t a bit of mushroom, and raised herself to about two feet high: even then she noticed a curious feeling!\' said.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(44,2,28,1.2,'Knave. The Knave did so, very carefully, remarking, \'I really must be a lesson to you never had fits, my dear, I think?\' he said to live. \'I\'ve seen a good deal until she made some tarts, All on a.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(45,21,12,3.9,'Dormouse denied nothing, being fast asleep. \'After that,\' continued the Gryphon. \'We can do without lobsters, you know. Come on!\' So they went on all the players, except the Lizard, who seemed to.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(46,3,20,1.4,'Alice said with a deep voice, \'are done with blacking, I believe.\' \'Boots and shoes under the sea,\' the Gryphon remarked: \'because they lessen from day to day.\' This was such a simple question,\'.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(47,24,43,2.1,'Alice; \'you needn\'t be so kind,\' Alice replied, so eagerly that the reason they\'re called lessons,\' the Gryphon only answered \'Come on!\' and ran till she too began dreaming after a few minutes she.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(48,17,44,1.4,'Do come back and finish your story!\' Alice called after her. \'I\'ve something important to say!\' This sounded promising, certainly: Alice turned and came back again. \'Keep your temper,\' said the Mock.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(49,19,3,2.2,'I think you\'d better finish the story for yourself.\' \'No, please go on!\' Alice said very politely, feeling quite pleased to have him with them,\' the Mock Turtle is.\' \'It\'s the thing yourself, some.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(50,20,46,2.6,'Time as well wait, as she leant against a buttercup to rest herself, and shouted out, \'You\'d better not talk!\' said Five. \'I heard the Queen\'s absence, and were quite dry again, the cook till his.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38');

/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table product_shipping_service_providers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_shipping_service_providers`;

CREATE TABLE `product_shipping_service_providers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `service_provider_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_shipping_service_providers_product_id_foreign` (`product_id`),
  KEY `product_shipping_service_providers_service_provider_id_foreign` (`service_provider_id`),
  CONSTRAINT `product_shipping_service_providers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_shipping_service_providers_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `shipping_service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table product_sizes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_sizes`;

CREATE TABLE `product_sizes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `size_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_sizes_product_id_foreign` (`product_id`),
  KEY `product_sizes_size_id_foreign` (`size_id`),
  CONSTRAINT `product_sizes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_sizes_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `product_sizes` WRITE;
/*!40000 ALTER TABLE `product_sizes` DISABLE KEYS */;

INSERT INTO `product_sizes` (`id`, `product_id`, `size_id`, `quantity`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,3,7,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(2,1,1,10,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(3,1,6,10,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(4,2,6,9,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(5,2,3,8,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(6,2,2,5,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(7,3,2,4,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(8,3,3,10,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(9,3,1,3,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(10,4,3,5,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(11,5,6,8,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(12,5,5,1,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(13,5,7,8,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(14,6,5,6,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(15,6,6,10,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(16,6,7,9,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(17,7,4,7,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(18,8,5,7,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(19,8,3,8,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(20,8,4,10,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(21,8,6,3,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(22,9,5,9,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(23,9,4,6,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(24,9,1,3,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(25,10,5,5,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(26,11,1,5,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(27,11,5,5,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(28,11,4,1,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(29,11,6,4,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(30,12,7,6,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(31,12,5,6,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(32,12,3,4,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(33,12,1,3,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(34,13,2,2,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(35,14,2,8,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(36,15,4,3,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(37,16,4,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,16,2,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,17,3,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,17,7,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,17,1,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,17,4,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(43,18,4,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(44,19,6,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(45,19,7,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(46,19,5,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(47,20,6,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(48,21,5,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(49,22,1,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(50,22,5,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(51,22,6,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(52,23,2,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(53,23,7,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(54,24,6,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(55,24,4,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(56,25,7,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(57,25,5,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(58,25,3,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(59,25,4,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(60,26,2,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(61,26,3,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(62,27,2,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(63,27,4,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(64,27,6,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(65,27,1,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(66,28,1,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(67,28,6,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(68,29,3,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(69,29,5,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(70,29,7,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(71,30,6,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(72,30,4,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(73,30,3,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(74,30,2,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(75,31,1,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(76,32,7,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(77,33,7,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(78,33,2,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(79,33,3,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(80,34,5,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(81,34,6,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(82,34,3,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(83,34,2,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(84,35,2,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(85,35,1,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(86,35,5,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(87,36,7,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(88,36,4,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(89,36,6,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(90,36,2,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(91,37,2,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(92,37,3,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(93,37,1,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(94,37,6,6,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(95,38,6,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(96,38,3,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(97,39,3,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(98,40,7,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(99,40,4,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(100,41,6,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(101,41,4,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(102,41,3,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(103,41,5,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(104,42,3,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(105,43,5,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(106,43,1,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(107,43,6,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(108,43,3,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(109,44,3,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(110,44,7,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(111,44,5,2,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(112,45,4,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(113,45,5,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(114,46,7,1,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(115,46,3,5,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(116,47,1,3,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(117,48,6,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(118,48,5,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(119,49,2,10,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(120,49,3,7,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(121,49,5,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(122,49,6,8,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(123,50,1,4,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(124,50,6,9,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `product_sizes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `condition_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('sale','hire') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_user_id_foreign` (`user_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_condition_id_foreign` (`condition_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_condition_id_foreign` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`id`, `user_id`, `category_id`, `condition_id`, `brand_id`, `name`, `description`, `price`, `is_featured`, `type`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,23,8,1,9,'Bloch Dance Skirt 6ded','Lightweight and breathable material.',38.7,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(2,7,13,3,14,'So Danca Jazz Shoes Yz04','High-quality material that will last for years.',115.74,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(3,24,18,2,4,'Reebok Tap Shoes slqh','Stylish and functional for all your dance needs.',19.18,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(4,14,4,3,1,'Nike Ballet Tutu SEID','Elegant design for a professional look.',65.9,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(5,13,8,3,11,'Freed of London Pointe Shoes PwLc','Lightweight and breathable material.',29.09,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(6,7,14,3,3,'Puma Tap Shoes Qo4b','Versatile piece for various dance styles.',34.49,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(7,8,2,1,3,'Puma Practice Wear Set iPrV','Lightweight and breathable material.',91.14,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(8,10,15,1,4,'Reebok Dance Shorts HGmV','Great for practice sessions and classes.',170.69,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(9,19,16,1,13,'Gaynor Minden Dance Bag GgEh','Stylish and functional for all your dance needs.',53.01,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(10,15,1,1,2,'Adidas Performance Costume PVXB','Lightweight and breathable material.',30.84,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(11,10,7,1,6,'Lululemon Pointe Shoes qyVI','Ideal for performances and competitions.',31.27,1,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(12,20,17,3,10,'Grishko Dance Top tSIZ','Versatile piece for various dance styles.',33.3,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(13,2,6,2,3,'Puma Dance Leotard QQcS','Comfortable and durable for long practice sessions.',46.34,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(14,22,5,3,13,'Gaynor Minden Dance Tights EDie','Designed with dancers in mind for maximum comfort and flexibility.',140.23,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(15,5,4,1,1,'Nike Dance Skirt 4buD','Comfortable and durable for long practice sessions.',182.06,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(16,11,15,2,13,'Gaynor Minden Ballet Shoes oD5A','High-quality material that will last for years.',20.5,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(17,25,5,1,12,'Russian Pointe Tap Shoes 4EtR','Great for practice sessions and classes.',33.05,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(18,24,16,3,9,'Bloch Pointe Shoes j9CI','Perfect for beginners and professionals alike.',131.2,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(19,21,4,2,8,'Capezio Practice Wear Set Vr35','Comfortable and durable for long practice sessions.',107.94,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(20,19,11,2,4,'Reebok Dance Shorts 1A5z','Perfect for beginners and professionals alike.',134.27,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(21,19,14,3,15,'Sansha Dance Skirt rh5O','Designed with dancers in mind for maximum comfort and flexibility.',24.09,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(22,22,6,2,14,'So Danca Jazz Shoes EXFn','Great for practice sessions and classes.',191.16,1,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(23,7,15,1,7,'Fabletics Jazz Shoes uNIY','Stylish and functional for all your dance needs.',177.94,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(24,5,1,1,3,'Puma Tap Shoes pR3X','Stylish and functional for all your dance needs.',73.07,1,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(25,15,11,1,3,'Puma Dance Skirt qQtc','Perfect for beginners and professionals alike.',25.29,1,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(26,8,13,2,6,'Lululemon Pointe Shoes 9g8z','Great for practice sessions and classes.',71,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(27,22,6,3,6,'Lululemon Ballet Shoes NLY0','Designed with dancers in mind for maximum comfort and flexibility.',28.85,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(28,23,14,2,5,'Under Armour Ballet Shoes uoiE','Lightweight and breathable material.',17.52,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(29,13,6,2,8,'Capezio Leg Warmers H0do','Great for practice sessions and classes.',110.35,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(30,24,2,3,7,'Fabletics Dance Tights 8LPZ','Perfect for beginners and professionals alike.',169.84,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(31,22,16,2,6,'Lululemon Dance Skirt xTg8','Versatile piece for various dance styles.',179.46,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(32,7,3,3,3,'Puma Ballet Shoes NUF7','Stylish and functional for all your dance needs.',143.92,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(33,5,9,3,3,'Puma Pointe Shoes w7YM','Great for practice sessions and classes.',127.78,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(34,2,9,3,14,'So Danca Performance Costume 9NPP','Great for practice sessions and classes.',92.83,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(35,26,3,3,8,'Capezio Dance Leotard oXhI','Elegant design for a professional look.',90.6,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(36,26,14,2,5,'Under Armour Dance Top eCAc','Great for practice sessions and classes.',155.02,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(37,24,3,1,9,'Bloch Jazz Shoes wDkL','High-quality material that will last for years.',141.97,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(38,21,13,3,5,'Under Armour Dance Tights t9oL','Perfect for beginners and professionals alike.',73.82,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(39,20,12,1,15,'Sansha Ballet Tutu idsB','Versatile piece for various dance styles.',39.22,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(40,1,1,1,2,'Adidas Dance Shorts E3Ba','Comfortable and durable for long practice sessions.',21.96,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(41,23,2,3,13,'Gaynor Minden Dance Shorts KRng','Lightweight and breathable material.',192.11,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(42,24,10,1,10,'Grishko Warm-up Booties hBcx','Stylish and functional for all your dance needs.',187.86,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(43,7,9,1,7,'Fabletics Ballet Shoes bPwI','High-quality material that will last for years.',179.73,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(44,6,4,2,7,'Fabletics Tap Shoes RIgj','Designed with dancers in mind for maximum comfort and flexibility.',21.92,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(45,10,14,2,14,'So Danca Dance Tights xWRA','Designed with dancers in mind for maximum comfort and flexibility.',198.6,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(46,3,4,1,2,'Adidas Dance Bag HMc4','Perfect for beginners and professionals alike.',16.68,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(47,1,3,3,8,'Capezio Warm-up Booties TK2y','Great for practice sessions and classes.',107.12,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(48,3,12,2,9,'Bloch Performance Costume RIp0','Lightweight and breathable material.',159.75,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(49,21,8,3,10,'Grishko Dance Skirt lKfp','Perfect for beginners and professionals alike.',100.52,0,'hire',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(50,23,12,1,4,'Reebok Dance Tights AIoJ','Perfect for beginners and professionals alike.',131.65,0,'sale',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36');

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table refund_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `refund_transactions`;

CREATE TABLE `refund_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `type` enum('full','partial') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refund_transactions_transaction_id_foreign` (`transaction_id`),
  KEY `refund_transactions_user_id_foreign` (`user_id`),
  KEY `refund_transactions_order_id_foreign` (`order_id`),
  CONSTRAINT `refund_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `refund_transactions_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `refund_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table seller_order_statuses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `seller_order_statuses`;

CREATE TABLE `seller_order_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seller_order_id` bigint unsigned NOT NULL,
  `order_status_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table seller_orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `seller_orders`;

CREATE TABLE `seller_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `seller_id` bigint unsigned NOT NULL,
  `amount` double NOT NULL,
  `transferred_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seller_orders_seller_id_foreign` (`seller_id`),
  KEY `seller_orders_order_id_foreign` (`order_id`),
  CONSTRAINT `seller_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `seller_orders_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `seller_orders` WRITE;
/*!40000 ALTER TABLE `seller_orders` DISABLE KEYS */;

INSERT INTO `seller_orders` (`id`, `order_id`, `seller_id`, `amount`, `transferred_at`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,10,512.07,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(2,1,24,131.2,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(3,2,23,261.95,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(4,2,5,383.34,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(5,3,19,53.01,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(6,3,22,191.16,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(7,3,24,281.58,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(8,4,24,617.07,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(9,4,19,217.19,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(10,4,23,131.65,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(11,5,19,159.03,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(12,5,2,221.18,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(13,6,22,382.32,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(14,6,19,268.54,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(15,6,7,589.02,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(16,7,15,162.09,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(17,7,8,328.08,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(18,8,24,149.87,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(19,8,1,335.84,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(20,8,21,203.72,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(21,9,19,361.92,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(22,9,15,157.95,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(23,9,21,363.48,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(24,10,10,170.69,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(25,10,5,383.34,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(26,11,19,268.54,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(27,11,21,73.82,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(28,12,22,573.48,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(29,13,2,185.66,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(30,14,26,181.2,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(31,14,23,785.85,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(32,15,7,287.84,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(33,16,20,117.66,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(34,16,23,488.98,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(35,16,10,210.14,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(36,17,20,111.84,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(37,17,7,637.38,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(38,18,24,141.97,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(39,18,19,402.81,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(40,18,20,78.44,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(41,19,13,69.08,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37'),
	(42,20,19,106.02,NULL,NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `seller_orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shipping_service_providers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shipping_service_providers`;

CREATE TABLE `shipping_service_providers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `shipping_service_providers` WRITE;
/*!40000 ALTER TABLE `shipping_service_providers` DISABLE KEYS */;

INSERT INTO `shipping_service_providers` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Evri','Evri is a global shipping company that provides shipping services to customers.',NULL,'2025-05-01 12:19:37','2025-05-01 12:19:37');

/*!40000 ALTER TABLE `shipping_service_providers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sizes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sizes`;

CREATE TABLE `sizes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `sizes` WRITE;
/*!40000 ALTER TABLE `sizes` DISABLE KEYS */;

INSERT INTO `sizes` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'XXS',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(2,'XS',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(3,'S',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(4,'M',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(5,'L',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(6,'XL',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(7,'XXL',NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29');

/*!40000 ALTER TABLE `sizes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stripe_payment_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `type` enum('order','payout','refund') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_addresses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_addresses`;

CREATE TABLE `user_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `address_id` bigint unsigned NOT NULL,
  `type` enum('shipping','billing') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_addresses_user_id_foreign` (`user_id`),
  KEY `user_addresses_address_id_foreign` (`address_id`),
  CONSTRAINT `user_addresses_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `user_addresses` WRITE;
/*!40000 ALTER TABLE `user_addresses` DISABLE KEYS */;

INSERT INTO `user_addresses` (`id`, `user_id`, `address_id`, `type`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,26,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(2,1,15,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(3,2,14,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(4,2,25,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(5,3,25,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(6,3,10,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(7,4,27,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(8,5,14,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(9,6,15,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(10,7,2,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(11,8,21,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(12,9,20,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(13,10,15,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(14,10,1,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(15,11,2,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(16,11,5,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(17,12,29,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(18,13,7,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(19,14,4,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(20,15,23,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(21,15,27,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(22,16,11,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(23,17,6,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(24,18,9,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(25,18,25,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(26,19,8,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(27,20,3,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(28,21,19,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(29,21,21,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(30,22,23,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(31,23,24,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(32,23,9,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(33,24,22,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(34,25,29,'billing',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(35,25,27,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(36,26,4,'shipping',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36');

/*!40000 ALTER TABLE `user_addresses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_reviews
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_reviews`;

CREATE TABLE `user_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` double NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_reviews_seller_id_foreign` (`seller_id`),
  KEY `user_reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `user_reviews_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `user_reviews` WRITE;
/*!40000 ALTER TABLE `user_reviews` DISABLE KEYS */;

INSERT INTO `user_reviews` (`id`, `seller_id`, `user_id`, `rating`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,16,13,3,'Item arrived earlier than expected.',NULL,'2025-04-26 12:19:38','2025-04-26 12:19:38'),
	(2,18,4,5,'Excellent service, highly recommend!',NULL,'2025-04-28 12:19:38','2025-04-28 12:19:38'),
	(3,4,14,3,'Excellent service, highly recommend!',NULL,'2025-04-01 12:19:38','2025-04-01 12:19:38'),
	(4,14,21,5,'Smooth transaction from start to finish.',NULL,'2025-04-04 12:19:38','2025-04-04 12:19:38'),
	(5,17,1,4,'Item arrived earlier than expected.',NULL,'2025-04-22 12:19:38','2025-04-22 12:19:38'),
	(6,10,20,5,'Great seller, item as described!',NULL,'2025-04-12 12:19:38','2025-04-12 12:19:38'),
	(7,14,13,4,'Would definitely buy from again!',NULL,'2025-04-23 12:19:38','2025-04-23 12:19:38'),
	(8,7,24,3,'Excellent service, highly recommend!',NULL,'2025-04-11 12:19:38','2025-04-11 12:19:38'),
	(9,2,14,3,'Excellent service, highly recommend!',NULL,'2025-04-04 12:19:38','2025-04-04 12:19:38'),
	(10,2,17,4,'Item arrived earlier than expected.',NULL,'2025-04-21 12:19:38','2025-04-21 12:19:38'),
	(11,3,15,5,'The item was exactly what I needed.',NULL,'2025-04-23 12:19:38','2025-04-23 12:19:38'),
	(12,6,23,5,'Smooth transaction from start to finish.',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(13,16,1,3,'The item was exactly what I needed.',NULL,'2025-04-15 12:19:38','2025-04-15 12:19:38'),
	(14,6,26,3,'Would definitely buy from again!',NULL,'2025-05-01 12:19:38','2025-05-01 12:19:38'),
	(15,1,15,4,'Fast shipping and good communication.',NULL,'2025-04-28 12:19:38','2025-04-28 12:19:38'),
	(16,11,24,4,'Item was in perfect condition.',NULL,'2025-04-06 12:19:38','2025-04-06 12:19:38'),
	(17,7,3,3,'The item was exactly what I needed.',NULL,'2025-04-23 12:19:38','2025-04-23 12:19:38'),
	(18,1,3,5,'Smooth transaction from start to finish.',NULL,'2025-04-12 12:19:38','2025-04-12 12:19:38'),
	(19,18,16,4,'Item was in perfect condition.',NULL,'2025-04-02 12:19:38','2025-04-02 12:19:38'),
	(20,9,14,5,'Item was in perfect condition.',NULL,'2025-04-26 12:19:38','2025-04-26 12:19:38');

/*!40000 ALTER TABLE `user_reviews` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_schools
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_schools`;

CREATE TABLE `user_schools` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_schools_user_id_foreign` (`user_id`),
  CONSTRAINT `user_schools_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `user_schools` WRITE;
/*!40000 ALTER TABLE `user_schools` DISABLE KEYS */;

INSERT INTO `user_schools` (`id`, `user_id`, `name`, `website`, `description`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,2,'Dance School 1','https://www.danceschool1.com','Welcome to Dance School 1! We offer a variety of dance classes for all ages and skill levels. Our experienced instructors are dedicated to helping you achieve your dance goals.',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(2,3,'Dance School 2','https://www.danceschool2.com','Welcome to Dance School 2! We offer a variety of dance classes for all ages and skill levels. Our experienced instructors are dedicated to helping you achieve your dance goals.',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(3,4,'Dance School 3','https://www.danceschool3.com','Welcome to Dance School 3! We offer a variety of dance classes for all ages and skill levels. Our experienced instructors are dedicated to helping you achieve your dance goals.',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(4,5,'Dance School 4','https://www.danceschool4.com','Welcome to Dance School 4! We offer a variety of dance classes for all ages and skill levels. Our experienced instructors are dedicated to helping you achieve your dance goals.',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(5,6,'Dance School 5','https://www.danceschool5.com','Welcome to Dance School 5! We offer a variety of dance classes for all ages and skill levels. Our experienced instructors are dedicated to helping you achieve your dance goals.',NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36');

/*!40000 ALTER TABLE `user_schools` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '1',
  `remember_me` tinyint(1) NOT NULL DEFAULT '0',
  `remember_me_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_password_token` text COLLATE utf8mb4_unicode_ci,
  `reset_password_token_at` datetime DEFAULT NULL,
  `type` enum('individual','school') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','blocked') COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_seller_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `email_verified`, `remember_me`, `remember_me_at`, `remember_token`, `reset_password_token`, `reset_password_token_at`, `type`, `status`, `stripe_seller_id`, `stripe_customer_id`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,'Admin User','test@test.test','$2y$12$l2tobBWOXPqprbvcVKNwkOTcW8AQaW2Z3DLvOai/4dugbln8eMNQO',NULL,NULL,NULL,1,0,NULL,'X4ZOgkHndA',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:29','2025-05-01 12:19:29'),
	(2,'Dance School 1','school1@example.com','$2y$12$k2lb9f/YTlhHTmxh.6yXK.J642lVCvi5bK3OO5N7Xm3MuY1Lo6a2.',NULL,NULL,NULL,1,0,NULL,'SxlwevegBR',NULL,NULL,'school','active',NULL,NULL,NULL,'2025-05-01 12:19:30','2025-05-01 12:19:30'),
	(3,'Dance School 2','school2@example.com','$2y$12$ODNTO7jgmegi3jge83WJC.dw0GuJpXzcvDqWFV.0C6Mjl20LU5hay',NULL,NULL,NULL,1,0,NULL,'1v4BNvbMGL',NULL,NULL,'school','active',NULL,NULL,NULL,'2025-05-01 12:19:30','2025-05-01 12:19:30'),
	(4,'Dance School 3','school3@example.com','$2y$12$9tbKbdhlJ/Ppls7lC0Z0lOlmcI33FpnSjlcd6c.eL2RBFueW1QmMi',NULL,NULL,NULL,1,0,NULL,'gHoAUM1qct',NULL,NULL,'school','active',NULL,NULL,NULL,'2025-05-01 12:19:30','2025-05-01 12:19:30'),
	(5,'Dance School 4','school4@example.com','$2y$12$cs6qpcci47H8qJnQ9YhzPeVbKCbLSIpPdbi2eYqXykGMSHJWYTeEe',NULL,NULL,NULL,1,0,NULL,'ieL1Mypqif',NULL,NULL,'school','active',NULL,NULL,NULL,'2025-05-01 12:19:31','2025-05-01 12:19:31'),
	(6,'Dance School 5','school5@example.com','$2y$12$OEInEhhut8/BSjl/0R.2leGSZgKpZhG/1wPuP0SGPGqr34i4cF90C',NULL,NULL,NULL,1,0,NULL,'aZ6Cqwyksl',NULL,NULL,'school','active',NULL,NULL,NULL,'2025-05-01 12:19:31','2025-05-01 12:19:31'),
	(7,'User 1','user1@example.com','$2y$12$/HGO4.EB.SmpIImTN2ins.y4mepD3gb5EKKF0xOsW5eiy0itjFTXu',NULL,NULL,NULL,1,0,NULL,'Kvncjv6vBp',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:31','2025-05-01 12:19:31'),
	(8,'User 2','user2@example.com','$2y$12$pegm0Qj7SXQUdWr06AanYusGSgmu4RK.3xj2VZIWByvoQungWJgWm',NULL,NULL,NULL,1,0,NULL,'S37YnbPXYS',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:31','2025-05-01 12:19:31'),
	(9,'User 3','user3@example.com','$2y$12$NRKTIlxOVYsGEgMRHS9oCO.0W6LUAWAdDT5NGRH/j9.sQKosW0InC',NULL,NULL,NULL,1,0,NULL,'Fg5TVJNb6e',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:32','2025-05-01 12:19:32'),
	(10,'User 4','user4@example.com','$2y$12$GlUDxJ.orWlIaIZJJO4LkO3Jl3/6zlAut94Q4obcUQ.n.OPReonWa',NULL,NULL,NULL,1,0,NULL,'LyjdxcaHNA',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:32','2025-05-01 12:19:32'),
	(11,'User 5','user5@example.com','$2y$12$oFGN7faF3vn/2QcvC0PwruMPrXB.W9dfr30gSO8gNfVtzCd2gLsES',NULL,NULL,NULL,1,0,NULL,'BFgvbt4qPr',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:32','2025-05-01 12:19:32'),
	(12,'User 6','user6@example.com','$2y$12$rPOP7b3IV.yqZG2AfdJPiuShvuyOd06GlzhVnU8xAMDXY9XLmWT3q',NULL,NULL,NULL,1,0,NULL,'5R6uDBiLTi',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:33','2025-05-01 12:19:33'),
	(13,'User 7','user7@example.com','$2y$12$lG0FzszumSNw9GAg0rXwjuazuPA/zWEjrbnUTFbq8c9Gy8shfCS0K',NULL,NULL,NULL,1,0,NULL,'VaJyfFjJIF',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:33','2025-05-01 12:19:33'),
	(14,'User 8','user8@example.com','$2y$12$klRF9DilS7GqV5JJH1Y5tembVPTV8sjxEdU6iRWO5ZAUgCaXdaL6a',NULL,NULL,NULL,1,0,NULL,'3i5jk4r6Te',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:33','2025-05-01 12:19:33'),
	(15,'User 9','user9@example.com','$2y$12$OgqRpYHbbPV/FR3JcIiv6epFrk20TCGXSz1FZPUy3PX62MeCjz2xe',NULL,NULL,NULL,1,0,NULL,'I1ePVF5XI9',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:33','2025-05-01 12:19:33'),
	(16,'User 10','user10@example.com','$2y$12$LCqWcLhbnHOCym5/mz/MseVbKvhftr/yUWlVzDER8aanPh85gnoW2',NULL,NULL,NULL,1,0,NULL,'jlKhq1s0Dj',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:34','2025-05-01 12:19:34'),
	(17,'User 11','user11@example.com','$2y$12$htAKCa1G1szxGEA27DzOyuHvLMMgJIpxfixI.mz8N11ltPk9VsJI2',NULL,NULL,NULL,1,0,NULL,'ooYYSBxmRR',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:34','2025-05-01 12:19:34'),
	(18,'User 12','user12@example.com','$2y$12$5.U.yrcW98ZGIEe8Vl4EhOtQ4SPTE6EFERUnq.U3c2OHAUZTO6cu6',NULL,NULL,NULL,1,0,NULL,'oHhcywmJWY',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:34','2025-05-01 12:19:34'),
	(19,'User 13','user13@example.com','$2y$12$.D1H5OD3CwfYh9aMnlO/PO.yVr0Y7IgOPFfsLEDkAWSCHXNMBZqdS',NULL,NULL,NULL,1,0,NULL,'yLWluhBGlI',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:34','2025-05-01 12:19:34'),
	(20,'User 14','user14@example.com','$2y$12$UkJOpInd3suIGrGRY5Qsp.x5pOzE2f9HMkcCo14VH5Zottls6kNVK',NULL,NULL,NULL,1,0,NULL,'E3nyayNxer',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:35','2025-05-01 12:19:35'),
	(21,'User 15','user15@example.com','$2y$12$3OOzBD9ireVwnQqC26bN8edFd8RYUUyG1G8nbL1YnqEhwBD5DW/la',NULL,NULL,NULL,1,0,NULL,'4qQeOuzTez',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:35','2025-05-01 12:19:35'),
	(22,'User 16','user16@example.com','$2y$12$C6PglQUHKRIfn/hCMNf4GehCfbRfFehG5MsHD9u7etRL169zjysO2',NULL,NULL,NULL,1,0,NULL,'nCIM0BGP8f',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:35','2025-05-01 12:19:35'),
	(23,'User 17','user17@example.com','$2y$12$fBTbkSv4ruqaZB8RuxJ0HODFviRqtY4xWrrgpxv2/nn6U2k7Z3kGG',NULL,NULL,NULL,1,0,NULL,'9yczHdrXso',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:35','2025-05-01 12:19:35'),
	(24,'User 18','user18@example.com','$2y$12$mRGThDLgB/OOJSX9wT/eI.AzJUEGuiau8nIq9Kh6I1BT3i.0qSVLS',NULL,NULL,NULL,1,0,NULL,'IirDc1jylx',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(25,'User 19','user19@example.com','$2y$12$QnMgIY4PrYevjSuWbsalfOtJNLzfBqViPBDEO6Lpf1Ggp7TaSjONa',NULL,NULL,NULL,1,0,NULL,'X1BKjHHyL7',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36'),
	(26,'User 20','user20@example.com','$2y$12$V5sAAUf76gks1Gx/L1lkvuo52s8Ocln2U4fkPqkXs6aQ2g/Qr3uqW',NULL,NULL,NULL,1,0,NULL,'MZltAXxFOP',NULL,NULL,'individual','active',NULL,NULL,NULL,'2025-05-01 12:19:36','2025-05-01 12:19:36');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
