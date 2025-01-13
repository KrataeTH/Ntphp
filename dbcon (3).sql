-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 04:23 AM
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
-- Database: `dbcon`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `address_text` text DEFAULT NULL,
  `amphure_id` int(11) DEFAULT NULL,
  `thambon_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `address_text`, `amphure_id`, `thambon_id`) VALUES
(10, 'ฟฟฟ', 7111, 711104),
(11, 'aa', 7111, 711103),
(13, 'aa', 7107, 710705),
(14, 'aaa', 7111, 711102),
(15, 'aa', 7110, 711004),
(19, 'ddd', 7101, 710101),
(29, 'aaaa', 7110, 711002),
(30, 'asd', 7101, 710116),
(31, '56asd46as5da', 7108, 710802),
(33, 'aaa', 7102, 710203),
(35, 'aa', 7111, 711103),
(36, 'ฟฟฟ', 7111, 711101),
(38, 'ddd', 7104, 710404),
(39, 'เเเเ', 7101, 710104),
(41, 'aaa', 7111, 711102),
(42, 'ddd', 7104, 710404),
(43, 'ฟฟฟ', 7101, 710104),
(44, 'ฟฟฟ', 7101, 710104),
(45, 'aa', 7111, 711103),
(46, 'aa', 7107, 710705),
(47, 'aaa', 7111, 711102),
(49, 'เมือง', 7101, 710101),
(51, 'ฟฟฟ', 7101, 710102),
(52, 'ฟหก', 7110, 711001),
(54, 'aaaa', 7112, 711201),
(56, 'ฟฟฟฟ', 7110, 711002),
(60, 'ฟฟฟ', 7101, 710102),
(61, 'ฟฟฟ', 7101, 710102),
(65, 'ฟฟฟ', 7101, 710102),
(70, '77', 7113, 711304),
(71, '77', 7113, 711304),
(72, '77', 7113, 711304),
(118, NULL, NULL, NULL),
(121, NULL, NULL, NULL),
(124, NULL, NULL, NULL),
(125, NULL, NULL, NULL),
(126, NULL, 7101, 710101),
(127, NULL, 7101, 710102),
(128, NULL, NULL, NULL),
(131, NULL, NULL, NULL),
(134, NULL, NULL, NULL),
(137, NULL, NULL, NULL),
(138, NULL, 7101, 710101),
(139, NULL, 7101, 710102),
(140, NULL, NULL, NULL),
(143, NULL, NULL, NULL),
(144, NULL, 7101, 710101),
(145, NULL, 7101, 710102),
(146, NULL, NULL, NULL),
(149, NULL, NULL, NULL),
(151, NULL, NULL, NULL),
(153, NULL, NULL, NULL),
(156, NULL, NULL, NULL),
(166, NULL, NULL, NULL),
(169, NULL, NULL, NULL),
(172, NULL, NULL, NULL),
(189, 'ไม่มี', 7101, 710103),
(190, 'asd', 7113, 711303),
(191, 'บ้านข้างวัด', 7101, 710101),
(192, 'หมู่บ้านพฤษากาญจน์', 7101, 710102),
(193, 'ฟฟฟ', 7101, 710102),
(194, 'ฟฟฟ', 7101, 710102),
(195, 'ฟฟฟ', 7101, 710102),
(196, 'ฟฟฟ', 7101, 710102),
(197, 'ฟฟฟ', 7101, 710102),
(198, 'ฟฟฟ', 7101, 710102),
(199, '-', 7101, 710102),
(200, '-', 7101, 710102),
(202, '-', 7101, 710106),
(204, '-', 7113, 711302),
(208, '-', 7112, 711202),
(209, '-', 7112, 711202),
(210, '-', 7112, 711202),
(211, 'ppp', 7112, 711202);

-- --------------------------------------------------------

--
-- Table structure for table `amphure`
--

CREATE TABLE `amphure` (
  `amphure_id` int(11) NOT NULL,
  `amphure_name` varchar(255) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amphure`
--

