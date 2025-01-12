-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 26, 2024 at 12:40 PM
-- Server version: 10.3.39-MariaDB
-- PHP Version: 7.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wprookie_mybook`
--

-- --------------------------------------------------------

--
-- Table structure for table `md_category`
--

CREATE TABLE `md_category` (
  `id_category` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_color` varchar(255) DEFAULT NULL,
  `icon_bg` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `md_category`
--

INSERT INTO `md_category` (`id_category`, `position`, `type`, `status`, `title`, `icon`, `icon_color`, `icon_bg`, `description`) VALUES
(1, NULL, 'payment-category', NULL, 'Mobile Recharge', 'svg-icon svg-mobile-charge', '', '', ''),
(2, NULL, 'payment-category', NULL, 'BroandBand / Landline', 'svg-icon svg-bsnl-selfcare', '#0b4e8d', '#fef303', ''),
(3, NULL, 'payment-category', NULL, 'Electricity', 'svg-icon svg-electricity', '#014f2d', '#b6fee8', ''),
(4, NULL, 'payment-method', NULL, 'Paytm', 'svg-icon svg-paytm', '', '', ''),
(5, NULL, 'payment-method', NULL, 'Google Pay', 'svg-icon svg-google-pay', '', '', ''),
(6, NULL, 'payment-method', NULL, 'YONO', 'fab fa-internet-explorer', '', '', ''),
(7, NULL, 'payment-method', NULL, 'Cash', 'svg-icon svg-cash', '', '', ''),
(8, NULL, 'payment-category', NULL, 'DTH Recharge', 'svg-icon svg-satellite-dish-antenna', '', '', ''),
(9, NULL, 'payment-category', NULL, 'Book Gas Cylinder', 'svg-icon svg-gas-cylinder', '#ff5a00', '#ffe808', ''),
(10, NULL, 'payment-category', NULL, 'Water', 'svg-icon svg-water-null', '#ffffff', '#74ccf4', ''),
(11, NULL, 'payment-category', NULL, 'LIC / Insurance', 'svg-icon svg-life-insurence', '', '', ''),
(12, NULL, 'payment-category', NULL, 'Hosting', 'svg-icon svg-cpanel', '', '', ''),
(13, NULL, 'payment-category', NULL, 'Domain', 'svg-icon svg-domain', '', '', ''),
(14, NULL, 'payment-category', NULL, 'Petrol', 'svg-icon svg-gas-station', '#ffffff', '#00bfff', ''),
(15, NULL, 'payment-category', NULL, 'Travel Fair', 'svg-icon svg-travel', '#950096', '#eed543', ''),
(16, NULL, 'payment-category', NULL, 'Electronics', 'svg-icon svg-electronics', '', '', ''),
(17, NULL, 'payment-category', NULL, 'Cloths', 'svg-icon svg-clothes', '', '', ''),
(18, NULL, 'payment-category', NULL, 'Gym', 'svg-icon svg-exercise-gym', '', '', ''),
(19, NULL, 'payment-category', NULL, 'Food', 'svg-icon svg-food', '', '', ''),
(20, NULL, 'payment-category', NULL, 'Others', 'fa-solid fa-otter', '', '', ''),
(21, NULL, 'payment-category', NULL, 'Stationary', 'svg-icon svg-notebook-pen', '#ffffff', '#b89d74', ''),
(22, NULL, 'payment-category', NULL, 'Doctor Consult', 'svg-icon svg-doctor', '#ffffff', '#385399', ''),
(23, NULL, 'payment-category', NULL, 'Medicines', 'svg-icon svg-medicines', '', '', ''),
(24, NULL, 'payment-category', NULL, 'Religion', 'svg-icon svg-temple', '', '', ''),
(25, NULL, 'payment-category', NULL, 'Online Forms', 'svg-icon svg-form', '', '', ''),
(26, NULL, 'payment-category', NULL, 'Beauty Products', 'svg-icon svg-beauty-products', '', '', ''),
(27, NULL, 'payment-category', NULL, 'Challan', 'svg-icon svg-traffic-fine', '', '', ''),
(28, NULL, 'payment-category', NULL, 'Ration', 'svg-icon svg-food-bag', '', '', ''),
(29, NULL, 'payment-category', NULL, 'Gift', 'svg-icon svg-gift', '#ffffff', '#ff00db', ''),
(30, NULL, 'payment-category', NULL, 'Cash Withdraw', 'svg-icon svg-cash', '#000000', '', ''),
(31, NULL, 'payment-category', NULL, 'Shopping', 'svg-icon svg-shopping-cart', '#ffffff', '#e86229', ''),
(32, NULL, 'payment-method', NULL, 'SBI Bank', 'svg-icon svg-bank', '', '', ''),
(33, NULL, 'payment-category', NULL, 'Fund Transfer', 'svg-icon svg-money-send', '', '', ''),
(34, NULL, 'payment-method', NULL, 'PhonePe', 'svg-icon svg-google-pay', '', '', ''),
(35, NULL, 'payment-category', NULL, 'Movie', 'svg-icon svg-movie-theater', '', '', ''),
(36, NULL, 'payment-category', NULL, 'Museum', 'svg-icon svg-museum', '', '', ''),
(37, NULL, 'payment-category', NULL, 'Medical Report', 'svg-icon svg-medical-report', '', '', ''),
(38, NULL, 'payment-category', NULL, 'Shoes', 'svg-icon svg-shoes', '', '', ''),
(39, NULL, 'payment-category', NULL, 'Hair Dresser', 'svg-icon svg-barber', '', '', ''),
(40, NULL, 'payment-method', NULL, 'Cheque', 'svg-icon svg-cheque', '', '', ''),
(41, NULL, 'payment-method', NULL, 'Amazon Pay', 'svg-icon svg-amazon-pay', '', '', ''),
(42, NULL, 'payment-category', NULL, 'Subscription Pack', 'svg-icon svg-subscriptions', '', '', ''),
(43, NULL, 'payment-category', NULL, 'Baby Products', 'svg-icon svg-baby-product', '', '', ''),
(44, NULL, 'payment-method', NULL, 'AU Bank', 'svg-icon svg-bank', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `md_category`
--
ALTER TABLE `md_category`
  ADD PRIMARY KEY (`id_category`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `md_category`
--
ALTER TABLE `md_category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
