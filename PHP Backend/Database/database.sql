-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2021 at 11:04 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `name` varchar(2500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `department_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(1000) DEFAULT NULL,
  `phone_no` varchar(500) DEFAULT NULL,
  `date` varchar(500) DEFAULT NULL,
  `time` varchar(500) DEFAULT NULL,
  `messages` varchar(10000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT '0' COMMENT 'receive=1,reschedule=2,approve=3,completed=4,refer_doc=>5,absent=0,reject=6',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `channel`
--

CREATE TABLE `channel` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `sender_user_id` int(11) DEFAULT NULL,
  `receiver_user_id` int(11) DEFAULT NULL,
  `channel` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `email` varchar(1000) DEFAULT NULL,
  `topic` varchar(2000) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `message` varchar(5000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `emergency_no` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `department_service`
--

CREATE TABLE `department_service` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(5000) DEFAULT NULL,
  `price` varchar(500) DEFAULT NULL,
  `price_for` varchar(250) DEFAULT NULL COMMENT '1=>Investigations,2=>Treatments',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `phone_no` varchar(250) DEFAULT NULL,
  `working_hour` varchar(500) DEFAULT NULL,
  `about_us` varchar(8000) DEFAULT NULL,
  `service` varchar(8000) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `facebook_id` varchar(500) DEFAULT NULL,
  `twitter_id` varchar(500) DEFAULT NULL,
  `google_id` varchar(1000) DEFAULT NULL,
  `instagram_id` varchar(1000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) AUTO_INCREMENT NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `album_id` int(11) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) AUTO_INCREMENT NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `message` varchar(10000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway`
--

CREATE TABLE `payment_gateway` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `gateway_name` varchar(500) DEFAULT NULL,
  `Image` varchar(500) DEFAULT NULL,
  `key` varchar(1000) DEFAULT NULL,
  `merchant_key` varchar(500) DEFAULT NULL,
  `secert` varchar(1000) DEFAULT NULL,
  `mode` varchar(500) DEFAULT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1=>active,0=>deactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_gateway`
--

INSERT INTO `payment_gateway` (`id`, `gateway_name`, `Image`, `key`, `merchant_key`, `secert`, `mode`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Authorize.net', 'featured-image.gif', '2w4YPn23', NULL, '225KJca8W7Y548ww', 'test', '1', '2020-07-20 09:39:47', '2020-07-30 03:27:14'),
(2, 'Braintree', 'pay_415299.png', 'xmdt5zcp75bdfnjf', 'qpj7hhj994nbzy5q', '48c75a472b66bf3c0bc1313466570f95', 'sandbox', '1', '2020-07-20 09:39:25', '2020-09-01 02:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `price_package`
--

CREATE TABLE `price_package` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `price` varchar(250) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `description` varchar(10000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_delete` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=>no,1=>yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `code` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `review` varchar(8000) NOT NULL,
  `ratting` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `icon` varchar(500) DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `is_demo` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=>live,1=>demo',
  `android_server_key` varchar(1000) DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `is_web` enum('1','2') NOT NULL DEFAULT '1',
  `is_rtl` enum('0','1') NOT NULL DEFAULT '0',
  `ios_server_key` varchar(500) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `phone_no` varchar(250) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `facebook_id` varchar(500) DEFAULT NULL,
  `twitter_id` varchar(500) DEFAULT NULL,
  `instagram_id` varchar(500) DEFAULT NULL,
  `google_id` varchar(500) DEFAULT NULL,
  `happy_client` varchar(100) DEFAULT NULL,
  `surgery_complete` varchar(100) DEFAULT NULL,
  `expert_doctor` varchar(100) DEFAULT NULL,
  `worldwide_branch` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `terms_condition` varchar(5000) DEFAULT NULL,
  `privacy_policy` varchar(5000) DEFAULT NULL,
  `TWILIO_ACCOUNT_SID` varchar(250) DEFAULT NULL,
  `TWILIO_API_KEY` varchar(250) DEFAULT NULL,
  `TWILIO_API_SECRET` varchar(250) DEFAULT NULL,
  `TWILIO_CHAT_SERVICE_SID` varchar(250) DEFAULT NULL,
  `TWILIO_AUTH_TOKEN` varchar(250) DEFAULT NULL,
  `APNCredentialSid` varchar(250) DEFAULT NULL,
  `GCM_or_FCM_Credential_Sid` varchar(250) DEFAULT NULL,
  `working_day` varchar(500) DEFAULT NULL,
  `main_banner` varchar(250) DEFAULT NULL,
  `web_key` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `is_demo`, `android_server_key`, `logo`, `is_web`, `is_rtl`, `ios_server_key`, `email`, `address`, `phone_no`, `timezone`, `currency`, `facebook_id`, `twitter_id`, `instagram_id`, `google_id`, `happy_client`, `surgery_complete`, `expert_doctor`, `worldwide_branch`, `created_at`, `updated_at`, `terms_condition`, `privacy_policy`, `TWILIO_ACCOUNT_SID`, `TWILIO_API_KEY`, `TWILIO_API_SECRET`, `TWILIO_CHAT_SERVICE_SID`, `TWILIO_AUTH_TOKEN`, `APNCredentialSid`, `GCM_or_FCM_Credential_Sid`, `working_day`, `main_banner`, `web_key`) VALUES