INSERT INTO `amphure` (`amphure_id`, `amphure_name`, `province_id`) VALUES
(7101, 'เมืองกาญจนบุรี', 56),
(7102, 'ไทรโยค', 56),
(7103, 'บ่อพลอย', 56),
(7104, 'ศรีสวัสดิ์', 56),
(7105, 'ท่ามะกา', 56),
(7106, 'ท่าม่วง', 56),
(7107, 'ทองผาภูมิ', 56),
(7108, 'สังขละบุรี', 56),
(7109, 'พนมทวน', 56),
(7110, 'เลาขวัญ', 56),
(7111, 'ด่านมะขามเตี้ย', 56),
(7112, 'หนองปรือ', 56),
(7113, 'ห้วยกระเจา', 56);

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int(11) NOT NULL,
  `bill_number` char(20) NOT NULL,
  `bill_status` enum('Active','Inactive') NOT NULL,
  `bill_type` enum('CIP+','Special bill','N1') NOT NULL,
  `start_date` date NOT NULL DEFAULT '2025-01-01',
  `end_date` date NOT NULL DEFAULT '2025-12-31',
  `bill_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bill_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `all_price` float NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`bill_id`, `bill_number`, `bill_status`, `bill_type`, `start_date`, `end_date`, `bill_create_at`, `bill_update_at`, `all_price`, `customer_id`) VALUES
(3, '08765321564', 'Inactive', 'CIP+', '2025-01-01', '2025-12-31', '2024-12-16 07:55:17', '2025-01-12 11:18:11', 1400, 3),
(4, '0876532156555', 'Active', 'CIP+', '2025-01-01', '2025-12-31', '2024-12-16 07:59:30', '2025-01-10 01:37:22', 845, 6),
(5, '08765321564sS', 'Active', 'CIP+', '2025-01-01', '2025-12-31', '2024-12-16 08:15:38', '2025-01-10 01:37:33', 1358, 4),
(6, '08765321565555', 'Active', 'Special bill', '2025-01-01', '2025-12-31', '2024-12-17 03:33:08', '2025-01-10 04:04:39', 1000, 3),
(7, '087653215655555', 'Active', 'N1', '2025-01-01', '2025-12-31', '2024-12-17 03:34:03', '2025-01-09 07:50:10', 540, 3),
(8, '099999999999', 'Active', 'CIP+', '2025-01-01', '2025-12-31', '2024-12-17 03:34:52', '2025-01-10 01:37:44', 1198, 3),
(9, '0000000008', 'Active', 'Special bill', '2025-01-01', '2025-12-31', '2024-12-17 15:50:50', '2025-01-10 04:04:47', 1399, 36),
(10, '07755446691287', 'Active', 'Special bill', '2025-01-01', '2025-12-31', '2024-12-19 04:13:38', '2025-01-10 04:04:50', 898, 81),
(11, '0555555554444', 'Active', 'N1', '2025-01-01', '2025-12-31', '2024-12-19 04:15:29', '2025-01-09 07:50:32', 1796, 82),
(12, '112', 'Active', 'N1', '2025-01-01', '2025-12-31', '2024-12-19 19:55:04', '2025-01-09 07:50:37', 1350, 4),
(13, '0000891018066', 'Active', 'Special bill', '2025-01-01', '2025-12-31', '2024-12-20 04:30:30', '2025-01-10 04:04:55', 14330, 126),
(14, '0000891082625', 'Active', 'Special bill', '2025-01-01', '2025-12-31', '2024-12-20 04:46:58', '2025-01-10 04:05:00', 6190, 126),
(15, '6711002934', 'Active', 'CIP+', '2025-01-01', '2025-12-31', '2024-12-20 04:52:21', '2025-01-10 01:37:51', 3890, 126),
(16, '087555', 'Active', 'N1', '2025-01-01', '2025-12-31', '2024-12-23 01:48:52', '2025-01-09 07:51:02', 780, 3),
(17, 'aaa', 'Active', 'CIP+', '2025-01-01', '2025-12-31', '2024-12-23 01:51:46', '2025-01-10 01:37:57', 75, 3),
(18, 'BA-37889FG56', 'Active', 'Special bill', '2025-01-01', '2025-12-31', '2025-01-08 03:21:21', '2025-01-10 04:05:03', 1298, 81),
(19, 'BA-37849FG57', 'Active', 'N1', '2025-01-01', '2025-12-31', '2025-01-08 07:13:29', '2025-01-09 07:51:20', 1288, 127),
(20, 'BA-37940FG48', 'Active', 'CIP+', '2025-01-01', '2025-12-31', '2025-01-08 07:29:26', '2025-01-10 01:38:02', 1298, 36),
(21, 'LL89944Fc', 'Active', 'N1', '2025-01-01', '2025-12-31', '2025-01-08 07:50:11', '2025-01-09 07:51:31', 1253, 81),
(22, '477', 'Active', 'Special bill', '2025-01-01', '2025-12-31', '2025-01-08 08:09:49', '2025-01-10 04:05:08', 498, 81),
(23, 'BA-3788GFT44', 'Active', 'CIP+', '2025-01-01', '2025-12-31', '2025-01-09 08:41:00', '2025-01-10 01:38:15', 3350, 81),
(24, 'CA47856Fg1', 'Active', '', '2025-01-01', '2025-12-31', '2025-01-10 08:53:14', '2025-01-10 08:53:14', 579, 127),
(25, 'VI4875k697', 'Active', '', '2025-01-01', '2025-12-31', '2025-01-10 09:07:11', '2025-01-10 09:07:11', 1186, 36);

-- --------------------------------------------------------

--
-- Table structure for table `bill_group`
--

CREATE TABLE `bill_group` (
  `bill_group_id` int(11) NOT NULL,
  `group_name` char(100) NOT NULL,
  `group_type` enum('1','2') NOT NULL,
  `group_price` int(11) DEFAULT NULL,
  `group_info` text DEFAULT NULL,
  `bill_id` int(11) NOT NULL,
  `group_price_a` decimal(10,2) NOT NULL DEFAULT 0.00,
  `group_price_b` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_group`
