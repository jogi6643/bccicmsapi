-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 01, 2021 at 11:08 AM
-- Server version: 8.0.13
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bccicmsapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `countrymsts`
--

CREATE TABLE `countrymsts` (
  `country_id` int(10) UNSIGNED NOT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_flag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `country_status` tinyint(1) NOT NULL DEFAULT '1',
  `country_nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countrymsts`
--

INSERT INTO `countrymsts` (`country_id`, `country_name`, `country_flag`, `created_on`, `modified_on`, `country_status`, `country_nationality`) VALUES
(1, 'India', '', '2021-08-19 05:58:30', '2021-08-19 05:58:30', 1, 'Unde qui similique veniam quaerat facere facilis itaque. Accusantium esse distinctio eius sit ipsa ducimus ea fugiat.');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchisesmsts`
--

CREATE TABLE `franchisesmsts` (
  `franchise_id` int(10) UNSIGNED NOT NULL,
  `franchise_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchise_abbrivation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `franchise_auction_year` int(11) NOT NULL,
  `indian_players_acquired_before_auction` int(11) NOT NULL,
  `pre_auction_budget` int(11) NOT NULL,
  `overseas_players_acquired_before_the_auction` int(11) NOT NULL,
  `franchise_created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `franchise_modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `franchise_created_by` int(11) NOT NULL,
  `franchise_modified_by` int(11) NOT NULL,
  `rtm_before_auction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchise_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('image','video','text') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imageUrl` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `coordinates` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `references` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `accountId` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2021_08_17_155654_create_posts_table', 1),
(10, '2021_08_17_172657_create_countrymsts_table', 1),
(11, '2021_08_17_172708_create_userroles_table', 1),
(12, '2021_08_17_172728_create_usersmsts_table', 1),
(13, '2021_08_19_073227_create_franchisesmsts_table', 2),
(14, '2021_08_19_080037_create_players_mst_table', 2),
(15, '2021_08_24_133000_create_images_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 't2E7Tf0P4T2aJWXbV4lBvZih7NJ3s92VK9QxkNRQ', NULL, 'http://localhost', 1, 0, 0, '2021-08-20 06:48:35', '2021-08-20 06:48:35'),
(2, NULL, 'Laravel Password Grant Client', '7OWI9VWf53iWWHlGqHn3Yj1vhqg0tVnXQezSDifG', 'users', 'http://localhost', 0, 1, 0, '2021-08-20 06:48:35', '2021-08-20 06:48:35'),
(3, NULL, 'Laravel Personal Access Client', 'MApX1c7G0HbeaibgBciAQioMxPQCEPHLOunlupRD', NULL, 'http://localhost', 1, 0, 0, '2021-08-20 07:26:20', '2021-08-20 07:26:20'),
(4, NULL, 'Laravel Password Grant Client', 'FVnEOuRerPqaI63tOiOTAw9gtVXUeDDAWc0opZHI', 'users', 'http://localhost', 0, 1, 0, '2021-08-20 07:26:20', '2021-08-20 07:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-08-20 06:48:35', '2021-08-20 06:48:35'),
(2, 3, '2021-08-20 07:26:20', '2021-08-20 07:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players_mst`
--

CREATE TABLE `players_mst` (
  `player_id` int(10) UNSIGNED NOT NULL,
  `player_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `player_nationality` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marquee_player` tinyint(1) NOT NULL DEFAULT '0',
  `bought_via_rtm` tinyint(1) NOT NULL DEFAULT '0',
  `player_speciality` enum('Batsman','Bowler','Wicket Keeper','All-Rounder') COLLATE utf8mb4_unicode_ci NOT NULL,
  `player_auction_status` enum('To Be Auctioned','Sold','Unsold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'To Be Auctioned',
  `user_photo_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reserve_price` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `player_created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `player_modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `player_created_by` int(11) NOT NULL,
  `player_modified_by` int(11) NOT NULL,
  `player_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userroles`
--

CREATE TABLE `userroles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role_status` tinyint(1) NOT NULL DEFAULT '1',
  `read` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `write` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `userroles`
--

INSERT INTO `userroles` (`role_id`, `role_name`, `created_at`, `modified_at`, `role_status`, `read`, `write`, `country_nationality`) VALUES
(1, 'Subper Admin', '2021-08-19 05:58:30', '2021-08-20 07:31:19', 1, 'upload-content,', 'Necessitatibus corporis aliquid error vel aliquid iure distinctio. Non dolores dolores modi. Ad dolor unde repellat minima sunt nam dolorem.', 'Est dolor aut odit deserunt illo ullam distinctio. Alias laudantium perferendis facere eveniet rem.'),
(2, 'Admin', '2021-08-20 04:15:42', '2021-08-20 04:15:42', 1, 'read', 'write', 'on test'),
(3, 'Admin', '2021-08-20 04:20:53', '2021-08-20 04:20:53', 1, 'read', 'write', 'on test'),
(4, 'Admin', '2021-08-20 04:21:13', '2021-08-20 04:21:13', 1, 'read', 'write', 'on test'),
(5, 'Admin', '2021-08-20 04:32:58', '2021-08-20 04:32:58', 1, 'read', 'write', 'on test'),
(6, 'Editor', '2021-08-20 04:33:19', '2021-08-20 04:36:04', 1, 'read update', 'write update', 'on test updated'),
(7, 'Admin', '2021-08-20 04:59:27', '2021-08-20 04:59:27', 1, 'read', 'write', 'on test'),
(8, 'Admin test', '2021-08-20 10:13:14', '2021-08-20 10:13:14', 1, 'upload_content', 'menu1,menu2', 'on test'),
(9, 'Admin test yy', '2021-08-24 05:54:54', '2021-08-24 05:54:54', 1, 'upload_content', 'menu1,menu2', 'on test'),
(10, 'ghgfh', '2021-08-24 06:17:32', '2021-08-24 06:27:17', 1, 'upload_content', 'menu1,menu2,menu3,menu4,menu,menu', 'on test ok,ll'),
(11, 'Admin test yy', '2021-08-24 06:25:22', '2021-08-24 06:25:22', 1, 'upload_content', 'menu1,menu2,menu3,menu4,menu,menu', 'on test ok');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'jogi', 'jogi.amu@gmail.com', NULL, '$2y$10$/lvSeAKMNMg/23hzj2aVtO.ljw2tjt7lGEyaSEy/9UtZLCLfGgMvq', NULL, '2021-08-20 06:44:27', '2021-08-20 06:44:27'),
(2, 'jogi1', 'jogi.amu1@gmail.com', NULL, '$2y$10$GqqqKDW8Hziguv8k1dF5pezSFHur4VtyNi6ddXbencNGi2Yu3o6Q.', NULL, '2021-08-20 06:50:51', '2021-08-20 06:50:51'),
(3, 'jogi1', 'jogi.amu2@gmail.com', NULL, '$2y$10$V8.WpTyHGkwgUEYMKiEh1.rFAH/869TOPbF/KyodXgSqzO2kBmCuq', NULL, '2021-08-20 06:51:46', '2021-08-20 06:51:46'),
(4, 'jogi test', 'jogitest.amu@gmail.com', NULL, '$2y$10$CrSYg..uL8s9azpQW84Vse9W0aSHpQGM0ANZG2yhm9CmMxDtirr7K', NULL, '2021-08-20 06:53:10', '2021-08-20 06:53:10'),
(5, 'jogi test', 'jogitest1.amu@gmail.com', NULL, '$2y$10$o5z9IepNbEHfu7s/FOyUuelkQAcdQAYGfpeUrdXxvXmUuxBCV/1z2', NULL, '2021-08-20 06:54:59', '2021-08-20 06:54:59'),
(6, 'jogi test', 'jogitestok.amu@gmail.com', NULL, '$2y$10$92SkBU0oLMNEsr8uvVePheGIbce0w9p/DIN.sQlk5csw.TihGrI.y', NULL, '2021-08-20 07:15:28', '2021-08-20 07:15:28'),
(7, 'jogi test', 'jogites11tok.amu@gmail.com', NULL, '$2y$10$CXvJhCeIzAPQ.se70k/AIuHaMSZwxsQDsaFkpcRI32o7R658iQ6M6', NULL, '2021-08-20 07:29:46', '2021-08-20 07:29:46'),
(8, 'jogi test', 'jogites11took.amu@gmail.com', NULL, '$2y$10$Bjf4WLOQBLsdkk1hoF4L0.PHJyEIA7Pz9yk07EtXVFDVfzczX.eS6', NULL, '2021-08-20 07:32:28', '2021-08-20 07:32:28'),
(9, 'jogi test', 'jogitesii.amu@gmail.com', NULL, '$2y$10$2EZH1dqpAfSm2ml6Oam/neAZDKPO74D/BTNytnPdQTnRMytwN6xd.', NULL, '2021-08-20 07:43:25', '2021-08-20 07:43:25'),
(10, 'jogi test', 'jogitegfgsii.amu@gmail.com', NULL, '$2y$10$o6QsebisAeeJ/VDbERAZ8OHiItvIDbv3iU8xl/J/n8uo7VaHR5VtW', NULL, '2021-08-20 07:46:28', '2021-08-20 07:46:28'),
(11, 'jogi test', 'gdfgfg@gmail.com', NULL, '$2y$10$totplrCCYZbn9d4O5jghB.K.ntxMKQdF.j5TqQceRKSBQsnFhavfO', NULL, '2021-08-20 07:47:31', '2021-08-20 07:47:31'),
(12, 'jogi test', 'jhkkjfg@gmail.com', NULL, '$2y$10$yKlzk4ozBil3HUp/bcGln.3jMH2eRtCeRfAqOuUSdCl1VGa1lZBIW', NULL, '2021-08-20 07:55:53', '2021-08-20 07:55:53'),
(13, 'jogi test', 'ok@gmail.com', NULL, '$2y$10$eQUdXYxywMzdrNK9QWLtN.07W89wk9/yLlvmLVfWQ4CB19cXlN2I6', NULL, '2021-08-20 08:01:46', '2021-08-20 08:01:46'),
(14, 'jogi test', 'okok@gmail.com', NULL, '$2y$10$al9hqPSSdFGPyNjB5/qlh.mHR1BACvHbs/O4OaWDsvqSrdWXErcYK', NULL, '2021-08-20 08:04:52', '2021-08-20 08:04:52'),
(15, 'jogi test', 'jogiq.amu@gmail.com', NULL, '$2y$10$AbWenUGUo6mZAeJCKupm5udWhyfCuIXrfd9WeM.xL3n3NoV/HGrHe', NULL, '2021-08-23 23:31:53', '2021-08-23 23:31:53'),
(16, 'jogitest', 'jogiq@gmail.com', NULL, '$2y$10$YUOqY11U94YYCKgWzggGxOfhFTQ9M8Gs0cRxLIb194PZPLgKoK9gm', NULL, '2021-08-23 23:32:57', '2021-08-23 23:32:57'),
(17, 'jogitest11', 'jogiq1@gmail.com', NULL, '$2y$10$Wz62/Z4HsvLnSfCXLeQrI.kS3gO1kCvftVOFwCUIMUt5ObGOaT0l2', NULL, '2021-08-23 23:35:38', '2021-08-23 23:35:38'),
(18, 'jogioo', 'jogiuu@gmail.com', NULL, '$2y$10$foMtS0VW7z27BHShdZb72uYDlSAoE7rx54pWJwCCadDjVVzmxNOJ6', NULL, '2021-08-23 23:36:21', '2021-08-23 23:36:21'),
(19, 'jogioo1', 'jogitest@gmail.com', NULL, '$2y$10$x5NHEZcBnQprQHgh6UJYmuMrnZQdHpn98Bwm676roSRDzE4toso/W', NULL, '2021-08-23 23:37:26', '2021-08-23 23:37:26'),
(20, 'jogioo1', 'jogitestii@gmail.com', NULL, '$2y$10$.Cp88EHgybe3SxA2qui2.eFgKZxGHxYrn2lctR.w6Dx0iBxCIL9YG', NULL, '2021-08-23 23:37:39', '2021-08-23 23:37:39'),
(21, 'jogi', 'johiii@gmail.com', NULL, '$2y$10$Ma8CREw8OSKmSpxdfqEzXuS.WznEj7TypvYPBY6FXYNTEusd8AwG2', NULL, '2021-08-23 23:41:16', '2021-08-23 23:41:16'),
(22, 'jogi', 'johwwiii@gmail.com', NULL, '$2y$10$ko6N3zjwXyPMVVpPIaquPuJdbYla2Fj0LIl8VAh2WDcrZGvieFOIW', NULL, '2021-08-23 23:52:45', '2021-08-23 23:52:45'),
(23, 'new update user ok', 'testapicalltest@gmail.com', NULL, '$2y$10$VC0hwWy..uN3mULCPevyeOXSWF1Rx6DXZbu2OHUsN63OwUtcomY7u', NULL, '2021-08-24 01:54:10', '2021-08-24 01:54:10'),
(24, 'new data ok test', 'newapicall@gmail.com', NULL, '$2y$10$Ynk12alhKO3U7Wk5XgCnJ.455tlv7ecLexFyjdIearuXgHcHtKVSK', NULL, '2021-08-24 01:57:15', '2021-08-24 01:57:15'),
(25, 'new data  new ok test', 'newapicall123@gmail.com', NULL, '$2y$10$oaQMvYC3iPTk1X0ccVuTC.qtVT6C28P3J09DQxMlsu5vV0pkSXvZe', NULL, '2021-08-24 02:20:44', '2021-08-24 02:20:44'),
(26, 'ok new update ok test', 'newapicall3@gmail.com', NULL, '$2y$10$BZ/51l5KDUe6GGVZy254tO13sAtiOYFPFS1bW6soRFDjKvDs8F9Gq', NULL, '2021-08-24 02:22:04', '2021-08-24 02:28:22'),
(27, 'jogi singh', 'newmail@gmail.com', NULL, '$2y$10$SfWFQ4odv9vegVcE/dLBJOvlLS0n/Q7zhl0vX.4vaMQzqyEWJhU0W', NULL, '2021-08-24 02:51:58', '2021-08-24 02:51:58'),
(28, 'jogi singh', 'newmail11@gmail.com', NULL, '$2y$10$ZULrgk0TBCgBChOtDsJPUOTDTQmvofArwpYU9.mGzYqi7KSqa5/DG', NULL, '2021-08-24 06:05:55', '2021-08-24 06:05:55'),
(29, 'jogi singh', 'newmai1@gmail.com', NULL, '$2y$10$Xowi/OPM5nA6DcpsL8lLeuBGLiioNsln3XuQ0FBi4kW6q4Da9s5xy', NULL, '2021-08-24 06:06:54', '2021-08-24 06:11:50'),
(30, 'jogi singh', 'tytu@gmail.com', NULL, '$2y$10$heXP0MQbrU4taKUb7xoCLOvwMgjbDCsQ97k.iZ54U3.jLw8vYr9Rq', NULL, '2021-08-24 06:43:47', '2021-08-24 06:45:03'),
(31, 'jogi singh', 'ghgfh@gmail.com', NULL, '$2y$10$WgC6eoUNTyrBd/n5ODWlhu61bu6pEuvvWq148NWBjORv4RpylIdTG', NULL, '2021-08-24 06:45:14', '2021-08-24 06:45:14'),
(32, 'jogi singh', 'gjgjg@gmail.com', NULL, '$2y$10$QycXgX/cqWMQhcu4Z/DP0..eYsG7GFCw1Lfk0ucuDqX4erjZKVdGq', NULL, '2021-08-24 06:49:42', '2021-08-24 06:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `usersmsts`
--

CREATE TABLE `usersmsts` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_title` enum('Mr.','Mrs.','Miss','Dr.') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mr.',
  `user_email_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_dob` date NOT NULL,
  `user_is_association` tinyint(1) NOT NULL DEFAULT '1',
  `user_group_id` int(11) NOT NULL,
  `user_country_id` int(11) NOT NULL,
  `user_address` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT '1',
  `user_created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_created_by` int(11) NOT NULL,
  `user_modified_by` int(11) NOT NULL,
  `user_role_id` int(11) NOT NULL,
  `user_is_online` tinyint(1) NOT NULL DEFAULT '1',
  `user_season_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `association_id` int(11) NOT NULL,
  `user_phone_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag_id` int(11) NOT NULL,
  `user_otp` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_gender` tinyint(1) NOT NULL,
  `user_photo_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usersmsts`
--

INSERT INTO `usersmsts` (`user_id`, `user_first_name`, `user_last_name`, `user_password`, `user_title`, `user_email_id`, `user_dob`, `user_is_association`, `user_group_id`, `user_country_id`, `user_address`, `user_status`, `user_created_on`, `user_modified_on`, `user_created_by`, `user_modified_by`, `user_role_id`, `user_is_online`, `user_season_id`, `association_id`, `user_phone_number`, `device_id`, `flag_id`, `user_otp`, `user_gender`, `user_photo_url`) VALUES
(1, 'Laborum est.', 'katiyar', 'katiyar', 'Mr.', 'test@gmail.com', '2017-06-15', 1, 12, 31, 'Ut laboriosam accusantium hic atque. Cum ut dolorem est. Non ad error deserunt.', 1, '2021-08-19 05:58:30', '2021-08-20 07:14:52', 20, 20, 1, 1, 'Id eveniet atque ea sed.', 421, '1234567890', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '123456', 1, 'Voluptatibus assumenda eius non dolor.'),
(4, 'ankit test', 'fgdfg', '1234567890', 'Mr.', 'usermail123456ankit iii', '2017-06-15', 1, 12, 1, 'user test', 0, '2021-08-19 09:14:27', '2021-08-20 04:45:47', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(6, 'rfgdrfg', 'fgdfg', 'fgfdg', 'Mr.', 'test1234', '2017-06-15', 1, 12, 1, 'rdtgr', 1, '2021-08-19 09:17:15', '2021-08-19 09:17:15', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(8, 'rfgdrfg', 'fgdfg', 'fgfdg', 'Mr.', 'test12343', '2017-06-15', 1, 12, 1, 'rdtgr', 1, '2021-08-19 09:18:16', '2021-08-19 09:18:16', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(10, 'ankit test jogi jogi 100', 'ok', 'ok', 'Mr.', 'usermail1112@gmail.com', '2017-06-15', 1, 12, 1, 'user test yesskjhsdkkhd jkhgjkhfghfhkfgfg', 2, '2021-08-19 09:46:51', '2021-08-24 12:11:19', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi.jpg'),
(11, 'testing', 'fgdfg', '1234567890', 'Mr.', 'usermail123', '2017-06-15', 1, 12, 1, 'rdtgr', 1, '2021-08-19 09:47:17', '2021-08-19 09:47:17', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(13, 'testing', 'fgdfg', '1234567890', 'Mr.', 'usermail1234', '2017-06-15', 1, 12, 1, 'rdtgr', 1, '2021-08-19 09:47:30', '2021-08-19 09:47:30', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(16, 'testing', 'fgdfg', '1234567890', 'Mr.', 'usermail123456', '2017-06-15', 1, 12, 1, 'rdtgr', 1, '2021-08-19 11:15:29', '2021-08-19 11:15:29', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(17, 'ankit test', 'fgdfg', '1234567890', 'Mr.', 'usermail12345690', '2017-06-15', 1, 12, 1, 'user test', 1, '2021-08-19 11:17:06', '2021-08-19 11:17:06', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(20, 'ankit test', 'fgdfg', '1234567890', 'Mr.', 'usertest@gmail.com', '2017-06-15', 1, 12, 1, 'user test', 1, '2021-08-20 05:03:59', '2021-08-20 05:03:59', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(21, 'ankit test jogi jogi 100', 'ok', 'ok', 'Mr.', 'rty@gmail.com', '2017-06-15', 1, 12, 1, 'user test yesskjhsdkkhd jkhgjkhfghfhkfgfg', 0, '2021-08-20 07:45:10', '2021-08-20 09:08:36', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'test1.jpg'),
(22, 'ankit test', 'fgdfg', '1234567890', 'Mr.', 'usertest1hjkhjki@gmail.com', '2017-06-15', 1, 12, 1, 'user test', 1, '2021-08-20 07:58:13', '2021-08-20 07:58:13', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, 'rdgdrf', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'Page Url'),
(23, 'ankit test jogi jogi 100', 'ok', 'ok', 'Mr.', 'testapi@gmail.com', '2017-06-15', 1, 12, 1, 'user test yesskjhsdkkhd jkhgjkhfghfhkfgfg', 1, '2021-08-20 12:33:54', '2021-08-20 12:33:54', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(24, 'new update user', 'ok', 'ok', 'Mr.', 'testapicall@gmail.com', '2017-06-15', 1, 12, 1, 'user test yesskjhsdkkhd jkhgjkhfghfhkfgfg', 1, '2021-08-24 07:21:44', '2021-08-24 07:21:44', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(25, 'new update user', 'ok', 'ok', 'Mr.', 'testapicalltest@gmail.com', '2017-06-15', 1, 12, 1, 'user user', 0, '2021-08-24 07:24:10', '2021-08-24 08:00:05', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(26, 'new data', 'ok test', 'ok', 'Mr.', 'newapicall@gmail.com', '2017-06-15', 1, 12, 1, 'user user', 1, '2021-08-24 07:27:15', '2021-08-24 07:27:15', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(27, 'new data  new', 'ok test', '$2y$10$oUXXqyXcVj1z5khVcneNEePdQywdzYmqtjwREzycn1/ryVXx3RCdG', 'Mr.', 'newapicall123@gmail.com', '2017-06-15', 1, 12, 1, 'user user', 1, '2021-08-24 07:50:44', '2021-08-24 07:50:44', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(28, 'ok new update', 'ok test', '12345', 'Mr.', 'newapicall3@gmail.com', '2017-06-15', 1, 12, 1, 'user user', 1, '2021-08-24 07:52:04', '2021-08-24 07:58:22', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(29, 'jogi', 'singh', '$2y$10$/UI4qJ0XJ2UDJk68eH1H/eUT1Y6EL1ROr4E6GLkR4wlvj4mPT93Ru', 'Mr.', 'newmail@gmail.com', '2017-06-15', 1, 12, 1, 'user user', 1, '2021-08-24 08:21:58', '2021-08-24 08:21:58', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(30, 'jogi', 'singh', '$2y$10$b6VbO2mb4X11I6/SIPjfrOdtXMneTMRRV1cL11alHA7eihW/laBri', 'Mr.', 'newmail11@gmail.com', '2017-06-15', 1, 12, 1, 'user user', 1, '2021-08-24 11:35:55', '2021-08-24 11:35:55', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(31, 'jogi', 'singh', '12345', 'Mr.', 'newmai1@gmail.com', '2017-06-15', 1, 12, 1, 'user user uu', 2, '2021-08-24 11:36:54', '2021-08-24 12:45:59', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(32, 'jogi', 'singh', '12345', 'Mr.', 'tytu@gmail.com', '2017-06-15', 1, 12, 1, 'user user uu', 2, '2021-08-24 12:13:47', '2021-08-24 12:18:24', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(33, 'jogi', 'singh', '12345', 'Mr.', 'ghgfh@gmail.com', '2017-06-15', 1, 12, 1, 'user user uu', 1, '2021-08-24 12:15:14', '2021-08-24 12:15:14', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg'),
(34, 'jogi', 'singh', '12345', 'Mr.', 'gjgjg@gmail.com', '2017-06-15', 1, 12, 1, 'user user uu', 1, '2021-08-24 12:19:42', '2021-08-24 12:25:24', 1, 1, 1, 1, 'Id eveniet atque ea sed.', 421, '8700588518', 'Culpa voluptas ut quo atque voluptatum. Nam earum tempore expedita quaerat.', 41, '101010', 2, 'jogi test.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countrymsts`
--
ALTER TABLE `countrymsts`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `franchisesmsts`
--
ALTER TABLE `franchisesmsts`
  ADD PRIMARY KEY (`franchise_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `players_mst`
--
ALTER TABLE `players_mst`
  ADD PRIMARY KEY (`player_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `userroles`
--
ALTER TABLE `userroles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `usersmsts`
--
ALTER TABLE `usersmsts`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `usersmsts_user_email_id_unique` (`user_email_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countrymsts`
--
ALTER TABLE `countrymsts`
  MODIFY `country_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `franchisesmsts`
--
ALTER TABLE `franchisesmsts`
  MODIFY `franchise_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `players_mst`
--
ALTER TABLE `players_mst`
  MODIFY `player_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userroles`
--
ALTER TABLE `userroles`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `usersmsts`
--
ALTER TABLE `usersmsts`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