(1, '0', NULL, '5ff92ce9b7706.png', '1', '1', NULL, 'inquiry@clinic.com', '343 Business Place, Suite 314,\r\nSeattle, WA 98112', '(+555) 555-1234', '326', 'BND - $', 'https://www.facebook.com/', 'https://twitter.com/explore', 'https://www.instagram.com/?hl=en', 'https://www.google.com/', '1000', '625', '125', '2', '2020-06-05 15:08:34', '2021-10-20 03:36:28', '<p>Use of this site is provided by Demos subject to the following Terms and Conditions:<br>\r\n1. Your use constitutes acceptance of these Terms and Conditions as at the date of your first use of the site.<br>\r\n2. Demos reserves the rights to change these Terms and Conditions at any time by posting changes online. Your continued use of this site after changes are posted constitutes your acceptance of this agreement as modified.<br>\r\n3. You agree to use this site only for lawful purposes, and in a manner which does not infringe the rights, or restrict, or inhibit the use and enjoyment of the site by any third party.<br>\r\n4. This site and the information, names, images, pictures, logos regarding or relating to Demos are provided “as is” without any representation or endorsement made and without warranty of any kind whether express or implied. In no event will Demos be liable for any damages including, without limitation, indirect or consequential damages, or any damages whatsoever arising from the use or in connection with such use or loss of use of the site, whether in contract or in negligence.<br>\r\n5. Demos does not warrant that the functions contained in the material contained in this site will be uninterrupted or error free, that defects will be corrected, or that this site or the server that makes it available are free of viruses or bugs or represents the full functionality, accuracy and reliability of the materials.<br>\r\n6. Copyright restrictions: please refer to our Creative Commons license terms governing the use of material on this site.<br>\r\n7. Demos takes no responsibility for the content of external Internet Sites.<br>\r\n8. Any communication or material that you transmit to, or post on, any public area of the site including any data, questions, comments, suggestions, or the like, is, and will be treated as, non-confidential and non-proprietary information.<br>\r\n9. If there is any conflict between these Terms and Conditions and rules and/or specific terms of use appearing on this site relating to specific material then the latter shall prevail.<br>\r\n10. These terms and conditions shall be governed and construed in accordance with the laws of England and Wales. Any disputes shall be subject to the exclusive jurisdiction of the Courts of England and Wales.<br>\r\n11. If these Terms and Conditions are not accepted in full, the use of this site must be terminated immediately.</p>', '<p>Use of this site is provided by Demos subject to the following Terms and Conditions:<br>\r\n1. Your use constitutes acceptance of these Terms and Conditions as at the date of your first use of the site.<br>\r\n2. Demos reserves the rights to change these Terms and Conditions at any time by posting changes online. Your continued use of this site after changes are posted constitutes your acceptance of this agreement as modified.<br>\r\n3. You agree to use this site only for lawful purposes, and in a manner which does not infringe the rights, or restrict, or inhibit the use and enjoyment of the site by any third party.<br>\r\n4. This site and the information, names, images, pictures, logos regarding or relating to Demos are provided “as is” without any representation or endorsement made and without warranty of any kind whether express or implied. In no event will Demos be liable for any damages including, without limitation, indirect or consequential damages, or any damages whatsoever arising from the use or in connection with such use or loss of use of the site, whether in contract or in negligence.<br>\r\n5. Demos does not warrant that the functions contained in the material contained in this site will be uninterrupted or error free, that defects will be corrected, or that this site or the server that makes it available are free of viruses or bugs or represents the full functionality, accuracy and reliability of the materials.<br>\r\n6. Copyright restrictions: please refer to our Creative Commons license terms governing the use of material on this site.<br>\r\n7. Demos takes no responsibility for the content of external Internet Sites.<br>\r\n8. Any communication or material that you transmit to, or post on, any public area of the site including any data, questions, comments, suggestions, or the like, is, and will be treated as, non-confidential and non-proprietary information.<br>\r\n9. If there is any conflict between these Terms and Conditions and rules and/or specific terms of use appearing on this site relating to specific material then the latter shall prevail.<br>\r\n10. These terms and conditions shall be governed and construed in accordance with the laws of England and Wales. Any disputes shall be subject to the exclusive jurisdiction of the Courts of England and Wales.<br>\r\n11. If these Terms and Conditions are not accepted in full, the use of this site must be terminated immediately.</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Monday - Saturday,8am to 10pm', '1599559630.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscriber`
--

CREATE TABLE `subscriber` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `email` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(250) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` varchar(250) DEFAULT NULL,
  `date` varchar(250) DEFAULT NULL,
  `time` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=>receive,2=>accept,3=>cancel,4=>inprogress,5=>complete,6=>refund	',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `time_table`
--

CREATE TABLE `time_table` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `from` varchar(250) DEFAULT NULL,
  `to` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `token` varchar(5000) DEFAULT NULL,
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `user_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) AUTO_INCREMENT NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_pic` varchar(500) DEFAULT NULL,
  `phone_no` varchar(250) DEFAULT NULL,
  `login_type` int(11) NOT NULL DEFAULT 1 COMMENT '1=>email',
  `usertype` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=>patient,2=>admin,3=>doctor',
  `status` varchar(250) DEFAULT NULL,
  `last_seen` varchar(250) DEFAULT NULL,
  `user_sid` varchar(500) DEFAULT NULL,
  `braintree_id` varchar(250) DEFAULT NULL,
  `paypal_email` varchar(500) DEFAULT NULL,
  `card_brand` varchar(250) DEFAULT NULL,
  `card_last_four` varchar(250) DEFAULT NULL,
  `trial_ends_at` varchar(250) DEFAULT NULL,
   PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `profile_pic`, `phone_no`, `login_type`, `usertype`, `status`, `last_seen`, `user_sid`, `braintree_id`, `paypal_email`, `card_brand`, `card_last_four`, `trial_ends_at`) VALUES
(1, 'Hospital Management', 'admin@gmail.com', NULL, '123', 'v2cMcR8PFqTn9yvNCu5jiwxlVODPFvC0Bj6tELrZDRfF5IqqV9Plej4U6HO5', NULL, '2021-10-20 03:42:49', 'admin.jpg', NULL, 1, '2', 'offline', '2021-10-20 03:42:49', 'US51258fd84906491284eef4b5717591e3', NULL, NULL, NULL, NULL, NULL);


COMMIT;