--

INSERT INTO `bill_group` (`bill_group_id`, `group_name`, `group_type`, `group_price`, `group_info`, `bill_id`, `group_price_a`, `group_price_b`) VALUES
(3, 'AAAA', '2', 300, NULL, 3, 50.00, 250.00),
(4, 'BBB', '2', 600, NULL, 3, 520.00, 80.00),
(5, 'CCCCC', '1', 500, NULL, 3, 450.00, 50.00),
(6, 'AAAASSS', '2', 845, NULL, 4, 520.00, 325.00),
(7, 'AAA', '2', 588, NULL, 5, 256.00, 332.00),
(8, 'sadasd', '1', 770, NULL, 5, 250.00, 520.00),
(9, 'aa', '2', 1000, NULL, 6, 500.00, 500.00),
(10, 'AAA', '1', 540, NULL, 7, 520.00, 20.00),
(11, 'AAA', '1', 1198, NULL, 8, 0.00, 0.00),
(12, 'bb', '1', 1399, NULL, 9, 0.00, 0.00),
(13, 'paapa', '1', 898, NULL, 10, 399.00, 499.00),
(14, 'mama', '1', 598, NULL, 11, 299.00, 299.00),
(15, 'papa', '2', 1198, NULL, 11, 599.00, 599.00),
(16, 'A', '1', 600, NULL, 12, 500.00, 100.00),
(17, 'E', '1', 750, NULL, 12, 250.00, 500.00),
(18, 'กองคลัง', '1', 6540, NULL, 13, 5190.00, 1350.00),
(19, 'ปลัด', '1', 7790, NULL, 13, 7000.00, 790.00),
(20, 'อื่นๆ', '2', 0, NULL, 13, 0.00, 0.00),
(21, 'บิล2', '2', 6190, NULL, 14, 4000.00, 2190.00),
(22, 'บิล 3', '1', 3890, NULL, 15, 3000.00, 890.00),
(23, '25', '2', 780, NULL, 16, 255.00, 525.00),
(24, 'a', '2', 75, NULL, 17, 25.00, 50.00),
(25, 'ppap', '1', 1298, NULL, 18, 899.00, 399.00),
(26, ';-;', '1', 1288, NULL, 19, 599.00, 689.00),
(27, 'H', '1', 1298, NULL, 20, 599.00, 699.00),
(28, 'AaAa', '1', 1253, NULL, 21, 899.00, 354.00),
(29, 'h', '1', 498, NULL, 22, 299.00, 199.00),
(30, 'ICT + 1C', '1', 798, NULL, 23, 599.00, 199.00),
(31, 'Fiber NT', '2', 1654, NULL, 23, 799.00, 855.00),
(32, 'Lest', '', 898, NULL, 23, 199.00, 699.00),
(33, 'gsssss', '1', 579, NULL, 24, 123.00, 456.00),
(34, 'vipa', '1', 1186, NULL, 25, 588.00, 598.00);

