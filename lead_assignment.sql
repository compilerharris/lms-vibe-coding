-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 08:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lead_assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developer_user_channel_partner_user_mappings`
--

CREATE TABLE `developer_user_channel_partner_user_mappings` (
  `id` char(36) NOT NULL,
  `developer_user_id` char(36) NOT NULL,
  `channel_partner_user_id` char(36) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `round_robin_count` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `developer_user_channel_partner_user_mappings`
--

INSERT INTO `developer_user_channel_partner_user_mappings` (`id`, `developer_user_id`, `channel_partner_user_id`, `is_active`, `round_robin_count`, `created_at`, `updated_at`) VALUES
('019a2a4a-9892-7139-b755-2bbf723e0f43', '019a2488-1a5f-7259-be1b-94abb13ed907', '019a2651-0511-70d3-833c-391d3df5c141', 1, 1, '2025-10-28 04:38:43', '2025-10-28 04:38:43'),
('019a2a4a-98aa-7024-949c-7ac54545c3ca', '019a2488-1a5f-7259-be1b-94abb13ed907', '019a2651-067b-724f-8a8b-7f70778e0f18', 1, 2, '2025-10-28 04:38:43', '2025-10-28 06:10:56'),
('019a2a5c-0327-7057-94ff-8775f94867f4', '019a29eb-8251-72ba-ad98-adcc6601b8c5', '019a29eb-8ce7-72f1-83e5-d4340a6e70e8', 1, 3, '2025-10-28 04:57:44', '2025-10-28 06:19:45'),
('019a2a5c-512f-703c-8c60-e4c4526ff31a', '019a29eb-84d7-7374-9888-f8bea56e4ffc', '019a2651-03b8-714a-9b61-a66fe58aec51', 1, 1, '2025-10-28 04:58:04', '2025-10-28 04:58:04'),
('019a2a8d-877b-72f9-a760-6eeb17b6a54a', '019a29eb-8251-72ba-ad98-adcc6601b8c5', '019a29eb-8762-7380-a674-b74ef6c8b386', 1, 4, '2025-10-28 05:51:49', '2025-10-28 06:20:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `project_id` char(36) NOT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `status` enum('new','assigned','contacted','converted','lost') NOT NULL DEFAULT 'new',
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `email`, `phone`, `message`, `source`, `project_id`, `assigned_user_id`, `status`, `assigned_at`, `created_at`, `updated_at`) VALUES
('019a3053-72e2-7337-a53a-472dd33cdf76', 'test1', 'test1@test.com', '8779923972', 'test', 'Website', '019a29eb-9224-7269-bb12-5e8bdf52dec4', '019a29eb-8762-7380-a674-b74ef6c8b386', 'assigned', '2025-10-29 08:46:06', '2025-10-29 08:46:06', '2025-10-29 08:46:06'),
('019a3054-1f32-73b8-81c6-f0c969ba0208', 'test2', 'test2@test.com', '9889989898', 'test2', 'Website', '019a29eb-9224-7269-bb12-5e8bdf52dec4', '019a29eb-8ce7-72f1-83e5-d4340a6e70e8', 'assigned', '2025-10-29 08:46:50', '2025-10-29 08:46:50', '2025-10-29 08:46:50'),
('019a3054-7c5c-725f-94e7-86f926aa8cc9', 'test3', 'test3@test.com', '8000890007', 'test3', 'Website', '019a29eb-9224-7269-bb12-5e8bdf52dec4', '019a29eb-8762-7380-a674-b74ef6c8b386', 'assigned', '2025-10-29 08:47:14', '2025-10-29 08:47:14', '2025-10-29 08:47:14'),
('019a3054-f75c-733b-b6ca-91444b32b615', 'test4', 'test4@test.com', '9889989898', 'test4', 'Website', '019a29eb-922f-7030-83dc-3cc234adee47', '019a29eb-8762-7380-a674-b74ef6c8b386', 'assigned', '2025-10-29 08:47:46', '2025-10-29 08:47:46', '2025-10-29 08:47:46'),
('019a3055-6e4e-7033-888b-e82edbaa07d9', 'test5', 'test5@test.com', '8000090000', 'test5', 'Website', '019a29eb-922f-7030-83dc-3cc234adee47', '019a29eb-8ce7-72f1-83e5-d4340a6e70e8', 'assigned', '2025-10-29 08:48:16', '2025-10-29 08:48:16', '2025-10-29 08:48:16'),
('019a3056-2c41-701c-95b9-b5624f7fd9ae', 'test6', 'test6@test.com', '8000090000', 'test6', 'Website', '019a29eb-9224-7269-bb12-5e8bdf52dec4', '019a29eb-8ce7-72f1-83e5-d4340a6e70e8', 'assigned', '2025-10-29 08:49:05', '2025-10-29 08:49:05', '2025-10-29 08:49:05'),
('019a3056-f9fb-7168-9771-341e4799f2b2', 'test7', 'test7@test.com', '8000090000', 'test7', 'Website', '019a29eb-9224-7269-bb12-5e8bdf52dec4', '019a29eb-8762-7380-a674-b74ef6c8b386', 'assigned', '2025-10-29 08:49:57', '2025-10-29 08:49:57', '2025-10-29 08:49:57'),
('019a3057-7bf2-70bb-b005-39f4ee8f04c7', 'test8', 'test8@test.com', '8000090000', 'test8', 'Website', '019a29eb-922f-7030-83dc-3cc234adee47', '019a29eb-8762-7380-a674-b74ef6c8b386', 'assigned', '2025-10-29 08:50:31', '2025-10-29 08:50:31', '2025-10-29 08:50:31'),
('019a33ff-36eb-7341-9a4b-30f99f708732', 'test9', 'test9@test.com', '8000090000', 'test9', 'Google Ads', '019a29eb-9232-7379-b7b6-98b737b98c5b', '019a2651-03b8-714a-9b61-a66fe58aec51', 'assigned', NULL, '2025-10-30 01:52:35', '2025-10-30 02:09:26'),
('019a3403-5f90-7269-a38e-032e659a757b', 'test10', 'test10@test.com', '8779923972', 'test10', 'Instagram', '019a29eb-9232-7379-b7b6-98b737b98c5b', '019a2651-03b8-714a-9b61-a66fe58aec51', 'assigned', NULL, '2025-10-30 01:57:07', '2025-10-30 02:34:01'),
('019a340e-166a-73cd-a1d0-a2b5b58b37f3', 'test11', 'test11@test.com', '8000090000', 'test11', 'Referral', '019a29eb-9232-7379-b7b6-98b737b98c5b', '019a2651-03b8-714a-9b61-a66fe58aec51', 'assigned', '2025-10-30 02:08:50', '2025-10-30 02:08:49', '2025-10-30 02:08:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_23_120506_create_roles_table', 1),
(5, '2025_10_23_120518_create_developers_table', 1),
(6, '2025_10_23_120531_create_projects_table', 1),
(7, '2025_10_23_120542_create_channel_partners_table', 1),
(8, '2025_10_23_120553_create_leads_table', 1),
(9, '2025_10_23_120603_add_role_to_users_table', 1),
(10, '2025_10_24_063507_add_unique_constraints_to_channel_partners_table', 1),
(12, '2025_10_24_112003_remove_round_robin_count_unique_constraint', 2),
(13, '2025_10_27_071623_recreate_tables_with_uuid_primary_keys', 2),
(14, '2025_10_27_123013_create_developer_channel_partner_mappings_table', 3),
(15, '2025_10_27_141903_add_alt_name_to_developers_and_projects_tables', 4),
(16, '2025_10_28_081416_remove_developer_channel_partner_entities', 5),
(18, '2025_10_28_082329_add_phone_address_to_users_table', 6),
(19, '2025_10_28_092254_create_developer_user_channel_partner_user_mappings_table', 7),
(20, '2025_10_28_101452_add_unique_constraint_to_channel_partner_mapping', 8),
(21, '2025_10_28_111503_add_alt_name_to_users_table', 9),
(22, '2025_10_28_113934_add_round_robin_count_to_mappings_table', 10),
(23, '2025_10_28_115535_add_round_robin_count_to_projects_table', 11),
(24, '2025_10_29_123400_add_last_assigned_cp_number_to_projects_table', 12),
(25, '2025_10_29_125508_add_cp_number_to_users_table', 12),
(26, '2025_10_29_140000_populate_cp_number_and_make_unique_on_users', 13),
(27, '2025_10_29_141000_fill_missing_cp_numbers_simple', 13);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `developer_user_id` char(36) DEFAULT NULL,
  `alt_name` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `round_robin_count` int(11) NOT NULL DEFAULT 1,
  `last_assigned_cp_number` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `developer_user_id`, `alt_name`, `description`, `is_active`, `round_robin_count`, `last_assigned_cp_number`, `created_at`, `updated_at`) VALUES
('019a2487-cc10-739a-b569-8cac446ec05c', 'Sample Project', '019a2488-1a5f-7259-be1b-94abb13ed907', 'SAMUPGGYC', 'A sample project for testing the lead assignment system', 1, 1, NULL, '2025-10-27 01:47:50', '2025-10-27 09:07:54'),
('019a29eb-9224-7269-bb12-5e8bdf52dec4', 'Sunrise Apartments', '019a29eb-8251-72ba-ad98-adcc6601b8c5', 'SUNRISE123', 'Luxury apartments with modern amenities', 1, 2, 1, '2025-10-28 02:54:55', '2025-10-29 07:41:39'),
('019a29eb-922f-7030-83dc-3cc234adee47', 'Garden Villas', '019a29eb-8251-72ba-ad98-adcc6601b8c5', 'GARDEN456', 'Spacious villas with private gardens', 1, 2, 1, '2025-10-28 02:54:55', '2025-10-29 07:42:16'),
('019a29eb-9232-7379-b7b6-98b737b98c5b', 'Tech Towers', '019a29eb-84d7-7374-9888-f8bea56e4ffc', 'TECH789', 'Modern office towers in tech district', 1, 1, NULL, '2025-10-28 02:54:55', '2025-10-28 02:54:55'),
('019a29eb-9236-73cb-b046-91fc3d2c4f7b', 'Urban Lofts', '019a29eb-84d7-7374-9888-f8bea56e4ffc', 'URBAN012', 'Contemporary lofts in city center', 1, 1, NULL, '2025-10-28 02:54:55', '2025-10-28 02:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
('019a2487-cb01-73cc-b9dc-3c93276b7f05', 'admin', 'Administrator with full access', '2025-10-27 01:47:50', '2025-10-27 01:47:50'),
('019a2487-cbd3-70a7-aee9-f19ffb947987', 'leader', 'Leader with master table access', '2025-10-27 01:47:50', '2025-10-27 01:47:50'),
('019a2487-cbd6-73df-8d43-d428ebef515f', 'site_head', 'Site Head (may be same as Leader)', '2025-10-27 01:47:50', '2025-10-27 01:47:50'),
('019a2487-cbda-70e5-b349-8841d5787a44', 'cs', 'Customer Service with read access', '2025-10-27 01:47:50', '2025-10-27 01:47:50'),
('019a2487-cbdf-718d-a6d8-138dbb72b65b', 'biddable', 'Biddable with read access', '2025-10-27 01:47:50', '2025-10-27 01:47:50'),
('019a2487-cbe3-7058-9990-6efacfc0ce78', 'developer', 'Developer with read access to all projects', '2025-10-27 01:47:50', '2025-10-27 01:47:50'),
('019a2487-cbe6-701c-9b8e-308a0a0eb7bf', 'channel_partner', 'Channel Partner with access to assigned projects', '2025-10-27 01:47:50', '2025-10-27 01:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8WK5rJgCWDq573A7qSDem0T6bilzWSohSvhps4dQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWklYREZUWUxiUVlUMkZoTDNnNlU1MEVUVzVqbVpZRk40QkFqWHF2eiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1761577190),
('Bmss6pRgvlbsrIRaifuP8bmW7TS9dHOvhdrW0mNA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.26100.6725', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTm5ubUhmb2NhYnpkWGNRSFBFS3dUeUNlaW9LUFZXUnZYZWdPdjNqNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761576070),
('KkkYHzP8HXr6l8JfOjZCxXfRwY9Ne127zJCGnYvh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU0F1SEYzQTlibmhtTGZpSllBcVBPMTVrQ094eVViT2pnd1Q4dTI0UyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1761577215),
('QhCK9pbFOU3grgaeeKb56S7iENPLT5TsIEWRflEX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.26100.6725', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1B4QUx2cUQ0Y3M3UmdONXRqMURYRXlFMUFhQXZPRnhyQnJkT3pBNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761577072),
('VueuY67EBktsHTGvHDMu6QQhGNX4LhFyXTJZQHo2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.26100.6725', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHZPRjJTeVZHZ2RxUkkxV0VJN2tiUFg2TDk1VGpFMTVKRXJGZFp5MyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761576640);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `alt_name` varchar(10) DEFAULT NULL,
  `cp_number` int(11) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` char(36) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `alt_name`, `cp_number`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `phone`, `address`) VALUES
