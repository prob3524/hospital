-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 24, 2024 at 08:27 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sh_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255)  DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(255)  DEFAULT NULL,
  `gender` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `email`, `phone_no`, `gender`, `status`, `password`, `created_at`, `updated_at`, `image`) VALUES
(1, 'Admin1', 'Hospital', 'admin@hospital.com', '986532012', NULL, 1, '$2y$10$cq/jpbvUphHSp/b5JdlsseBQqCwAyQE0wY9a2Psla3nyiyu8LJ0U6', '2024-01-18 04:56:07', '2024-01-18 04:56:07', '');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `patient_id` int DEFAULT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `diagnosis` varchar(255)  DEFAULT NULL,
  `doctor_id` int DEFAULT NULL,
  `opd_date` datetime DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) DEFAULT NULL,
  `canceled_by` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `patient_name`, `diagnosis`, `doctor_id`, `opd_date`, `description`, `created_at`, `status`, `canceled_by`) VALUES
(58, 18, '', '1', 19, '2024-01-25 00:00:00', 'esRest', '2024-01-24 12:08:28', 'Confirm', NULL),
(59, 18, '', '1', 19, '2024-01-24 00:00:00', 'Tetestestes', '2024-01-24 12:09:51', 'Confirm', NULL),
(63, 18, '', '1', 19, '2024-06-11 00:00:00', 'wew', '2024-06-10 07:45:17', 'Confirm', NULL),
(64, 18, '', '1', 19, '2024-06-11 00:00:00', '32232', '2024-06-10 07:47:46', 'Pending', NULL),
(65, 18, '', '1', 19, '2024-06-12 00:00:00', 'ds', '2024-06-10 07:48:34', 'Pending', NULL),
(66, 18, '', '1', 19, '2024-06-12 00:00:00', 'ds', '2024-06-10 07:48:47', 'Pending', NULL),
(67, 18, '', '1', 19, '2024-06-13 00:00:00', 'dsd', '2024-06-10 08:20:31', 'Pending', NULL),
(68, 19, 'Test', '1', 19, '2024-06-28 00:00:00', 'Test', '2024-06-22 05:27:17', 'Pending', NULL),
(69, 18, '', '1', 19, '2024-06-22 00:00:00', 'Test', '2024-06-22 05:35:34', 'Pending', NULL),
(70, 18, '', '1', 19, '2024-06-22 00:00:00', 'Test', '2024-06-22 05:41:48', 'Pending', NULL),
(71, 18, '', '1', 19, '2024-06-22 00:00:00', 'Test', '2024-06-22 05:42:10', 'Pending', NULL),
(72, 18, '', '1', 19, '2024-06-22 00:00:00', 'Rest', '2024-06-22 05:42:29', 'Pending', NULL),
(73, 19, '', '1', 19, '2024-06-24 00:00:00', '', '2024-06-24 08:27:07', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `id` int NOT NULL,
  `bed_name` text NOT NULL,
  `bed_type` varchar(255) NOT NULL,
  `bed_charge` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`id`, `bed_name`, `bed_type`, `bed_charge`, `status`, `created_at`) VALUES
(15, 'Test', '3 Star', '200', 1, '2024-06-24 13:54:03');

-- --------------------------------------------------------

--
-- Table structure for table `bed_assignments`
--

CREATE TABLE `bed_assignments` (
  `id` int NOT NULL,
  `ipd_patient_department_id` int NOT NULL,
  `bed_id` int NOT NULL,
  `assign_date` date NOT NULL,
  `status` tinyint(1) DEFAULT '1'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--

CREATE TABLE `diagnosis` (
  `id` int NOT NULL,
  `diagnosis_name` varchar(255)  NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`id`, `diagnosis_name`, `created_at`, `updated_at`) VALUES
(1, 'Test', '2024-01-18 10:26:19', '2024-01-18 10:26:19'),
(4, 'Another Test', '2024-01-18 11:15:44', '2024-01-18 11:15:44'),
(5, 'Dfddg', '2024-01-24 11:29:06', '2024-01-24 11:29:06');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `diagnosis_id` int DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `gender` int NOT NULL,
  `status` int NOT NULL,
  `specialist` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `phone_no` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `first_name`, `last_name`, `email`, `diagnosis_id`, `designation`, `qualification`, `gender`, `status`, `specialist`, `password`, `created_at`, `updated_at`, `phone_no`, `image`) VALUES