-- --------------------------------------------------------

--
-- Table structure for table `bill_group_info`
--

CREATE TABLE `bill_group_info` (
  `bill_group_info_id` int(11) NOT NULL,
  `group_info_name` char(100) NOT NULL,
  `bill_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_group_info`
--

INSERT INTO `bill_group_info` (`bill_group_info_id`, `group_info_name`, `bill_group_id`) VALUES
(3, 'AERC', 3),
(4, 'BBV', 4),
(5, 'CCCER', 5),
(6, 'A', 6),
(7, 'B', 6),
(8, 'C', 6),
(9, 'A', 7),
(10, 'S', 7),
(11, 'E', 7),
(12, 'S', 8),
(13, 'b', 9),
(14, 'a', 9),
(15, '50', 10),
(16, 'NTPLC', 13),
(17, 'NN', 14),
(18, 'NTPLC', 15),
(19, 'A', 16),
(20, 'B', 16),
(21, '1', 17),
(22, '2', 17),
(23, '034515974', 18),
(24, '3451J0998', 18),
(25, 'C010003096', 18),
(26, '034510990', 19),
(27, '3451J1157', 19),
(28, 'C010003095', 19),
(29, '034510825', 20),
(30, '034510991', 20),
(31, '034510533', 21),
(32, '3452๋J1009', 21),
(33, 'C010003094', 21),
(34, 'หาไม่เจอ', 22),
(35, '100', 23),
(36, '100', 24),
(37, 'Smart Solution 300MB/300MBps', 25),
(38, 'A', 26),
(39, 'NTPLC', 26),
(40, '1234,5678,7894', 30),
(41, '8994,7884', 31),
(42, '2345', 32),
(43, 'บิลเดี่ยว', 32),
(44, '3345,8788', 33);

-- --------------------------------------------------------

--
-- Table structure for table `bill_notes`
--

CREATE TABLE `bill_notes` (
  `note_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `note_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_notes`
--

INSERT INTO `bill_notes` (`note_id`, `bill_id`, `note_text`, `created_at`, `updated_at`) VALUES
(1, 17, 'ประเภทบิล speacial', '2025-01-05 17:40:41', '2025-01-05 17:41:23'),
(2, 3, 'ฟฟ', '2025-01-06 06:38:44', '2025-01-06 06:38:44'),
(3, 23, 'hh', '2025-01-09 08:41:31', '2025-01-09 08:41:31'),
(4, 15, 'ต้องเพิ่มอุปกรณ์', '2025-01-09 08:49:27', '2025-01-09 08:49:27'),
(5, 22, 'ยังแก้ไม่ได้', '2025-01-10 04:24:11', '2025-01-10 04:24:11'),
(6, 24, 'อะไร', '2025-01-10 08:53:45', '2025-01-10 08:53:45'),
(7, 25, 'l', '2025-01-12 10:09:39', '2025-01-12 10:09:39');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` char(100) DEFAULT NULL,
  `customer_type` enum('เทศบาลเมือง','เทศบาลตำบล','อบจ','อบต','บริษัทเอกชน','โรงพยาบาล','วัด','มูลนิธิ','โรงเรียนประถม','โรงเรียนมัธยม','โรงเรียนเอกชน','วิทยาลัย','มหาวิทยาลัย') DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_status` enum('Active','Inactive') DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `create_at` datetime DEFAULT current_timestamp(),
  `update_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `customer_type`, `customer_phone`, `customer_status`, `address_id`, `create_at`, `update_at`) VALUES
(3, 'การไฟฟ้า', 'เทศบาลเมือง', 'ค.ฝน 034556668', 'Active', 198, '2024-12-31 00:00:00', '2025-01-10'),
(4, 'มูลนิธิกระจกเงา', 'มูลนิธิ', '0123456789', 'Inactive', 45, '2024-12-17 08:46:33', '2024-12-14'),
(6, 'บริษัท สุริยะค้าเหล็ก', 'เทศบาลตำบล', 'aaa', 'Active', 46, '2024-12-18 08:46:40', '2024-12-14'),
(36, 'Fenty', 'อบจ', '0123456789', 'Active', 72, '2024-12-19 00:00:00', '2024-12-17'),
(81, 'Papa', 'เทศบาลเมือง', 'ย.09755884321', 'Active', 191, '2024-12-19 00:00:00', '2025-01-10'),
(82, 'Mama', 'เทศบาลตำบล', 'ก.05087544861', 'Active', 192, '2025-01-02 00:00:00', '2025-01-10'),
(126, 'สำนักงานเทศบาลตำบลปากแพรก', 'อบต', 'ปากแฟรก 0801546555', 'Active', 189, '2025-01-02 00:00:00', '2024-12-20'),
(127, 'dd', 'โรงพยาบาล', '0123456789', 'Active', 190, '2023-12-28 00:00:00', '2024-12-23'),
(132, 'สำนักปลัด', 'เทศบาลเมือง', 'ค.ชัย 0974555678', 'Active', 211, NULL, '2025-01-10');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_type` enum('-','Fttx.','Fttx+ICT Solution','Fttx 2 โครงข่าย+ICT Solution','บริการ SI','วงจรเช่า','IP Phone','Smart City','WIfi','อื่นๆ') NOT NULL,
  `equipment_service` enum('เช่าอุปกรณ์','ขายขาดอุปกรณ์','ผสมเช่าและขายขาด','-') NOT NULL,
  `bill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_type`, `equipment_service`, `bill_id`) VALUES
(1, 'Fttx.', 'เช่าอุปกรณ์', 17),
(2, 'Fttx 2 โครงข่าย+ICT Solution', 'ขายขาดอุปกรณ์', 19);

-- --------------------------------------------------------

--
-- Table structure for table `thambon`
--

CREATE TABLE `thambon` (
  `thambon_id` int(11) NOT NULL,
  `thambon_name` varchar(255) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `amphure_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thambon`
--

INSERT INTO `thambon` (`thambon_id`, `thambon_name`, `zip_code`, `amphure_id`) VALUES
(710101, 'บ้านเหนือ', '71000', 7101),
(710102, 'บ้านใต้', '71000', 7101),
(710103, 'ปากแพรก', '71000', 7101),
(710104, 'ท่ามะขาม', '71000', 7101),
(710105, 'แก่งเสี้ยน', '71000', 7101),
(710106, 'หนองบัว', '71190', 7101),
(710107, 'ลาดหญ้า', '71190', 7101),
(710108, 'วังด้ง', '71190', 7101),
(710109, 'ช่องสะเดา', '71190', 7101),
(710110, 'หนองหญ้า', '71000', 7101),
(710111, 'เกาะสำโรง', '71000', 7101),
(710113, 'บ้านเก่า', '71000', 7101),
(710116, 'วังเย็น', '71000', 7101),
(710201, 'ลุ่มสุ่ม', '71150', 7102),
(710202, 'ท่าเสา', '71150', 7102),
(710203, 'สิงห์', '71150', 7102),
(710204, 'ไทรโยค', '71150', 7102),
(710205, 'วังกระแจะ', '71150', 7102),
(710206, 'ศรีมงคล', '71150', 7102),
(710207, 'บ้องตี้', '71150', 7102),
(710301, 'บ่อพลอย', '71160', 7103),
(710302, 'หนองกุ่ม', '71160', 7103),
(710303, 'หนองรี', '71220', 7103),
(710305, 'หลุมรัง', '71160', 7103),
(710308, 'ช่องด่าน', '71160', 7103),
(710309, 'หนองกร่าง', '71220', 7103),
(710401, 'นาสวน', '71250', 7104),
(710402, 'ด่านแม่แฉลบ', '71250', 7104),
(710403, 'หนองเป็ด', '71250', 7104),
(710404, 'ท่ากระดาน', '71250', 7104),
(710405, 'เขาโจด', '71220', 7104),
(710406, 'แม่กระบุง', '71250', 7104),
(710501, 'พงตึก', '71120', 7105),
(710502, 'ยางม่วง', '71120', 7105),
(710503, 'ดอนชะเอม', '71130', 7105),
(710504, 'ท่าไม้', '71120', 7105),
(710505, 'ตะคร้ำเอน', '71130', 7105),
(710506, 'ท่ามะกา', '71120', 7105),
(710507, 'ท่าเรือ', '71130', 7105),
(710508, 'โคกตะบอง', '71120', 7105),
(710509, 'ดอนขมิ้น', '71120', 7105),
(710510, 'อุโลกสี่หมื่น', '71130', 7105),
(710511, 'เขาสามสิบหาบ', '71120', 7105),
(710512, 'พระแท่น', '71130', 7105),
(710513, 'หวายเหนียว', '71120', 7105),
(710514, 'แสนตอ', '71130', 7105),
(710515, 'สนามแย้', '70190', 7105),
(710516, 'ท่าเสา', '71120', 7105),
(710517, 'หนองลาน', '71130', 7105),
(710601, 'ท่าม่วง', '71110', 7106),
(710602, 'วังขนาย', '71110', 7106),
(710603, 'วังศาลา', '71110', 7106),
(710604, 'ท่าล้อ', '71000', 7106),
(710605, 'หนองขาว', '71110', 7106),
(710606, 'ทุ่งทอง', '71110', 7106),
(710607, 'เขาน้อย', '71110', 7106),
(710608, 'ม่วงชุม', '71110', 7106),
(710609, 'บ้านใหม่', '71110', 7106),
(710610, 'พังตรุ', '71110', 7106),
(710611, 'ท่าตะคร้อ', '71130', 7106),
(710612, 'รางสาลี่', '71110', 7106),
(710613, 'หนองตากยา', '71110', 7106),
(710701, 'ท่าขนุน', '71180', 7107),
(710702, 'ปิล๊อก', '71180', 7107),
(710703, 'หินดาด', '71180', 7107),
(710704, 'ลิ่นถิ่น', '71180', 7107),
(710705, 'ชะแล', '71180', 7107),
(710706, 'ห้วยเขย่ง', '71180', 7107),
(710707, 'สหกรณ์นิคม', '71180', 7107),
(710801, 'หนองลู', '71240', 7108),
(710802, 'ปรังเผล', '71240', 7108),
(710803, 'ไล่โว่', '71240', 7108),
(710901, 'พนมทวน', '71140', 7109),
(710902, 'หนองโรง', '71140', 7109),
(710903, 'ทุ่งสมอ', '71140', 7109),
(710904, 'ดอนเจดีย์', '71140', 7109),
(710905, 'พังตรุ', '71140', 7109),
(710906, 'รางหวาย', '71170', 7109),
(710911, 'หนองสาหร่าย', '71140', 7109),
(710912, 'ดอนตาเพชร', '71140', 7109),
(711001, 'เลาขวัญ', '71210', 7110),
(711002, 'หนองโสน', '71210', 7110),
(711003, 'หนองประดู่', '71210', 7110),
(711004, 'หนองปลิง', '71210', 7110),
(711005, 'หนองนกแก้ว', '71210', 7110),
(711006, 'ทุ่งกระบ่ำ', '71210', 7110),
(711007, 'หนองฝ้าย', '71210', 7110),
(711101, 'ด่านมะขามเตี้ย', '71260', 7111),
(711102, 'กลอนโด', '71260', 7111),
(711103, 'จรเข้เผือก', '71260', 7111),
(711104, 'หนองไผ่', '71260', 7111),
(711201, 'หนองปรือ', '71220', 7112),
(711202, 'หนองปลาไหล', '71220', 7112),
(711203, 'สมเด็จเจริญ', '71220', 7112),
(711301, 'ห้วยกระเจา', '71170', 7113),
(711302, 'วังไผ่', '71170', 7113),
(711303, 'ดอนแสลบ', '71170', 7113),
(711304, 'สระลงเรือ', '71170', 7113);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `verify` tinyint(1) DEFAULT 0,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `otp_attempts` int(11) DEFAULT 0,
  `last_otp_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `verify`, `otp`, `otp_expiry`, `otp_attempts`, `last_otp_sent`) VALUES
