-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2021 at 09:33 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tax_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `address` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  `pin` varchar(20) NOT NULL,
  `entity_id` varchar(20) NOT NULL COMMENT 'Entity: Could be customer, admin, subadmin, customer and firm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL,
  `enq_id` varchar(20) NOT NULL,
  `customer_id` varchar(20) NOT NULL,
  `enq_type` varchar(100) NOT NULL COMMENT 'GST,MSME,Compnay registration, GST registration, etc.',
  `firm` varchar(200) NOT NULL,
  `gst_vat` varchar(15) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-Active, 0-Deactive',
  `created_datetime` datetime NOT NULL,
  `updated_datetime` datetime NOT NULL,
  `updated_by` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `notification` text NOT NULL,
  `createdby` varchar(20) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `notification`, `createdby`, `created_datetime`, `status`, `deleted_datetime`) VALUES
(28, 'ADSDD', 'AD001', '2021-09-11 04:20:07', 1, '0000-00-00 00:00:00'),
(29, 'sadfasdfasd', 'AD001', '2021-09-11 04:20:16', 1, '0000-00-00 00:00:00'),
(30, 'asdfasdf', 'AD001', '2021-09-11 04:20:21', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `title`, `description`, `created_datetime`) VALUES
(1, 'admin', 'admin', '2021-08-31 23:36:30'),
(2, 'sub_admin', 'sub_admin', '2021-08-31 23:36:38'),
(3, 'customer', 'customer', '2021-08-31 23:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `status`) VALUES
(1, 'GST Return Filing', 'GST Return with experts', 1),
(2, 'Income tax return (ITR)', 'Income tax return (ITR) with expert', 1),
(3, 'New GST Registration', 'New GST Registration', 1),
(4, 'FSSAI Food License', 'FSSAI Food License', 1),
(5, 'Company/LLP registrion', 'Tredmark registration', 1),
(6, 'MSME Registrion', 'MSME Registrion', 1),
(7, 'Import Export code', 'Import Export code', 1),
(8, 'Account service', 'Account service', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subadmin_users_relation`
--

CREATE TABLE `subadmin_users_relation` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(20) NOT NULL,
  `sub_admin_id` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `assign_datetime` datetime NOT NULL,
  `updated_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subadmin_users_relation`
--

INSERT INTO `subadmin_users_relation` (`id`, `customer_id`, `sub_admin_id`, `status`, `assign_datetime`, `updated_datetime`) VALUES
(3, 'CST210920020', 'CST210919002', 1, '2021-09-20 01:48:54', '0000-00-00 00:00:00'),
(4, 'CST210920021', 'CST210919002', 1, '2021-09-22 12:21:46', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `transactionid` varchar(20) NOT NULL COMMENT 'transactionid will generate manualy',
  `financial_year_month` varchar(20) DEFAULT NULL COMMENT 'if ITR then financial_year else if GST then month_year',
  `fy` varchar(15) NOT NULL,
  `service_type` text NOT NULL COMMENT 'GST, and ITR',
  `customer_id` varchar(20) NOT NULL,
  `douments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'will be accept document array',
  `sales_document` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sales_document`)),
  `purchage_document` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`purchage_document`)),
  `return_calculation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`return_calculation`)),
  `acknowledge_document` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'upload final document when done.(will be accept document array)',
  `payment` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-payment done, 0-pending',
  `uploaded_date_time` datetime NOT NULL,
  `uploaded_by` varchar(20) NOT NULL,
  `modified_by` varchar(20) NOT NULL,
  `modified_datetime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive',
  `acknowledge_by_user` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-not approved users, 1-approved by users',
  `ack_datetime_by_users` datetime NOT NULL COMMENT 'update datetime when customer acknowledge'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(300) NOT NULL,
  `role` int(11) NOT NULL,
  `access_control` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `firm_name` text DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `avatar` text NOT NULL,
  `auth_key` varchar(300) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_datetime` datetime NOT NULL,
  `otp` varchar(6) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-not deleted, 1-deleted'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userid`, `username`, `password`, `role`, `access_control`, `email`, `mobile`, `first_name`, `firm_name`, `last_name`, `avatar`, `auth_key`, `created_datetime`, `updated_datetime`, `otp`, `last_login`, `status`, `is_deleted`) VALUES
(1, 'AD001', 'John doe', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 1, NULL, 'admin@admin.com', '9874563210', 'Admin', NULL, '', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImFkbWluQGFkbWluLmNvbSIsInRpbWVTdGFtcCI6IjIwMjEtMDktMjIgMTI6MjA6MzMifQ.LmuhVB22kc9tVTGCz5la0P85DrKOFbLyFoc0ytLpn2Q', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '2021-09-22 00:20:33', 1, 0),
(20, 'CST210920020', 'u-user', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', 3, NULL, 'user@user.com', '987456321', 'Rohit', NULL, '', '', '', '2021-09-20 01:40:26', '0000-00-00 00:00:00', '', NULL, 0, 0),
(21, 'CST210920021', 'u-user1', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', 3, NULL, 'user1@user.com', '987456322', 'Govind', '', 'Singh', '', '', '2021-09-20 01:41:05', '0000-00-00 00:00:00', '', NULL, 0, 0),
(19, 'CST210919002', 'u-mohit', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', 2, '[{\"itr\":\"true\"},{\"gst\":\"true\"}]', 'mohit@mail.com', '9874563211', 'Mohit', NULL, 'Singh', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1vaGl0QG1haWwuY29tIiwidGltZVN0YW1wIjoiMjAyMS0wOS0yMiAxMjoyMzowMSJ9.b8YQfqY2FfHgGapnZ4JSzSUBYxiigj5ZSDJFmrhpI0M', '2021-09-19 10:02:41', '0000-00-00 00:00:00', '420598', '2021-09-22 00:23:01', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subadmin_users_relation`
--
ALTER TABLE `subadmin_users_relation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactionid` (`transactionid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile_numer` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subadmin_users_relation`
--
ALTER TABLE `subadmin_users_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
