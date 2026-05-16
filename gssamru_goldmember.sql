-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 20, 2026 at 12:09 PM
-- Server version: 10.11.16-MariaDB-cll-lve-log
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gssamru_goldmember`
--

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

CREATE TABLE `aboutus` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `left_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `aboutus`
--

INSERT INTO `aboutus` (`id`, `title`, `short_description`, `description`, `left_text`) VALUES
(1, 'Goldmember', '<p>Շնորհավորում եմ քեզ, դու վերջապես գտար ոսկյա զարդերի և ադամանդների առք և վաճառք առաջատար հարթակը տարածաշրջանում</p>\r\n', '<p>Congratulations, you finally found the top wholesale platform of gold, golden jewelry and diamonds in region.</p>\r\n', '<p>Congratulations, you finally found the top wholesale platform of gold, golden jewelry and diamonds in region.</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `address` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address_attr`
--

CREATE TABLE `address_attr` (
  `id` int(10) UNSIGNED NOT NULL,
  `attr_name` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `attr_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `address_attr`
--

INSERT INTO `address_attr` (`id`, `attr_name`, `attr_value`) VALUES
(1, 'Nrbancq', ''),
(2, 'Drop', '');

-- --------------------------------------------------------

--
-- Table structure for table `attribute`
--

CREATE TABLE `attribute` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_unity` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `attribute`
--

INSERT INTO `attribute` (`id`, `name`, `category_id`, `path`, `parent_id`, `type`, `ordering`, `created_date`, `updated_date`, `is_unity`) VALUES
(1, 'sfgsdfg', NULL, NULL, NULL, 1, 1, '2024-09-07 16:44:11', '2024-09-07 16:44:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `attribute_category`
--

CREATE TABLE `attribute_category` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `attribute_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `attribute_category`
--

INSERT INTO `attribute_category` (`id`, `category_id`, `attribute_id`) VALUES
(2, 11, 2),
(3, 12, 2),
(4, 13, 2),
(5, 11, 3),
(6, 12, 3),
(7, 13, 3),
(8, 11, 4),
(9, 12, 4),
(10, 11, 5),
(11, 12, 5),
(12, 13, 5),
(13, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `auction`
--

CREATE TABLE `auction` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `start_price` float DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lot_number` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `auction`
--

INSERT INTO `auction` (`id`, `product_id`, `start_date`, `end_date`, `video_link`, `start_price`, `user_id`, `lot_number`, `created_date`, `updated_at`) VALUES
(5, 10, '2025-05-06 07:20:23', '0000-00-00', 'fghdfgh', 123, NULL, NULL, '2025-05-04 14:01:30', '2025-05-04 15:25:11'),
(6, 11, '2025-06-09 00:00:00', '2025-06-09', 'fghdfgh', 200.5, NULL, NULL, '2025-06-07 17:13:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `user_id`, `title`, `description`, `short_description`, `meta_description`, `meta_key`, `status`, `views`, `created_at`, `updated_at`, `deleted_at`, `ordering`) VALUES
(1, 0, 'asdfasdf', '<p>asdfasdfasdf</p>\r\n', 'sdfsadf', 'asdfasdfasdf', 'asdfasdf', 1, 0, '2019-03-05 15:21:55', '0000-00-00 00:00:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `opened` tinyint(1) DEFAULT 0,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `route_name`, `description`, `short_description`, `path`, `parent_id`, `ordering`, `opened`, `created_date`, `updated_date`) VALUES
(20, 'Մատանիներ', 'rings', NULL, NULL, NULL, NULL, 1, 0, '2025-05-04 12:44:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `state_id` int(11) NOT NULL,
  `ordering` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `state_id`, `ordering`) VALUES
(1, 'asdfasdf', 232, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `connected_products`
--

CREATE TABLE `connected_products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `conn_product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phonecode` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `sortname`, `name`, `phonecode`, `status`) VALUES
(1, 'AF', 'Afghanistan', 93, 1),
(2, 'AL', 'Albania', 355, 1),
(3, 'DZ', 'Algeria', 213, 1),
(4, 'AS', 'American Samoa', 1684, 1),
(5, 'AD', 'Andorra', 376, 1),
(6, 'AO', 'Angola', 244, 1),
(7, 'AI', 'Anguilla', 1264, 1),
(8, 'AQ', 'Antarctica', 0, 1),
(9, 'AG', 'Antigua And Barbuda', 1268, 1),
(10, 'AR', 'Argentina', 54, 1),
(11, 'AM', 'Armenia', 374, 1),
(12, 'AW', 'Aruba', 297, 1),
(13, 'AU', 'Australia', 61, 1),
(14, 'AT', 'Austria', 43, 1),
(15, 'AZ', 'Azerbaijan', 994, 1),
(16, 'BS', 'Bahamas The', 1242, 1),
(17, 'BH', 'Bahrain', 973, 1),
(18, 'BD', 'Bangladesh', 880, 1),
(19, 'BB', 'Barbados', 1246, 1),
(20, 'BY', 'Belarus', 375, 1),
(21, 'BE', 'Belgium', 32, 1),
(22, 'BZ', 'Belize', 501, 1),
(23, 'BJ', 'Benin', 229, 1),
(24, 'BM', 'Bermuda', 1441, 1),
(25, 'BT', 'Bhutan', 975, 1),
(26, 'BO', 'Bolivia', 591, 1),
(27, 'BA', 'Bosnia and Herzegovina', 387, 1),
(28, 'BW', 'Botswana', 267, 1),
(29, 'BV', 'Bouvet Island', 0, 1),
(30, 'BR', 'Brazil', 55, 1),
(31, 'IO', 'British Indian Ocean Territory', 246, 1),
(32, 'BN', 'Brunei', 673, 1),
(33, 'BG', 'Bulgaria', 359, 1),
(34, 'BF', 'Burkina Faso', 226, 1),
(35, 'BI', 'Burundi', 257, 1),
(36, 'KH', 'Cambodia', 855, 1),
(37, 'CM', 'Cameroon', 237, 1),
(38, 'CA', 'Canada', 1, 1),
(39, 'CV', 'Cape Verde', 238, 1),
(40, 'KY', 'Cayman Islands', 1345, 1),
(41, 'CF', 'Central African Republic', 236, 1),
(42, 'TD', 'Chad', 235, 1),
(43, 'CL', 'Chile', 56, 1),
(44, 'CN', 'China', 86, 1),
(45, 'CX', 'Christmas Island', 61, 1),
(46, 'CC', 'Cocos (Keeling) Islands', 672, 1),
(47, 'CO', 'Colombia', 57, 1),
(48, 'KM', 'Comoros', 269, 1),
(49, 'CG', 'Republic Of The Congo', 242, 1),
(50, 'CD', 'Democratic Republic Of The Congo', 242, 1),
(51, 'CK', 'Cook Islands', 682, 1),
(52, 'CR', 'Costa Rica', 506, 1),
(53, 'CI', 'Cote D\'Ivoire (Ivory Coast)', 225, 1),
(54, 'HR', 'Croatia (Hrvatska)', 385, 1),
(55, 'CU', 'Cuba', 53, 1),
(56, 'CY', 'Cyprus', 357, 1),
(57, 'CZ', 'Czech Republic', 420, 1),
(58, 'DK', 'Denmark', 45, 1),
(59, 'DJ', 'Djibouti', 253, 1),
(60, 'DM', 'Dominica', 1767, 1),
(61, 'DO', 'Dominican Republic', 1809, 1),
(62, 'TP', 'East Timor', 670, 1),
(63, 'EC', 'Ecuador', 593, 1),
(64, 'EG', 'Egypt', 20, 1),
(65, 'SV', 'El Salvador', 503, 1),
(66, 'GQ', 'Equatorial Guinea', 240, 1),
(67, 'ER', 'Eritrea', 291, 1),
(68, 'EE', 'Estonia', 372, 1),
(69, 'ET', 'Ethiopia', 251, 1),
(70, 'XA', 'External Territories of Australia', 61, 1),
(71, 'FK', 'Falkland Islands', 500, 1),
(72, 'FO', 'Faroe Islands', 298, 1),
(73, 'FJ', 'Fiji Islands', 679, 1),
(74, 'FI', 'Finland', 358, 1),
(75, 'FR', 'France', 33, 1),
(76, 'GF', 'French Guiana', 594, 1),
(77, 'PF', 'French Polynesia', 689, 1),
(78, 'TF', 'French Southern Territories', 0, 1),
(79, 'GA', 'Gabon', 241, 1),
(80, 'GM', 'Gambia The', 220, 1),
(81, 'GE', 'Georgia', 995, 1),
(82, 'DE', 'Germany', 49, 1),
(83, 'GH', 'Ghana', 233, 1),
(84, 'GI', 'Gibraltar', 350, 1),
(85, 'GR', 'Greece', 30, 1),
(86, 'GL', 'Greenland', 299, 1),
(87, 'GD', 'Grenada', 1473, 1),
(88, 'GP', 'Guadeloupe', 590, 1),
(89, 'GU', 'Guam', 1671, 1),
(90, 'GT', 'Guatemala', 502, 1),
(91, 'XU', 'Guernsey and Alderney', 44, 1),
(92, 'GN', 'Guinea', 224, 1),
(93, 'GW', 'Guinea-Bissau', 245, 1),
(94, 'GY', 'Guyana', 592, 1),
(95, 'HT', 'Haiti', 509, 1),
(96, 'HM', 'Heard and McDonald Islands', 0, 1),
(97, 'HN', 'Honduras', 504, 1),
(98, 'HK', 'Hong Kong S.A.R.', 852, 1),
(99, 'HU', 'Hungary', 36, 1),
(100, 'IS', 'Iceland', 354, 1),
(101, 'IN', 'India', 91, 1),
(102, 'ID', 'Indonesia', 62, 1),
(103, 'IR', 'Iran', 98, 1),
(104, 'IQ', 'Iraq', 964, 1),
(105, 'IE', 'Ireland', 353, 1),
(106, 'IL', 'Israel', 972, 1),
(107, 'IT', 'Italy', 39, 1),
(108, 'JM', 'Jamaica', 1876, 1),
(109, 'JP', 'Japan', 81, 1),
(110, 'XJ', 'Jersey', 44, 1),
(111, 'JO', 'Jordan', 962, 1),
(112, 'KZ', 'Kazakhstan', 7, 1),
(113, 'KE', 'Kenya', 254, 1),
(114, 'KI', 'Kiribati', 686, 1),
(115, 'KP', 'Korea North', 850, 1),
(116, 'KR', 'Korea South', 82, 1),
(117, 'KW', 'Kuwait', 965, 1),
(118, 'KG', 'Kyrgyzstan', 996, 1),
(119, 'LA', 'Laos', 856, 1),
(120, 'LV', 'Latvia', 371, 1),
(121, 'LB', 'Lebanon', 961, 1),
(122, 'LS', 'Lesotho', 266, 1),
(123, 'LR', 'Liberia', 231, 1),
(124, 'LY', 'Libya', 218, 1),
(125, 'LI', 'Liechtenstein', 423, 1),
(126, 'LT', 'Lithuania', 370, 1),
(127, 'LU', 'Luxembourg', 352, 1),
(128, 'MO', 'Macau S.A.R.', 853, 1),
(129, 'MK', 'Macedonia', 389, 1),
(130, 'MG', 'Madagascar', 261, 1),
(131, 'MW', 'Malawi', 265, 1),
(132, 'MY', 'Malaysia', 60, 1),
(133, 'MV', 'Maldives', 960, 1),
(134, 'ML', 'Mali', 223, 1),
(135, 'MT', 'Malta', 356, 1),
(136, 'XM', 'Man (Isle of)', 44, 1),
(137, 'MH', 'Marshall Islands', 692, 1),
(138, 'MQ', 'Martinique', 596, 1),
(139, 'MR', 'Mauritania', 222, 1),
(140, 'MU', 'Mauritius', 230, 1),
(141, 'YT', 'Mayotte', 269, 1),
(142, 'MX', 'Mexico', 52, 1),
(143, 'FM', 'Micronesia', 691, 1),
(144, 'MD', 'Moldova', 373, 1),
(145, 'MC', 'Monaco', 377, 1),
(146, 'MN', 'Mongolia', 976, 1),
(147, 'MS', 'Montserrat', 1664, 1),
(148, 'MA', 'Morocco', 212, 1),
(149, 'MZ', 'Mozambique', 258, 1),
(150, 'MM', 'Myanmar', 95, 1),
(151, 'NA', 'Namibia', 264, 1),
(152, 'NR', 'Nauru', 674, 1),
(153, 'NP', 'Nepal', 977, 1),
(154, 'AN', 'Netherlands Antilles', 599, 1),
(155, 'NL', 'Netherlands The', 31, 1),
(156, 'NC', 'New Caledonia', 687, 1),
(157, 'NZ', 'New Zealand', 64, 1),
(158, 'NI', 'Nicaragua', 505, 1),
(159, 'NE', 'Niger', 227, 1),
(160, 'NG', 'Nigeria', 234, 1),
(161, 'NU', 'Niue', 683, 1),
(162, 'NF', 'Norfolk Island', 672, 1),
(163, 'MP', 'Northern Mariana Islands', 1670, 1),
(164, 'NO', 'Norway', 47, 1),
(165, 'OM', 'Oman', 968, 1),
(166, 'PK', 'Pakistan', 92, 1),
(167, 'PW', 'Palau', 680, 1),
(168, 'PS', 'Palestinian Territory Occupied', 970, 1),
(169, 'PA', 'Panama', 507, 1),
(170, 'PG', 'Papua new Guinea', 675, 1),
(171, 'PY', 'Paraguay', 595, 1),
(172, 'PE', 'Peru', 51, 1),
(173, 'PH', 'Philippines', 63, 1),
(174, 'PN', 'Pitcairn Island', 0, 1),
(175, 'PL', 'Poland', 48, 1),
(176, 'PT', 'Portugal', 351, 1),
(177, 'PR', 'Puerto Rico', 1787, 1),
(178, 'QA', 'Qatar', 974, 1),
(179, 'RE', 'Reunion', 262, 1),
(180, 'RO', 'Romania', 40, 1),
(181, 'RU', 'Russia', 70, 1),
(182, 'RW', 'Rwanda', 250, 1),
(183, 'SH', 'Saint Helena', 290, 1),
(184, 'KN', 'Saint Kitts And Nevis', 1869, 1),
(185, 'LC', 'Saint Lucia', 1758, 1),
(186, 'PM', 'Saint Pierre and Miquelon', 508, 1),
(187, 'VC', 'Saint Vincent And The Grenadines', 1784, 1),
(188, 'WS', 'Samoa', 684, 1),
(189, 'SM', 'San Marino', 378, 1),
(190, 'ST', 'Sao Tome and Principe', 239, 1),
(191, 'SA', 'Saudi Arabia', 966, 1),
(192, 'SN', 'Senegal', 221, 1),
(193, 'RS', 'Serbia', 381, 1),
(194, 'SC', 'Seychelles', 248, 1),
(195, 'SL', 'Sierra Leone', 232, 1),
(196, 'SG', 'Singapore', 65, 1),
(197, 'SK', 'Slovakia', 421, 1),
(198, 'SI', 'Slovenia', 386, 1),
(199, 'XG', 'Smaller Territories of the UK', 44, 1),
(200, 'SB', 'Solomon Islands', 677, 1),
(201, 'SO', 'Somalia', 252, 1),
(202, 'ZA', 'South Africa', 27, 1),
(203, 'GS', 'South Georgia', 0, 1),
(204, 'SS', 'South Sudan', 211, 1),
(205, 'ES', 'Spain', 34, 1),
(206, 'LK', 'Sri Lanka', 94, 1),
(207, 'SD', 'Sudan', 249, 1),
(208, 'SR', 'Suriname', 597, 1),
(209, 'SJ', 'Svalbard And Jan Mayen Islands', 47, 1),
(210, 'SZ', 'Swaziland', 268, 1),
(211, 'SE', 'Sweden', 46, 1),
(212, 'CH', 'Switzerland', 41, 1),
(213, 'SY', 'Syria', 963, 1),
(214, 'TW', 'Taiwan', 886, 1),
(215, 'TJ', 'Tajikistan', 992, 1),
(216, 'TZ', 'Tanzania', 255, 1),
(217, 'TH', 'Thailand', 66, 1),
(218, 'TG', 'Togo', 228, 1),
(219, 'TK', 'Tokelau', 690, 1),
(220, 'TO', 'Tonga', 676, 1),
(221, 'TT', 'Trinidad And Tobago', 1868, 1),
(222, 'TN', 'Tunisia', 216, 1),
(223, 'TR', 'Turkey', 90, 1),
(224, 'TM', 'Turkmenistan', 7370, 1),
(225, 'TC', 'Turks And Caicos Islands', 1649, 1),
(226, 'TV', 'Tuvalu', 688, 1),
(227, 'UG', 'Uganda', 256, 1),
(228, 'UA', 'Ukraine', 380, 1),
(229, 'AE', 'United Arab Emirates', 971, 1),
(230, 'GB', 'United Kingdom', 44, 1),
(231, 'US', 'United States', 1, 1),
(232, 'UM', 'United States Minor Outlying Islands', 1, 1),
(233, 'UY', 'Uruguay', 598, 1),
(234, 'UZ', 'Uzbekistan', 998, 1),
(235, 'VU', 'Vanuatu', 678, 1),
(236, 'VA', 'Vatican City State (Holy See)', 39, 1),
(237, 'VE', 'Venezuela', 58, 1),
(238, 'VN', 'Vietnam', 84, 1),
(239, 'VG', 'Virgin Islands (British)', 1284, 1),
(240, 'VI', 'Virgin Islands (US)', 1340, 1),
(241, 'WF', 'Wallis And Futuna Islands', 681, 1),
(242, 'EH', 'Western Sahara', 212, 1),
(243, 'YE', 'Yemen', 967, 1),
(244, 'YU', 'Yugoslavia', 38, 1),
(245, 'ZM', 'Zambia', 260, 1),
(246, 'ZW', 'Zimbabwe', 263, 1);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `symbol` varchar(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `created_at`, `updated_at`) VALUES
(1, 'USD', 'Dollar', '', '2025-03-30 16:28:01', NULL),
(2, 'EUR', 'Euro', '', '2025-03-30 16:28:18', NULL),
(4, 'RUB', 'Ruble', '', '2025-03-30 16:28:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `social_user_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'active or passive',
  `user_id` int(11) DEFAULT NULL COMMENT 'it will be login id, for registered users and null for guests',
  `last_ip` varchar(50) DEFAULT NULL,
  `auth_token` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `surname`, `email`, `phone`, `mobile_phone`, `company_name`, `social_user_name`, `status`, `user_id`, `last_ip`, `auth_token`, `created_date`, `updated_date`) VALUES
(1, 'Harut55', 'Soghomonyan55', 'test@test.com', '+37477355378', NULL, NULL, '', 1, 18, '46.71.249.175', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Sargis', 'Machkalyan', 'sarkismatchkalian@hotmail.com', '+374555555555', NULL, NULL, '', 1, NULL, '37.252.82.72', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'dgdfghdfgh', 'dfgdfghdfgh', 'test@test2.com', NULL, NULL, NULL, '', 0, 34, '45.159.74.69', '', '2025-05-18 09:48:20', '2025-05-18 09:48:20'),
(4, 'Harut', 'Soghomonyan', 'harut.soghomonyan@gmail.com', NULL, NULL, NULL, '', 0, 35, '45.159.74.70', '', '2025-05-25 03:40:52', '2025-05-25 03:40:52'),
(5, 'Albert', 'TEST', 'tes@testAlber.com', NULL, NULL, NULL, '', 0, 36, '45.159.74.70', '', '2025-05-25 03:41:38', '2025-05-25 03:41:38'),
(7, 'Test User', 'Test Fname', 'test@email.com', NULL, NULL, NULL, '', 0, 38, '45.159.74.70', '', '2025-05-25 04:13:33', '2025-05-25 04:13:33'),
(8, 'AlbertTest', 'Albert2test', 'albert@test.com', NULL, NULL, NULL, '', 0, 39, '87.241.157.8', '', '2025-05-25 08:04:43', '2025-05-25 08:04:43'),
(10, 'Sergey', 'Ilyin', 'stilauto@mail.ru', NULL, NULL, NULL, '', 0, 41, '178.78.141.198', '', '2025-10-08 11:26:57', '2025-10-08 11:26:57'),
(11, 'Harut', 'Soghomonyan', 'harut.soghomonyan1@gmail.com', NULL, NULL, NULL, '', 0, 42, '178.78.161.97', '', '2025-12-11 15:03:10', '2025-12-11 15:03:10'),
(12, 'New', 'User', 'user@test.com', NULL, NULL, NULL, '', 0, 43, '104.28.252.201', '', '2025-12-11 15:11:26', '2025-12-11 15:11:26'),
(13, 'User', 'name', 'user@test2.com', NULL, NULL, NULL, '', 0, 44, '178.78.161.97', '', '2025-12-11 15:54:57', '2025-12-11 15:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
  `id` int(11) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `long` double DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `default_address` smallint(1) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donate`
--

CREATE TABLE `donate` (
  `id` int(11) UNSIGNED NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `donate`
--

INSERT INTO `donate` (`id`, `bank_name`, `bank_account`, `description`) VALUES
(2, 'Inecobank', '74854465464798789', ''),
(3, 'Ardshinbank', '8789789798', '');

-- --------------------------------------------------------

--
-- Table structure for table `donation_info`
--

CREATE TABLE `donation_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`transaction_info`))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `donation_info`
--

INSERT INTO `donation_info` (`id`, `name`, `email`, `phone`, `amount`, `message`, `created_at`, `updated_at`, `status`, `transaction_info`) VALUES
(1, 'ddfgh', 'harut.soghomonyan@gmail.com', '077355378', 100, NULL, '2025-02-02 18:43:13', NULL, 0, NULL),
(2, 'Harut Soghomonyan', 'harut.soghomonyan@gmail.com', '077355378', 50000, NULL, '2025-05-25 07:39:31', NULL, 0, NULL),
(3, 'Harut Soghomonyan3333', 'harut.soghomonyan@gmail.com333', '0773553783333', 20000, NULL, '2025-05-25 07:39:52', NULL, 0, NULL),
(4, 'ggrggt', 'gt5gt@ili9ol9.8i', 'u7u', 20000, NULL, '2025-05-25 11:34:33', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exchange`
--

CREATE TABLE `exchange` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `exchange_enum` enum('usd','eur','rub','gold','silver') NOT NULL,
  `sell` varchar(50) NOT NULL,
  `buy` varchar(50) NOT NULL,
  `original_sell` varchar(50) DEFAULT NULL,
  `original_buy` varchar(50) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `exchange`
--

INSERT INTO `exchange` (`id`, `name`, `exchange_enum`, `sell`, `buy`, `original_sell`, `original_buy`, `data`, `created_date`, `updated_date`) VALUES
(24, 'Gold', 'usd', '86.31', '86.31', '86.31', '86.31', NULL, '2024-11-11 12:50:11', '0000-00-00 00:00:00'),
(25, 'Gold', 'usd', '87.23', '87.23', '87.23', '87.23', NULL, '2024-11-24 16:28:31', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `sell_rate` decimal(10,2) NOT NULL,
  `buy_rate` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `currency_id`, `sell_rate`, `buy_rate`, `created_at`, `updated_at`) VALUES
(3, 2, 448.00, 436.00, '2025-03-30 20:17:27', '2025-08-23 07:32:22'),
(4, 4, 4.83, 4.69, '2025-04-04 16:35:14', '2025-08-23 07:33:56'),
(7, 1, 382.68, 390.00, '2025-09-02 14:31:00', '2025-09-02 14:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `category` enum('gallery','video','about','power-of-penny') DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `top` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `extension` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `path`, `category`, `category_id`, `mime`, `created_at`, `updated_at`, `top`, `status`, `extension`) VALUES
(7, '16968557846523f6e8095d25.43823296_0.jpg', 'gallery', NULL, NULL, '2023-10-09 12:49:44', NULL, NULL, 1, NULL),
(8, '16968558176523f709447222.96655944_0.jpg', 'gallery', NULL, NULL, '2023-10-09 12:50:17', NULL, NULL, 1, NULL),
(9, '16968558176523f7094488f8.14746348_1.jpg', 'gallery', NULL, NULL, '2023-10-09 12:50:17', NULL, NULL, 1, NULL),
(10, '16968558436523f7232b3508.17892856_0.jpg', 'gallery', NULL, NULL, '2023-10-09 12:50:43', NULL, NULL, 1, NULL),
(11, NULL, NULL, NULL, NULL, '2023-10-09 12:51:53', NULL, NULL, 1, NULL),
(12, NULL, NULL, NULL, NULL, '2023-10-09 12:51:55', NULL, NULL, 1, NULL),
(13, NULL, 'video', NULL, NULL, '2023-10-09 12:54:01', NULL, NULL, 1, NULL),
(14, NULL, 'video', NULL, NULL, '2023-10-09 12:54:09', NULL, NULL, 1, NULL),
(25, '172573937966dcb17335f771.64574774_0.png', 'gallery', NULL, NULL, '2024-09-07 20:02:59', NULL, NULL, 1, NULL),
(26, '172573939666dcb1848118e1.18520295_0.png', 'gallery', NULL, NULL, '2024-09-07 20:03:16', NULL, NULL, 1, NULL),
(27, NULL, 'video', NULL, NULL, '2024-09-16 18:54:26', NULL, NULL, 1, NULL),
(28, NULL, 'video', NULL, NULL, '2024-09-16 18:54:37', NULL, NULL, 1, NULL),
(30, '17314839786734594a921523.24247074_0.jpg', 'power-of-penny', 1, NULL, '2024-11-13 07:46:22', NULL, 1, 1, NULL),
(31, '175587902168a8966d35da40.52950927_0.jpg', 'power-of-penny', 12, NULL, '2025-08-22 16:10:21', NULL, 1, 1, ''),
(32, '175593523268a9720088dad2.62733589_0.jpg', 'power-of-penny', 13, NULL, '2025-08-23 07:47:12', NULL, 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `homepage`
--

CREATE TABLE `homepage` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `is_banner` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `short_code` varchar(250) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT 1,
  `is_default` smallint(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `short_code`, `ordering`, `is_default`) VALUES
(2, 'English', 'en', 1, 0),
(3, 'Armenia', 'am', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`id`, `name`, `short_description`, `description`, `path`) VALUES
(1, 'Gold', 'dfgdfg', NULL, NULL),
(5, 'Briliant', 'asdfasdf', NULL, ''),
(6, 'Silver', 'sdfgsdfg', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `language` varchar(16) NOT NULL,
  `translation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `language`, `translation`) VALUES
(1, 'am', 'Short Description'),
(2, 'am', 'Description'),
(3, 'am', 'Обновить'),
(4, 'am', 'Category'),
(5, 'am', 'Update'),
(6, 'am', 'Delete'),
(7, 'am', 'Создать категорию'),
(8, 'am', 'Name'),
(9, 'am', 'Сброс'),
(10, 'am', 'Серии'),
(11, 'am', 'Фильтры'),
(12, 'am', 'Создать Фильтр'),
(13, 'am', 'Картинка'),
(14, 'am', 'Category www'),
(15, 'am', 'Имя Фильтра'),
(16, 'am', 'Choose Category'),
(17, 'am', 'Добавить новый атрибут'),
(18, 'am', 'Создать'),
(19, 'am', 'Бренды'),
(20, 'am', 'Бренды'),
(21, 'am', 'Создать бренд'),
(22, 'am', 'Вернуться к списку брендов'),
(23, 'am', 'Имя бренда'),
(24, 'am', 'Active'),
(25, 'am', 'Неактивный'),
(26, 'am', 'Выбрать категорию'),
(27, 'am', 'Бренд успешно создан'),
(28, 'am', 'Общий'),
(29, 'am', 'Products'),
(30, 'am', 'Add Product'),
(31, 'am', 'Обратно к списку'),
(32, 'am', 'Product Name'),
(33, 'am', 'Product Code'),
(34, 'am', 'Названия URL'),
(35, 'am', 'Price'),
(36, 'am', 'Pages'),
(37, 'am', 'Заказы'),
(38, 'am', 'Product Management'),
(39, 'am', 'Клиенты'),
(40, 'am', 'ТОВАРЫ НЕДЕЛИ'),
(41, 'am', 'Sliders'),
(42, 'am', 'Новый продукт'),
(43, 'am', 'Популярный'),
(44, 'am', 'Sales'),
(45, 'am', 'Скидка'),
(46, 'am', 'Распродажа'),
(47, 'am', 'Pages'),
(48, 'am', 'Создать новую страницу'),
(49, 'am', 'Заглавие'),
(50, 'am', 'Status'),
(51, 'am', 'Мы свяжемся с вами течение дня'),
(52, 'am', 'Կապ'),
(53, 'am', 'Dashboard'),
(54, 'am', 'Изменить пароль'),
(55, 'am', 'Настройки'),
(56, 'am', 'Изменить логин'),
(57, 'am', 'User Name'),
(58, 'am', 'Рабочее время'),
(59, 'am', 'Название сайта'),
(60, 'am', 'Электронная почта сайта'),
(61, 'am', 'Телефон Сайта'),
(62, 'am', 'Телефон диспетчера доставки'),
(63, 'am', 'Комментарий доставки'),
(64, 'am', 'Создать социальные иконки'),
(65, 'am', 'Ссылка'),
(66, 'am', 'Название соц. страницы'),
(67, 'am', 'Создать Слайдер'),
(68, 'am', 'Посмотреть корзину'),
(69, 'am', 'Перейти к оформлению'),
(70, 'am', 'Корзина '),
(71, 'am', 'View'),
(72, 'am', 'Клиент'),
(73, 'am', 'Address'),
(74, 'am', 'Phone'),
(75, 'am', 'Характеристики'),
(76, 'am', 'Тех Данные'),
(77, 'am', 'Связанные продукты'),
(78, 'am', 'Создать социальную иконку'),
(79, 'am', 'Социальная страница'),
(80, 'am', 'Текущий пароль'),
(81, 'am', 'Новый пароль'),
(82, 'am', 'Повторите пароль'),
(83, 'am', 'Слайдер успешно создан'),
(84, 'am', 'Выбрать родительский каталог'),
(85, 'am', 'Открыто по умолчанию'),
(86, 'am', 'Создать серию'),
(87, 'am', 'Выберите бренд'),
(88, 'am', 'Выберите Серию'),
(89, 'am', 'Поиск'),
(90, 'am', 'asf'),
(91, 'am', 'Filters'),
(92, 'am', 'Users'),
(93, 'am', 'Մեր Մասին'),
(94, 'am', 'Cities'),
(95, 'am', 'Countries'),
(96, 'am', 'Addresses'),
(97, 'am', 'States'),
(98, 'am', 'Rent'),
(99, 'am', 'States'),
(100, 'am', 'Choose City'),
(101, 'am', 'Choose State'),
(102, 'am', 'Choose Address'),
(103, 'am', 'Գլխավոր'),
(103, 'en', 'Home'),
(104, 'am', 'No Found'),
(105, 'am', 'View More'),
(106, 'am', 'From'),
(107, 'am', 'To'),
(108, 'am', 'User Addresses'),
(109, 'am', 'Բրոկերների Կառավարում'),
(110, 'am', 'Բրոկերներ'),
(111, 'am', 'Ավելացնել Բրոկեր'),
(112, 'am', 'Բրոկերի Համարը'),
(113, 'am', 'Product Filters'),
(114, 'am', 'Հասցեն հաջողությամբ ավելացվել է'),
(115, 'am', 'Հասցեն գոյություն ունի'),
(116, 'am', 'Շենք Համար'),
(117, 'am', 'Բնակարան Համար'),
(118, 'am', 'Պահպանել Հասցեն'),
(119, 'am', 'Լրացրեք Հասցեն'),
(120, 'am', 'Լրացրեք Հասցեն'),
(121, 'am', 'Հեռախոսահամար'),
(122, 'am', 'Էլ. Հասցե'),
(123, 'am', 'Գաղտնաբառ'),
(124, 'am', 'Դադարեցված'),
(125, 'am', 'Վաճառված'),
(126, 'am', 'Ընտրել Բրոկերին'),
(127, 'am', 'Վաճառողի Անունը'),
(128, 'am', 'Վաճառողի Հեռ. Համարը'),
(129, 'am', 'Փողոց'),
(130, 'am', 'Քաղաք'),
(131, 'am', 'Ընտրեք տարբերակներ'),
(132, 'am', 'Select Status'),
(133, 'am', 'Տիփ'),
(134, 'am', 'Back to Products'),
(135, 'am', 'Ցույց տալ գլխավոր էջում'),
(136, 'am', 'Վաճառվում է'),
(137, 'am', 'Թոփ առաջարկներ'),
(138, 'am', 'Ավելացնել թիմի անդամ'),
(139, 'am', 'Թիմի անդամները'),
(140, 'am', 'Անուն'),
(141, 'am', 'Ազգանուն'),
(142, 'am', 'Ավելացնել թիմի անդամ'),
(143, 'am', 'Մասնագիտություն'),
(144, 'am', 'Ավելացնել'),
(145, 'am', 'Մեր Թիմը'),
(146, 'am', 'Դիտել սկզբի էջում'),
(147, 'am', 'All Products'),
(148, 'am', 'Վաճառք'),
(149, 'am', 'Վարձակալություն'),
(150, 'am', 'Հողատարածքներ'),
(151, 'am', 'Այլ Մանրամասներ'),
(152, 'am', 'Ավելացնել'),
(153, 'en-US', 'LATEST CARS'),
(154, 'am', 'READ MORE6929'),
(155, 'am', 'Latest Cars'),
(156, 'en', 'Auction'),
(157, 'am', 'Register AM'),
(158, 'am', 'Lorem ipsum dolor sit amet. Id consectetur tempora rem ratione magnam qui voluptatem aperiam et autem quia. Sed voluptatum cupiditate aut quia ducimus qui magnam quos nam doloremque tempora.'),
(158, 'en', 'Lorem ipsum dolor sit amet. Id consectetur tempora rem ratione magnam qui voluptatem aperiam et autem quia. Sed voluptatum cupiditate aut quia ducimus qui magnam quos nam doloremque tempora.'),
(159, 'am', 'Նորություններ'),
(160, 'am', 'Կապ'),
(161, 'am', 'Մուտք'),
(162, 'am', 'Մեր վստահությունը ոսկին է'),
(162, 'en', 'In gold we trust'),
(163, 'am', 'Գրոշի ուժը'),
(164, 'am', 'Աջակցիր մեծ գործին քո ներդրումով'),
(165, 'am', 'Գործադիր տնօրեն'),
(165, 'en', 'CEO'),
(166, 'am', 'Ալբերտ Պապիկյան'),
(167, 'am', 'Լավագույն առաջարկը'),
(167, 'en', 'Best offer'),
(168, 'am', 'Ստացեք լավագույն գները ոսկի գնելու և վաճառելու համար՝ վստահությամբ ու թափանցիկությամբ'),
(169, 'am', 'Սպասում ենք ձեզ այսօր'),
(170, 'am', 'Բարի գալուստ Goldmember'),
(171, 'am', 'Աճուրդ'),
(171, 'en', 'Auction'),
(172, 'am', 'Հիմա մասնակցեք աճուրդին՝ դառնալու սեփականատեր'),
(173, 'am', 'Լավագույն առաջարկը։ Գործեք հիմա!'),
(173, 'en', 'Best deal! Act now!'),
(174, 'am', 'Օնլայն հաճախորդներ'),
(175, 'am', 'Գրանցված հաճախորդներ'),
(176, 'am', 'Այսօրվա այցելությունները'),
(177, 'am', 'Գլխավոր Նորություններ'),
(178, 'am', 'Ավելին'),
(179, 'am', 'Տեղական Գներ'),
(180, 'am', 'Համաշխարհային գներ'),
(181, 'am', 'Փոխարժեքներ'),
(182, 'am', 'Առևտուր'),
(183, 'am', 'ՀՀ, Երևան 0018, Մովսես Խորենացի փողոց 20'),
(184, 'am', 'Աշխատանքային ժամեր'),
(185, 'am', 'Մեր Մասին');

-- --------------------------------------------------------

--
-- Table structure for table `message_system`
--

CREATE TABLE `message_system` (
  `id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL COMMENT 'User who sent message',
  `recipient_user_id` int(11) NOT NULL COMMENT 'User who get message',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL COMMENT 'Is message read',
  `send_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `metals`
--

CREATE TABLE `metals` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `metals`
--

INSERT INTO `metals` (`id`, `name`, `symbol`, `created_at`, `updated_at`) VALUES
(1, 'Gold', 'XAU', '2025-03-30 16:07:33', NULL),
(2, 'Silver', 'XAG', '2025-03-30 16:08:30', NULL),
(3, 'Platinum', 'XPT', '2025-03-30 16:09:03', NULL),
(4, 'Palladium', 'XPD', '2025-03-30 16:09:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `metal_prices`
--

CREATE TABLE `metal_prices` (
  `id` int(11) NOT NULL,
  `metal_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `karat` int(11) NOT NULL,
  `original_buy_price` decimal(10,4) NOT NULL,
  `original_sell_price` decimal(10,4) NOT NULL,
  `buy_price` decimal(10,4) NOT NULL,
  `sell_price` decimal(10,4) NOT NULL,
  `rate_status` tinyint(1) DEFAULT NULL,
  `rate_price` decimal(10,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `metal_prices`
--

INSERT INTO `metal_prices` (`id`, `metal_id`, `currency_id`, `karat`, `original_buy_price`, `original_sell_price`, `buy_price`, `sell_price`, `rate_status`, `rate_price`, `created_at`, `updated_at`) VALUES
(40, 1, 1, 999, 106.3180, 106.3449, 105.9180, 105.9449, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(41, 1, 1, 995, 105.8923, 105.9191, 105.4923, 105.5191, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(42, 1, 1, 958, 101.9546, 101.9804, 101.5546, 101.5804, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(43, 1, 1, 585, 62.2583, 62.2741, 61.8583, 61.8741, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(44, 1, 1, 916, 97.4848, 97.5095, 97.0848, 97.1095, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(45, 1, 1, 900, 95.7820, 95.8063, 95.3820, 95.4063, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(46, 1, 1, 875, 93.1214, 93.1450, 92.7214, 92.7450, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(47, 1, 1, 750, 79.8183, 79.8386, 79.4183, 79.4386, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(48, 1, 1, 500, 53.2122, 53.2257, 52.8122, 52.8257, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(49, 1, 1, 417, 44.3790, 44.3902, 43.9790, 43.9902, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(50, 1, 1, 375, 39.9092, 39.9193, 39.5092, 39.5193, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(51, 1, 1, 333, 35.4393, 35.4483, 35.0393, 35.0483, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(52, 1, 1, 250, 26.6061, 26.6129, 26.2061, 26.2129, 0, -42.2400, '2025-06-07 17:06:23', '2025-06-07 17:06:23'),
(53, 1, 1, 999, 106.3180, 106.3449, 105.8180, 105.8449, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(54, 1, 1, 995, 105.8923, 105.9191, 105.3923, 105.4191, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(55, 1, 1, 958, 101.9546, 101.9804, 101.4546, 101.4804, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(56, 1, 1, 585, 62.2583, 62.2741, 61.7583, 61.7741, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(57, 1, 1, 916, 97.4848, 97.5095, 96.9848, 97.0095, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(58, 1, 1, 900, 95.7820, 95.8063, 95.2820, 95.3063, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(59, 1, 1, 875, 93.1214, 93.1450, 92.6214, 92.6450, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(60, 1, 1, 750, 79.8183, 79.8386, 79.3183, 79.3386, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(61, 1, 1, 500, 53.2122, 53.2257, 52.7122, 52.7257, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(62, 1, 1, 417, 44.3790, 44.3902, 43.8790, 43.8902, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(63, 1, 1, 375, 39.9092, 39.9193, 39.4092, 39.4193, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(64, 1, 1, 333, 35.4393, 35.4483, 34.9393, 34.9483, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(65, 1, 1, 250, 26.6061, 26.6129, 26.1061, 26.1129, 0, -42.2400, '2025-06-08 08:55:51', '2025-06-08 08:55:51'),
(66, 1, 1, 999, 106.3180, 106.3449, 105.9180, 105.9449, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(67, 1, 1, 995, 105.8923, 105.9191, 105.4923, 105.5191, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(68, 1, 1, 958, 101.9546, 101.9804, 101.5546, 101.5804, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(69, 1, 1, 585, 62.2583, 62.2741, 61.8583, 61.8741, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(70, 1, 1, 916, 97.4848, 97.5095, 97.0848, 97.1095, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(71, 1, 1, 900, 95.7820, 95.8063, 95.3820, 95.4063, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(72, 1, 1, 875, 93.1214, 93.1450, 92.7214, 92.7450, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(73, 1, 1, 750, 79.8183, 79.8386, 79.4183, 79.4386, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(74, 1, 1, 500, 53.2122, 53.2257, 52.8122, 52.8257, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(75, 1, 1, 417, 44.3790, 44.3902, 43.9790, 43.9902, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(76, 1, 1, 375, 39.9092, 39.9193, 39.5092, 39.5193, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(77, 1, 1, 333, 35.4393, 35.4483, 35.0393, 35.0483, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(78, 1, 1, 250, 26.6061, 26.6129, 26.2061, 26.2129, 0, -42.2400, '2025-08-22 15:10:59', '2025-08-22 15:10:59'),
(79, 1, 1, 999, 106.3180, 106.3449, 106.1180, 106.1449, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(80, 1, 1, 995, 105.8923, 105.9191, 105.6923, 105.7191, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(81, 1, 1, 958, 101.9546, 101.9804, 101.7546, 101.7804, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(82, 1, 1, 585, 62.2583, 62.2741, 62.0583, 62.0741, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(83, 1, 1, 916, 97.4848, 97.5095, 97.2848, 97.3095, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(84, 1, 1, 900, 95.7820, 95.8063, 95.5820, 95.6063, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(85, 1, 1, 875, 93.1214, 93.1450, 92.9214, 92.9450, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(86, 1, 1, 750, 79.8183, 79.8386, 79.6183, 79.6386, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(87, 1, 1, 500, 53.2122, 53.2257, 53.0122, 53.0257, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(88, 1, 1, 417, 44.3790, 44.3902, 44.1790, 44.1902, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(89, 1, 1, 375, 39.9092, 39.9193, 39.7092, 39.7193, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(90, 1, 1, 333, 35.4393, 35.4483, 35.2393, 35.2483, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(91, 1, 1, 250, 26.6061, 26.6129, 26.4061, 26.4129, 0, -42.2400, '2025-08-23 07:34:17', '2025-08-23 07:34:17'),
(92, 1, 1, 999, 106.3180, 106.3449, 106.3180, 106.3449, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(93, 1, 1, 995, 105.8923, 105.9191, 105.8923, 105.9191, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(94, 1, 1, 958, 101.9546, 101.9804, 101.9546, 101.9804, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(95, 1, 1, 585, 62.2583, 62.2741, 62.2583, 62.2741, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(96, 1, 1, 916, 97.4848, 97.5095, 97.4848, 97.5095, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(97, 1, 1, 900, 95.7820, 95.8063, 95.7820, 95.8063, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(98, 1, 1, 875, 93.1214, 93.1450, 93.1214, 93.1450, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(99, 1, 1, 750, 79.8183, 79.8386, 79.8183, 79.8386, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(100, 1, 1, 500, 53.2122, 53.2257, 53.2122, 53.2257, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(101, 1, 1, 417, 44.3790, 44.3902, 44.3790, 44.3902, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(102, 1, 1, 375, 39.9092, 39.9193, 39.9092, 39.9193, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(103, 1, 1, 333, 35.4393, 35.4483, 35.4393, 35.4483, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23'),
(104, 1, 1, 250, 26.6061, 26.6129, 26.6061, 26.6129, 0, -42.2400, '2025-09-02 14:31:23', '2025-09-02 14:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `metal_price_real`
--

CREATE TABLE `metal_price_real` (
  `id` int(11) UNSIGNED NOT NULL,
  `metal_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`request_data`)),
  `request_error` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`request_error`))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `metal_price_real`
--

INSERT INTO `metal_price_real` (`id`, `metal_id`, `currency_id`, `created_date`, `request_data`, `request_error`) VALUES
(3, 1, 1, '2025-06-07 14:30:05', '{\"timestamp\":1749306605,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL),
(4, 1, 1, '2025-06-07 14:35:04', '{\"timestamp\":1749306893,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL),
(5, 1, 1, '2025-06-07 14:40:05', '{\"timestamp\":1749307204,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL),
(6, 1, 1, '2025-06-07 14:45:04', '{\"timestamp\":1749307485,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL),
(7, 1, 1, '2025-06-07 14:50:05', '{\"timestamp\":1749307795,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL),
(8, 1, 1, '2025-06-07 18:00:05', '{\"timestamp\":1749319179,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL),
(9, 1, 1, '2025-06-07 22:00:07', '{\"timestamp\":1749333600,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL),
(10, 1, 1, '2025-06-08 13:00:07', '{\"timestamp\":1749333600,\"metal\":\"XAU\",\"currency\":\"USD\",\"exchange\":\"FOREXCOM\",\"symbol\":\"FOREXCOM:XAUUSD\",\"prev_close_price\":3352.83,\"open_price\":3352.83,\"low_price\":3307.21,\"high_price\":3375.68,\"open_time\":1749168000,\"price\":3310.59,\"ch\":-42.24,\"chp\":-1.26,\"ask\":3311.01,\"bid\":3310.17,\"price_gram_24k\":106.4379,\"price_gram_22k\":97.5681,\"price_gram_21k\":93.1332,\"price_gram_20k\":88.6983,\"price_gram_18k\":79.8285,\"price_gram_16k\":70.9586,\"price_gram_14k\":62.0888,\"price_gram_10k\":44.3491}', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `short_description` varchar(250) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `route_name` varchar(255) DEFAULT NULL,
  `source_url` varchar(255) DEFAULT NULL,
  `top_news` smallint(6) DEFAULT NULL,
  `latest_news` smallint(6) DEFAULT NULL,
  `is_primary_news` tinyint(1) NOT NULL DEFAULT 0,
  `resized` tinyint(4) DEFAULT NULL,
  `rate` int(11) DEFAULT 0,
  `running_line` tinyint(1) NOT NULL DEFAULT 0,
  `video_url` varchar(500) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `short_description`, `status`, `category_id`, `meta_description`, `meta_title`, `ordering`, `route_name`, `source_url`, `top_news`, `latest_news`, `is_primary_news`, `resized`, `rate`, `running_line`, `video_url`, `created_date`, `updated_date`) VALUES
(17, 'Gold Prices Rise as Investors Seek Safe Haven Amid Global Uncertainty', '<p>Gold prices climbed during the latest trading session as uncertainty in global markets pushed investors toward safer assets. Rising geopolitical risks, combined with mixed economic data from major economies, increased demand for gold as a store of value.</p>\r\n\r\n<p>Additionally, expectations that central banks may slow the pace of interest rate hikes supported gold prices, as lower real yields tend to boost non-interest-bearing assets. Analysts note that continued central bank gold purchases, especially from emerging markets, are also providing long-term support to prices.</p>\r\n\r\n<p>Market participants will closely monitor upcoming inflation data and central bank statements, which could further influence gold&rsquo;s short-term direction.&nbsp;</p>\r\n', '<p>Gold prices moved higher as investors increased safe-haven buying amid geopolitical tensions and expectations of slower global growth.</p>\r\n', 1, 18, NULL, NULL, 1, 'gold-prices-rise-as-investors-seek-safe-haven-amid-global-uncertainty', NULL, 1, 1, 0, NULL, 0, 0, '', '2025-12-14 08:29:29', '0000-00-00 00:00:00'),
(18, 'Silver Volatility Increases on Industrial Demand and Inflation Expectations', '<p>Silver experienced sharp price movements as markets reacted to shifting expectations around inflation and industrial consumption. Demand from renewable energy sectors, particularly solar panel manufacturing, continued to support silver prices.</p>\r\n\r\n<p>However, stronger-than-expected economic indicators raised concerns that interest rates could remain elevated for longer, limiting upside potential. Traders remain cautious, as silver tends to amplify movements seen in the gold market due to its dual role as both a precious and industrial metal.</p>\r\n\r\n<p>Analysts suggest that sustained industrial expansion could offset monetary tightening pressures, keeping silver prices supported in the medium term.</p>\r\n', '<p>Silver prices showed increased volatility as traders balanced industrial demand growth against persistent inflation concerns.</p>\r\n', 1, 18, NULL, NULL, 2, 'silver-volatility-increases-on-industrial-demand-and-inflation-expectations', NULL, 1, 1, 1, NULL, 0, 0, '', '2025-12-14 08:29:29', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `news_category`
--

CREATE TABLE `news_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `is_top` tinyint(1) DEFAULT 0,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `news_category`
--

INSERT INTO `news_category` (`id`, `name`, `route_name`, `description`, `short_description`, `parent_id`, `ordering`, `is_top`, `created_date`, `updated_date`) VALUES
(18, 'Metal Prices', 'metal-prices', '', '', NULL, 1, 1, '2024-11-13 18:08:04', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

CREATE TABLE `news_images` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `news_id` int(11) DEFAULT NULL,
  `default_image_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `type` smallint(6) DEFAULT NULL,
  `resized` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `news_images`
--

INSERT INTO `news_images` (`id`, `name`, `news_id`, `default_image_id`, `created_date`, `updated_date`, `type`, `resized`) VALUES
(45, '1765700581693e73e59b5f09.17963342_0.png', 17, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, NULL),
(46, '1765700582693e73e616dd11.72748538_1.png', 17, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, NULL),
(47, '1765700969693e7569317ed9.13189232_0.avif', 18, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, NULL),
(48, '1765700969693e7569c20889.32734816_1.png', 18, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, NULL),
(49, '1765700969693e7569e38239.25239590_2.png', 18, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `type` int(11) DEFAULT NULL,
  `short_description` varchar(255) NOT NULL,
  `route_name` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `position` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `ordering` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `power_of_penny`
--

CREATE TABLE `power_of_penny` (
  `id` int(11) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `video_url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `power_of_penny`
--

INSERT INTO `power_of_penny` (`id`, `content`, `video_url`, `name`, `status`) VALUES
(13, '<p>ghdfghdfgh</p>\r\n', 'dfghdfghdf', 'dfghdfgh', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `price` float DEFAULT NULL,
  `fineness` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `weight` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `material_id` int(11) UNSIGNED DEFAULT NULL,
  `gemstone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(255) NOT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `rateing` int(11) DEFAULT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `popular` tinyint(1) DEFAULT 0,
  `best_offer` tinyint(1) DEFAULT 0,
  `another_price` float DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `is_auction` tinyint(1) DEFAULT 0,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `title`, `price`, `fineness`, `weight`, `color`, `state`, `country`, `material_id`, `gemstone`, `description`, `short_description`, `category_id`, `rateing`, `product_sku`, `ordering`, `route_name`, `popular`, `best_offer`, `another_price`, `status`, `is_auction`, `created_date`, `updated_date`) VALUES
(10, 'Rings', 15555, '1555', '3', 'Red', 'Good', 'Armenia', 1, 'Diamond', '<p>ring: fineness - 583, weight - 3 gram</p>\r\n', '<h2>ring: fineness - 583, weight - 3 gram</h2>\r\n', 20, 5, '123145', 1, 'rings', 1, 0, NULL, 1, 1, '2025-05-04 12:57:03', NULL),
(11, 'Elegant 14K Gold Ring wit', 15555, 'sdfg', 'sdfg', 'sdfg', 'sdfg', 'sdfg', 1, 'sdfg', '<p>Discover timeless elegance with this beautifully handcrafted 585 (14 karat) gold ring, designed to captivate. The piece features a delicate vintage-inspired <strong>filigree pattern</strong>, blending intricate scrollwork with modern minimalism. Expertly polished for a radiant finish, the ring shines with a soft golden warmth that complements any skin tone.</p>\r\n\r\n<p>At its center lies a subtle <strong>marquise-cut cubic zirconia</strong>, nestled in an ornate openwork bezel that evokes old-world charm. The slim, tapered band ensures a comfortable fit, making this ring ideal for everyday wear or as a graceful statement piece for special occasions.</p>\r\n\r\n<p>Whether you&#39;re treating yourself or gifting someone special, this 14K gold ring is the perfect blend of tradition, craftsmanship, and contemporary style.<br />\r\n<br />\r\n&nbsp;</p>\r\n\r\n<p><strong>Key Features:</strong></p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Made from 585 (14K) solid yellow gold</p>\r\n	</li>\r\n	<li>\r\n	<p>Intricate vintage filigree detailing</p>\r\n	</li>\r\n	<li>\r\n	<p>Marquise-cut clear stone centerpiece</p>\r\n	</li>\r\n	<li>\r\n	<p>Lightweight and elegant &ndash; perfect for daily wear</p>\r\n	</li>\r\n	<li>\r\n	<p>Available in multiple sizes</p>\r\n	</li>\r\n</ul>\r\n', '<p>Discover timeless elegance with this beautifully handcrafted 585 (14 karat) gold ring, designed to captivate.</p>\r\n', 20, 22, 'dfgh', 2, '585', 1, 1, NULL, 1, 1, '2025-06-07 17:13:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products_details`
--

CREATE TABLE `products_details` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `show_front` tinyint(1) DEFAULT 0,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_filters`
--

CREATE TABLE `products_filters` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `filter_id` int(11) DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  `attribute_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_address`
--

CREATE TABLE `product_address` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_address`
--

INSERT INTO `product_address` (`id`, `product_id`, `city_id`, `state_id`, `address_id`) VALUES
(1, 1, 1, 232, 1),
(2, 2, 1, 232, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute`
--

CREATE TABLE `product_attribute` (
  `id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_fields_status`
--

CREATE TABLE `product_fields_status` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `default_image_id` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` smallint(6) DEFAULT NULL,
  `resized` tinyint(4) NOT NULL DEFAULT 0,
  `compresed` tinyint(1) DEFAULT NULL,
  `thumbCompress` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`id`, `name`, `product_id`, `folder`, `default_image_id`, `created_date`, `updated_date`, `type`, `resized`, `compresed`, `thumbCompress`) VALUES
(52, '172660159766e9d97d2d3925.01709923_0.jpg', 5, NULL, 0, '2024-09-17 19:33:17', '2024-09-17 19:33:17', 1, 1, NULL, NULL),
(53, '172660159766e9d97d8815f4.71709905_1.jpg', 5, NULL, 0, '2024-09-17 19:33:17', '2024-09-17 19:33:17', 1, 1, NULL, NULL),
(60, '174422753267f6cccc660ae6.60228101_0.png', 5, NULL, 1, '2025-04-09 19:38:52', '2025-04-09 19:38:52', 1, 1, NULL, NULL),
(61, '174456452367fbf12b2a74f7.74769133_0.jpg', 6, NULL, 1, '2025-04-13 17:15:23', '2025-04-13 17:15:23', 1, 1, NULL, NULL),
(62, '174456458767fbf16bc909b3.80789855_0.jpg', 7, NULL, 1, '2025-04-13 17:16:28', '2025-04-13 17:16:28', 1, 1, NULL, NULL),
(63, '174456464867fbf1a8591cf2.98595612_0.jpg', 8, NULL, 1, '2025-04-13 17:17:28', '2025-04-13 17:17:28', 1, 1, NULL, NULL),
(64, '174456470067fbf1dc727cd0.56296443_0.jpg', 9, NULL, 1, '2025-04-13 17:18:20', '2025-04-13 17:18:20', 1, 1, NULL, NULL),
(65, '17463634236817641f895171.21215063_0.jpg', 10, NULL, 1, '2025-05-04 12:57:04', '2025-05-04 12:57:04', 1, 1, NULL, NULL),
(66, '17463634246817642034d1d3.42314992_1.jpg', 10, NULL, 0, '2025-05-04 12:57:04', '2025-05-04 12:57:04', 1, 1, NULL, NULL),
(67, '17493163886844732410be21.88157875_0.jpg', 11, NULL, 1, '2025-06-07 17:13:08', '2025-06-07 17:13:08', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` char(40) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `last_write` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `user_id`, `last_write`, `expire`, `data`) VALUES
('0j6cn9jgv5soui1u83q9svlvj8', NULL, 1765704361, 1765705801, 0x5f5f666c6173687c613a303a7b7d),
('3pckodmj6hv512dkk6u0fg9ch6', NULL, 1776506088, 1776507528, 0x5f5f666c6173687c613a303a7b7d),
('g19kcmjub6gqvebmk5j7hj22ve', NULL, 1776572264, 1776573704, 0x5f5f666c6173687c613a303a7b7d),
('h8te06s24t21kip79sdon94hbp', 44, 1765488234, 1765489674, 0x5f5f666c6173687c613a303a7b7d5f5f69647c693a34343b5f5f617574684b65797c733a33323a2264776c465455324f4163336d51397134685a756b45767846517933645f5a744b223b),
('niqtgmq7nitd4jd9aah2iu2r5c', 43, 1765486154, 1765487594, 0x5f5f666c6173687c613a303a7b7d5f5f69647c693a34333b5f5f617574684b65797c733a33323a2256645f4f68443242722d5768776a32767a735f31375f654e4353596441346c4f223b),
('v30aqevoc1pi0nuvth8c9tdbje', NULL, 1776573316, 1776574756, 0x5f5f666c6173687c613a303a7b7d);

-- --------------------------------------------------------

--
-- Table structure for table `sitesettings`
--

CREATE TABLE `sitesettings` (
  `id` int(11) NOT NULL,
  `meta_tag` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `site_title` varchar(255) NOT NULL,
  `site_email` varchar(255) NOT NULL,
  `work_time` varchar(255) NOT NULL,
  `site_phone` varchar(255) NOT NULL,
  `text1` text NOT NULL,
  `text2` text NOT NULL,
  `text3` text NOT NULL,
  `text4` text NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `maintenance_mode` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `sitesettings`
--

INSERT INTO `sitesettings` (`id`, `meta_tag`, `meta_description`, `site_title`, `site_email`, `work_time`, `site_phone`, `text1`, `text2`, `text3`, `text4`, `address`, `maintenance_mode`) VALUES
(1, 'Goldmember', 'Goldmember', 'Goldmember.am', 'info@goldmember.am', 'пн-вс 10:00 -19:00', '(+374) 94261606, (+374) 43261605', 'TEXT1', 'TEXT2', '', '', 'ԵՐԵՎԱՆ , Խորենացի 24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `title` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`title`, `short_description`, `description`, `link`, `status`, `id`, `path`) VALUES
('', NULL, NULL, 'dfghdfgh', 1, 13, '174465613867fd570a981860.96443390_0.jpg'),
('', NULL, NULL, '', 1, 14, '17450565226803730a6e6364.36017550_0.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `social_net`
--

CREATE TABLE `social_net` (
  `id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `social_type` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `social_net`
--

INSERT INTO `social_net` (`id`, `link`, `social_type`, `active`) VALUES
(1, 'https://www.facebook.com/goldmember.am', 'facebook', 1),
(2, 'https://www.tiktok.com/@goldmember.am', 'tiktok', 1),
(3, 'https://www.youtube.com/@albertpapikyan305', 'youtube', 1),
(5, 'https://www.instagram.com/goldmember.am', 'instagram', 1),
(6, 'https://invite.viber.com/?g2=AQAezp2viSwqSE3CKztVv5YMfDSZxp%2FUslglE6K%2Bq8E3fMyuvPn6dFx3JvJ5Ofje&lang=ru', 'whatsapp', 1);

-- --------------------------------------------------------

--
-- Table structure for table `source_message`
--

CREATE TABLE `source_message` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` varchar(255) DEFAULT 'app',
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `source_message`
--

INSERT INTO `source_message` (`id`, `category`, `message`) VALUES
(1, 'app', 'Short Description'),
(2, 'app', 'Description'),
(3, 'app', 'Update'),
(4, 'app', 'Categories'),
(5, 'app', 'Edit'),
(6, 'app', 'Delete'),
(7, 'app', 'Create Category'),
(8, 'app', 'Name'),
(9, 'app', 'Reset'),
(10, 'app', 'Series'),
(11, 'app', 'Attributes'),
(12, 'app', 'Create Attribute'),
(13, 'app', 'Image'),
(14, 'app', 'Category'),
(15, 'app', 'Attribute Name'),
(16, 'app', 'Select Category'),
(17, 'app', 'Add New Attribute'),
(18, 'app', 'Create'),
(19, 'app', 'Brands'),
(20, 'app', 'BrandsAdmin'),
(21, 'app', 'Create Brand'),
(22, 'app', 'Back to brand list'),
(23, 'app', 'Brand Name'),
(24, 'app', 'Active'),
(25, 'app', 'Pasive'),
(26, 'app', 'Select Categories'),
(27, 'app', 'Brand successfully created'),
(28, 'app', 'General'),
(29, 'app', 'Products'),
(30, 'app', 'Create Product'),
(31, 'app', 'Back to list'),
(32, 'app', 'Product Name'),
(33, 'app', 'Product SKU'),
(34, 'app', 'Route Name'),
(35, 'app', 'Price'),
(36, 'app', 'Static Pages'),
(37, 'app', 'Orders'),
(38, 'app', 'Product Management'),
(39, 'app', 'Customers'),
(40, 'app', 'Week Product'),
(41, 'app', 'Sliders'),
(42, 'app', 'New Product'),
(43, 'app', 'Popular'),
(44, 'app', 'Sell'),
(45, 'app', 'Discount'),
(46, 'app', 'Sale'),
(47, 'app', 'Pages'),
(48, 'app', 'Create New Page'),
(49, 'app', 'Title'),
(50, 'app', 'Status'),
(51, 'app', 'Your Message Here'),
(52, 'app', 'Contact'),
(53, 'app', 'Dashboard'),
(54, 'app', 'Change password'),
(55, 'app', 'Settings'),
(56, 'app', 'Change login'),
(57, 'app', 'Username'),
(58, 'app', 'Work Time'),
(59, 'app', 'Site Title'),
(60, 'app', 'Site Email'),
(61, 'app', 'Site Phone'),
(62, 'app', 'Delivery Manager Phone'),
(63, 'app', 'Delivery Comment'),
(64, 'app', 'Create Social'),
(65, 'app', 'Link'),
(66, 'app', 'Social Type'),
(67, 'app', 'Create Slider'),
(68, 'app', 'View shopping cart'),
(69, 'app', 'Proceed to Checkout'),
(70, 'app', 'Basket'),
(71, 'app', 'View'),
(72, 'app', 'Customer'),
(73, 'app', 'Address'),
(74, 'app', 'Phone'),
(75, 'app', 'Detail Name'),
(76, 'app', 'Detail Value'),
(77, 'app', 'Connected Products'),
(78, 'app', 'Create Social Net'),
(79, 'app', 'Select Social Type'),
(80, 'app', 'Current Password'),
(81, 'app', 'New Password'),
(82, 'app', 'Repeat Password'),
(83, 'app', 'Slider successfully created'),
(84, 'app', 'Select Parent'),
(85, 'app', 'Opened'),
(86, 'app', 'Create Serie'),
(87, 'app', 'Select Brand'),
(88, 'app', 'Select Seria'),
(89, 'app', 'Search'),
(90, 'app', 'asf'),
(91, 'app', 'Filters'),
(92, 'app', 'Brokers'),
(93, 'app', 'About Us'),
(94, 'app', 'States'),
(95, 'app', 'Places'),
(96, 'app', 'Addresses'),
(97, 'app', 'Cities'),
(98, 'app', 'Rent'),
(99, 'app', 'City'),
(100, 'app', 'Select State'),
(101, 'app', 'Select City'),
(102, 'app', 'Select Address'),
(103, 'app', 'Home'),
(104, 'app', 'Not Found'),
(105, 'app', 'More'),
(106, 'app', 'From'),
(107, 'app', 'To'),
(108, 'app', 'Brokers Address'),
(109, 'app', 'Broker Controll'),
(110, 'app', 'Users'),
(111, 'app', 'Create User'),
(112, 'app', 'User Number'),
(113, 'app', 'Product Filters'),
(114, 'app', 'Successfully Saved'),
(115, 'app', 'Entry Exist'),
(116, 'app', 'Address Part 1'),
(117, 'app', 'Address Part 2'),
(118, 'app', 'Save Address'),
(120, 'app', 'Please fill address'),
(121, 'app', 'Phone Number'),
(122, 'app', 'Email'),
(123, 'app', 'Password'),
(124, 'app', 'Unavailable'),
(125, 'app', 'Sales'),
(126, 'app', 'Select Broker'),
(127, 'app', 'Client Name'),
(128, 'app', 'Client Phone'),
(129, 'app', 'Road'),
(130, 'app', 'State'),
(131, 'app', 'Select options'),
(132, 'app', 'Select Status'),
(133, 'app', 'Type'),
(134, 'app', 'Back to Products'),
(135, 'app', 'Allow to show on Home page'),
(136, 'app', 'For Sale'),
(137, 'app', 'TOP OFFERS'),
(138, 'app', 'Add Team Member'),
(139, 'app', 'Teams'),
(140, 'app', 'Fname'),
(141, 'app', 'Sname'),
(142, 'app', 'Create Team'),
(143, 'app', 'Profession'),
(144, 'app', 'Save'),
(145, 'app', 'Our Team'),
(146, 'app', 'See Product on First Page'),
(147, 'app', 'All Products'),
(148, 'app', 'Sale Products'),
(149, 'app', 'For Rent Products'),
(150, 'app', 'Land area'),
(151, 'app', 'Other Information'),
(152, 'app', 'Create Filter'),
(153, 'app', 'LATEST CARS'),
(154, 'app', 'READ MORE'),
(157, 'app', 'Register'),
(158, 'app', 'Footer About Text'),
(159, 'app', 'News'),
(160, 'app', 'Contact Us'),
(161, 'app', 'Login'),
(162, 'app', 'In gold we trust'),
(163, 'app', 'Power of penny'),
(164, 'app', 'Support us with donation'),
(165, 'app', 'CEO'),
(166, 'app', 'Albert Papikyan'),
(167, 'app', 'Best Offer'),
(168, 'app', 'Get the best prices for buying and selling gold with trust and transparency'),
(169, 'app', 'Visit us today'),
(170, 'app', 'Welcome to Goldmember'),
(171, 'app', 'Auction'),
(172, 'app', 'Bid now for a chance to own'),
(173, 'app', 'Best deal! Act now!'),
(174, 'app', 'ONLINE CLIENTS'),
(175, 'app', 'Registered Clients'),
(176, 'app', 'TODAYS VISITS'),
(177, 'app', 'TOP NEWS'),
(178, 'app', 'Show more'),
(179, 'app', 'Local Prices'),
(180, 'app', 'Global Prices'),
(181, 'app', 'Exchange Rates'),
(182, 'app', 'Trade'),
(183, 'app', '20 Movses Khorenatsi Street, Yerevan 0018'),
(184, 'app', 'Hours open'),
(185, 'app', 'About');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `country_id`) VALUES
(232, 'Երևան', 11);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(10) UNSIGNED NOT NULL,
  `fname` varchar(255) NOT NULL,
  `sname` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_director` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `fname`, `sname`, `email`, `phone`, `image`, `profession`, `description`, `is_director`) VALUES
(6, 'Albert', 'Papikyan', '95532albert@gmail.com', '+37494261606', '1745056932680374a419ec22.58565339_0.jpg', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tr_aboutus`
--

CREATE TABLE `tr_aboutus` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `aboutus_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `tr_aboutus`
--

INSERT INTO `tr_aboutus` (`id`, `title`, `short_description`, `description`, `aboutus_id`, `language_id`) VALUES
(1, 'Gold Member', 'Congratulations, you finally found the top wholesale platform of gold, golden jewelry and diamonds in region.', 'Congratulations, you finally found the top wholesale platform of gold, golden jewelry and diamonds in region.', 1, 2),
(2, 'Goldmember', '<p>Շնորհավորում եմ քեզ, դու վերջապես գտար ոսկյա զարդերի և ադամանդների առք և վաճառք առաջատար հարթակը տարածաշրջանում</p>\r\n', '<p>Congratulations, you finally found the top wholesale platform of gold, golden jewelry and diamonds in region.</p>\r\n', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tr_attribute`
--

CREATE TABLE `tr_attribute` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `attribute_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tr_attribute`
--

INSERT INTO `tr_attribute` (`id`, `name`, `language_id`, `attribute_id`) VALUES
(2, 'Մակնիշը', 2, 2),
(3, 'Մոդելը', 2, 3),
(4, 'Ղեկը', 2, 4),
(5, 'Շարժիչը', 2, 5),
(6, 'sfgsdfg', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tr_category`
--

CREATE TABLE `tr_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `short_description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tr_category`
--

INSERT INTO `tr_category` (`id`, `name`, `description`, `short_description`, `route_name`, `category_id`, `language_id`) VALUES
(37, 'Rings', NULL, NULL, NULL, 20, 2),
(38, 'Մատանիներ', NULL, NULL, NULL, 20, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tr_homepage`
--

CREATE TABLE `tr_homepage` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `homepage_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tr_homepage`
--

INSERT INTO `tr_homepage` (`id`, `title`, `description`, `language_id`, `homepage_id`) VALUES
(1, 'ghjkgjkg', 'fasdfasfasdfasdf111', 3, 1),
(2, 'dfghdfgh', 'sdfghdfghdfgh2222', 4, 1),
(3, 'Vin-Radar find a car by VIN code', 'You can easily find a car by putting VIN number', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tr_material`
--

CREATE TABLE `tr_material` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `material_id` int(11) UNSIGNED DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `tr_material`
--

INSERT INTO `tr_material` (`id`, `name`, `short_description`, `description`, `material_id`, `language_id`) VALUES
(3, 'Briliant', 'asdfasdf', NULL, 5, 2),
(4, 'Gold', 'dfgdfg', NULL, 1, 2),
(5, 'Silver', 'sdfgsdfg', NULL, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tr_news`
--

CREATE TABLE `tr_news` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `news_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `tr_news`
--

INSERT INTO `tr_news` (`id`, `title`, `short_description`, `content`, `news_id`, `language_id`) VALUES
(39, 'Gold Prices Rise as Investors Seek Safe Haven Amid Global Uncertainty', '<p>Gold prices moved higher as investors increased safe-haven buying amid geopolitical tensions and expectations of slower global growth.</p>\r\n', '<p>Gold prices climbed during the latest trading session as uncertainty in global markets pushed investors toward safer assets. Rising geopolitical risks, combined with mixed economic data from major economies, increased demand for gold as a store of value.</p>\r\n\r\n<p>Additionally, expectations that central banks may slow the pace of interest rate hikes supported gold prices, as lower real yields tend to boost non-interest-bearing assets. Analysts note that continued central bank gold purchases, especially from emerging markets, are also providing long-term support to prices.</p>\r\n\r\n<p>Market participants will closely monitor upcoming inflation data and central bank statements, which could further influence gold&rsquo;s short-term direction.&nbsp;</p>\r\n', 17, 2),
(40, 'Gold Prices Rise as Investors Seek Safe Haven Amid Global Uncertainty', '<p>Gold prices moved higher as investors increased safe-haven buying amid geopolitical tensions and expectations of slower global growth.</p>\r\n', '<p>Gold prices climbed during the latest trading session as uncertainty in global markets pushed investors toward safer assets. Rising geopolitical risks, combined with mixed economic data from major economies, increased demand for gold as a store of value.</p>\r\n\r\n<p>Additionally, expectations that central banks may slow the pace of interest rate hikes supported gold prices, as lower real yields tend to boost non-interest-bearing assets. Analysts note that continued central bank gold purchases, especially from emerging markets, are also providing long-term support to prices.</p>\r\n\r\n<p>Market participants will closely monitor upcoming inflation data and central bank statements, which could further influence gold&rsquo;s short-term direction.&nbsp;</p>\r\n', 17, 3),
(41, 'Silver Volatility Increases on Industrial Demand and Inflation Expectations', '<p>Silver prices showed increased volatility as traders balanced industrial demand growth against persistent inflation concerns.</p>\r\n', '<p>Silver experienced sharp price movements as markets reacted to shifting expectations around inflation and industrial consumption. Demand from renewable energy sectors, particularly solar panel manufacturing, continued to support silver prices.</p>\r\n\r\n<p>However, stronger-than-expected economic indicators raised concerns that interest rates could remain elevated for longer, limiting upside potential. Traders remain cautious, as silver tends to amplify movements seen in the gold market due to its dual role as both a precious and industrial metal.</p>\r\n\r\n<p>Analysts suggest that sustained industrial expansion could offset monetary tightening pressures, keeping silver prices supported in the medium term.</p>\r\n', 18, 2),
(42, 'Silver Volatility Increases on Industrial Demand and Inflation Expectations', '<p>Silver prices showed increased volatility as traders balanced industrial demand growth against persistent inflation concerns.</p>\r\n', '<p>Silver experienced sharp price movements as markets reacted to shifting expectations around inflation and industrial consumption. Demand from renewable energy sectors, particularly solar panel manufacturing, continued to support silver prices.</p>\r\n\r\n<p>However, stronger-than-expected economic indicators raised concerns that interest rates could remain elevated for longer, limiting upside potential. Traders remain cautious, as silver tends to amplify movements seen in the gold market due to its dual role as both a precious and industrial metal.</p>\r\n\r\n<p>Analysts suggest that sustained industrial expansion could offset monetary tightening pressures, keeping silver prices supported in the medium term.</p>\r\n', 18, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tr_news_category`
--

CREATE TABLE `tr_news_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_pages`
--

CREATE TABLE `tr_pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `pages_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `short_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_power_of_penny`
--

CREATE TABLE `tr_power_of_penny` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `power_of_penny_id` int(11) UNSIGNED NOT NULL,
  `language_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tr_power_of_penny`
--

INSERT INTO `tr_power_of_penny` (`id`, `name`, `content`, `power_of_penny_id`, `language_id`) VALUES
(15, 'fhjfghfghfgh', '<p>ghjfghjfgh</p>\r\n', 10, 2),
(16, 'fhjfghfghfgh', '<p>ghjfghjfgh</p>\r\n', 10, 3),
(17, 'fhjfghfghfgh', '<p>ghjfghjfgh</p>\r\n', 11, 2),
(18, 'fhjfghfghfgh', '<p>ghjfghjfgh</p>\r\n', 11, 3),
(19, 'fhjfghfghfgh', '<p>ghjfghjfgh</p>\r\n', 12, 2),
(20, 'fhjfghfghfgh', '<p>ghjfghjfgh</p>\r\n', 12, 3),
(21, 'dfghdfgh', '<p>ghdfghdfgh</p>\r\n', 13, 2),
(22, 'dfghdfgh', '<p>ghdfghdfgh</p>\r\n', 13, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tr_product`
--

CREATE TABLE `tr_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `short_description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tr_product`
--

INSERT INTO `tr_product` (`id`, `title`, `short_description`, `description`, `product_id`, `language_id`) VALUES
(13, 'sdfsfdgsf sdfgsdfgsfd', '<p>sdfgsdfg</p>\r\n', '<p>sdfgsdfgsdfg</p>\r\n', 4, 2),
(14, 'fghjfgh', '<p>dfghdfghdfg</p>\r\n', '<p>dfghdfgh</p>\r\n', 5, 2),
(15, 'Кольцо из золота 585', '<p>dfghdfghf</p>\r\n', '<p>dghfdghfgh</p>\r\n', 6, 2),
(16, 'Briliant asdasdasd', '<p>fgsdfgsdfg</p>\r\n', '<p>sdfgsdfgsdf</p>\r\n', 7, 2),
(17, 'Lav zarddddd', '<p>gjghjghjghj</p>\r\n', '<p>ghjghjgjghjgh</p>\r\n', 8, 2),
(18, 'cfrcfr', '<p>edxe4xdexde</p>\r\n', '<p>exedxedxedx</p>\r\n', 9, 2),
(19, 'cfrcfr', '<p>edxe4xdexde</p>\r\n', '<p>exedxedxedx</p>\r\n', 9, 3),
(20, 'fghjfgh', '<p>dfghdfghdfg</p>\r\n', '<p>dfghdfgh</p>\r\n', NULL, NULL),
(21, 'fghjfgh', '<p>dfghdfghdfg</p>\r\n', '<p>dfghdfgh</p>\r\n', NULL, NULL),
(22, 'Кольцо из золота 585', '<p>dfghdfghf</p>\r\n', '<p>dghfdghfgh</p>\r\n', NULL, NULL),
(23, 'Briliant asdasdasd', '<p>fgsdfgsdfg</p>\r\n', '<p>sdfgsdfgsdf</p>\r\n', NULL, NULL),
(24, 'Lav zarddddd', '<p>gjghjghjghj</p>\r\n', '<p>ghjghjgjghjgh</p>\r\n', NULL, NULL),
(25, 'Rings US', 'Rings US', '\r\nRings US', 10, 2),
(26, 'Rings', '<h2>ring: fineness - 583, weight - 3 gram</h2>\r\n', '<p>ring: fineness - 583, weight - 3 gram</p>\r\n', 10, 3),
(27, 'Elegant 14K Gold Ring wit', '<p>Discover timeless elegance with this beautifully handcrafted 585 (14 karat) gold ring, designed to captivate.</p>\r\n', '<p>Discover timeless elegance with this beautifully handcrafted 585 (14 karat) gold ring, designed to captivate. The piece features a delicate vintage-inspired <strong>filigree pattern</strong>, blending intricate scrollwork with modern minimalism. Expertly polished for a radiant finish, the ring shines with a soft golden warmth that complements any skin tone.</p>\r\n\r\n<p>At its center lies a subtle <strong>marquise-cut cubic zirconia</strong>, nestled in an ornate openwork bezel that evokes old-world charm. The slim, tapered band ensures a comfortable fit, making this ring ideal for everyday wear or as a graceful statement piece for special occasions.</p>\r\n\r\n<p>Whether you&#39;re treating yourself or gifting someone special, this 14K gold ring is the perfect blend of tradition, craftsmanship, and contemporary style.<br />\r\n<br />\r\n&nbsp;</p>\r\n\r\n<p><strong>Key Features:</strong></p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Made from 585 (14K) solid yellow gold</p>\r\n	</li>\r\n	<li>\r\n	<p>Intricate vintage filigree detailing</p>\r\n	</li>\r\n	<li>\r\n	<p>Marquise-cut clear stone centerpiece</p>\r\n	</li>\r\n	<li>\r\n	<p>Lightweight and elegant &ndash; perfect for daily wear</p>\r\n	</li>\r\n	<li>\r\n	<p>Available in multiple sizes</p>\r\n	</li>\r\n</ul>\r\n', 11, 2),
(28, 'Elegant 14K Gold Ring wit', '<p>Discover timeless elegance with this beautifully handcrafted 585 (14 karat) gold ring, designed to captivate.</p>\r\n', '<p>Discover timeless elegance with this beautifully handcrafted 585 (14 karat) gold ring, designed to captivate. The piece features a delicate vintage-inspired <strong>filigree pattern</strong>, blending intricate scrollwork with modern minimalism. Expertly polished for a radiant finish, the ring shines with a soft golden warmth that complements any skin tone.</p>\r\n\r\n<p>At its center lies a subtle <strong>marquise-cut cubic zirconia</strong>, nestled in an ornate openwork bezel that evokes old-world charm. The slim, tapered band ensures a comfortable fit, making this ring ideal for everyday wear or as a graceful statement piece for special occasions.</p>\r\n\r\n<p>Whether you&#39;re treating yourself or gifting someone special, this 14K gold ring is the perfect blend of tradition, craftsmanship, and contemporary style.<br />\r\n<br />\r\n&nbsp;</p>\r\n\r\n<p><strong>Key Features:</strong></p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Made from 585 (14K) solid yellow gold</p>\r\n	</li>\r\n	<li>\r\n	<p>Intricate vintage filigree detailing</p>\r\n	</li>\r\n	<li>\r\n	<p>Marquise-cut clear stone centerpiece</p>\r\n	</li>\r\n	<li>\r\n	<p>Lightweight and elegant &ndash; perfect for daily wear</p>\r\n	</li>\r\n	<li>\r\n	<p>Available in multiple sizes</p>\r\n	</li>\r\n</ul>\r\n', 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tr_sitesettings`
--

CREATE TABLE `tr_sitesettings` (
  `id` int(10) UNSIGNED NOT NULL,
  `logoText` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL,
  `settings_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `password_token` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `social_type` varchar(255) DEFAULT NULL,
  `social_id` varchar(255) DEFAULT NULL,
  `user_number` int(11) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `allow_create` tinyint(1) NOT NULL DEFAULT 0,
  `ordering` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password`, `role`, `password_token`, `created`, `updated`, `api_key`, `email`, `social_type`, `social_id`, `user_number`, `phone_number`, `allow_create`, `ordering`) VALUES
(18, 'harut', '', '$2y$13$QnceK39/hBdOSyAIhn5QMu9.EAqfSpHJE7wZKn8t6.rjZqtBNX9d.', 0, NULL, '2016-07-26 00:00:00', '2024-10-19 05:09:17', NULL, 'test@test.com', '', '', NULL, NULL, 0, 5),
(34, 'test', 'SEWcesvYtOyzeLISktrhnhZF6q0iflxd', '$2y$13$ZVu2LukFH1Bu748VM1sog.8oV2PDMQGLTB1/E2kYdh7UUH2LBHW0.', 20, NULL, '2025-05-18 09:48:20', '2025-05-18 09:48:20', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(35, 'test2025', '6aD7--Vg8COpm_AnsX_tRaJoHybIYaew', '$2y$13$7XJCyfDneF9DAlE486ddROSFI1gAqeDOD/1e84hU4WDcY78tf3zrS', 20, NULL, '2025-05-25 03:40:52', '2025-05-25 03:40:52', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(36, 'albert2025', 'oLF7cpWulfJi3GbSh2i8EAgjXJNjJSNL', '$2y$13$5V1CH5WCb22hyLLqPkZSfe8XKVAsUT5KhaTM5LdW9AGT3CaJeotDe', 20, NULL, '2025-05-25 03:41:38', '2025-05-25 03:41:38', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(37, 'sdfsdf', 'lqncYz5rFXamhenj6KLkXacg9CZtiIi2', '$2y$13$Voz.E2N0gKkRq1gyEcvBCeVTf8itT.8HK3MlDc/UbjUNfBhdTHElW', 20, NULL, '2025-05-25 04:12:02', '2025-05-25 04:12:02', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(38, 'username', 'zg7w1jUlyzcZkTnY88_rcv-Bzg0dDhb2', '$2y$13$DVe7Hz.upfmEB31fRP1tMu5GJ9CbGG/Z0xCeY6rOM5FpoCQm13EdS', 20, NULL, '2025-05-25 04:13:33', '2025-05-25 04:13:33', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(39, 'albertusername', 'Lt0UvD0KAW7yp_0OqUYvjA6922guLOU9', '$2y$13$2mLrZ5ic0bqZaBM1p3ZWAebr1UgMzVgpaqp0BiGG1.8LUCjOKVYaO', 20, NULL, '2025-05-25 08:04:43', '2025-05-25 08:04:43', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(40, 'harut2025', 'JqoW9HP2xQI2jI8GvU3G3-MKFTy0HaVH', '$2y$13$FG/4s2PoCHfzalOdXfHUfuFn2siIZhs8m8rtFq3yFIeFAARD9U1PG', 20, NULL, '2025-09-08 04:47:46', '2025-09-08 04:47:46', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(41, 'Серго', 'cMYRzZ2ouozkVAwxmbloHLVKuVj0mGJV', '$2y$13$OYZeX0sK8hqF.gkqNpF2FeLnZS7OVBuxSrhnI8qBDIBTwyxNEm4au', 20, NULL, '2025-10-08 11:26:57', '2025-10-08 11:26:57', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(42, 'harut1991', '_5kHY3Xz115qbYwh2CWU5kyCizImeNyR', '$2y$13$QnceK39/hBdOSyAIhn5QMu9.EAqfSpHJE7wZKn8t6.rjZqtBNX9d.', 20, NULL, '2025-12-11 15:03:10', '2025-12-11 15:03:10', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(43, 'user1991', 'Vd_OhD2Br-Whwj2vzs_17_eNCSYdA4lO', '$2y$13$PqtnXC4BLeEOL6Q2SC2qKOKQyn.NRVcPDUfdIJ44E95cJpTpqVl1G', 20, NULL, '2025-12-11 15:11:26', '2025-12-11 15:11:26', NULL, '', NULL, NULL, NULL, NULL, 0, NULL),
(44, 'user2001', 'dwlFTU2OAc3mQ9q4hZukEvxFQy3d_ZtK', '$2y$13$UJ7izcxmDR0KWwSXcD7pJuxshtQWiPayYiHiCmEDuQXCcT/dJsOIe', 20, NULL, '2025-12-11 15:54:57', '2025-12-11 15:54:57', NULL, '', NULL, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE `user_images` (
  `id` int(11) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aboutus`
--
ALTER TABLE `aboutus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `address_attr`
--
ALTER TABLE `address_attr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute`
--
ALTER TABLE `attribute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_category`
--
ALTER TABLE `attribute_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `auction`
--
ALTER TABLE `auction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_3` (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connected_products`
--
ALTER TABLE `connected_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `donate`
--
ALTER TABLE `donate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donation_info`
--
ALTER TABLE `donation_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exchange`
--
ALTER TABLE `exchange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homepage`
--
ALTER TABLE `homepage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`,`language`),
  ADD KEY `idx_message_language` (`language`);

--
-- Indexes for table `metals`
--
ALTER TABLE `metals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `symbol` (`symbol`);

--
-- Indexes for table `metal_prices`
--
ALTER TABLE `metal_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_price` (`metal_id`,`currency_id`,`karat`,`created_at`) USING BTREE,
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `idx_created_at_15e0c0` (`created_at`);

--
-- Indexes for table `metal_price_real`
--
ALTER TABLE `metal_price_real`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metal_id` (`metal_id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `route_name` (`route_name`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `news_category`
--
ALTER TABLE `news_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pr_img_prod_id` (`news_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type_10f5b7` (`type`),
  ADD KEY `idx_ordering_4c2188` (`ordering`),
  ADD KEY `idx_position_66ef5d` (`position`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `power_of_penny`
--
ALTER TABLE `power_of_penny`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `material_id` (`material_id`),
  ADD KEY `category_id_2` (`category_id`),
  ADD KEY `idx_status_13d307` (`status`),
  ADD KEY `idx_best_offer_b4c747` (`best_offer`);

--
-- Indexes for table `products_details`
--
ALTER TABLE `products_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_filters`
--
ALTER TABLE `products_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_address`
--
ALTER TABLE `product_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `fk_product_attr_product_id` (`product_id`);

--
-- Indexes for table `product_fields_status`
--
ALTER TABLE `product_fields_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pr_img_prod_id` (`product_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sitesettings`
--
ALTER TABLE `sitesettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_net`
--
ALTER TABLE `social_net`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `source_message`
--
ALTER TABLE `source_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_aboutus`
--
ALTER TABLE `tr_aboutus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_attribute`
--
ALTER TABLE `tr_attribute`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attr_attribute_id` (`attribute_id`),
  ADD KEY `fk_attr_language_id` (`language_id`);

--
-- Indexes for table `tr_category`
--
ALTER TABLE `tr_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cat_category_id` (`category_id`),
  ADD KEY `fk_cat_language_id` (`language_id`);

--
-- Indexes for table `tr_homepage`
--
ALTER TABLE `tr_homepage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_material`
--
ALTER TABLE `tr_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lan_language_id` (`language_id`),
  ADD KEY `fk_pr_product_id` (`material_id`);

--
-- Indexes for table `tr_news`
--
ALTER TABLE `tr_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lan_language_id` (`language_id`),
  ADD KEY `fk_pr_product_id` (`news_id`);

--
-- Indexes for table `tr_news_category`
--
ALTER TABLE `tr_news_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cat_category_id` (`category_id`),
  ADD KEY `fk_cat_language_id` (`language_id`);

--
-- Indexes for table `tr_pages`
--
ALTER TABLE `tr_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_page_pages_id` (`pages_id`),
  ADD KEY `fk_page_language_id` (`language_id`);

--
-- Indexes for table `tr_power_of_penny`
--
ALTER TABLE `tr_power_of_penny`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_product`
--
ALTER TABLE `tr_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lan_language_id` (`language_id`),
  ADD KEY `fk_pr_product_id` (`product_id`);

--
-- Indexes for table `tr_sitesettings`
--
ALTER TABLE `tr_sitesettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_images`
--
ALTER TABLE `user_images`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aboutus`
--
ALTER TABLE `aboutus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `address_attr`
--
ALTER TABLE `address_attr`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attribute`
--
ALTER TABLE `attribute`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attribute_category`
--
ALTER TABLE `attribute_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `auction`
--
ALTER TABLE `auction`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `connected_products`
--
ALTER TABLE `connected_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donate`
--
ALTER TABLE `donate`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donation_info`
--
ALTER TABLE `donation_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `exchange`
--
ALTER TABLE `exchange`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `homepage`
--
ALTER TABLE `homepage`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `metals`
--
ALTER TABLE `metals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `metal_prices`
--
ALTER TABLE `metal_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `metal_price_real`
--
ALTER TABLE `metal_price_real`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `news_category`
--
ALTER TABLE `news_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `power_of_penny`
--
ALTER TABLE `power_of_penny`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products_details`
--
ALTER TABLE `products_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_filters`
--
ALTER TABLE `products_filters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_address`
--
ALTER TABLE `product_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_attribute`
--
ALTER TABLE `product_attribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_fields_status`
--
ALTER TABLE `product_fields_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `sitesettings`
--
ALTER TABLE `sitesettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `social_net`
--
ALTER TABLE `social_net`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `source_message`
--
ALTER TABLE `source_message`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tr_aboutus`
--
ALTER TABLE `tr_aboutus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tr_attribute`
--
ALTER TABLE `tr_attribute`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tr_category`
--
ALTER TABLE `tr_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tr_homepage`
--
ALTER TABLE `tr_homepage`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tr_material`
--
ALTER TABLE `tr_material`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tr_news`
--
ALTER TABLE `tr_news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tr_news_category`
--
ALTER TABLE `tr_news_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tr_pages`
--
ALTER TABLE `tr_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_power_of_penny`
--
ALTER TABLE `tr_power_of_penny`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tr_product`
--
ALTER TABLE `tr_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tr_sitesettings`
--
ALTER TABLE `tr_sitesettings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auction`
--
ALTER TABLE `auction`
  ADD CONSTRAINT `auction_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auction_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD CONSTRAINT `exchange_rates_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`);

--
-- Constraints for table `metal_prices`
--
ALTER TABLE `metal_prices`
  ADD CONSTRAINT `metal_prices_ibfk_1` FOREIGN KEY (`metal_id`) REFERENCES `metals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `metal_prices_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `metal_price_real`
--
ALTER TABLE `metal_price_real`
  ADD CONSTRAINT `metal_price_real_ibfk_1` FOREIGN KEY (`metal_id`) REFERENCES `metals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `metal_price_real_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news_images`
--
ALTER TABLE `news_images`
  ADD CONSTRAINT `news_images_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_material`
--
ALTER TABLE `tr_material`
  ADD CONSTRAINT `tr_material_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_news_category`
--
ALTER TABLE `tr_news_category`
  ADD CONSTRAINT `tr_news_category_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `news_category` (`id`);

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_images`
--
ALTER TABLE `user_images`
  ADD CONSTRAINT `user_images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