(15, 'rattapoom.p@ku.th', 'Chatchai', '$2y$10$JRRG/Tl3jBYrIyvhSH.KJecyGXNhgW3W8xkxT8dIN2fJ7O1bNxgTe', 1, NULL, NULL, 0, '2025-01-10 14:52:09'),
(42, 'kanyanut.somb@ku.th', 'Kanyanut', '$2y$10$hy2AT08v138XLHvhfQi.PuqRS3hjMPZuqhFRnxt4wsCRYBoXYPTlG', 1, NULL, NULL, 0, '2025-01-13 08:43:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `amphure_id` (`amphure_id`),
  ADD KEY `thambon_id` (`thambon_id`);

--
-- Indexes for table `amphure`
--
ALTER TABLE `amphure`
  ADD PRIMARY KEY (`amphure_id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `bill_group`
--
ALTER TABLE `bill_group`
  ADD PRIMARY KEY (`bill_group_id`),
  ADD KEY `bill_id` (`bill_id`);

--
-- Indexes for table `bill_group_info`
--
ALTER TABLE `bill_group_info`
  ADD PRIMARY KEY (`bill_group_info_id`),
  ADD KEY `bill_group_id` (`bill_group_id`);

--
-- Indexes for table `bill_notes`
--
ALTER TABLE `bill_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `fk_bill_notes_bill` (`bill_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `bill_service_1` (`bill_id`);

--
-- Indexes for table `thambon`
--
ALTER TABLE `thambon`
  ADD PRIMARY KEY (`thambon_id`),
  ADD KEY `amphure_id` (`amphure_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bill_group`
--
ALTER TABLE `bill_group`
  MODIFY `bill_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `bill_group_info`
--
ALTER TABLE `bill_group_info`
  MODIFY `bill_group_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `bill_notes`
--
ALTER TABLE `bill_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`amphure_id`) REFERENCES `amphure` (`amphure_id`),
  ADD CONSTRAINT `address_ibfk_2` FOREIGN KEY (`thambon_id`) REFERENCES `thambon` (`thambon_id`);

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `bill_group`
--
ALTER TABLE `bill_group`
  ADD CONSTRAINT `bill_group_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`bill_id`);

--
-- Constraints for table `bill_group_info`
--
ALTER TABLE `bill_group_info`
  ADD CONSTRAINT `bill_group_info_ibfk_1` FOREIGN KEY (`bill_group_id`) REFERENCES `bill_group` (`bill_group_id`);

--
-- Constraints for table `bill_notes`
--
ALTER TABLE `bill_notes`
  ADD CONSTRAINT `fk_bill_notes_bill` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`bill_id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `bill_service_1` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`bill_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thambon`
--
ALTER TABLE `thambon`
  ADD CONSTRAINT `thambon_ibfk_1` FOREIGN KEY (`amphure_id`) REFERENCES `amphure` (`amphure_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
