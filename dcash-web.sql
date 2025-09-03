-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 29, 2025 at 11:32 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dcash-web`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_type` enum('image','video','slider') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json NOT NULL,
  `cta_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` bigint UNSIGNED NOT NULL DEFAULT '0',
  `clicks` bigint UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content_type`, `content`, `cta_text`, `cta_link`, `views`, `clicks`, `is_active`, `starts_at`, `ends_at`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Slider 1', 'slider', '{\"paths\": [\"announcements/sliders/fwIEgdloGdr7LkOqchxL185JvvjvmIr4uf7Ui7Qh.webp\", \"announcements/sliders/I0p7nD14Rm8QafItnBrLmxjV7dchYMzD1Y8mqXPx.webp\", \"announcements/sliders/EG2HvfrbwQkKgpKjBxe38pbHYFxM2VA00neIhh18.webp\"]}', NULL, NULL, 0, 0, 1, '2025-08-13 12:29:53', NULL, 'published', '2025-08-13 12:29:53', '2025-08-13 12:29:53'),
(4, 'Single Images', 'image', '{\"path\": \"announcements/BeAAceWThc7W5cOzF7WIvTH8E5nUscsuYEhQ94rg.webp\"}', NULL, NULL, 0, 0, 1, '2025-08-13 12:43:02', NULL, 'published', '2025-08-13 12:43:02', '2025-08-13 12:43:02'),
(5, 'Video', 'video', '{\"url\": \"https://youtu.be/Y4n_p9w8pGY?si=Su83sZPjeNguP6cL\"}', NULL, NULL, 0, 0, 1, '2025-08-13 12:44:56', NULL, 'published', '2025-08-13 12:44:56', '2025-08-13 12:44:56');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int NOT NULL,
  `code` varchar(22) NOT NULL,
  `name` varchar(66) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(1, '044', 'Access Bank', '2022-06-17 14:53:00', NULL),
(2, '023', 'Citi Bank', '2022-06-17 14:53:00', NULL),
(3, '050', 'EcoBank PLC', '2022-06-17 14:53:00', NULL),
(4, '011', 'First Bank PLC', '2022-06-17 14:53:00', NULL),
(5, '214', 'First City Monument Bank', '2022-06-17 14:53:00', NULL),
(6, '070', 'Fidelity Bank', '2022-06-17 14:53:00', NULL),
(7, '058', 'Guaranty Trust Bank', '2022-06-17 14:53:00', NULL),
(8, '076', 'Polaris bank', '2022-06-17 14:53:00', NULL),
(9, '221', 'Stanbic IBTC Bank', '2022-06-17 14:53:00', NULL),
(10, '068', 'Standard Chaterted bank PLC', '2022-06-17 14:53:00', NULL),
(11, '232', 'Sterling Bank PLC', '2022-06-17 14:53:00', NULL),
(12, '033', 'United Bank for Africa', '2022-06-17 14:53:00', NULL),
(13, '032', 'Union Bank PLC', '2022-06-17 14:53:00', NULL),
(14, '035', 'Wema Bank PLC', '2022-06-17 14:53:00', NULL),
(15, '057', 'Zenith bank PLC', '2022-06-17 14:53:00', NULL),
(16, '215', 'Unity Bank PLC', '2022-06-17 14:53:00', NULL),
(17, '101', 'ProvidusBank PLC', '2022-06-17 14:53:00', NULL),
(18, '082', 'Keystone Bank', '2022-06-17 14:53:00', NULL),
(19, '301', 'Jaiz Bank', '2022-06-17 14:53:00', NULL),
(20, '030', 'Heritage Bank', '2022-06-17 14:53:00', NULL),
(21, '100', 'Suntrust Bank', '2022-06-17 14:53:00', NULL),
(22, '608', 'FINATRUST MICROFINANCE BANK', '2022-06-17 14:53:00', NULL),
(23, '090175', 'Rubies Microfinance Bank', '2022-06-17 14:53:00', NULL),
(24, '090267', 'Kuda', '2022-06-17 14:53:00', NULL),
(25, '090115', 'TCF MFB', '2022-06-17 14:53:00', NULL),
(26, '400001', 'FSDH Merchant Bank', '2022-06-17 14:53:00', NULL),
(27, '502', 'Rand merchant Bank', '2022-06-17 14:53:00', NULL),
(28, '103', 'Globus Bank', '2022-06-17 14:53:00', NULL),
(29, '327', 'Paga', '2022-06-17 14:53:00', NULL),
(30, '000026', 'Taj Bank Limited', '2022-06-17 14:53:00', NULL),
(31, '100022', 'GoMoney', '2022-06-17 14:53:00', NULL),
(32, '090180', 'AMJU Unique Microfinance Bank', '2022-06-17 14:53:00', NULL),
(33, '090393', 'BRIDGEWAY MICROFINANCE BANK', '2022-06-17 14:53:00', NULL),
(34, '090328', 'Eyowo MFB', '2022-06-17 14:53:00', NULL),
(35, '090281', 'Mint-Finex MICROFINANCE BANK', '2022-06-17 14:53:00', NULL),
(36, '070006', 'Covenant Microfinance Bank', '2022-06-17 14:53:00', NULL),
(37, '090110', 'VFD Micro Finance Bank', '2022-06-17 14:53:00', NULL),
(38, '090317', 'PatrickGold Microfinance Bank', '2022-06-17 14:53:00', NULL),
(39, '090325', 'Sparkle', '2022-06-17 14:53:00', NULL),
(40, '305', 'OPAY(Paycom)', '2022-06-17 14:53:00', NULL),
(41, '070001', 'NPF MicroFinance Bank', '2022-06-17 14:53:00', NULL),
(42, '110001', 'PayAttitude Online', '2022-06-17 14:53:00', NULL),
(43, '100027', 'Intellifin', '2022-06-17 14:53:00', NULL),
(44, '100032', 'Contec Global Infotech Limited (NowNow)', '2022-06-17 14:53:00', NULL),
(45, '100031', 'FCMB Easy Account', '2022-06-17 14:53:00', NULL),
(46, '100030', 'EcoMobile', '2022-06-17 14:53:00', NULL),
(47, '100029', 'Innovectives Kesh', '2022-06-17 14:53:00', NULL),
(48, '100026', 'One Finance', '2022-06-17 14:53:00', NULL),
(49, '100025', 'Zinternet Nigera Limited', '2022-06-17 14:53:00', NULL),
(50, '100023', 'TagPay', '2022-06-17 14:53:00', NULL),
(51, '100021', 'Eartholeum', '2022-06-17 14:53:00', NULL),
(52, '100020', 'MoneyBox', '2022-06-17 14:53:00', NULL),
(53, '100019', 'Fidelity Mobile', '2022-06-17 14:53:00', NULL),
(54, '000019', 'Enterprise Bank', '2022-06-17 14:53:00', NULL),
(55, '060001', 'Coronation Merchant Bank', '2022-06-17 14:53:00', NULL),
(56, '060002', 'FBNQUEST Merchant Bank', '2022-06-17 14:53:00', NULL),
(57, '060003', 'Nova Merchant Bank', '2022-06-17 14:53:00', NULL),
(58, '070007', 'Omoluabi savings and loans', '2022-06-17 14:53:00', NULL),
(59, '090001', 'ASOSavings & Loans', '2022-06-17 14:53:00', NULL),
(60, '090005', 'Trustbond Mortgage Bank', '2022-06-17 14:53:00', NULL),
(61, '090006', 'SafeTrust ', '2022-06-17 14:53:00', NULL),
(62, '090107', 'FBN Mortgages Limited', '2022-06-17 14:53:00', NULL),
(63, '100024', 'Imperial Homes Mortgage Bank', '2022-06-17 14:53:00', NULL),
(64, '100028', 'AG Mortgage Bank', '2022-06-17 14:53:00', NULL),
(65, '070009', 'Gateway Mortgage Bank', '2022-06-17 14:53:00', NULL),
(66, '070010', 'Abbey Mortgage Bank', '2022-06-17 14:53:00', NULL),
(67, '070011', 'Refuge Mortgage Bank', '2022-06-17 14:53:00', NULL),
(68, '070012', 'Lagos Building Investment Company', '2022-06-17 14:53:00', NULL),
(69, '070013', 'Platinum Mortgage Bank', '2022-06-17 14:53:00', NULL),
(70, '070014', 'First Generation Mortgage Bank', '2022-06-17 14:53:00', NULL),
(71, '070015', 'Brent Mortgage Bank', '2022-06-17 14:53:00', NULL),
(72, '070016', 'Infinity Trust Mortgage Bank', '2022-06-17 14:53:00', NULL),
(73, '090003', 'Jubilee-Life Mortgage  Bank', '2022-06-17 14:53:00', NULL),
(74, '070017', 'Haggai Mortgage Bank Limited', '2022-06-17 14:53:00', NULL),
(75, '090108', 'New Prudential Bank', '2022-06-17 14:53:00', NULL),
(76, '070002', 'Fortis Microfinance Bank', '2022-06-17 14:53:00', NULL),
(77, '070008', 'Page Financials', '2022-06-17 14:53:00', NULL),
(78, '090004', 'Parralex Microfinance bank', '2022-06-17 14:53:00', NULL),
(79, '090097', 'Ekondo MFB', '2022-06-17 14:53:00', NULL),
(80, '090112', 'Seed Capital Microfinance Bank', '2022-06-17 14:53:00', NULL),
(81, '090114', 'Empire trust MFB', '2022-06-17 14:53:00', NULL),
(82, '090116', 'AMML MFB', '2022-06-17 14:53:00', NULL),
(83, '090117', 'Boctrust Microfinance Bank', '2022-06-17 14:53:00', NULL),
(84, '090118', 'IBILE Microfinance Bank', '2022-06-17 14:53:00', NULL),
(85, '090119', 'Ohafia Microfinance Bank', '2022-06-17 14:53:00', NULL),
(86, '090120', 'Wetland Microfinance Bank', '2022-06-17 14:53:00', NULL),
(87, '090121', 'Hasal Microfinance Bank', '2022-06-17 14:53:00', NULL),
(88, '090122', 'Gowans Microfinance Bank', '2022-06-17 14:53:00', NULL),
(89, '090123', 'Verite Microfinance Bank', '2022-06-17 14:53:00', NULL),
(90, '090124', 'Xslnce Microfinance Bank', '2022-06-17 14:53:00', NULL),
(91, '090125', 'Regent Microfinance Bank', '2022-06-17 14:53:00', NULL),
(92, '090126', 'Fidfund Microfinance Bank', '2022-06-17 14:53:00', NULL),
(93, '090127', 'BC Kash Microfinance Bank', '2022-06-17 14:53:00', NULL),
(94, '090128', 'Ndiorah Microfinance Bank', '2022-06-17 14:53:00', NULL),
(95, '090129', 'Money Trust Microfinance Bank', '2022-06-17 14:53:00', NULL),
(96, '090130', 'Consumer Microfinance Bank', '2022-06-17 14:53:00', NULL),
(97, '090131', 'Allworkers Microfinance Bank', '2022-06-17 14:53:00', NULL),
(98, '090132', 'Richway Microfinance Bank', '2022-06-17 14:53:00', NULL),
(99, '090133', ' AL-Barakah Microfinance Bank', '2022-06-17 14:53:00', NULL),
(100, '090134', 'Accion Microfinance Bank', '2022-06-17 14:53:00', NULL),
(101, '090135', 'Personal Trust Microfinance Bank', '2022-06-17 14:53:00', NULL),
(102, '090136', 'Baobab Microfinance Bank', '2022-06-17 14:53:00', NULL),
(103, '090137', 'PecanTrust Microfinance Bank', '2022-06-17 14:53:00', NULL),
(104, '090138', 'Royal Exchange Microfinance Bank', '2022-06-17 14:53:00', NULL),
(105, '090139', 'Visa Microfinance Bank', '2022-06-17 14:53:00', NULL),
(106, '090140', 'Sagamu Microfinance Bank', '2022-06-17 14:53:00', NULL),
(107, '090141', 'Chikum Microfinance Bank', '2022-06-17 14:53:00', NULL),
(108, '090142', 'Yes Microfinance Bank', '2022-06-17 14:53:00', NULL),
(109, '090143', 'Apeks Microfinance Bank', '2022-06-17 14:53:00', NULL),
(110, '090144', 'CIT Microfinance Bank', '2022-06-17 14:53:00', NULL),
(111, '090145', 'Fullrange Microfinance Bank', '2022-06-17 14:53:00', NULL),
(112, '090146', 'Trident Microfinance Bank', '2022-06-17 14:53:00', NULL),
(113, '090147', 'Hackman Microfinance Bank', '2022-06-17 14:53:00', NULL),
(114, '090148', 'Bowen Microfinance Bank', '2022-06-17 14:53:00', NULL),
(115, '090149', 'IRL Microfinance Bank', '2022-06-17 14:53:00', NULL),
(116, '090150', 'Virtue Microfinance Bank', '2022-06-17 14:53:00', NULL),
(117, '090151', 'Mutual Trust Microfinance Bank', '2022-06-17 14:53:00', NULL),
(118, '090152', 'Nagarta Microfinance Bank', '2022-06-17 14:53:00', NULL),
(119, '090153', 'FFS Microfinance Bank', '2022-06-17 14:53:00', NULL),
(120, '090154', 'CEMCS Microfinance Bank', '2022-06-17 14:53:00', NULL),
(121, '090155', 'La  Fayette Microfinance Bank', '2022-06-17 14:53:00', NULL),
(122, '090156', 'e-Barcs Microfinance Bank', '2022-06-17 14:53:00', NULL),
(123, '090157', 'Infinity Microfinance Bank', '2022-06-17 14:53:00', NULL),
(124, '090158', 'Futo Microfinance Bank', '2022-06-17 14:53:00', NULL),
(125, '090159', 'Credit Afrique Microfinance Bank', '2022-06-17 14:53:01', NULL),
(126, '090160', 'Addosser Microfinance Bank', '2022-06-17 14:53:01', NULL),
(127, '090161', 'Okpoga Microfinance Bank', '2022-06-17 14:53:01', NULL),
(128, '090162', 'Stanford Microfinance Bak', '2022-06-17 14:53:01', NULL),
(129, '090164', 'First Royal Microfinance Bank', '2022-06-17 14:53:01', NULL),
(130, '090165', 'Petra Microfinance Bank', '2022-06-17 14:53:01', NULL),
(131, '090166', 'Eso-E Microfinance Bank', '2022-06-17 14:53:01', NULL),
(132, '090167', 'Daylight Microfinance Bank', '2022-06-17 14:53:01', NULL),
(133, '090168', 'Gashua Microfinance Bank', '2022-06-17 14:53:01', NULL),
(134, '090169', 'Alpha Kapital Microfinance Bank', '2022-06-17 14:53:01', NULL),
(135, '090171', 'Mainstreet Microfinance Bank', '2022-06-17 14:53:01', NULL),
(136, '090172', 'Astrapolaris Microfinance Bank', '2022-06-17 14:53:01', NULL),
(137, '090173', 'Reliance Microfinance Bank', '2022-06-17 14:53:01', NULL),
(138, '090174', 'Malachy Microfinance Bank', '2022-06-17 14:53:01', NULL),
(139, '090175', 'HighStreet Microfinance Bank', '2022-06-17 14:53:01', NULL),
(140, '090176', 'Bosak Microfinance Bank', '2022-06-17 14:53:01', NULL),
(141, '090177', 'Lapo Microfinance Bank', '2022-06-17 14:53:01', NULL),
(142, '090178', 'GreenBank Microfinance Bank', '2022-06-17 14:53:01', NULL),
(143, '090179', 'FAST Microfinance Bank', '2022-06-17 14:53:01', NULL),
(144, '090188', 'Baines Credit Microfinance Bank', '2022-06-17 14:53:01', NULL),
(145, '090189', 'Esan Microfinance Bank', '2022-06-17 14:53:01', NULL),
(146, '090190', 'Mutual Benefits Microfinance Bank', '2022-06-17 14:53:01', NULL),
(147, '090191', 'KCMB Microfinance Bank', '2022-06-17 14:53:01', NULL),
(148, '090192', 'Midland Microfinance Bank', '2022-06-17 14:53:01', NULL),
(149, '090193', 'Unical Microfinance Bank', '2022-06-17 14:53:01', NULL),
(150, '090194', 'NIRSAL Microfinance Bank', '2022-06-17 14:53:01', NULL),
(151, '090195', 'Grooming Microfinance Bank', '2022-06-17 14:53:01', NULL),
(152, '090196', 'Pennywise Microfinance Bank', '2022-06-17 14:53:01', NULL),
(153, '090197', 'ABU Microfinance Bank', '2022-06-17 14:53:01', NULL),
(154, '090198', 'RenMoney Microfinance Bank', '2022-06-17 14:53:01', NULL),
(155, '090205', 'New Dawn Microfinance Bank', '2022-06-17 14:53:01', NULL),
(156, '090251', 'UNN MFB', '2022-06-17 14:53:01', NULL),
(157, '090258', 'Imo State Microfinance Bank', '2022-06-17 14:53:01', NULL),
(158, '090259', 'Alekun Microfinance Bank', '2022-06-17 14:53:01', NULL),
(159, '090260', 'Above Only Microfinance Bank', '2022-06-17 14:53:01', NULL),
(160, '090261', 'Quickfund Microfinance Bank', '2022-06-17 14:53:01', NULL),
(161, '090262', 'Stellas Microfinance Bank', '2022-06-17 14:53:01', NULL),
(162, '090263', 'Navy Microfinance Bank', '2022-06-17 14:53:01', NULL),
(163, '090264', 'Auchi Microfinance Bank', '2022-06-17 14:53:01', NULL),
(164, '090265', 'Lovonus Microfinance Bank', '2022-06-17 14:53:01', NULL),
(165, '090266', 'Uniben Microfinance Bank', '2022-06-17 14:53:01', NULL),
(166, '090268', 'Adeyemi College Staff Microfinance Bank', '2022-06-17 14:53:01', NULL),
(167, '090269', 'Greenville Microfinance Bank', '2022-06-17 14:53:01', NULL),
(168, '090270', 'AB Microfinance Bank', '2022-06-17 14:53:01', NULL),
(169, '090271', 'Lavender Microfinance Bank', '2022-06-17 14:53:01', NULL),
(170, '090272', 'Olabisi Onabanjo University Microfinance Bank', '2022-06-17 14:53:01', NULL),
(171, '090273', 'Emeralds Microfinance Bank', '2022-06-17 14:53:01', NULL),
(172, '090276', 'Trustfund Microfinance Bank', '2022-06-17 14:53:01', NULL),
(173, '090277', 'Al-Hayat Microfinance Bank', '2022-06-17 14:53:01', NULL),
(174, '100001', 'FET', '2022-06-17 14:53:01', NULL),
(175, '100003', 'Parkway-ReadyCash', '2022-06-17 14:53:01', NULL),
(176, '100005', 'Cellulant', '2022-06-17 14:53:01', NULL),
(177, '100006', 'eTranzact', '2022-06-17 14:53:01', NULL),
(178, '100007', 'Stanbic IBTC @ease wallet', '2022-06-17 14:53:01', NULL),
(179, '100008', 'Ecobank Xpress Account', '2022-06-17 14:53:01', NULL),
(180, '100009', 'GTMobile', '2022-06-17 14:53:01', NULL),
(181, '100010', 'TeasyMobile', '2022-06-17 14:53:01', NULL),
(182, '100011', 'Mkudi', '2022-06-17 14:53:01', NULL),
(183, '100012', 'VTNetworks', '2022-06-17 14:53:01', NULL),
(184, '100013', 'AccessMobile', '2022-06-17 14:53:01', NULL),
(185, '100014', 'FBNMobile', '2022-06-17 14:53:01', NULL),
(186, '100015', 'Kegow', '2022-06-17 14:53:01', NULL),
(187, '100016', 'FortisMobile', '2022-06-17 14:53:01', NULL),
(188, '100017', 'Hedonmark', '2022-06-17 14:53:01', NULL),
(189, '100018', 'ZenithMobile', '2022-06-17 14:53:01', NULL),
(190, '110002', 'Flutterwave Technology Solutions Limited', '2022-06-17 14:53:01', NULL),
(191, '999999', 'NIP Virtual Bank', '2022-06-17 14:53:01', NULL),
(192, '000025', 'Titan Trust Bank', '2022-06-17 14:53:01', NULL),
(193, '303', 'ChamsMobile', '2022-06-17 14:53:01', NULL),
(194, '090423', 'MAUTECH Microfinance Bank', '2022-06-17 14:53:01', NULL),
(195, '060004', 'Greenwich Merchant Bank', '2022-06-17 14:53:01', NULL),
(196, '000030', 'Parallex Bank', '2022-06-17 14:53:01', NULL),
(197, '090366', 'Firmus MFB', '2022-06-17 14:53:01', NULL),
(198, '100033', 'PALMPAY', '2022-06-17 14:53:01', NULL),
(199, '090383', 'Manny Microfinance bank', '2022-06-17 14:53:01', NULL),
(200, '090420', 'Letshego MFB', '2022-06-17 14:53:01', NULL),
(201, '100035', 'M36', '2022-06-17 14:53:01', NULL),
(202, '090286', 'Safe Haven MFB', '2022-06-17 14:53:01', NULL),
(203, '120001', '9 Payment Service Bank', '2022-06-17 14:53:01', NULL),
(204, '090426', 'Tangerine Bank', '2022-06-17 14:53:01', NULL),
(205, '090482', 'FEDETH MICROFINANCE BANK', '2022-06-17 14:53:01', NULL),
(206, '100026', 'Carbon', '2022-06-17 14:53:01', NULL),
(207, '090470', 'CHANGHAN RTS MICROFINANCE BANK', '2022-06-17 14:53:01', NULL),
(208, '000031', 'PremiumTrust Bank', '2022-06-17 14:53:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bank code for easier identification',
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_payments`
--

