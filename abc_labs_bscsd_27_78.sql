-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2024 at 07:31 PM
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
-- Database: `abc_labs_bscsd_27_78`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `test_type` varchar(255) NOT NULL,
  `price` varchar(5000) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `patient_prev_reports` longblob NOT NULL,
  `patient_message` varchar(500) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `appointment_status` enum('Processing','Appointed') NOT NULL DEFAULT 'Processing',
  `payment_status` enum('Pending Payment','Processing Payment','Paid') NOT NULL DEFAULT 'Pending Payment',
  `appointment_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `doctor_availability` varchar(255) NOT NULL,
  `technician_availability` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `test_type`, `price`, `date`, `time`, `patient_prev_reports`, `patient_message`, `patient_id`, `doctor_id`, `technician_id`, `appointment_status`, `payment_status`, `appointment_timestamp`, `doctor_availability`, `technician_availability`) VALUES
(1, 'Blood Test', 'LKR 6,000', '2024-04-10', '10:00 to 10:30', 0x613a313a7b693a303b733a37333a2275706c6f6164732f50617469656e742050726576696f7573204d65646963616c20446f63756d656e74732f50617469656e742049445f382f363566623237663339313730662e706466223b7d, 'Test Message', 8, 2, 5, 'Appointed', 'Paid', '2024-03-20 18:18:17', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `test_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `test_type` varchar(255) NOT NULL,
  `test_date` date NOT NULL,
  `test_room` varchar(255) NOT NULL,
  `test_result` varchar(255) NOT NULL,
  `test_status` enum('Processing','Successful','Postponed') NOT NULL DEFAULT 'Processing',
  `documents` longblob NOT NULL,
  `test_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`test_id`, `appointment_id`, `patient_id`, `doctor_id`, `technician_id`, `test_type`, `test_date`, `test_room`, `test_result`, `test_status`, `documents`, `test_timestamp`) VALUES
(1, 1, 8, 2, 5, 'Blood Test', '2024-04-10', '48A', 'Your test was successful, report can be downloaded from your account', 'Successful', 0x426c6f6f642054657374205265706f72742053616d706c652e706e67, '2024-03-20 18:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(255) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `amount` varchar(500) NOT NULL,
  `receipt` longblob NOT NULL,
  `status` enum('Processing','Completed','Declined') NOT NULL DEFAULT 'Processing',
  `transaction_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `card_name` varchar(255) NOT NULL DEFAULT 'Not Saved',
  `card_number` int(11) NOT NULL,
  `card_exp` int(11) NOT NULL,
  `card_cvv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `patient_id`, `appointment_id`, `amount`, `receipt`, `status`, `transaction_timestamp`, `card_name`, `card_number`, `card_exp`, `card_cvv`) VALUES
(1, 8, 1, 'LKR 6,000', 0x75706c6f6164732f5472616e73616374696f6e2052656365697074732f50617469656e742049445f382f5061796d656e7420526563656970742e706e67, 'Completed', '2024-03-20 18:18:17', 'Not Saved', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other','') NOT NULL,
  `address_street` varchar(100) NOT NULL,
  `address_city` varchar(50) NOT NULL,
  `address_state` varchar(50) NOT NULL,
  `address_postal_code` varchar(20) NOT NULL,
  `address_country` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `nic` varchar(50) NOT NULL,
  `medical_conditions` text NOT NULL,
  `allergies` text NOT NULL,
  `medications` text NOT NULL,
  `previous_procedures` text NOT NULL,
  `family_medical_history` text NOT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_relationship` varchar(50) NOT NULL,
  `emergency_contact_phone` varchar(20) NOT NULL,
  `medical_license_number` varchar(255) NOT NULL,
  `doc_specialty` varchar(255) NOT NULL,
  `technician_specialization` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `name`, `email`, `password`, `user_type`, `dob`, `gender`, `address_street`, `address_city`, `address_state`, `address_postal_code`, `address_country`, `phone_number`, `nic`, `medical_conditions`, `allergies`, `medications`, `previous_procedures`, `family_medical_history`, `emergency_contact_name`, `emergency_contact_relationship`, `emergency_contact_phone`, `medical_license_number`, `doc_specialty`, `technician_specialization`) VALUES
(1, 'Sloveshan Dayalan', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', '2006-03-20', 'Male', '', '', '', '', '', '0779052559', '', '', '', '', '', '', '', '', '', '', '', ''),
(2, 'Daniel Hoffman', 'doctor1@gmail.com', '202cb962ac59075b964b07152d234b70', 'doctor', '2024-03-20', 'Male', '2062 Homestead Rd', 'Colombo', 'Western', '01500', 'Sri Lanka', '0779052559', '200408010369', '', '', '', '', '', '', '', '', 'EX23494357', 'Cardiology', ''),
(3, 'Noelle Stevens', 'doctor2@gmail.com', '202cb962ac59075b964b07152d234b70', 'doctor', '1999-03-04', 'Female', '3861 W Sherman Dr', 'Kandy', 'Central', '01000', 'Sri Lanka', '0779052559', '200408010369', '', '', '', '', '', '', '', '', 'NM3244', 'Anesthesiology', ''),
(4, 'Zack Russell', 'doctor3@gmail.com', '202cb962ac59075b964b07152d234b70', 'doctor', '1982-02-22', 'Male', '2155 Preston Rd', 'Colombo', 'Western', '08000', 'Sri Lanka', '0779052559', '200408010369', '', '', '', '', '', '', '', '', 'XS8787', 'Neourology', ''),
(5, 'Rene Hunter', 'technician1@gmail.com', '202cb962ac59075b964b07152d234b70', 'technician', '2022-07-07', 'Male', '2305 Lakeview St', 'Colombo', 'Western', '09000', 'Sri Lanka', '0779052559', '200408010369', '', '', '', '', '', '', '', '', '', '', 'Medical Laboratory Technology'),
(6, 'Allison Tucker', 'technician2@gmail.com', '202cb962ac59075b964b07152d234b70', 'technician', '1955-06-22', 'Female', '8771 Lone Wolf Trail', 'Colombo', 'Western', '01300', 'Sri Lanka', '0779052559', '200408010369', '', '', '', '', '', '', '', '', '', '', 'Surgical Technology'),
(7, 'Herman Morrison', 'technician3@gmail.com', '202cb962ac59075b964b07152d234b70', 'technician', '1990-12-12', 'Other', '6799 Forest Ln', 'Colombo', 'Western', '05000', 'Sri Lanka', '0779052559', '200408010369', '', '', '', '', '', '', '', '', '', '', 'Healthcare Administration'),
(8, 'Malweena Weerakoon', 'sxoxexhxn2@gmail.com', '202cb962ac59075b964b07152d234b70', 'patient', '1978-10-18', 'Male', '1050 College St', 'Gampaha', 'Western', '03000', 'Sri Lanka', '0779052559', '200408010369', 'No', 'Yes', 'Yes', 'No', 'No', 'Weerakoon', 'Father', '0779388806', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
