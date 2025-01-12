-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2023 at 12:05 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mybook`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `md_category`
--

INSERT INTO `md_category` (`id_category`, `position`, `type`, `status`, `title`, `icon`, `icon_color`, `icon_bg`, `description`) VALUES
(1, NULL, 'payment-category', NULL, 'Mobile Recharge', 'fas fa-mobile', NULL, NULL, ''),
(2, NULL, 'payment-category', NULL, 'BroandBand / Landline', 'fa-solid fa-wifi', '', '', ''),
(3, NULL, 'payment-category', NULL, 'Electricity', 'fas fa-bolt', '#014f2d', '#b6fee8', ''),
(4, NULL, 'payment-method', NULL, 'Paytm', 'fab fa-google-drive', '#00B9F1', '#002E6E', ''),
(5, NULL, 'payment-method', NULL, 'Google Pay', 'fab fa-google-pay', '', '', ''),
(6, NULL, 'payment-method', NULL, 'YONO', 'fab fa-internet-explorer', '', '', ''),
(7, NULL, 'payment-method', NULL, 'Cash', 'fas fa-money-bill', '', '', ''),
(8, NULL, 'payment-category', NULL, 'DTH Recharge', 'fa-solid fa-satellite-dish', '', '', ''),
(9, NULL, 'payment-category', NULL, 'Book Gas Cylinder', 'fa-solid fa-fire-flame-simple', '#ff5a00', '#ffe808', ''),
(10, NULL, 'payment-category', NULL, 'Water', 'fa-solid fa-faucet-drip', '#0f5e9c', '#74ccf4', ''),
(11, NULL, 'payment-category', NULL, 'LIC / Insurance', 'fa-solid fa-house-crack', '', '', ''),
(12, NULL, 'payment-category', NULL, 'Hosting', 'fa-solid fa-server', '', '', ''),
(13, NULL, 'payment-category', NULL, 'Domain', 'fa-solid fa-globe', '', '', ''),
(14, NULL, 'payment-category', NULL, 'Petrol', 'fa-solid fa-gas-pump', '#ffffff', '#00bfff', ''),
(15, NULL, 'payment-category', NULL, 'Travel Fair', 'fa-solid fa-van-shuttle', '#950096', '#eed543', ''),
(16, NULL, 'payment-category', NULL, 'Electronics', 'fab fa-google-drive', '', '', ''),
(17, NULL, 'payment-category', NULL, 'Fashion', 'fa-solid fa-shirt', '', '', ''),
(18, NULL, 'payment-category', NULL, 'Gym', 'fa-solid fa-dumbbell', '', '', ''),
(19, NULL, 'payment-category', NULL, 'Food', 'fa-solid fa-utensils', '', '', ''),
(20, NULL, 'payment-category', NULL, 'Others', 'fa-solid fa-otter', '', '', ''),
(21, NULL, 'payment-category', NULL, 'Stationary', 'fa-solid fa-book-open-reader', '#ffffff', '#b89d74', ''),
(22, NULL, 'payment-category', NULL, 'Doctor Consult', 'fa-solid fa-user-doctor', '#ffffff', '#385399', ''),
(23, NULL, 'payment-category', NULL, 'Medicines', 'fa-solid fa-file-prescription', '', '', ''),
(24, NULL, 'payment-category', NULL, 'Religion', 'fa-solid fa-gopuram', '', '', ''),
(25, NULL, 'payment-category', NULL, 'Online Forms', 'fa-brands fa-wpforms', '', '', ''),
(26, NULL, 'payment-category', NULL, 'Beauty Products', 'fab fa-google-drive', '', '', ''),
(27, NULL, 'payment-category', NULL, 'Challan', 'fa-solid fa-traffic-light', '', '', ''),
(28, NULL, 'payment-category', NULL, 'Ration', 'fa-solid fa-plate-wheat', '', '', ''),
(29, NULL, 'payment-category', NULL, 'Gift', 'fa-solid fa-gift', '#ffffff', '#ff00db', ''),
(30, NULL, 'payment-category', NULL, 'Cash Withdraw', 'fa-solid fa-money-from-bracket', '', '', ''),
(31, NULL, 'payment-category', NULL, 'Shopping', 'fas fa-shopping-cart', '#ffffff', '#e86229', ''),
(32, NULL, 'payment-method', NULL, 'Bank', 'fab fa-google-drive', '', '', ''),
(33, NULL, 'payment-category', NULL, 'Fund Transfer', 'fa-solid fa-money-bill-transfer', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `md_logs`
--

CREATE TABLE `md_logs` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `md_payment`
--

CREATE TABLE `md_payment` (
  `id_payment` int(11) NOT NULL,
  `id_category` int(11) DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `md_payment`
--

INSERT INTO `md_payment` (`id_payment`, `id_category`, `payment_method`, `status`, `position`, `date`, `title`, `description`, `amount`) VALUES
(1, 8, 4, '0', NULL, '2021-02-08 12:00:00', 'Tata Sky', 'Tata Sky Recharge Home', 170),
(2, 9, 4, '0', NULL, '2021-03-04 00:00:00', 'Gas Cylinder', '', 825),
(3, 3, 4, '0', NULL, '2021-03-05 00:00:00', 'Electricity', '', 615),
(4, 12, 4, '0', NULL, '2021-03-08 00:00:00', 'Xozz Renew', '', 588),
(5, 8, 4, '0', NULL, '2021-03-08 12:00:00', 'Tata Sky', '', 170),
(6, 1, 4, '0', NULL, '2021-03-12 12:00:00', 'Aditi Mobile recharge', '', 149),
(7, 14, 4, '0', NULL, '2021-03-13 12:00:00', 'Petrol', '', 160),
(8, 13, 4, '0', NULL, '2021-03-22 12:00:00', 'WpWebGuru ', '', 470),
(9, 14, 4, '0', NULL, '2021-04-07 12:00:00', 'Petrol', '', 200),
(10, 8, 4, '0', NULL, '2021-04-07 12:00:00', 'Tata Sky', '', 170),
(11, 1, 4, '0', NULL, '2021-04-08 12:00:00', 'Papa Mobile recharge', '', 379),
(12, 1, 4, '0', NULL, '2021-04-08 12:00:00', 'Aditi Mobile recharge', '', 149),
(13, 1, 4, '0', NULL, '2021-04-12 12:00:00', 'Vivek Topup', '', 49),
(14, 1, 4, '0', NULL, '2021-04-12 12:00:00', 'Vivek Topup', '', 49),
(15, 2, 7, '0', NULL, '2021-04-13 12:00:00', 'BSNL Security', '', 500),
(16, 15, 7, '0', NULL, '2021-04-13 12:00:00', 'Bus Fair', '', 500),
(17, 16, 4, '0', NULL, '2021-04-13 00:00:00', 'TP-Link Archer', '', 1500),
(18, 1, 4, '0', NULL, '2021-04-16 12:00:00', 'Vivek Airtel Data', '', 78),
(19, 1, 4, '0', NULL, '2021-04-17 12:00:00', 'Alpana Mobile', '', 199),
(20, 1, 4, '0', NULL, '2021-04-20 12:00:00', 'Vivek Airtel Data', '', 49),
(21, 16, 7, '0', NULL, '2021-04-20 00:00:00', 'Netis Modem', '', 500),
(22, 16, 7, '0', NULL, '2021-05-05 00:00:00', 'BSNL Telephone', '', 200),
(23, 2, 4, '0', NULL, '2021-05-13 00:00:00', 'BSNL Bill', '', 263),
(24, 1, 4, '0', NULL, '2021-06-08 12:00:00', 'Vivek Topup	', '', 49),
(25, 1, 4, '0', NULL, '2021-06-08 12:00:00', 'Alpana Topup', '', 49),
(26, 2, 4, '0', NULL, '2021-06-16 12:00:00', 'BSNL Bill', '', 636),
(27, 1, 4, '0', NULL, '2021-07-01 12:00:00', 'Papa Mobile recharge', '', 379),
(28, 3, 4, '0', NULL, '2021-07-09 12:00:00', 'Electricity', '', 3497),
(29, 1, 4, '0', NULL, '2021-07-16 12:00:00', 'Vivek Topup	', '', 49),
(30, 2, 4, '0', NULL, '2021-07-19 12:00:00', 'BSNL Bill', '', 459),
(31, 18, 4, '0', NULL, '2021-08-03 00:00:00', 'Gurjar International Gym', '', 1300),
(32, 16, 4, '0', NULL, '2021-08-17 12:00:00', 'Realme 8', 'Give Vinit for Realme 8 Mobile', 14000),
(33, 2, 4, '0', NULL, '2021-08-18 12:00:00', 'BSNL Bill', '', 459),
(34, 1, 4, '0', NULL, '2021-08-19 00:00:00', 'vinit for Pizza', '', 860),
(35, 19, 4, '0', NULL, '2021-08-19 12:00:00', 'Vinit for Pizza', '', 860),
(36, 20, 4, '0', NULL, '2021-08-19 00:00:00', 'Tempered Realme 8', '', 50),
(37, 20, 4, '0', NULL, '2021-08-21 12:00:00', 'Tempered Realme 8', '', 100),
(38, 16, 4, '0', NULL, '2021-08-30 00:00:00', 'Redmi Note 10 pro Max', '', 18000),
(39, 2, 4, '0', NULL, '2021-09-12 12:00:00', 'BSNL Bill', '', 459),
(40, 3, 4, '0', NULL, '2021-09-12 12:00:00', 'Electricity', '', 3571),
(41, 1, 4, '0', NULL, '2021-09-12 12:00:00', 'Vivek Topup	', '', 129),
(42, 20, 7, '0', NULL, '2021-09-27 00:00:00', 'Rahul (BSNL)', '', 100),
(43, 19, 4, '0', NULL, '2021-10-01 12:00:00', 'Vivek Lunch(Chole/Nan)', '', 60),
(44, 16, 4, '0', NULL, '2021-10-04 12:00:00', 'Syska Trimmer', '', 1144),
(45, 1, 4, '0', NULL, '2021-10-08 12:00:00', 'Vivek Mobile Recharge', '', 149),
(46, 19, 4, '0', NULL, '2021-10-08 12:00:00', 'Paneer', 'Paneer From Country Delight', 200),
(47, 2, 4, '0', NULL, '2021-10-15 12:00:00', 'BSNL Bill', '', 459),
(48, 20, 7, '0', NULL, '2021-10-16 12:00:00', 'Withdraw from ATM', '', 800),
(49, 3, 4, '0', NULL, '2021-10-16 12:00:00', 'Factory', 'Factory Electricity', 249),
(50, 16, 4, '0', NULL, '2021-10-16 12:00:00', 'CPU SMPS', '', 600),
(51, 16, 4, '0', NULL, '2021-10-16 12:00:00', 'Lenovo Battery', '', 600),
(52, 17, 4, '0', NULL, '2021-10-25 12:00:00', 'Dress from Deepak', '', 450),
(53, 20, 4, '0', NULL, '2021-11-06 12:00:00', 'Alpana paytm', '', 110),
(54, 1, 4, '0', NULL, '2021-10-08 12:00:00', 'Vivek Topup	', '', 129),
(55, 2, 4, '0', NULL, '2021-11-08 12:00:00', 'BSNL Bill', '', 459),
(56, 3, 4, '0', NULL, '2021-11-08 12:00:00', 'Home', 'Home Electricity', 2622),
(57, 17, 4, '0', NULL, '2021-11-27 12:00:00', 'RedTape Coat', 'RedTape Coat For Vivek', 2340),
(58, 1, 4, '0', NULL, '2021-11-27 12:00:00', 'Vivek Mobile Recharge', '', 249),
(59, 17, 4, '0', NULL, '2021-12-01 12:00:00', 'Dress from KC Tailor', 'Dress from KC Tailor For Vivek', 630),
(60, 19, 4, '0', NULL, '2021-12-07 12:00:00', 'Gajak 500G for Office', '', 140),
(61, 2, 4, '0', NULL, '2021-12-09 12:00:00', 'BSNL Bill', '', 459),
(62, 9, 4, '0', NULL, '2021-12-13 12:00:00', 'Gas Cylinder', '', 903),
(63, 16, 4, '0', NULL, '2021-12-21 12:00:00', 'Hoffen Weight Machine', '', 900),
(64, 13, 4, '0', NULL, '2021-12-28 12:00:00', 'WpDevGuru', 'GoDaddy Domain (Fraud)', 486),
(65, 1, 4, '0', NULL, '2022-01-02 12:00:00', 'Vivek Mobile Recharge', '', 395),
(66, 2, 4, '0', NULL, '2022-01-06 12:00:00', 'BSNL Bill', '', 459),
(67, 20, 4, '0', NULL, '2022-01-06 12:00:00', 'Alpana GGD', '', 51),
(68, 3, 4, '0', NULL, '2022-01-15 12:00:00', 'Home', 'Home Electricity', 1347),
(69, 21, 4, '0', NULL, '2022-01-20 12:00:00', 'Alpana', 'Notebook/pens', 110),
(70, 1, 4, '0', NULL, '2022-01-26 12:00:00', 'Maa Mobile Recharge', '', 91),
(71, 17, 4, '0', NULL, '2022-01-29 12:00:00', 'Cantabil Dress', 'Dress For Vivek', 1987),
(72, 22, 4, '0', NULL, '2022-02-09 12:00:00', 'ALCS Consult Fee', '', 500),
(73, 12, 4, '0', NULL, '2022-02-12 12:00:00', 'Xozz Renew', '', 706),
(74, 3, 4, '0', NULL, '2022-02-13 12:00:00', 'Factory', 'Factory Electricity', 457),
(75, 2, 4, '0', NULL, '2022-02-13 12:00:00', 'BSNL Bill', '', 459),
(76, 13, 4, '0', NULL, '2022-02-23 12:00:00', 'WpWebGuru ', 'Domain renew (2yr)', 2121),
(77, 21, 4, '0', NULL, '2022-02-23 12:00:00', 'Alpana', 'Notebook/pens', 96),
(78, 24, 4, '0', NULL, '2022-02-25 12:00:00', 'rudraksha-diksha(online', '', 51),
(79, 25, 4, '0', NULL, '2022-02-27 12:00:00', 'Vivek', 'Computer teacher form', 360),
(80, 1, 4, '0', NULL, '2022-02-27 12:00:00', 'Maa Mobile Recharge', '', 91),
(81, 16, 4, '0', NULL, '2022-02-28 12:00:00', 'Redmi 9i sport Mobile', 'For Papa', 7650),
(82, 1, 4, '0', NULL, '2022-03-08 12:00:00', 'Papa Mobile recharge', '', 455),
(83, 18, 4, '0', NULL, '2022-03-14 12:00:00', 'Gurjar International Gym', 'April Month', 200),
(84, 3, 4, '0', NULL, '2022-03-14 12:00:00', 'Home', 'Home Electricity (JAN-FEB)', 1279),
(85, 2, 4, '0', NULL, '2022-03-20 12:00:00', 'BSNL Bill', '', 460),
(86, 1, 4, '0', NULL, '2022-03-25 12:00:00', 'Vivek Mobile Recharge', '', 395),
(87, 1, 4, '0', NULL, '2022-04-03 12:00:00', 'Maa Mobile Recharge', '', 75),
(88, 26, 4, '0', NULL, '2022-04-09 12:00:00', 'Himalaya Shampoo', '', 240),
(89, 16, 4, '0', NULL, '2022-04-09 12:00:00', 'Syska Iron', '', 549),
(90, 25, 4, '0', NULL, '2022-04-14 12:00:00', 'Alpana', '2nd Grade form', 260),
(91, 2, 4, '0', NULL, '2022-04-16 12:00:00', 'BSNL Bill', '', 482),
(92, 3, 4, '0', NULL, '2022-05-11 12:00:00', 'Home', 'Home Electricity (MAR-APR)', 1551),
(93, 19, 4, '0', NULL, '2022-05-16 12:00:00', 'Vinit', 'For Pizza', 1100),
(94, 14, 4, '0', NULL, '2022-05-19 12:00:00', 'Petrol', '', 110),
(95, 2, 4, '0', NULL, '2022-04-20 12:00:00', 'BSNL Bill', '', 489),
(96, 21, 4, '0', NULL, '2022-05-26 12:00:00', 'Alpana', 'Notebook', 90),
(97, 20, 4, '0', NULL, '2022-05-26 12:00:00', 'Home Expenses', '', 140),
(98, 14, 4, '0', NULL, '2022-05-29 12:00:00', 'Petrol', 'Car petrol (Sonu)', 1000),
(99, 21, 4, '0', NULL, '2022-06-02 12:00:00', 'Books', 'Computer book(Daksh)', 250),
(100, 21, 4, '0', NULL, '2022-06-02 12:00:00', 'Books', 'Computer Papers', 75),
(101, 19, 4, '0', NULL, '2022-06-04 00:00:00', 'Mountain Dave', '', 105),
(102, 13, 4, '0', NULL, '2022-06-07 12:00:00', 'Rajmishtri', '', 605),
(103, 2, 4, '0', NULL, '2022-06-13 12:00:00', 'BSNL Bill', '', 540),
(104, 27, 4, '0', NULL, '2022-06-18 12:00:00', 'Traffic Challan', '', 100),
(105, 1, 4, '0', NULL, '2022-06-19 12:00:00', 'Vivek Mobile Recharge', '', 395),
(106, 19, 4, '0', NULL, '2022-06-26 12:00:00', 'Sheetal Hotel, Tonk', 'Tonk Gaye Ladki dekhne', 750),
(107, 23, 4, '0', NULL, '2022-06-27 12:00:00', 'Craving Cure Cafe', '', 200),
(108, 18, 4, '0', NULL, '2022-07-04 12:00:00', 'Gurjar International Gym', 'July Month', 500),
(109, 3, 4, '0', NULL, '2022-07-09 12:00:00', 'Home', 'Home Electricity (MAY-JUN)', 2651),
(110, 28, 4, '0', NULL, '2022-07-10 12:00:00', 'Home', 'Ramkishor Ghiya', 202),
(111, 2, 4, '0', NULL, '2022-07-17 12:00:00', 'BSNL Bill', '', 530),
(112, 19, 4, '0', NULL, '2022-07-20 12:00:00', 'Banana', '', 40),
(113, 19, 4, '0', NULL, '2022-08-02 12:00:00', 'Birthday Party', 'Give to Shubham', 1000),
(114, 18, 4, '0', NULL, '2022-08-04 12:00:00', 'Gurjar International Gym', 'August Month', 500),
(115, 2, 4, '0', NULL, '2022-08-08 12:00:00', 'BSNL Bill', '', 530),
(116, 17, 4, '0', NULL, '2022-08-08 12:00:00', 'Shoes 2 Pairs', 'Shoes 2 Pairs For Vivek', 1520),
(117, 29, 4, '0', NULL, '2022-08-12 12:00:00', 'Rakhi Gift	', '', 300),
(118, 30, 7, '0', NULL, '2022-08-20 12:00:00', 'ATM', '', 1500),
(119, 2, 4, '0', NULL, '2022-09-11 12:00:00', 'BSNL Bill', '', 530),
(120, 1, 4, '0', NULL, '2022-09-11 12:00:00', 'Vivek Mobile Recharge', '', 395),
(121, 17, 4, '0', NULL, '2022-09-17 12:00:00', 'Mr Kammez', '2 Dress For Vivek', 2450),
(122, 9, 4, '0', NULL, '2021-09-20 12:00:00', 'Gas Cylinder', '', 1060),
(123, 19, 4, '0', NULL, '2022-10-06 12:00:00', 'Namkeen', '', 50),
(124, 2, 4, '0', NULL, '2022-10-16 12:00:00', 'BSNL Bill', '', 530),
(125, 19, 4, '0', NULL, '2022-10-17 12:00:00', 'Bread', '', 30),
(126, 19, 4, '0', NULL, '2022-10-27 12:00:00', 'BhaiDuj', 'Aditi Gift', 500),
(127, 19, 4, '0', NULL, '2022-10-29 12:00:00', 'BhaiDuj', 'Alpana Gift', 118),
(128, 19, 4, '0', NULL, '2022-10-29 12:00:00', 'BhaiDuj', 'Suman Gift', 100),
(129, 3, 4, '0', NULL, '2022-11-05 12:00:00', 'Home', 'Home Electricity (SEP-OCT)', 1958),
(130, 17, 4, '0', NULL, '2022-11-05 12:00:00', 'Belt', '', 60),
(131, 2, 4, '0', NULL, '2022-11-08 12:00:00', 'BSNL Bill', '', 530),
(132, 19, 4, '0', NULL, '2022-11-14 12:00:00', 'Gajak', '', 240),
(133, 25, 4, '0', NULL, '2022-11-17 12:00:00', 'Alpana', 'LDC', 360),
(134, 17, 4, '0', NULL, '2022-11-18 12:00:00', 'Jacket', 'For Vivek', 700),
(135, 1, 4, '0', NULL, '2022-12-03 12:00:00', 'Vivek Mobile Recharge', '', 395),
(136, 14, 4, '0', NULL, '2022-12-03 12:00:00', 'Petrol', '', 120),
(137, 16, 4, '0', NULL, '2022-12-07 12:00:00', 'Tp-Link Router', '', 3100),
(138, 20, 4, '0', NULL, '2022-12-17 12:00:00', 'Marriage Garden', 'For Vivek Marriage', 1100),
(139, 2, 4, '0', NULL, '2022-12-17 12:00:00', 'BSNL Bill', '', 588),
(140, 20, 4, '0', NULL, '2022-12-18 12:00:00', 'Halwai', 'For Vivek Marriage', 1100),
(141, 20, 32, '0', NULL, '2022-12-18 12:00:00', 'Aaasha', 'For Vivek Marriage', 5100),
(142, 22, 4, '0', NULL, '2022-12-22 10:56:00', 'PRP', 'For Vivek\r\nPaid to ALCS', 4500),
(143, 26, 4, '0', NULL, '2022-12-22 11:36:00', 'Shampoo', 'For Vivek\r\nPaid to ALCS', 647),
(144, 17, 4, '0', NULL, '2022-12-25 13:50:00', '3 Piece Suit Blue', 'For Vivek', 6000),
(146, 25, 4, '0', NULL, '2023-01-03 12:00:00', 'Alpana', 'Reet', 360),
(147, 17, 4, '0', NULL, '2023-01-05 17:37:00', 'Sharma Fabric', '2 Piece Suit Cloth For Vivek', 4663),
(148, 17, 4, '0', NULL, '2023-01-05 17:37:00', 'Shoes', 'For Vivek', 300),
(149, 17, 4, '0', NULL, '2023-01-05 12:00:00', 'Shoes', 'For Prashant', 250),
(150, 17, 4, '0', NULL, '2023-01-05 18:30:00', 'Sharma Fabric', '1 pair Dress For Vivek', 1304),
(151, 23, 4, '0', NULL, '2023-01-06 14:40:00', 'Medicins', 'Papa\r\nPaid to Amar Medicose', 70),
(152, 16, 4, '0', NULL, '2023-01-09 11:12:00', 'Redmi 11 Prime 5G Mobile', 'For Heena\r\nPaid to Xiaomi', 12999),
(153, 17, 4, '0', NULL, '2023-01-09 14:22:00', 'KC Tailor', 'Dress For Vivek', 680),
(154, 19, 4, '0', NULL, '2023-01-09 14:56:00', 'Office Party', 'Vivek Marriage Party', 1049),
(155, 19, 4, '0', NULL, '2023-01-11 12:32:00', 'Papita shake', 'Paid to Tulsi Das morwani At Ajmeri gate', 30),
(156, 19, 4, '0', NULL, '2023-01-11 12:00:00', 'Tikia', 'Paid to Shani Singh Parihar\r\nFor Vivek and Radhamohan\r\nDuring Purchasing Sherwani For Marriage\r\n', 80),
(157, 2, 4, '0', NULL, '2022-01-11 12:00:00', 'BSNL Bill', '', 589),
(158, 3, 4, '0', NULL, '2023-01-11 22:46:00', 'Home', 'Home Electricity (NOV-DEC)', 794),
(159, 25, 4, '0', NULL, '2023-01-12 12:00:00', 'Aditi', 'University', 2000),
(160, 19, 4, '0', NULL, '2023-01-13 19:08:00', 'Namkeen', 'Paid to Vijay Singh Rajawat', 50),
(162, 19, 4, '0', NULL, '2023-01-19 16:26:00', 'KC Tailor', '2 Dress For Vivek', 1800),
(163, 20, 7, '0', NULL, '2022-08-20 12:00:00', 'From Bank', '', 50000),
(164, 30, 7, '0', NULL, '2023-01-21 12:00:00', 'Withdraw From Bank', '', 50000),
(165, 30, 7, '0', NULL, '2023-01-21 12:00:00', 'Withdraw From ATM', '', 10000),
(166, 30, 7, '0', NULL, '2023-01-21 12:00:00', 'Withdraw From ATM', '', 10000),
(167, 17, 4, '0', NULL, '2023-01-22 12:38:00', 'Hair Dressing', 'Paid to Mukesh Sain', 70),
(168, 20, 4, '0', NULL, '2023-01-22 14:08:00', 'Seema Devi', 'For Vivek Marriage', 10000),
(169, 1, 4, '0', NULL, '2023-01-24 12:03:00', 'Vivek Topup	', '2 GB Data Jio	', 25),
(170, 30, 7, '0', NULL, '2023-01-30 12:00:00', 'Withdraw From Bank', '', 50000),
(171, 30, 7, '0', NULL, '2023-01-30 12:00:00', 'Withdraw From ATM', '', 20000),
(172, 30, 7, '0', NULL, '2023-01-30 12:00:00', 'Withdraw From ATM', '', 20000),
(173, 30, 7, '0', NULL, '2023-01-31 12:00:00', 'Withdraw From Bank', '', 50000),
(174, 30, 7, '0', NULL, '2023-01-31 12:00:00', 'Withdraw From ATM', '', 20000),
(175, 30, 7, '0', NULL, '2023-01-31 12:00:00', 'Withdraw From ATM', '', 20000),
(176, 14, 4, '0', NULL, '2023-02-04 13:38:00', 'Balaji Petrol Pump', 'Car petrol (Sonu) Niwai', 1000),
(177, 1, 4, '0', NULL, '2023-02-04 18:50:00', 'Heena Mobile Recharge', '', 395),
(178, 1, 4, '0', NULL, '2023-02-06 12:00:00', 'Vivek Topup	', '2 GB Data Jio	', 25),
(179, 23, 4, '0', NULL, '2023-02-09 21:02:00', 'Swastik Medical', 'Medicines for suman', 180),
(180, 30, 7, '0', NULL, '2023-02-10 12:00:00', 'Withdraw From ATM', '', 20000),
(181, 30, 7, '0', NULL, '2023-02-10 12:00:00', 'Withdraw From ATM', '', 20000),
(182, 33, 4, '0', NULL, '2023-02-12 16:15:00', 'Seema Devi', 'For Vivek Marriage', 15000),
(183, 33, 4, '0', NULL, '2023-02-12 16:18:00', 'Ashok Sharma', 'For Vivek Marriage', 7000),
(184, 33, 4, '0', NULL, '2023-02-12 16:22:00', 'Laxmi Narayan Jaiswal', 'For Vivek Marriage', 30000),
(185, 2, 4, '0', NULL, '2023-02-12 19:53:00', 'BSNL Bill', '', 589),
(186, 33, 32, '0', NULL, '2023-02-15 11:55:00', 'Shree Jagmohan Traders', 'For Vivek Marriage', 14100),
(187, 33, 32, '0', NULL, '2023-02-17 16:40:00', 'Laxmi Narayan Jaiswal', 'For Vivek Marriage', 7000),
(188, 33, 32, '0', NULL, '2023-02-21 15:09:00', 'vijay singh rajawat', 'For Vivek Marriage', 500),
(189, 1, 4, '0', NULL, '2023-02-26 12:00:00', 'Vivek Mobile Recharge', '', 395),
(190, 12, 32, '0', NULL, '2023-03-05 08:03:00', 'Xozz Renew', '', 825),
(191, 3, 32, '0', NULL, '2023-03-08 15:41:00', 'Home', 'Home Electricity (JAN-FEB)', 727),
(192, 15, 32, '0', NULL, '2023-03-06 18:49:00', 'Bus Fair', 'Papa (Sanganer - Chaksu)', 40),
(193, 23, 4, '0', NULL, '2023-02-28 14:00:00', 'Swastik Medical', '', 90),
(194, 1, 4, '0', NULL, '2023-02-20 12:08:00', 'Vivek Topup	', '1 GB Data Jio', 15),
(195, 1, 4, '0', NULL, '2023-01-16 12:00:00', 'New Jio Sim for Heena', 'Paid to Triveni Saini', 149),
(196, 2, 4, '0', NULL, '2023-01-11 22:43:00', 'BSNL Bill', '', 589),
(197, 31, 32, '0', NULL, '2023-03-08 21:06:00', 'ElectroSky ABS Pushup Board', 'ElectroSky ABS Pushup Board, 15 in 1 Pushup Bar With 1 Year Warranty (Black)', 598);

-- --------------------------------------------------------

--
-- Table structure for table `md_user`
--

CREATE TABLE `md_user` (
  `id_user` int(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `id_category` int(11) DEFAULT NULL,
  `superadmin` tinyint(11) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `last_connection` datetime DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `cookie` varchar(255) DEFAULT NULL,
  `reset` varchar(255) DEFAULT NULL,
  `calendar_notification` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `md_user`
--

INSERT INTO `md_user` (`id_user`, `status`, `id_category`, `superadmin`, `gender`, `first_name`, `last_name`, `email`, `profile_picture`, `last_connection`, `password`, `cookie`, `reset`, `calendar_notification`) VALUES
(1, 'enabled', 1, NULL, 'male', 'Jaouad', 'Lizati', 'jaouadlizati@gmail.com', '1', '2022-03-19 12:38:43', '123456789', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `md_user_connect`
--

CREATE TABLE `md_user_connect` (
  `id_user_connect` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `date_connect` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `md_user_connect`
--

INSERT INTO `md_user_connect` (`id_user_connect`, `id_user`, `date_connect`) VALUES
(47, 1, '2023-02-17 18:10:58'),
(48, 1, '2023-02-18 19:18:49'),
(49, 1, '2023-03-07 09:55:18'),
(50, 1, '2023-03-08 18:34:43'),
(51, 1, '2023-03-08 20:20:05'),
(52, 1, '2023-03-08 21:10:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `md_category`
--
ALTER TABLE `md_category`
  ADD PRIMARY KEY (`id_category`) USING BTREE;

--
-- Indexes for table `md_logs`
--
ALTER TABLE `md_logs`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `md_payment`
--
ALTER TABLE `md_payment`
  ADD PRIMARY KEY (`id_payment`);

--
-- Indexes for table `md_user`
--
ALTER TABLE `md_user`
  ADD PRIMARY KEY (`id_user`) USING BTREE;

--
-- Indexes for table `md_user_connect`
--
ALTER TABLE `md_user_connect`
  ADD PRIMARY KEY (`id_user_connect`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `md_category`
--
ALTER TABLE `md_category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `md_logs`
--
ALTER TABLE `md_logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `md_payment`
--
ALTER TABLE `md_payment`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `md_user`
--
ALTER TABLE `md_user`
  MODIFY `id_user` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `md_user_connect`
--
ALTER TABLE `md_user_connect`
  MODIFY `id_user_connect` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