(19, 'Doctor', 'Hospital', 'doctor@hospital.com', 1, 'MBBS', 'MS', 0, 1, 'Surgeon', '$2y$10$/.CrJ1Ie1Y5GTnd/1MjmcOAlC4yPBK20WVSUXf8K.24AzQU1Zr.oa', '2024-01-23 05:09:26', '2024-01-24 13:58:07', '8956230174', '');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `manufacturer` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `description`, `manufacturer`, `price`, `stock_quantity`, `expiry_date`, `created_at`) VALUES
(1, 'Test', NULL, NULL, NULL, NULL, NULL, '2024-06-10 04:26:15'),
(2, '23', NULL, NULL, NULL, 23223, NULL, '2024-06-10 04:27:33'),
(3, 'est', NULL, NULL, NULL, 231, NULL, '2024-06-22 06:07:34'),
(4, 'est', NULL, NULL, NULL, 12, NULL, '2024-06-22 06:09:49'),
(5, 'est', NULL, NULL, NULL, 31, NULL, '2024-06-22 06:09:52'),
(6, 'ew', NULL, NULL, NULL, 32, NULL, '2024-06-22 06:11:03');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255)  DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(255)  DEFAULT NULL,
  `gender` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `first_name`, `last_name`, `email`, `phone_no`, `gender`, `status`, `password`, `created_at`, `updated_at`, `image`) VALUES
(18, 'Patient', 'Hospital', 'patient@hospital.com', '9856230147', 0, 1, '$2y$10$VVFZ/4EdTsVOEC2fkunJbO.FOpwH7ZK9xr9x2I05RPcIr2C6pGE2a', '2024-01-23 05:08:42', '2024-01-23 05:08:42', ''),
(19, 'Test', 'tset', 'tetst@mail.com', NULL, NULL, 1, '$2y$10$tfjCtbOiT/GYGMWaWdpPVe2QTeq6Lz/DOJ1GIf5emAlkDqPR8wiqK', '2024-06-22 05:27:17', '2024-06-22 05:27:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescribed_medicines`
--

CREATE TABLE `prescribed_medicines` (
  `id` int NOT NULL,
  `appointment_id` int NOT NULL,
  `medicine_id` int NOT NULL,
  `dosage` varchar(255) NOT NULL,
  `frequency` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `prescribed_medicines`
--

INSERT INTO `prescribed_medicines` (`id`, `appointment_id`, `medicine_id`, `dosage`, `frequency`, `duration`, `created_at`) VALUES
(1, 58, 2, '12', '12', '12', '2024-06-10 06:08:08'),
(2, 58, 2, '12', '12', '12', '2024-06-10 06:08:41'),
(3, 58, 1, '34', '43', '43', '2024-06-10 06:23:07'),
(4, 63, 2, '12', '21', '12', '2024-06-22 07:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int NOT NULL,
  `appointment_id` int DEFAULT NULL,
  `prescription_details` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `appointment_id`, `prescription_details`, `created_at`) VALUES
(5, 59, 'Test', '2024-01-24 12:13:50'),
(6, 58, 'Test', '2024-06-10 06:23:02'),
(7, 63, 'Etest', '2024-06-22 07:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `receptionists`
--

CREATE TABLE `receptionists` (
  `id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` int NOT NULL,
  `status` int NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `phone_no` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `receptionists`
--

INSERT INTO `receptionists` (`id`, `first_name`, `last_name`, `email`, `gender`, `status`, `password`, `created_at`, `updated_at`, `phone_no`, `image`) VALUES
(4, 'Receptionist', 'Hopsital', 'receptionist@hospital.com', 0, 1, '$2y$10$CW44bwvlbmiC1t0aun3vPeehoVx5ejbWrkIW2rI09X1QS5EHZOfma', '2024-01-24 13:03:50', '2024-01-24 13:36:59', '8956231047', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100)  NOT NULL,
  `roles` varchar(2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `created_at`, `updated_at`) VALUES
(18, 'admin@hospital.com', 'a', '2024-01-18 10:26:07', '2024-01-18 10:26:07'),
(41, 'patient@hospital.com', 'p', '2024-01-23 05:08:42', '2024-01-23 05:08:42'),
(42, 'doctor@hospital.com', 'd', '2024-01-23 05:09:26', '2024-01-23 05:09:26'),
(43, 'receptionist@hospital.com', 'r', '2024-01-24 13:03:50', '2024-01-24 13:03:50'),
(44, 'tetst@mail.com', 'p', '2024-06-22 05:27:17', '2024-06-22 05:27:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_appointments_doctors` (`doctor_id`),
  ADD KEY `fk_appointments_patients` (`patient_id`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_patient_department_id` (`ipd_patient_department_id`),
  ADD KEY `bed_id` (`bed_id`);

--
-- Indexes for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diagnosis_id` (`diagnosis_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescribed_medicines`
--
ALTER TABLE `prescribed_medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions` (`appointment_id`);

--
-- Indexes for table `receptionists`
--
ALTER TABLE `receptionists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `diagnosis`
--
ALTER TABLE `diagnosis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `prescribed_medicines`
--
ALTER TABLE `prescribed_medicines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `receptionists`
--
ALTER TABLE `receptionists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_doctors` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointments_patients` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  ADD CONSTRAINT `bed_assignments_ibfk_1` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `bed_assignments_ibfk_2` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`diagnosis_id`) REFERENCES `diagnosis` (`id`);

--
-- Constraints for table `prescribed_medicines`
--
ALTER TABLE `prescribed_medicines`
  ADD CONSTRAINT `prescribed_medicines_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  ADD CONSTRAINT `prescribed_medicines_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