CREATE TABLE `bill_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flw_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biller_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biller_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NGN',
  `country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','successful','failed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `recurrence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee` decimal(8,2) DEFAULT NULL,
  `flw_response` json DEFAULT NULL,
  `callback_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `failure_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-7b52009b64fd0a2a49e6d8a939753077792b0554', 'i:1;', 1756471426),
('laravel-cache-7b52009b64fd0a2a49e6d8a939753077792b0554:timer', 'i:1756471426;', 1756471426);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_bank_accounts`
--

CREATE TABLE `company_bank_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `account_type` enum('g-cash','mobile_wallet','bank','crypto_wallet','paymaya') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank' COMMENT 'type of account',
  `bank_account_qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'QR code for non-crypto payments',
  `is_crypto` tinyint(1) NOT NULL DEFAULT '0',
  `crypto_wallet_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crypto_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crypto_network` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crypto_qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_bank_accounts`
--

INSERT INTO `company_bank_accounts` (`id`, `bank_name`, `account_number`, `account_name`, `currency_id`, `account_type`, `bank_account_qr_code`, `is_crypto`, `crypto_wallet_address`, `crypto_name`, `crypto_network`, `crypto_qr_code`, `qr`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Providus Bank', '1305520735', 'DCASH', 1, 'bank', NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-08-14 20:06:48'),
(5, 'Access bank', '9083773733', 'QX', 1, 'bank', 'images/bank_qr_codes/QGPV7JeqckVIi4z2HfNG1rdWD6SQ93WhdEyPhTJR.png', 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-08-13 10:13:37', '2025-08-13 10:17:29'),
(6, 'Fidelity Bank', '9837736332', 'QX', 1, 'bank', 'images/bank_qr_codes/CRJkoC2D65BeI3HdVYdp83N7yoQUs48CJyflGW1D.webp', 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-08-13 10:16:31', '2025-08-13 10:17:27'),
(8, NULL, NULL, NULL, 10, 'bank', NULL, 1, 'hhfeyeueeuww', 'USDT Wallet 1', 'TRC20', 'images/crypto_qr_codes/w3ZVbQPeSJhAO9MnjZrgsqNjQaUgry1CiOFfXLKX.png', NULL, 1, '2025-08-13 10:43:10', '2025-08-13 12:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fiat','crypto') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fiat',
  `symbol` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_wallet_supported` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `type`, `symbol`, `flag`, `is_wallet_supported`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Naira', 'NGN', 'fiat', '₦', 'images/flags/ng.png', 0, 1, NULL, NULL),
(7, 'Phillipine Pesos', 'PHP', 'fiat', '₱', 'images/flags/ph.png', 0, 1, '2025-08-13 07:22:32', '2025-08-13 07:22:32'),
(8, 'Ghanian Cedis', 'GHC', 'fiat', 'GH₵', 'images/flags/gh.jpg', 0, 1, '2025-08-13 07:23:26', '2025-08-13 07:23:26'),
(9, 'US Dollar', 'USD', 'fiat', '$', 'images/flags/us.png', 0, 1, '2025-08-13 07:26:08', '2025-08-13 07:26:08'),
(10, 'TetherUSDT', 'USDT', 'crypto', 'USDT', 'images/flags/usdt.png', 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `currency_pairs`
--

CREATE TABLE `currency_pairs` (
  `id` bigint UNSIGNED NOT NULL,
  `base_currency_id` bigint UNSIGNED NOT NULL,
  `quote_currency_id` bigint UNSIGNED NOT NULL,
  `rate` decimal(16,8) NOT NULL COMMENT 'rate to for calculations',
  `raw_rate` decimal(16,8) DEFAULT NULL COMMENT 'rate to display to front end',
  `auto_update` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency_pairs`
--

INSERT INTO `currency_pairs` (`id`, `base_currency_id`, `quote_currency_id`, `rate`, `raw_rate`, `auto_update`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 29.00000000, NULL, 0, 1, '2025-08-13 07:22:32', '2025-08-13 07:22:32'),
(2, 1, 7, 28.00000000, NULL, 0, 1, '2025-08-13 07:22:32', '2025-08-13 07:22:32'),
(3, 8, 1, 23.00000000, NULL, 0, 1, '2025-08-13 07:23:26', '2025-08-13 07:23:26'),
(4, 1, 8, 21.00000000, NULL, 0, 1, '2025-08-13 07:23:26', '2025-08-13 07:23:26'),
(5, 8, 7, 44.00000000, NULL, 0, 1, '2025-08-13 07:23:26', '2025-08-13 07:23:26'),
(6, 7, 8, 43.00000000, NULL, 0, 1, '2025-08-13 07:23:26', '2025-08-13 07:23:26'),
(7, 9, 1, 1500.00000000, NULL, 0, 1, '2025-08-13 07:26:08', '2025-08-13 07:26:08'),
(8, 1, 9, 1620.00000000, NULL, 0, 1, '2025-08-13 07:26:08', '2025-08-13 07:26:08'),
(9, 9, 7, 54.00000000, NULL, 0, 1, '2025-08-13 07:26:08', '2025-08-13 07:26:08'),
(10, 7, 9, 56.00000000, NULL, 0, 1, '2025-08-13 07:26:08', '2025-08-13 07:26:08'),
(11, 9, 8, 41.00000000, NULL, 0, 1, '2025-08-13 07:26:08', '2025-08-13 07:26:08'),
(12, 8, 9, 45.00000000, NULL, 0, 1, '2025-08-13 07:26:08', '2025-08-13 07:26:08'),
(13, 10, 1, 1550.00000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27'),
(14, 1, 10, 1600.00000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27'),
(15, 10, 7, 23.00000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27'),
(16, 7, 10, 21.00000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27'),
(17, 10, 8, 34.00000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27'),
(18, 8, 10, 32.00000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27'),
(19, 10, 9, 0.97000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27'),
(20, 9, 10, 1.05000000, NULL, 0, 1, '2025-08-13 10:19:27', '2025-08-13 10:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `exchange_transactions`
--

CREATE TABLE `exchange_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_bank_account_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `from_currency_id` bigint UNSIGNED NOT NULL,
  `to_currency_id` bigint UNSIGNED NOT NULL,
  `amount_from` decimal(24,8) NOT NULL,
  `amount_to` decimal(24,8) NOT NULL,
  `rate` decimal(24,8) NOT NULL,
  `recipient_bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_wallet_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User''s sending crypto wallet address for verification',
  `recipient_network` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g., TRC20, ERC20, BEP20',
  `payment_transaction_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Transaction hash/ID of the user''s crypto payment',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to the uploaded payment receipt file',
  `status` enum('pending_payment','pending_confirmation','processing','completed','failed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, '3c9564f4-46c6-49c1-8b8d-b5212e1e215b', 'database', 'default', '{\"uuid\":\"3c9564f4-46c6-49c1-8b8d-b5212e1e215b\",\"displayName\":\"App\\\\Mail\\\\app\\\\OtpVerificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\app\\\\OtpVerificationMail\\\":4:{s:3:\\\"otp\\\";s:6:\\\"272044\\\";s:4:\\\"name\\\";s:4:\\\"Abua\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"ccccc@getnada.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1755243438,\"delay\":null}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Unable to write bytes on the wire. in C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\AbstractStream.php:51\nStack trace:\n#0 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(223): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\AbstractStream->write(\'e\\r\', false)\n#1 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#2 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(138): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#3 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(584): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#4 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(331): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#5 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#6 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#9 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#10 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#12 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#13 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#14 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#15 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#16 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#17 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#19 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#20 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#21 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#24 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#26 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#29 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#32 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#33 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#34 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#35 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#36 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\laragon\\www\\dcash-web\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#43 {main}', '2025-08-15 06:39:58'),
(2, '7ce35f6f-3ae5-4cde-8290-c8488bc72bdb', 'database', 'default', '{\"uuid\":\"7ce35f6f-3ae5-4cde-8290-c8488bc72bdb\",\"displayName\":\"App\\\\Mail\\\\app\\\\OtpVerificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\app\\\\OtpVerificationMail\\\":4:{s:3:\\\"otp\\\";s:6:\\\"637599\\\";s:4:\\\"name\\\";s:4:\\\"Abua\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"ccccc@getnada.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1755243466,\"delay\":null}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Unable to write bytes on the wire. in C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\AbstractStream.php:51\nStack trace:\n#0 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(223): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\AbstractStream->write(\'\\n\\r\\nLaravel: htt...\', false)\n#1 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#2 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(138): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#3 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(584): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#4 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(331): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#5 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#6 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#9 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#10 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#12 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#13 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#14 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#15 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#16 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#17 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#19 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#20 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#21 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#24 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#26 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#29 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#32 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#33 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#34 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#35 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#36 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\laragon\\www\\dcash-web\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#43 {main}', '2025-08-15 06:39:58'),
(3, 'bf5c24d1-8dba-499e-ba1e-56d7e2836f83', 'database', 'default', '{\"uuid\":\"bf5c24d1-8dba-499e-ba1e-56d7e2836f83\",\"displayName\":\"App\\\\Mail\\\\app\\\\OtpVerificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\app\\\\OtpVerificationMail\\\":4:{s:3:\\\"otp\\\";s:6:\\\"332679\\\";s:4:\\\"name\\\";s:4:\\\"Abua\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:17:\\\"ccccc@getnada.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1755243536,\"delay\":null}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Unable to write bytes on the wire. in C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\AbstractStream.php:51\nStack trace:\n#0 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(223): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\AbstractStream->write(\'e\\r\', false)\n#1 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#2 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(138): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#3 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(584): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#4 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(331): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#5 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#6 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#7 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#8 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#9 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#10 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#11 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#12 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#13 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#14 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#15 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#16 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#17 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#19 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#20 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#21 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#24 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#25 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#26 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#29 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#30 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#31 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#32 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#33 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(780): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#34 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#35 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#36 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(1092): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(341): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\laragon\\www\\dcash-web\\vendor\\symfony\\console\\Application.php(192): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\laragon\\www\\dcash-web\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\laragon\\www\\dcash-web\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#43 {main}', '2025-08-15 06:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `flutterwave_bills_items`
--

CREATE TABLE `flutterwave_bills_items` (
  `id` bigint UNSIGNED NOT NULL,
  `biller_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_commission` decimal(12,2) DEFAULT NULL,
  `date_added` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_airtime` tinyint(1) DEFAULT NULL,
  `biller_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee` decimal(12,2) DEFAULT NULL,
  `commission_on_fee` decimal(12,2) DEFAULT NULL,
  `reg_expression` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `is_resolvable` tinyint(1) DEFAULT NULL,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_data` tinyint(1) DEFAULT NULL,
  `default_commission_on_amount` decimal(12,2) DEFAULT NULL,
  `commission_on_fee_or_amount` decimal(12,2) DEFAULT NULL,
  `validity_period` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flutterwave_bills_items`
--

INSERT INTO `flutterwave_bills_items` (`id`, `biller_code`, `name`, `default_commission`, `date_added`, `country`, `is_airtime`, `biller_name`, `item_code`, `short_name`, `fee`, `commission_on_fee`, `reg_expression`, `label_name`, `amount`, `is_resolvable`, `group_name`, `category_name`, `is_data`, `default_commission_on_amount`, `commission_on_fee_or_amount`, `validity_period`, `created_at`, `updated_at`) VALUES
(1, 'BIL099', 'MTN VTU', 0.03, '2018-07-03T00:00:00Z', 'NG', 1, 'AIRTIME', 'AT099', 'MTN VTU', 0.00, 0.00, '^[+]{1}[0-9]+$', 'Mobile Number', 0.00, 1, 'MTN', 'Airtime', 0, 0.03, 2.00, '1', '2025-08-26 22:01:43', '2025-08-26 22:01:43'),
(267, 'BIL112', 'EKEDC PREPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'EKEDC PREPAID TOPUP', 'UB157', 'EKEDC PREPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'EKO DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:05:32', '2025-08-26 22:05:32'),
(268, 'BIL112', 'EKEDC POSTPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'EKEDC POSTPAID TOPUP', 'UB158', 'EKEDC POSTPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'EKO DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:05:32', '2025-08-26 22:05:32'),
(269, 'BIL113', 'IKEDC  PREPAID', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'IKEDC  PREPAID', 'UB159', 'IKEDC  PREPAID', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'IKEJA DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:06:07', '2025-08-26 22:06:07'),
(270, 'BIL113', 'IKEDC  POSTPAID', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'IKEDC  POSTPAID', 'UB160', 'IKEDC  POSTPAID', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'IKEJA DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:06:07', '2025-08-26 22:06:07'),
(271, 'BIL114', 'IBADAN DISCO ELECTRICITY PREPAID', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'IBADAN DISCO ELECTRICITY PREPAID', 'UB161', 'IBADAN DISCO ELECTRICITY PREPAID', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'IBADAN DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:06:45', '2025-08-26 22:06:45'),
(272, 'BIL114', 'IBADAN DISCO ELECTRICITY POSTPAID', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'IBADAN DISCO ELECTRICITY POSTPAID', 'UB162', 'IBADAN DISCO ELECTRICITY POSTPAID', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'IBADAN DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:06:45', '2025-08-26 22:06:45'),
(273, 'BIL115', 'ENUGU DISCO ELECTRIC BILLS PREPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'ENUGU DISCO ELECTRIC BILLS PREPAID TOPUP', 'UB163', 'ENUGU DISCO ELECTRIC BILLS PREPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'ENUGU DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:07:25', '2025-08-26 22:07:25'),
(274, 'BIL115', 'ENUGU DISCO ELECTRIC BILLS POSTPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'ENUGU DISCO ELECTRIC BILLS POSTPAID TOPUP', 'UB164', 'ENUGU DISCO ELECTRIC BILLS POSTPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'ENUGU DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:07:25', '2025-08-26 22:07:25'),
(275, 'BIL116', 'PHC DISCO POSTPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'PHC DISCO POSTPAID TOPUP', 'UB165', 'PHC DISCO POSTPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'PORT HARCOURT DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:08:26', '2025-08-26 22:08:26'),
(276, 'BIL117', 'BENIN DISCO POSTPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'BENIN DISCO POSTPAID TOPUP', 'UB166', 'BENIN DISCO POSTPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'BENIN DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(277, 'BIL117', 'BENIN DISCO PREPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'BENIN DISCO PREPAID TOPUP', 'UB167', 'BENIN DISCO PREPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'BENIN DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(278, 'BIL118', 'YOLA DISCO TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'YOLA DISCO TOPUP', 'UB168', 'YOLA DISCO TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'YOLA DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(279, 'BIL120', 'KANO DISCO PREPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'KANO DISCO PREPAID TOPUP', 'UB169', 'KANO DISCO PREPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'KANO DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(280, 'BIL120', 'KANO DISCO POSTPAID TOPUP', 0.30, '2020-02-11T11:09:48.087Z', 'NG', 0, 'KANO DISCO POSTPAID TOPUP', 'UB170', 'KANO DISCO POSTPAID TOPUP', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'KANO DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(287, 'BIL121', 'DSTV COMPACT', 0.30, '2025-04-16T10:51:14.12Z', 'NG', 0, 'DSTV COMPACT', 'CB177', 'DSTV COMPACT', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 19000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(288, 'BIL121', 'DSTV COMPACT + HD', 0.30, '2024-10-15T23:08:28.977Z', 'NG', 0, 'DSTV COMPACT + HD', 'CB178', 'DSTV COMPACT + HD', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 20700.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(289, 'BIL121', 'DSTV COMPACT PLUS', 0.30, '2020-02-11T11:13:29.62Z', 'NG', 0, 'DSTV COMPACT PLUS', 'CB179', 'DSTV COMPACT PLUS', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 25000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(290, 'BIL121', 'DSTV COMPACT PLUS + ASIA', 0.30, '2025-04-16T10:51:14.55Z', 'NG', 0, 'DSTV COMPACT PLUS + ASIA', 'CB180', 'DSTV COMPACT PLUS + ASIA', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 44900.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(291, 'BIL121', 'DSTV COMPACT PLUS + HD', 0.30, '2025-04-16T10:51:14.81Z', 'NG', 0, 'DSTV COMPACT PLUS + HD', 'CB181', 'DSTV COMPACT PLUS + HD', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 36000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(292, 'BIL121', 'DSTV PREMIUM', 0.30, '2025-04-16T10:51:15.067Z', 'NG', 0, 'DSTV PREMIUM', 'CB182', 'DSTV PREMIUM', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 44500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(293, 'BIL121', 'DSTV PREMIUM ASIA', 0.30, '2025-04-16T10:51:15.32Z', 'NG', 0, 'DSTV PREMIUM ASIA', 'CB183', 'DSTV PREMIUM ASIA', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 50500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(295, 'BIL121', 'DSTV BOX OFFICE', 0.30, '2020-02-11T11:13:29.62Z', 'NG', 0, 'DSTV BOX OFFICE', 'CB221', 'DSTV BOX OFFICE', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 800.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(299, 'BIL122', 'GOTV MAX', 0.30, '2025-04-16T10:51:24.45Z', 'NG', 0, 'GOTV MAX', 'CB188', 'GOTV MAX', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 8500.00, 1, 'GOTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:14:58', '2025-08-26 22:14:58'),
(300, 'BIL123', 'NOVA - One Day', 0.30, '2025-05-07T18:55:16.193Z', 'NG', 0, 'NOVA - One Day', 'CB189', 'NOVA - One Day', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 100.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(301, 'BIL123', 'NOVA - One Week', 0.30, '2025-05-07T18:55:16.36Z', 'NG', 0, 'NOVA - One Week', 'CB190', 'NOVA - One Week', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 400.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '7', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(302, 'BIL123', 'NOVA - One Month', 0.30, '2025-05-07T18:55:16.547Z', 'NG', 0, 'NOVA - One Month', 'CB191', 'NOVA - One Month', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 1200.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(303, 'BIL123', 'BASIC - One Day', 0.30, '2025-05-07T18:55:16.723Z', 'NG', 0, 'BASIC - One Day', 'CB192', 'BASIC - One Day', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 200.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '1', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(304, 'BIL123', 'BASIC - One Week', 0.30, '2025-05-07T18:55:16.893Z', 'NG', 0, 'BASIC - One Week', 'CB193', 'BASIC - One Week', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 700.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '7', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(305, 'BIL123', 'BASIC - One Month', 0.30, '2025-05-07T18:55:17.063Z', 'NG', 0, 'BASIC - One Month', 'CB260', 'BASIC - One Month', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 2100.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(306, 'BIL123', 'SMART - One Day', 0.30, '2025-05-07T18:55:17.233Z', 'NG', 0, 'SMART - One Day', 'CB261', 'SMART - One Day', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 250.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '1', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(307, 'BIL123', 'SMART - One Week', 0.30, '2025-05-07T18:55:17.403Z', 'NG', 0, 'SMART - One Week', 'CB262', 'SMART - One Week', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 900.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '7', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(308, 'BIL123', 'SMART - One Month', 0.30, '2025-05-07T18:55:16.01Z', 'NG', 0, 'SMART - One Month', 'CB263', 'SMART - One Month', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 2800.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(309, 'BIL123', 'CLASSIC - One Day', 0.30, '2025-05-07T18:55:17.563Z', 'NG', 0, 'CLASSIC - One Day', 'CB264', 'CLASSIC - One Day', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 320.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '1', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(310, 'BIL123', 'CLASSIC - One Week', 0.30, '2025-05-07T18:55:17.743Z', 'NG', 0, 'CLASSIC - One Week', 'CB265', 'CLASSIC - One Week', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 1200.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '7', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(311, 'BIL123', 'CLASSIC - One month', 0.30, '2025-05-07T18:55:17.92Z', 'NG', 0, 'CLASSIC - One month', 'CB266', 'CLASSIC - One month', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 3100.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(315, 'BIL123', 'SUPER - One Day', 0.30, '2025-05-07T18:55:18.34Z', 'NG', 0, 'SUPER - One Day', 'CB270', 'SUPER - One Day', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 500.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '1', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(316, 'BIL123', 'SUPER - One Week', 0.30, '2025-05-07T18:55:18.497Z', 'NG', 0, 'SUPER - One Week', 'CB271', 'SUPER - One Week', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 1800.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '7', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(317, 'BIL123', 'SUPER - One Month', 0.30, '2025-05-07T18:55:18.69Z', 'NG', 0, 'SUPER - One Month', 'CB272', 'SUPER - One Month', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 5300.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(375, 'BIL109', 'GLO 350 MB DATA BUNDLE', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'GLO 350 MB DATA BUNDLE', 'MD147', 'GLO 350 MB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 200.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '1', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(376, 'BIL109', 'GLO 1.05GB DATA BUNDLE ', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'GLO 1.05GB DATA BUNDLE ', 'MD148', 'GLO 1.05GB DATA BUNDLE ', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 500.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(377, 'BIL109', 'GLO 3.9GB data purchase', 0.03, '2025-05-07T18:55:27.53Z', 'NG', 0, 'GLO 3.9GB data purchase', 'MD149', 'GLO 3.9GB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 1000.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(378, 'BIL109', 'GLO 9.2GB data bundle', 0.03, '2025-05-07T18:55:27.71Z', 'NG', 0, 'GLO 9.2GB data bundle', 'MD150', 'GLO 9.2GB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2000.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(379, 'BIL109', 'GLO 10.8GB data bundle', 0.03, '2025-05-07T18:55:27.88Z', 'NG', 0, 'GLO 10.8GB data bundle', 'MD151', 'GLO 10.8GB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2500.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(381, 'BIL109', 'GLO 18GB DATA BUNDLE', 0.03, '2025-05-07T18:55:28.213Z', 'NG', 0, 'GLO 18GB DATA BUNDLE', 'MD367', 'GLO 18GB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 4000.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(382, 'BIL109', 'GLO 18.25GB DATA BUNDLE', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'GLO 18.25GB DATA BUNDLE', 'MD368', 'GLO 24GB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 5000.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(384, 'BIL109', 'GLO 50GB DATA BUNDLE', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'GLO 50GB DATA BUNDLE', 'MD370', 'GLO 50GB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 10000.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(386, 'BIL109', 'GLO 119GB DATA BUNDLE', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'GLO 119GB DATA BUNDLE', 'MD372', 'GLO 119GB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 18000.00, 1, 'GLO DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:48:38', '2025-08-26 21:48:38'),
(387, 'BIL110', 'AIRTEL 40 MB data bundle', 0.03, '2025-03-28T12:59:17.79Z', 'NG', 0, 'AIRTEL 40 MB data bundle', 'MD135', 'AIRTEL 40 MB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 75.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '1s', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(388, 'BIL110', 'AIRTEL 100 MB data bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'AIRTEL 100 MB data bundle', 'MD136', 'AIRTEL 100 MB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 100.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '7', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(389, 'BIL110', 'AIRTEL 200 MB data bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'AIRTEL 200 MB data bundle', 'MD137', 'AIRTEL 200 MB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 200.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '7', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(391, 'BIL110', 'AIRTEL 750 MB data bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'AIRTEL 750 MB data bundle', 'MD139', 'AIRTEL 750 MB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 500.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '7', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(392, 'BIL110', 'AIRTEL 1.5GB data bundle', 0.03, '2025-03-28T12:59:18.62Z', 'NG', 0, 'AIRTEL 1.5GB data bundle', 'MD140', 'AIRTEL 1.5GB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 1500.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(393, 'BIL110', 'AIRTEL 3GB Data Bundle', 0.03, '2025-03-28T12:59:18.91Z', 'NG', 0, 'AIRTEL 3GB Data Bundle', 'MD373', 'AIRTEL 3GB Data Bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2000.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(394, 'BIL110', 'AIRTEL 6GB Data Bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'AIRTEL 6GB Data Bundle', 'MD374', 'AIRTEL 6GB Data Bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2500.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(396, 'BIL110', 'AIRTEL 11GB Data Bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'AIRTEL 11GB Data Bundle', 'MD376', 'AIRTEL 11GB Data Bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 4000.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(397, 'BIL110', 'AIRTEL 13GB Data Bundle', 0.03, '2025-05-07T18:55:29.823Z', 'NG', 0, 'AIRTEL 13GB Data Bundle', 'MD377', 'AIRTEL 13GB Data Bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 5000.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(398, 'BIL110', 'AIRTEL 40GB Data Bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'AIRTEL 40GB Data Bundle', 'MD378', 'AIRTEL 40GB Data Bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 10000.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(399, 'BIL110', 'AIRTEL 75GB Data Bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, 'AIRTEL 75GB Data Bundle', 'MD379', 'AIRTEL 75GB Data Bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 15000.00, 1, 'AIRTEL DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:49:38', '2025-08-26 21:49:38'),
(402, 'BIL111', '9MOBILE 1.5GB data bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, '9MOBILE 1.5GB data bundle', 'MD154', '9MOBILE 1.5GB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 1000.00, 1, '9MOBILE DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:50:11', '2025-08-26 21:50:11'),
(403, 'BIL111', '9MOBILE 4.5GB data bundle', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, '9MOBILE 4.5GB data bundle', 'MD155', '9MOBILE 4.5GB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2000.00, 1, '9MOBILE DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:50:11', '2025-08-26 21:50:11'),
(405, 'BIL111', '9MOBILE 11GB data bundle ', 0.03, '2020-02-11T11:16:42.727Z', 'NG', 0, '9MOBILE 11GB data bundle ', 'MD361', '9MOBILE 11GB data bundle ', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 4000.00, 1, '9MOBILE DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:50:11', '2025-08-26 21:50:11'),
(16871, 'BIL111', '9MOBILE 650 MB data bundle', 0.03, '2020-05-30T00:00:00Z', 'NG', 0, '9MOBILE 650 MB data bundle', 'MD152', '9MOBILE 650 MB data bundle', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 200.00, 1, '9MOBILE DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:50:11', '2025-08-26 21:50:11'),
(16873, 'BIL108', 'MTN 2.5GB data purchase (2 Days)', 0.03, '2025-05-07T18:55:26.947Z', 'NG', 0, 'MTN 2.5GB data purchase (2 Days)', 'MD496', 'MTN 2.5GB data purchase (2 Days)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 900.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '2s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(16885, 'BIL108', 'MTN 3GB data purchase', 0.03, '2025-03-28T12:59:14.32Z', 'NG', 0, 'MTN 3GB data purchase', 'MD491', 'MTN 3GB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(16886, 'BIL108', 'MTN 2GB data purchase', 0.03, '2025-03-28T12:59:14.62Z', 'NG', 0, 'MTN 2GB data purchase', 'MD492', 'MTN 2GB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(16887, 'BIL108', 'MTN 6GB data purchase (7 days)', 0.03, '2025-05-07T18:55:26.513Z', 'NG', 0, 'MTN 6GB data purchase (7 days)', 'MD493', 'MTN 6GB data purchase (7 days)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '7s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(16888, 'BIL108', 'MTN 1GB data purchase (7 days)', 0.03, '2025-05-07T18:55:26.693Z', 'NG', 0, 'MTN 1GB data purchase (7 days)', 'MD494', 'MTN 1GB data purchase (7 days)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 800.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '7s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(16889, 'BIL121', 'DSTV Yanga', 0.30, '2025-04-16T10:51:15.837Z', 'NG', 0, 'DSTV Yanga', 'CB482', 'DSTV Yanga', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 6000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16894, 'BIL121', 'DSTV Confam', 0.30, '2025-04-16T10:51:16.13Z', 'NG', 0, 'DSTV Confam', 'CB483', 'DSTV Confam', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 11000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16897, 'BIL122', 'GOtv Jinja', 0.30, '2025-04-16T10:51:23.51Z', 'NG', 0, 'GOtv Jinja', 'CB486', 'GOtv Jinja', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 3900.00, 1, 'GOTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:14:58', '2025-08-26 22:14:58'),
(16898, 'BIL122', 'GOtv Jolli', 0.30, '2025-04-16T10:51:23.763Z', 'NG', 0, 'GOtv Jolli', 'CB487', 'GOtv Jolli', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 5800.00, 1, 'GOTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:14:58', '2025-08-26 22:14:58'),
(16899, 'BIL121', 'Compact Plus + Asia +Xtraview', 0.30, '2025-04-16T10:51:16.643Z', 'NG', 0, 'Compact Plus + Asia +Xtraview', 'CB509', 'Compact Plus + Asia +Xtraview', 100.00, 1.00, '^[0-9]+$', 'Smartcard Number', 50900.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16903, 'BIL121', 'Compact Plus + French Touch', 0.30, '2020-02-11T11:13:29.62Z', 'NG', 0, 'Compact Plus + French Touch', 'CB510', 'Compact Plus + French Touch', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 30800.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16904, 'BIL121', 'Compact Plus + French Plus', 0.30, '2024-10-15T23:08:32.26Z', 'NG', 0, 'Compact Plus + French Plus', 'CB511', 'Compact Plus + French Plus', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 45500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16905, 'BIL121', 'DSTV PADI', 0.30, '2025-04-16T10:51:17.07Z', 'NG', 0, 'DSTV PADI', 'CB512', 'DSTV PADI', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 4400.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16906, 'BIL121', 'DSTV PADI + XTRA VIEW', 0.30, '2025-04-16T10:51:22.48Z', 'NG', 0, 'DSTV PADI + XTRA VIEW', 'Cb513', 'DSTV PADI + XTRA VIEW', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 10400.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16926, 'BIL121', 'Premium + Xtraview', 0.30, '2024-10-15T23:08:33.34Z', 'NG', 0, 'Premium + Xtraview', 'CB524', 'Premium + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 42000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16928, 'BIL121', 'Premium + French', 0.30, '2024-10-15T23:08:33.91Z', 'NG', 0, 'Premium + French', 'CB526', 'Premium + French', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 57500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16929, 'BIL121', 'Premium + French + Xtraview', 0.30, '2025-04-16T10:51:17.753Z', 'NG', 0, 'Premium + French + Xtraview', 'CB527', 'Premium + French + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 75000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16930, 'BIL121', 'CompactPlus + French Plus + Xtraview', 0.30, '2022-07-26T21:23:03.4Z', 'NG', 0, 'CompactPlus + French Plus + Xtraview', 'CB528', 'CompactPlus + French Plus + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 50500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16931, 'BIL121', 'Compact + Asia', 0.30, '2022-07-26T21:23:03.533Z', 'NG', 0, 'Compact + Asia', 'CB529', 'Compact + Asia', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 28100.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16932, 'BIL121', 'Compact + French Touch', 0.30, '2022-07-26T21:23:03.667Z', 'NG', 0, 'Compact + French Touch', 'CB530', 'Compact + French Touch', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 21500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16933, 'BIL121', 'Compact + Xtraview', 0.30, '2025-04-16T10:51:18.263Z', 'NG', 0, 'Compact + Xtraview', 'CB531', 'Compact + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 25000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16934, 'BIL121', 'Compact + French Touch + Xtraview', 0.30, '2025-04-16T10:51:18.553Z', 'NG', 0, 'Compact + French Touch + Xtraview', 'CB532', 'Compact + French Touch + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 32000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16935, 'BIL121', 'Compact + Asia + Xtraview', 0.30, '2024-10-15T23:08:35.02Z', 'NG', 0, 'Compact + Asia + Xtraview', 'CB533', 'Compact + Asia + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 33100.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16936, 'BIL121', 'Compact + French Plus', 0.30, '2022-07-26T21:23:04.2Z', 'NG', 0, 'Compact + French Plus', 'CB534', 'Compact + French Plus', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 36200.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16938, 'BIL121', 'DStv French Touch Add-on Bouquet E36', 0.30, '2022-07-26T21:23:04.467Z', 'NG', 0, 'DStv French Touch Add-on Bouquet E36', 'CB536', 'DStv French Touch Add-on Bouquet E36', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 5800.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16939, 'BIL121', 'DStv Asian Add-on Bouquet E36', 0.30, '2025-04-16T10:51:19.237Z', 'NG', 0, 'DStv Asian Add-on Bouquet E36', 'CB537', 'DStv Asian Add-on Bouquet E36', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 14900.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16940, 'BIL121', 'DStv French Plus Add-on Bouquet E36', 0.30, '2022-07-26T21:23:04.74Z', 'NG', 0, 'DStv French Plus Add-on Bouquet E36', 'CB538', 'DStv French Plus Add-on Bouquet E36', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 20500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16941, 'BIL121', 'Dstv Great Wall standalone Bouquet', 0.30, '2025-04-16T10:51:19.58Z', 'NG', 0, 'Dstv Great Wall standalone Bouquet', 'CB539', 'Dstv Great Wall standalone Bouquet', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 3800.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16942, 'BIL121', 'French 11 Bouquet E36', 0.30, '2022-07-26T21:23:05.007Z', 'NG', 0, 'French 11 Bouquet E36', 'CB540', 'French 11 Bouquet E36', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 9000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16943, 'BIL121', 'Box Office (New Premier Price)', 0.30, '2022-07-26T21:23:05.14Z', 'NG', 0, 'Box Office (New Premier Price)', 'CB541', 'Box Office (New Premier Price)', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 1100.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16945, 'BIL122', 'GOtv Supa', 0.30, '2025-04-16T10:51:23.253Z', 'NG', 0, 'GOtv Supa', 'CB543', 'GOtv Supa', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 11400.00, 1, 'GOTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:14:58', '2025-08-26 22:14:58'),
(16947, 'BIL121', 'French 11', 0.30, '2022-08-01T05:17:57.407Z', 'NG', 0, 'French 11', 'CB545', 'French 11', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 3180.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16948, 'BIL121', 'Premium Asia + HD/ExtraView', 0.30, '2024-10-15T23:08:37.23Z', 'NG', 0, 'Premium Asia + HD/ExtraView', 'CB546', 'Premium Asia + HD/ExtraView', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 54400.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16949, 'BIL121', 'Asian + HD/ExtraView', 0.30, '2022-08-01T05:17:57.71Z', 'NG', 0, 'Asian + HD/ExtraView', 'CB547', 'Asian + HD/ExtraView', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 11700.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16951, 'BIL121', 'Premium + French Touch + HD/ExtraView', 0.30, '2024-10-15T23:08:38.15Z', 'NG', 0, 'Premium + French Touch + HD/ExtraView', 'CB549', 'Premium + French Touch + HD/ExtraView', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 51000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16953, 'BIL121', 'Confam + Xtraview', 0.30, '2025-04-16T10:51:20.93Z', 'NG', 0, 'Confam + Xtraview', 'CB552', 'Confam + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 17000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16954, 'BIL121', 'PremiumAsia Showmax', 0.30, '2024-11-05T16:34:27.877Z', 'NG', 0, 'PremiumAsia Showmax', 'CB553', 'PremiumAsia Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 57500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16955, 'BIL121', 'Premium + Showmax', 0.30, '2024-10-15T23:08:40.133Z', 'NG', 0, 'Premium + Showmax', 'CB555', 'Premium + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 37000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16956, 'BIL121', 'Asian + Showmax', 0.30, '2022-08-01T05:17:59.347Z', 'NG', 0, 'Asian + Showmax', 'CB556', 'Asian + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 11200.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16957, 'BIL121', 'CompactPlus + Showmax', 0.30, '2024-10-15T23:08:40.577Z', 'NG', 0, 'CompactPlus + Showmax', 'CB557', 'CompactPlus + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 26750.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16958, 'BIL121', 'Compact + Showmax', 0.30, '2024-10-15T23:08:40.857Z', 'NG', 0, 'Compact + Showmax', 'CB558', 'Compact + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 17450.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16959, 'BIL121', 'Confam + Showmax', 0.30, '2024-11-05T16:34:28.37Z', 'NG', 0, 'Confam + Showmax', 'CB559', 'Confam + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 11050.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16960, 'BIL121', 'Yanga + Showmax', 0.30, '2024-11-05T16:34:28.537Z', 'NG', 0, 'Yanga + Showmax', 'CB560', 'Yanga + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 6850.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16961, 'BIL121', 'Padi + Showmax', 0.30, '2022-08-01T05:18:00.397Z', 'NG', 0, 'Padi + Showmax', 'CB561', 'Padi + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 5400.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(16962, 'BIL121', 'DSTV COMPACT PLUS + XTRAVIEW', 0.30, '2025-04-16T10:51:21.877Z', 'NG', 0, 'DSTV COMPACT PLUS + XTRAVIEW', 'CB562', 'DSTV COMPACT PLUS + XTRAVIEW', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 36000.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(17147, 'BIL100', 'AIRTEL VTU', 0.03, '2022-09-19T18:27:54.507Z', 'NG', 1, 'AIRTEL VTU', 'AT100', 'AIRTEL VTU', 0.00, 0.00, '^[+]{1}[0-9]+$', 'Mobile Number', 0.00, 1, 'Airtime', 'Airtime', 0, 0.03, 2.00, NULL, '2025-08-26 22:01:06', '2025-08-26 22:01:06'),
(17148, 'BIL102', 'GLO VTU', 0.03, '2022-09-19T18:27:55.327Z', 'NG', 1, 'GLO VTU', 'AT133', 'GLO VTU', 0.00, 0.00, '^[+]{1}[0-9]+$', 'Mobile Number', 0.00, 1, 'Airtime', 'Airtime', 0, 0.03, 2.00, NULL, '2025-08-26 22:02:13', '2025-08-26 22:02:13'),
(17149, 'BIL103', '9MOBILE VTU', 0.03, '2022-09-19T18:27:56.16Z', 'NG', 1, '9MOBILE VTU', 'AT134', '9MOBILE VTU', 0.00, 0.00, '^[+]{1}[0-9]+$', 'Mobile Number', 0.00, 1, 'Airtime', 'Airtime', 0, 0.03, 2.00, NULL, '2025-08-26 22:04:40', '2025-08-26 22:04:40'),
(17167, 'BIL121', 'DStv Yanga + XTRA VIEW', 0.10, '2024-10-15T23:08:31.157Z', 'NG', 0, 'DStv Yanga + XTRA VIEW', 'CB484', 'DStv Yanga + XTRA VIEW', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 10100.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, NULL, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(17202, 'BIL108', 'MTN 1GB data purchase (1 day)', 0.03, '2025-05-07T18:55:26.01Z', 'NG', 0, 'MTN 1GB data purchase (1 day)', 'MD489', 'MTN 1GB data purchase (1 day)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '1s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17614, 'BIL121', 'PremiumFrench + Showmax', 0.30, '2024-10-15T23:08:42.293Z', 'NG', 0, 'PremiumFrench + Showmax', 'CB554', 'PremiumFrench + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 57500.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, '30', '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(17633, 'BIL108', 'MTN 500MB data purchase', 0.03, '2023-01-09T00:12:37.62Z', 'NG', 0, 'MTN 500MB data purchase', 'MD565', 'MTN 500MB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17634, 'BIL108', 'MTN 6GB data purchase', 0.03, '2025-05-07T18:55:23.207Z', 'NG', 0, 'MTN 6GB data purchase', 'MD566', 'MTN 6GB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '7s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17635, 'BIL108', 'MTN 12GB data purchase', 0.03, '2025-03-28T12:59:10.163Z', 'NG', 0, 'MTN 12GB data purchase', 'MD567', 'MTN 12GB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 6500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17637, 'BIL108', 'MTN 25GB data purchase', 0.03, '2025-03-28T12:59:10.65Z', 'NG', 0, 'MTN 25GB data purchase', 'MD569', 'MTN 25GB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 9000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17641, 'BIL108', 'MTN 11GB data purchase + 20MIN', 0.03, '2025-05-07T18:55:24.213Z', 'NG', 0, 'MTN 11GB data purchase + 20MIN', 'MD573', 'MTN 11GB data purchase + 20MIN', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 3500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17642, 'BIL108', 'MTN 35GB data purchase', 0.03, '2023-01-09T00:12:38.67Z', 'NG', 0, 'MTN 35GB data purchase', 'MD574', 'MTN 35GB data purchase', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 13500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17646, 'BIL204', 'ABUJA DISCO Prepaid', 0.30, '2023-07-04T21:50:38.983Z', 'NG', 0, 'ABUJA DISCO Prepaid', 'UB584', 'ABUJA DISCO Prepaid', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'ABUJA DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(17647, 'BIL204', 'ABUJA DISCO Postpaid', 0.30, '2023-07-04T21:50:38.983Z', 'NG', 0, 'ABUJA DISCO Postpaid', 'UB585', 'ABUJA DISCO Postpaid', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'ABUJA DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(17648, 'BIL122', 'GOtv Smallie (Yearly)	', 0.30, '2025-05-07T18:55:15.087Z', 'NG', 0, 'GOtv Smallie (Yearly)	', 'CB542', 'GOtv Smallie (Yearly)	', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 15000.00, 1, 'GOTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:14:58', '2025-08-26 22:14:58'),
(17649, 'BIL122', 'GOtv SMALLIE (Monthly)', 0.30, '2025-04-17T12:47:09.453Z', 'NG', 0, 'GOtv SMALLIE (Monthly)', 'CB185', 'GOtv SMALLIE (Monthly)', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 1900.00, 1, 'GOTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:14:58', '2025-08-26 22:14:58'),
(17650, 'BIL122', 'GOtv Smallie (Quarterly) ', 0.30, '2025-05-07T18:55:14.83Z', 'NG', 0, 'GOtv Smallie (Quarterly) ', 'CB514', 'GOtv Smallie (Quarterly) ', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 5100.00, 1, 'GOTV', 'Cable Bill Payment', 0, NULL, 1.00, '0s', '2025-08-26 22:14:58', '2025-08-26 22:14:58'),
(17651, 'BIL121', 'Great Wall Standalone Bouquet E36 + Showmax', 0.30, '2023-07-26T13:29:17.71Z', 'NG', 0, 'Great Wall Standalone Bouquet E36 + Showmax', 'CB586', 'Great Wall Standalone Bouquet E36 + Showmax', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 4950.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(17652, 'BIL123', 'Special - One Month', 0.30, '2025-05-07T18:55:19.363Z', 'NG', 0, 'Special - One Month', 'CB590', 'Special - One Month', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 3900.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(17653, 'BIL123', 'Special - One Week', 0.30, '2025-05-07T18:55:19.193Z', 'NG', 0, 'Special - One Week', 'CB589', 'Special - One Week', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 1300.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(17654, 'BIL123', 'Special - One Day', 0.30, '2025-05-07T18:55:19.027Z', 'NG', 0, 'Special - One Day', 'CB588', 'Special - One Day', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 400.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(17655, 'BIL123', 'Chinese - One Month', 0.30, '2025-05-07T18:55:18.86Z', 'NG', 0, 'Chinese - One Month', 'CB587', 'Chinese - One Month', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 9800.00, 1, 'STARTIMES', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:15:42', '2025-08-26 22:15:42'),
(17663, 'BIL121', 'Premiumasia + Xtraview', 0.30, '2024-10-15T23:08:33.61Z', 'NG', 0, 'Premiumasia + Xtraview', 'CB525', 'Premiumasia + Xtraview', 100.00, 1.00, '^[0-9]+$', 'SmartCard Number', 54400.00, 1, 'DSTV', 'Cable Bill Payment', 0, NULL, 1.00, NULL, '2025-08-26 22:13:27', '2025-08-26 22:13:27'),
(17667, 'BIL108', 'MTN 100 MB DATA BUNDLE', 0.03, '2023-09-19T19:20:24.633Z', 'NG', 0, 'MTN 100 MB DATA BUNDLE', 'MD141', 'MTN 100 MB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 100.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17668, 'BIL108', 'MTN 200 MB DATA BUNDLE', 0.03, '2024-08-17T07:18:04.68Z', 'NG', 0, 'MTN 200 MB DATA BUNDLE', 'MD142', 'MTN 200 MB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 200.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17674, 'BIL108', 'MTN 32 GB DATA BUNDLE', 0.03, '2025-05-07T18:55:25.507Z', 'NG', 0, 'MTN 32 GB DATA BUNDLE', 'MD257', 'MTN 32 GB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 11000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17675, 'BIL108', 'MTN 75 GB DATA BUNDLE', 0.03, '2025-03-28T12:59:13.14Z', 'NG', 0, 'MTN 75 GB DATA BUNDLE', 'MD258', 'MTN 75 GB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 18000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17676, 'BIL108', 'MTN 120 GB DATA BUNDLE', 0.03, '2025-03-28T12:59:13.433Z', 'NG', 0, 'MTN 120 GB DATA BUNDLE', 'MD259', 'MTN 120 GB DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 35000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17678, 'BIL108', 'MTN 750 data purchase (7Days)', 0.03, '2025-05-07T18:55:26.18Z', 'NG', 0, 'MTN 750 data purchase (7Days)', 'MD490', 'MTN 750 data purchase (7Days)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '7', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17679, 'BIL108', 'MTN 6GB data purchase (1 Month)', 0.03, '2025-03-28T12:59:15.53Z', 'NG', 0, 'MTN 6GB data purchase (1 Month)', 'MD495', 'MTN 6GB data purchase (1 Month)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 4500.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17683, 'BIL119', 'Kaduna Prepaid', 0.30, '2023-09-19T19:20:33.19Z', 'NG', 0, 'KADUNA DISCO ELECTRICITY BILLS', 'UB602', 'Kaduna Prepaid', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'KADUNA DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(17684, 'BIL119', 'Kaduna Postpaid', 0.30, '2023-09-19T19:20:33.28Z', 'NG', 0, 'KADUNA DISCO ELECTRICITY BILLS', 'UB603', 'Kaduna Postpaid', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'KADUNA DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, NULL, 1.00, NULL, '2025-08-26 22:12:40', '2025-08-26 22:12:40'),
(17698, 'BIL108', 'MTN 1.2GB+2GB Youtube', 0.03, '2025-05-07T18:55:19.877Z', 'NG', 0, 'MTN 1.2GB+2GB Youtube', 'MD606', 'MTN 1.2GB+2GB Youtube', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 1500.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17700, 'BIL108', 'MTN 11GB', 0.03, '2025-05-07T18:55:20.22Z', 'NG', 0, 'MTN 11GB', 'MD608', 'MTN 11GB', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 3500.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, '7s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17701, 'BIL108', 'MTN 350MB All Social', 0.03, '2025-05-07T18:55:20.4Z', 'NG', 0, 'MTN 350MB All Social', 'MD609', 'MTN 350MB All Social', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 100.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17702, 'BIL108', 'MTN 1.5GB+2.4GB Youtube (Night)', 0.03, '2025-05-07T18:55:20.563Z', 'NG', 0, 'MTN 1.5GB+2.4GB Youtube (Night)', 'MD610', 'MTN 1.5GB+2.4GB Youtube (Night)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 2000.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17703, 'BIL108', 'MTN 4GB+2GB Youtube (Night)', 0.03, '2025-05-07T18:55:20.727Z', 'NG', 0, 'MTN 4GB+2GB Youtube (Night)', 'MD611', 'MTN 4GB+2GB Youtube (Night)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 3500.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17704, 'BIL108', 'MTN 10GB+2GB Youtube (Night)', 0.03, '2025-05-07T18:55:20.907Z', 'NG', 0, 'MTN 10GB+2GB Youtube (Night)', 'MD612', 'MTN 10GB+2GB Youtube (Night)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 5500.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17705, 'BIL108', 'MTN 20GB+2GB Youtube (Night)', 0.03, '2025-05-07T18:55:21.077Z', 'NG', 0, 'MTN 20GB+2GB Youtube (Night)', 'MD613', 'MTN 20GB+2GB Youtube (Night)', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 7500.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, '30s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17706, 'BIL108', 'MTN 30GB Marble FUP Unlimited', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 30GB Marble FUP Unlimited', 'MD615', 'MTN 30GB Marble FUP Unlimited', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 5000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17707, 'BIL108', 'MTN 100GB Broadband Router only', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 100GB Broadband Router only', 'MD617', 'MTN 100GB Broadband Router only', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 18000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17708, 'BIL108', 'MTN 150GB Silver FUP Unlimited', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 150GB Silver FUP Unlimited', 'MD618', 'MTN 150GB Silver FUP Unlimited', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 20000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17709, 'BIL108', 'MTN 200GB Broadband Router only', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 200GB Broadband Router only', 'MD619', 'MTN 200GB Broadband Router only', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 25000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17710, 'BIL108', 'MTN 300GB Ruby FUP Unlimited', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 300GB Ruby FUP Unlimited', 'MD620', 'MTN 300GB Ruby FUP Unlimited', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 30000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17711, 'BIL108', 'MTN 300GB Broadband Router only', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 300GB Broadband Router only', 'MD621', 'MTN 300GB Broadband Router only', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 36000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17712, 'BIL108', 'MTN 400GB Gold FUP Unlimited', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 400GB Gold FUP Unlimited', 'MD622', 'MTN 400GB Gold FUP Unlimited', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 45000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17713, 'BIL108', 'MTN 400GB Broadband Router only', 0.03, '2025-03-28T12:59:07.707Z', 'NG', 0, 'MTN 400GB Broadband Router only', 'MD623', 'MTN 400GB Broadband Router only', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 75000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '90s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17714, 'BIL108', 'MTN 1TB Broadband Router only', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 1TB Broadband Router only', 'MD624', 'MTN 1TB Broadband Router only', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 100000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17715, 'BIL108', 'MTN 1.5TB Broadband Router only', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 1.5TB Broadband Router only', 'MD625', 'MTN 1.5TB Broadband Router only', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 150000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17716, 'BIL108', 'MTN 1.5TB Platinum FUP Unlimited', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 1.5TB Platinum FUP Unlimited', 'MD626', 'MTN 1.5TB Platinum FUP Unlimited', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 165000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17717, 'BIL108', 'MTN 1TB', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 1TB', 'MD627', 'MTN 1TB', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 350000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17718, 'BIL108', 'MTN 360GB', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 360GB', 'MD628', 'MTN 360GB', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 100000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17719, 'BIL108', 'MTN 100GB', 0.03, '2025-03-28T12:59:08.5Z', 'NG', 0, 'MTN 100GB', 'MD629', 'MTN 100GB', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 40000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '60s', '2025-08-26 21:25:42', '2025-08-26 21:25:42');
INSERT INTO `flutterwave_bills_items` (`id`, `biller_code`, `name`, `default_commission`, `date_added`, `country`, `is_airtime`, `biller_name`, `item_code`, `short_name`, `fee`, `commission_on_fee`, `reg_expression`, `label_name`, `amount`, `is_resolvable`, `group_name`, `category_name`, `is_data`, `default_commission_on_amount`, `commission_on_fee_or_amount`, `validity_period`, `created_at`, `updated_at`) VALUES
(17720, 'BIL108', 'MTN 4.5TB', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 4.5TB', 'MD630', 'MTN 4.5TB', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 450000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17721, 'BIL108', 'MTN 400GB', 0.03, '2025-03-28T12:59:08.89Z', 'NG', 0, 'MTN 400GB', 'MD631', 'MTN 400GB', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 90000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, '90s', '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17722, 'BIL108', 'MTN 600GB', 0.03, '2024-10-25T23:08:52.447Z', 'NG', 0, 'MTN 600GB', 'MD632', 'MTN 600GB', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 75000.00, 1, 'MTN DATA BUNDLE', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42'),
(17769, 'BIL116', 'PHC DISCO  PREPAID TOPUP', 0.30, '2023-12-04T09:39:59.42Z', 'NG', 0, 'PHC DISCO  PREPAID TOPUP', 'UB633', 'PORT HARCOURT DISCO ELECTRICITY BILLS', 100.00, 1.00, '^[0-9]+$', 'Meter Number', 0.00, 1, 'PORT HARCOURT DISCO ELECTRICITY BILLS', 'Electricity/Utility Bills', 0, 0.03, 2.00, NULL, '2025-08-26 22:08:26', '2025-08-26 22:08:26'),
(17770, 'BIL108', 'MTN DATA BUNDLE', 0.10, '2023-12-04T09:39:45.937Z', 'NG', 0, 'MTN 36GB', 'MD633', 'MTN DATA BUNDLE', 0.00, 0.00, '^[0-9]+$', 'Mobile Number', 11000.00, 1, 'Mobile Data Service', 'Mobile Data Service', 1, 0.03, 2.00, NULL, '2025-08-26 21:25:42', '2025-08-26 21:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc_verifications`
--

CREATE TABLE `kyc_verifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `bvn` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(90) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Nigeria',
  `document_type` enum('nin','passport','drivers_license','voters_card') COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_front_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path to front of ID card',
  `document_back_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path to back of ID card',
  `selfie_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path to selfie with ID',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kyc_verifications`
--

INSERT INTO `kyc_verifications` (`id`, `user_id`, `first_name`, `last_name`, `date_of_birth`, `bvn`, `address`, `nationality`, `document_type`, `document_number`, `document_front_image`, `document_back_image`, `selfie_image`, `status`, `rejection_reason`, `verified_at`, `created_at`, `updated_at`) VALUES
(6, 12, 'Jon', 'doe', '2025-08-29', '23542324223', 'edee', 'Nigeria', 'passport', '22222222222', 'kyc/documents/4VzlwMdqYvvqV7KOl8C3E1IwDdApGC0pxAPPbHeb.webp', NULL, 'kyc/selfies/selfie_12_1756471428.png', 'approved', NULL, '2025-08-29 12:21:45', '2025-08-29 11:43:48', '2025-08-29 12:21:45');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(12, '0001_01_01_000000_create_users_table', 1),
(13, '0001_01_01_000001_create_cache_table', 1),
(14, '0001_01_01_000002_create_jobs_table', 1),
(15, '2025_07_31_171628_currencies', 1),
(17, '2025_07_31_192448_wallets', 1),
(18, '2025_07_31_193650_wallet_transactions', 1),
(19, '2025_07_31_193817_beneficiaries', 1),
(20, '2025_07_31_194428_company_bank_accounts', 1),
(21, '2025_07_31_195552_virtual_bank_accounts', 1),
(22, '2025_07_31_195835_currency_pairs', 1),
(23, '2025_08_01_035206_kyc_verifications', 1),
(24, '2025_07_31_172414_exchange_transactions', 2),
(26, '2025_08_13_124012_create_announcements_table', 3),
(27, '2025_08_23_204404_create_utility_bills_table', 4),
(28, '2025_08_25_042706_create_personal_access_tokens_table', 5),
(29, '2025_08_25_070111_create_bill_payments_table', 6),
(30, '2025_08_26_213508_create_flutterwave_bill_items_table', 7),
(31, '2025_08_29_221702_create_nigerian_banks_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `nigerian_banks`
--

CREATE TABLE `nigerian_banks` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ussd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nigerian_banks`
--

INSERT INTO `nigerian_banks` (`id`, `name`, `code`, `slug`, `ussd`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Branch International Financial Services Limited', 'FC40163', 'branch', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/branch.png', NULL, NULL),
(2, 'Eyowo', '50126', 'eyowo', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/eyowo.png', NULL, NULL),
(3, 'Platinum Mortgage Bank', '268', 'platinum-mortgage-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/platinum-mortgage-bank-ng.png', NULL, NULL),
(4, 'Corestep MFB', '50204', 'corestep-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/corestep-mfb.png', NULL, NULL),
(5, 'Bowen Microfinance Bank', '50931', 'bowen-microfinance-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/bowen-microfinance-bank.png', NULL, NULL),
(6, 'Unical MFB', '50871', 'unical-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/unical-mfb.png', NULL, NULL),
(7, 'Waya Microfinance Bank', '51355', 'waya-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/waya-microfinance-bank-ng.png', NULL, NULL),
(8, 'Titan Paystack', '100039', 'titan-paystack', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/titan-paystack.png', NULL, NULL),
(9, 'Rubies MFB', '125', 'rubies-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/rubies-mfb.png', NULL, NULL),
(10, 'Heritage Bank', '030', 'heritage-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/heritage-bank.png', NULL, NULL),
(11, 'Guaranty Trust Bank', '058', 'guaranty-trust-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/guaranty-trust-bank.png', NULL, NULL),
(12, 'Sterling Bank', '232', 'sterling-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/sterling-bank.png', NULL, NULL),
(13, 'GoMoney', '100022', 'gomoney', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/gomoney.png', NULL, NULL),
(14, 'Lagos Building Investment Company Plc.', '90052', 'lbic-plc', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/lbic-plc.png', NULL, NULL),
(15, 'United Bank For Africa', '033', 'united-bank-for-africa', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/united-bank-for-africa.png', NULL, NULL),
(16, 'Safe Haven MFB', '51113', 'safe-haven-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/safe-haven-mfb-ng.png', NULL, NULL),
(17, 'Ecobank Nigeria', '050', 'ecobank-nigeria', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/ecobank-nigeria.png', NULL, NULL),
(18, 'Aramoko MFB', '50083', 'aramoko-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/aramoko-mfb.png', NULL, NULL),
(19, 'Links MFB', '50549', 'links-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/links-mfb.png', NULL, NULL),
(20, 'Parkway - ReadyCash', '311', 'parkway-ready-cash', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/parkway-ready-cash.png', NULL, NULL),
(21, 'Abbey Mortgage Bank', '801', 'abbey-mortgage-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/abbey-mortgage-bank.png', NULL, NULL),
(22, 'ROCKSHIELD MICROFINANCE BANK', '50767', 'rockshield-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/rockshield-microfinance-bank-ng.png', NULL, NULL),
(23, 'Kuda Bank', '50211', 'kuda-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/kuda-bank.png', NULL, NULL),
(24, 'Accion Microfinance Bank', '602', 'accion-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/accion-microfinance-bank-ng.png', NULL, NULL),
(25, 'Bainescredit MFB', '51229', 'bainescredit-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/bainescredit-mfb.png', NULL, NULL),
(26, 'Peace Microfinance Bank', '50743', 'peace-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/peace-microfinance-bank-ng.png', NULL, NULL),
(27, 'Globus Bank', '00103', 'globus-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/globus-bank.png', NULL, NULL),
(28, 'Astrapolaris MFB LTD', 'MFB50094', 'astrapolaris-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/astrapolaris-mfb.png', NULL, NULL),
(29, 'Moniepoint MFB', '50515', 'moniepoint-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/moniepoint-mfb-ng.png', NULL, NULL),
(30, 'Suntrust Bank', '100', 'suntrust-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/suntrust-bank.png', NULL, NULL),
(31, 'Above Only MFB', '51204', 'above-only-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/above-only-mfb.png', NULL, NULL),
(32, 'Unity Bank', '215', 'unity-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/unity-bank.png', NULL, NULL),
(33, 'First City Monument Bank', '214', 'first-city-monument-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/first-city-monument-bank.png', NULL, NULL),
(34, 'Solid Rock MFB', '50800', 'solid-rock-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/solid-rock-mfb.png', NULL, NULL),
(35, 'Sparkle Microfinance Bank', '51310', 'sparkle-microfinance-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/sparkle-microfinance-bank.png', NULL, NULL),
(36, 'Refuge Mortgage Bank', '90067', 'refuge-mortgage-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/refuge-mortgage-bank.png', NULL, NULL),
(37, 'Ilaro Poly Microfinance Bank', '50442', 'ilaro-poly-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/ilaro-poly-microfinance-bank-ng.png', NULL, NULL),
(38, 'Coronation Merchant Bank', '559', 'coronation-merchant-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/coronation-merchant-bank-ng.png', NULL, NULL),
(39, 'U&C Microfinance Bank Ltd (U AND C MFB)', '50840', 'uc-microfinance-bank-ltd-u-and-c-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/uc-microfinance-bank-ltd-u-and-c-mfb-ng.png', NULL, NULL),
(40, 'TAJ Bank', '302', 'taj-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/taj-bank.png', NULL, NULL),
(41, 'Lotus Bank', '303', 'lotus-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/lotus-bank.png', NULL, NULL),
(42, 'PremiumTrust Bank', '105', 'premiumtrust-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/premiumtrust-bank-ng.png', NULL, NULL),
(43, '9mobile 9Payment Service Bank', '120001', '9mobile-9payment-service-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/9mobile-9payment-service-bank-ng.png', NULL, NULL),
(44, 'MTN Momo PSB', '120003', 'mtn-momo-psb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/mtn-momo-psb-ng.png', NULL, NULL),
(45, 'TCF MFB', '51211', 'tcf-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/tcf-mfb.png', NULL, NULL),
(46, 'Ibile Microfinance Bank', '51244', 'ibile-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/ibile-mfb.png', NULL, NULL),
(47, 'CASHCONNECT MFB', '865', 'cashconnect-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/cashconnect-mfb-ng.png', NULL, NULL),
(48, 'Infinity MFB', '50457', 'infinity-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/infinity-mfb.png', NULL, NULL),
(49, 'Providus Bank', '101', 'providus-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/providus-bank.png', NULL, NULL),
(50, 'FirstTrust Mortgage Bank Nigeria', '413', 'firsttrust-mortgage-bank-nigeria-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/firsttrust-mortgage-bank-nigeria-ng.png', NULL, NULL),
(51, 'Ekimogun MFB', '50263', 'ekimogun-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/ekimogun-mfb-ng.png', NULL, NULL),
(52, 'Goodnews Microfinance Bank', '50739', 'goodnews-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/goodnews-microfinance-bank-ng.png', NULL, NULL),
(53, 'Unilag Microfinance Bank', '51316', 'unilag-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/unilag-microfinance-bank-ng.png', NULL, NULL),
(54, 'Rand Merchant Bank', '502', 'rand-merchant-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/rand-merchant-bank.png', NULL, NULL),
(55, 'Union Bank of Nigeria', '032', 'union-bank-of-nigeria', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/union-bank-of-nigeria.png', NULL, NULL),
(56, 'Firmus MFB', '51314', 'firmus-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/firmus-mfb.png', NULL, NULL),
(57, 'Polaris Bank', '076', 'polaris-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/polaris-bank.png', NULL, NULL),
(58, 'Stellas MFB', '51253', 'stellas-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/stellas-mfb.png', NULL, NULL),
(59, 'Solid Allianze MFB', '51062', 'solid-allianze-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/solid-allianze-mfb.png', NULL, NULL),
(60, 'Gateway Mortgage Bank LTD', '812', 'gateway-mortgage-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/gateway-mortgage-bank.png', NULL, NULL),
(61, 'CEMCS Microfinance Bank', '50823', 'cemcs-microfinance-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/cemcs-microfinance-bank.png', NULL, NULL),
(62, 'Consumer Microfinance Bank', '50910', 'consumer-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/consumer-microfinance-bank-ng.png', NULL, NULL),
(63, 'Hasal Microfinance Bank', '50383', 'hasal-microfinance-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/hasal-microfinance-bank.png', NULL, NULL),
(64, 'Carbon', '565', 'carbon', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/carbon.png', NULL, NULL),
(65, 'Keystone Bank', '082', 'keystone-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/keystone-bank.png', NULL, NULL),
(66, 'Ikoyi Osun MFB', '50439', 'ikoyi-osun-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/ikoyi-osun-mfb.png', NULL, NULL),
(67, 'AMPERSAND MICROFINANCE BANK', '51341', 'ampersand-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/ampersand-microfinance-bank-ng.png', NULL, NULL),
(68, 'Chikum Microfinance bank', '312', 'chikum-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/chikum-microfinance-bank-ng.png', NULL, NULL),
(69, 'Amegy Microfinance Bank', '090629', 'amegy-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/amegy-microfinance-bank-ng.png', NULL, NULL),
(70, 'Imowo MFB', '50453', 'imowo-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/imowo-mfb-ng.png', NULL, NULL),
(71, 'SAGE GREY FINANCE LIMITED', '40165', 'sage-grey-finance-limited-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/sage-grey-finance-limited-ng.png', NULL, NULL),
(72, 'Polyunwana MFB', '50864', 'polyunwana-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/polyunwana-mfb-ng.png', NULL, NULL),
(73, 'Zenith Bank', '057', 'zenith-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/zenith-bank.png', NULL, NULL),
(74, 'Fidelity Bank', '070', 'fidelity-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/fidelity-bank.png', NULL, NULL),
(75, 'Standard Chartered Bank', '068', 'standard-chartered-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/standard-chartered-bank.png', NULL, NULL),
(76, 'Abulesoro MFB', '51312', 'abulesoro-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/abulesoro-mfb-ng.png', NULL, NULL),
(77, 'Kadpoly MFB', '50502', 'kadpoly-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/kadpoly-mfb.png', NULL, NULL),
(78, 'QuickFund MFB', '51293', 'quickfund-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/quickfund-mfb.png', NULL, NULL),
(79, 'Parallex Bank', '104', 'parallex-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/parallex-bank.png', NULL, NULL),
(80, 'Living Trust Mortgage Bank', '031', 'living-trust-mortgage-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/living-trust-mortgage-bank.png', NULL, NULL),
(81, 'Stanbic IBTC Bank', '221', 'stanbic-ibtc-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/stanbic-ibtc-bank.png', NULL, NULL),
(82, 'Tangerine Money', '51269', 'tangerine-money', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/tangerine-money.png', NULL, NULL),
(83, 'Ekondo Microfinance Bank', '098', 'ekondo-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/ekondo-microfinance-bank-ng.png', NULL, NULL),
(84, 'FLOURISH MFB', '50315', 'flourish-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/flourish-mfb-ng.png', NULL, NULL),
(85, 'Optimus Bank Limited', '107', 'optimus-bank-ltd', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/optimus-bank-ltd.png', NULL, NULL),
(86, 'Dot Microfinance Bank', '50162', 'dot-microfinance-bank-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/dot-microfinance-bank-ng.png', NULL, NULL),
(87, 'Amju Unique MFB', '50926', 'amju-unique-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/amju-unique-mfb.png', NULL, NULL),
(88, 'HopePSB', '120002', 'hopepsb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/hopepsb-ng.png', NULL, NULL),
(89, 'First Bank of Nigeria', '011', 'first-bank-of-nigeria', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/first-bank-of-nigeria.png', NULL, NULL),
(90, 'Access Bank (Diamond)', '063', 'access-bank-diamond', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/access-bank-diamond.png', NULL, NULL),
(91, 'Uhuru MFB', 'MFB51322', 'uhuru-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/uhuru-mfb-ng.png', NULL, NULL),
(92, 'Safe Haven Microfinance Bank Limited', '951113', 'safe-haven-microfinance-bank-limited-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/safe-haven-microfinance-bank-limited-ng.png', NULL, NULL),
(93, 'Shield MFB', '50582', 'shield-mfb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/shield-mfb-ng.png', NULL, NULL),
(94, 'Chanelle Microfinance Bank Limited', '50171', 'chanelle-microfinance-bank-limited-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/chanelle-microfinance-bank-limited-ng.png', NULL, NULL),
(95, 'Jaiz Bank', '301', 'jaiz-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/jaiz-bank.png', NULL, NULL),
(96, 'Access Bank', '044', 'access-bank', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/access-bank.png', NULL, NULL),
(97, 'VFD Microfinance Bank Limited', '566', 'vfd', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/vfd.png', NULL, NULL),
(98, 'Kredi Money MFB LTD', '50200', 'kredi-money-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/kredi-money-mfb.png', NULL, NULL),
(99, 'Mint MFB', '50304', 'mint-mfb', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/mint-mfb.png', NULL, NULL),
(100, 'Airtel Smartcash PSB', '120004', 'airtel-smartcash-psb-ng', NULL, 'https://supermx1.github.io/nigerian-banks-api/logos/airtel-smartcash-psb-ng.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('teUpDsmdcQ080OcyfxjRbjYwHSTEeGXtFiVKFwJW', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXd1QmI0bTBDcHJBZnVWSEV1MmJheERqd2F6Z3dUSnhPUUhjT3A4NCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9kY2FzaC13ZWIudGVzdC93YWxsZXQvdHJhbnNmZXJzL2NyZWF0ZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyO30=', 1756505737);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `fname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kyc_status` enum('unverified','pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unverified',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `username`, `email`, `phone`, `email_verified_at`, `password`, `kyc_status`, `remember_token`, `is_admin`, `status`, `created_at`, `updated_at`) VALUES
(12, 'Ornen', 'Jacob', 'ornenjacob8070', 'ornen@gmail.com', '08065646464', '2025-08-29 08:56:00', '$2y$12$jrvVWfK98urrg4FfrFrUTeMdeM02hcxILgRUehF3ayc7fDsTONoh.', 'verified', NULL, 0, 'active', '2025-08-29 08:54:30', '2025-08-29 12:21:45');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_bank_accounts`
--

CREATE TABLE `virtual_bank_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `currency_id` bigint UNSIGNED DEFAULT '1',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_code` int DEFAULT NULL,
  `provider` enum('flutterwave','paystack') COLLATE utf8mb4_unicode_ci NOT NULL,
  `trx_ref` varchar(99) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_ref` varchar(99) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `virtual_bank_accounts`
--

INSERT INTO `virtual_bank_accounts` (`id`, `user_id`, `currency_id`, `bank_name`, `account_number`, `account_name`, `bank_code`, `provider`, `trx_ref`, `order_ref`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 12, 1, 'WEMA BANK', '8548926539', 'Ornen Jacob FLW', NULL, 'flutterwave', 'VA-890389cd-601f-45c1-a7e1-4a3d81e634bd', 'URF_1756477136952_3907335', '1', '2025-08-29 13:19:05', '2025-08-29 13:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `currency_id`, `balance`, `created_at`, `updated_at`) VALUES
(2, 12, 1, 45.88, '2025-08-29 13:19:05', '2025-08-29 13:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `type` enum('deposit','airtime','data','electricity','transfer','withdrawal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(16,4) NOT NULL COMMENT 'The principal amount of the transaction',
  `charge` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','successful','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `metadata` json DEFAULT NULL COMMENT 'To store extra details like phone number, meter number, recipient account etc.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beneficiaries_user_id_foreign` (`user_id`),
  ADD KEY `beneficiaries_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bill_payments_reference_unique` (`reference`),
  ADD KEY `bill_payments_user_id_status_index` (`user_id`,`status`),
  ADD KEY `bill_payments_reference_index` (`reference`),
  ADD KEY `bill_payments_biller_code_status_index` (`biller_code`,`status`),
  ADD KEY `bill_payments_created_at_status_index` (`created_at`,`status`);

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
-- Indexes for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_bank_accounts_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_name_unique` (`name`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`);

--
-- Indexes for table `currency_pairs`
--
ALTER TABLE `currency_pairs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currency_pairs_base_currency_id_quote_currency_id_unique` (`base_currency_id`,`quote_currency_id`),
  ADD KEY `currency_pairs_quote_currency_id_foreign` (`quote_currency_id`);

--
-- Indexes for table `exchange_transactions`
--
ALTER TABLE `exchange_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exchange_transactions_reference_unique` (`reference`),
  ADD KEY `exchange_transactions_company_bank_account_id_foreign` (`company_bank_account_id`),
  ADD KEY `exchange_transactions_user_id_foreign` (`user_id`),
  ADD KEY `exchange_transactions_from_currency_id_foreign` (`from_currency_id`),
  ADD KEY `exchange_transactions_to_currency_id_foreign` (`to_currency_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `flutterwave_bills_items`
--
ALTER TABLE `flutterwave_bills_items`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kyc_verifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nigerian_banks`
--
ALTER TABLE `nigerian_banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nigerian_banks_code_unique` (`code`),
  ADD UNIQUE KEY `nigerian_banks_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

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
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `virtual_bank_accounts`
--
ALTER TABLE `virtual_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `virtual_bank_accounts_account_number_unique` (`account_number`),
  ADD KEY `virtual_bank_accounts_user_id_foreign` (`user_id`),
  ADD KEY `virtual_bank_accounts_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`),
  ADD KEY `wallets_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallet_transactions_reference_unique` (`reference`),
  ADD KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_payments`
--
ALTER TABLE `bill_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `currency_pairs`
--
ALTER TABLE `currency_pairs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `exchange_transactions`
--
ALTER TABLE `exchange_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flutterwave_bills_items`
--
ALTER TABLE `flutterwave_bills_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17771;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `nigerian_banks`
--
ALTER TABLE `nigerian_banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `virtual_bank_accounts`
--
ALTER TABLE `virtual_bank_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beneficiaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  ADD CONSTRAINT `company_bank_accounts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `currency_pairs`
--
ALTER TABLE `currency_pairs`
  ADD CONSTRAINT `currency_pairs_base_currency_id_foreign` FOREIGN KEY (`base_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `currency_pairs_quote_currency_id_foreign` FOREIGN KEY (`quote_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exchange_transactions`
--
ALTER TABLE `exchange_transactions`
  ADD CONSTRAINT `exchange_transactions_company_bank_account_id_foreign` FOREIGN KEY (`company_bank_account_id`) REFERENCES `company_bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exchange_transactions_from_currency_id_foreign` FOREIGN KEY (`from_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exchange_transactions_to_currency_id_foreign` FOREIGN KEY (`to_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exchange_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD CONSTRAINT `kyc_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `virtual_bank_accounts`
--
ALTER TABLE `virtual_bank_accounts`
  ADD CONSTRAINT `virtual_bank_accounts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `virtual_bank_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