('019a2487-cf9a-7003-b6c2-ebdb474505e5', 'Admin User', 'admin@example.com', NULL, NULL, NULL, '$2y$12$uY3a.ZEeQ.TlnY30kQw0quCbtQOkMdF29TM7ZGVrnf63y3zDSn8PK', NULL, '2025-10-27 01:47:51', '2025-10-27 01:53:41', '019a2487-cb01-73cc-b9dc-3c93276b7f05', NULL, NULL),
('019a2488-1a5f-7259-be1b-94abb13ed907', 'Developer User', 'developer@example.com', 'DEV712ORK', NULL, NULL, '$2y$12$afoz9hfG.hqfJJiY3KUxW.8QK9UvQmTHOaEmEd/htA4URZ8qKwssa', NULL, '2025-10-27 01:48:10', '2025-10-28 05:49:39', '019a2487-cbe3-7058-9990-6efacfc0ce78', NULL, NULL),
('019a2650-ff7f-7312-a9ac-d7d3a8422f4c', 'CS User', 'cs@example.com', NULL, NULL, NULL, '$2y$12$qs2UB6okwKiB2PeAt9xjqOF1CMMxDwDm7YNo8NN9TcCzMsXLvLPW.', NULL, '2025-10-27 10:07:13', '2025-10-27 10:07:13', '019a2487-cbda-70e5-b349-8841d5787a44', NULL, NULL),
('019a2651-00d0-71e3-bbdd-1d6dd1ec2307', 'Biddable User', 'biddable@example.com', NULL, NULL, NULL, '$2y$12$WXuXMqqnCQuT94dazKXIH.TaTA73ad2RSH.J9mBS8LQjq/fncYIGS', 'M1YfX7io2xynfgP9klyHmwjDPFctK64iNKceBWDUQx8MCpEGERA7WA7QgVWx', '2025-10-27 10:07:14', '2025-10-27 10:07:14', '019a2487-cbdf-718d-a6d8-138dbb72b65b', NULL, NULL),
('019a2651-03b8-714a-9b61-a66fe58aec51', 'Sample Channel Partner User', 'user@samplechannelpartner.com', NULL, 4, NULL, '$2y$12$BH4QwrcBrpEfON0C2fMvDuaKWsS1saq6YfDBv9VoYmQb38aN6dAQW', NULL, '2025-10-27 10:07:14', '2025-10-27 10:07:14', '019a2487-cbe6-701c-9b8e-308a0a0eb7bf', NULL, NULL),
('019a2651-0511-70d3-833c-391d3df5c141', 'ABC Real Estate User', 'user@abcrealestate.com', NULL, 5, NULL, '$2y$12$aneVjkODVBW7v3aCD6rGpeP4FpGH0kGjEF1u.IFl84v7MiERlH/oS', NULL, '2025-10-27 10:07:15', '2025-10-27 10:07:15', '019a2487-cbe6-701c-9b8e-308a0a0eb7bf', NULL, NULL),
('019a2651-067b-724f-8a8b-7f70778e0f18', 'XYZ Properties User', 'user@xyzproperties.com', NULL, 6, NULL, '$2y$12$tNJmfDuF29rfXDPWh4UYA.H/AzQ5J7eQ1WTXkIDNXJBhkTqYXoSnW', NULL, '2025-10-27 10:07:15', '2025-10-27 10:07:15', '019a2487-cbe6-701c-9b8e-308a0a0eb7bf', NULL, NULL),
('019a29eb-7ee1-7208-a0fc-ac7dfe0a653b', 'Admin User', 'admin@leadassignment.com', NULL, NULL, NULL, '$2y$12$/kpPpat2pSRhjglY.ucqNep9wb/tAaqBGsFUeUg945ouKlRylbtia', NULL, '2025-10-28 02:54:50', '2025-10-28 02:54:50', '019a2487-cb01-73cc-b9dc-3c93276b7f05', NULL, NULL),
('019a29eb-8251-72ba-ad98-adcc6601b8c5', 'John Developer', 'developer1@example.com', 'JOH9CL2YF', NULL, NULL, '$2y$12$iKEvTFkWJMpQ6KppRM2Kqe8bWtQPRW6heVBvXyIyab51rI/XbL2ey', NULL, '2025-10-28 02:54:51', '2025-10-28 05:49:39', '019a2487-cbe3-7058-9990-6efacfc0ce78', NULL, NULL),
('019a29eb-84d7-7374-9888-f8bea56e4ffc', 'Jane Developer', 'developer2@example.com', 'JANZG9SN3', NULL, NULL, '$2y$12$iJU3H7L7fCnba4vN3K.nW.yYOrzo8hmHgsthkOt1QzHL3xGvUyox.', NULL, '2025-10-28 02:54:52', '2025-10-28 05:49:40', '019a2487-cbe3-7058-9990-6efacfc0ce78', NULL, NULL),
('019a29eb-8762-7380-a674-b74ef6c8b386', 'ABC Real Estate', 'cp1@example.com', NULL, 1, NULL, '$2y$12$hfEv3OQArt6Tf7VsfK04cuAuWzRG4otLVIP.A8/yS.V7RSWGGnSYC', NULL, '2025-10-28 02:54:52', '2025-10-28 02:54:52', '019a2487-cbe6-701c-9b8e-308a0a0eb7bf', NULL, NULL),
('019a29eb-8a59-7114-a99f-31975f1705a2', 'XYZ Properties', 'cp2@example.com', NULL, 2, NULL, '$2y$12$My9KQUQpWqvNLqTsfRhQeOU5RKvmKTMERpdcNY1l3kc95t0XFckeK', NULL, '2025-10-28 02:54:53', '2025-10-28 02:54:53', '019a2487-cbe6-701c-9b8e-308a0a0eb7bf', NULL, NULL),
('019a29eb-8ce7-72f1-83e5-d4340a6e70e8', 'Prime Realty', 'cp3@example.com', NULL, 3, NULL, '$2y$12$YcMo66.Fpp6l0b0.GIN9XufMIWMQHo/SJGpRnnKHSOpHNb24L3joq', NULL, '2025-10-28 02:54:54', '2025-10-28 02:54:54', '019a2487-cbe6-701c-9b8e-308a0a0eb7bf', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `developer_user_channel_partner_user_mappings`
--
ALTER TABLE `developer_user_channel_partner_user_mappings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dev_cp_user_unique` (`developer_user_id`,`channel_partner_user_id`),
  ADD UNIQUE KEY `unique_channel_partner_mapping` (`channel_partner_user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_project_id_foreign` (`project_id`),
  ADD KEY `leads_assigned_user_id_foreign` (`assigned_user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_alt_name_unique` (`alt_name`),
  ADD KEY `projects_developer_user_id_foreign` (`developer_user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_alt_name_unique` (`alt_name`),
  ADD UNIQUE KEY `users_cp_number_unique` (`cp_number`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `developer_user_channel_partner_user_mappings`
--
ALTER TABLE `developer_user_channel_partner_user_mappings`
  ADD CONSTRAINT `cp_user_fk` FOREIGN KEY (`channel_partner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dev_user_fk` FOREIGN KEY (`developer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_assigned_user_id_foreign` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_developer_user_id_foreign` FOREIGN KEY (`developer_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
