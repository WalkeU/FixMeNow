-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 07:45 PM
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
-- Database: `fixmenow`
--
CREATE DATABASE IF NOT EXISTS `fixmenow` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fixmenow`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `report_id`, `user_id`, `comment_text`, `comment_date`) VALUES
(78, 480, 2, 'na ezt nem akarom megcsinalni pls', '2025-02-18 12:35:28');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`) VALUES
(1, 'Tanár'),
(2, 'Rendszergazda'),
(3, 'Műszaki vezető');

-- --------------------------------------------------------

--
-- Table structure for table `helyszinek`
--

CREATE TABLE `helyszinek` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `helyszinek`
--

INSERT INTO `helyszinek` (`id`, `name`) VALUES
(1, '1. terem'),
(2, '\"B\" épület 1. emeleti tanári'),
(3, '\"B\" épület 2. emeleti tanári'),
(4, '002 - Klub 1'),
(5, '004 - Klub 2'),
(6, '011 - Étkező'),
(7, '022'),
(8, '023'),
(9, '024'),
(10, '025'),
(11, '026 - III. tárgyaló'),
(12, '1/2 - Igazgatóhelyettesi iroda'),
(13, '1/3 - Igazgatóhelyettesi iroda'),
(14, '1/4 - Igazgatóhelyettesi iroda'),
(15, '10 - humán tanári'),
(16, '101 - informatika tanári'),
(17, '102'),
(18, '103'),
(19, '104'),
(20, '105 - matematika tanári'),
(21, '106'),
(22, '107 - fizika tanári'),
(23, '108'),
(24, '11 - humán tanári'),
(25, '110'),
(26, '112'),
(27, '113 - tanári szoba'),
(28, '114'),
(29, '115'),
(30, '116'),
(31, '117'),
(32, '118 - Könyvtár'),
(33, '119-1 Lakatos műhely 1'),
(34, '119-2 Lakatos műhely 2'),
(35, '12'),
(36, '121'),
(37, '13 - humán tanári'),
(38, '14'),
(39, '15'),
(40, '18 - Gyakorlati oktatásvezető'),
(41, '2 - Igazgatói iroda, titkárság'),
(42, '20 - műhely tanári'),
(43, '201 - szerver szoba'),
(44, '202'),
(45, '203'),
(46, '204'),
(47, '205'),
(48, '206 - műszaki tanári'),
(49, '207'),
(50, '208'),
(51, '209'),
(52, '21 - Anyagvizsgáló és hidraulika labor'),
(53, '210'),
(54, '211 - humán tanári'),
(55, '212'),
(56, '213 - orvosi szoba'),
(57, '24 - CÍM, robot labor'),
(58, '25 - CNC programozó'),
(59, '28 - Esztergályos műhely'),
(60, '29-es kisterem'),
(61, '3 - Tárgyaló'),
(62, '30 - PLC labor'),
(63, '302'),
(64, '303'),
(65, '304 - informatika tanári'),
(66, '31 - Pneumatika labor'),
(67, '32 - Mérőszoba'),
(68, '36 - Hegesztő labor'),
(69, '4 - GINOP projektiroda'),
(70, '401 - vendégszoba'),
(71, '402 - tehetségszoba'),
(72, '41'),
(73, '42 - CAD-CAM, CNC'),
(74, '43 - Vegyes forgácsoló'),
(75, '5 - Gazdasági iroda, pénztár'),
(76, 'Aqua sportközpont'),
(77, 'B1'),
(78, 'B2'),
(79, 'B4'),
(80, 'B5'),
(81, 'B6'),
(82, 'B7'),
(83, 'B8'),
(84, 'egyéb'),
(85, 'K101'),
(86, 'K102'),
(87, 'K103'),
(88, 'K104'),
(89, 'K105'),
(90, 'K106'),
(91, 'K107'),
(92, 'K108'),
(93, 'K109'),
(94, 'K110'),
(95, 'K111'),
(96, 'K112'),
(97, 'K201'),
(98, 'K202'),
(99, 'K203'),
(100, 'K204'),
(101, 'K205'),
(102, 'K206'),
(103, 'K207'),
(104, 'K208'),
(105, 'K209'),
(106, 'K210'),
(107, 'K211'),
(108, 'Kollégiumi beteg szoba'),
(109, 'Kollégiumi étkező'),
(110, 'Kollégiumi foglalkoztató'),
(111, 'Kollégiumi gépterem'),
(112, 'Kollégiumi konditerem'),
(113, 'Kollégiumi nevelői szoba 1'),
(114, 'Kollégiumi nevelői szoba 2'),
(115, 'Kollégiumi tanulószoba 1'),
(116, 'Kollégiumi tanulószoba 2'),
(117, 'Kollégiumi teakonyha'),
(118, 'Kollégiumi vezetői iroda'),
(119, 'Külső helyszín'),
(120, 'Online'),
(121, 'Stúdió'),
(122, 'T1'),
(123, 'T2'),
(124, 'T3'),
(125, 'T4'),
(126, 'T5'),
(127, 'T6'),
(128, 'Testnevelő tanári'),
(129, 'Tömegsport'),
(130, 'tornacsarnok'),
(131, 'tornacsarnok2');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `muvelet` varchar(255) NOT NULL,
  `datum` datetime DEFAULT current_timestamp(),
  `kezelo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `report_id`, `muvelet`, `datum`, `kezelo`) VALUES
(469, 477, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 08:18:56', 4),
(470, 478, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 08:19:20', 4),
(471, 479, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:20:01', 1),
(472, 480, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 08:20:39', 4),
(473, 481, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:20:46', 1),
(474, 482, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 08:21:36', 4),
(475, 483, 'Új hibabejelentés létrehozta: rendszer.gergo', '2024-12-05 08:22:30', 2),
(476, 484, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:22:30', 1),
(477, 485, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:28:03', 1),
(478, 486, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:29:29', 1),
(479, 487, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:35:01', 1),
(480, 488, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:37:12', 1),
(481, 489, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:37:56', 1),
(482, 490, 'Új hibabejelentés létrehozta: rendszer.gergo', '2024-12-05 08:39:44', 2),
(483, 491, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 08:40:09', 4),
(484, 492, 'Új hibabejelentés létrehozta: rendszer.gergo', '2024-12-05 08:40:53', 2),
(485, 493, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:41:09', 1),
(486, 494, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:43:41', 3),
(487, 495, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:46:28', 3),
(488, 496, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:47:53', 3),
(489, 497, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:50:04', 3),
(490, 498, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:50:18', 3),
(491, 499, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:50:27', 3),
(492, 500, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:50:51', 3),
(493, 501, 'Új hibabejelentés létrehozta: rendszer.gabor', '2024-12-05 08:53:25', 3),
(494, 487, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:54:34', 2),
(495, 498, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:54:53', 2),
(496, 479, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:55:00', 2),
(497, 502, 'Új hibabejelentés létrehozta: rendszer.gergo', '2024-12-05 08:57:51', 2),
(498, 503, 'Új hibabejelentés létrehozta: rendszer.gergo', '2024-12-05 08:58:02', 2),
(499, 504, 'Új hibabejelentés létrehozta: rendszer.gergo', '2024-12-05 08:58:24', 2),
(500, 499, 'Feladatot elvállalta: muszaki.vilma', '2024-12-05 08:58:44', 4),
(501, 505, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:58:45', 1),
(502, 497, 'Feladatott kiosztotta: muszaki.vilma', '2024-12-05 08:58:53', 4),
(503, 497, 'Feladatot megkapta: rendszer.gergo', '2024-12-05 08:58:53', 2),
(504, 496, 'Feladatott kiosztotta: muszaki.vilma', '2024-12-05 08:59:08', 4),
(505, 496, 'Feladatot megkapta: rendszer.gergo', '2024-12-05 08:59:08', 2),
(506, 505, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:59:26', 2),
(507, 505, 'rendszer.gergo a státuszt Beszerzés alatt-ra változtatta', '2024-12-05 08:59:28', 2),
(508, 504, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:59:35', 2),
(509, 499, 'Feladatot elküldte elfogadtatásra: rendszer.gergo', '2024-12-05 08:59:43', 2),
(510, 506, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 08:59:46', 1),
(511, 506, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:59:50', 2),
(512, 503, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:59:54', 2),
(513, 502, 'Feladatot elvállalta: rendszer.gergo', '2024-12-05 08:59:57', 2),
(514, 506, 'Feladatot elküldte elfogadtatásra: rendszer.gergo', '2024-12-05 09:00:01', 2),
(515, 505, 'Feladatot elküldte elfogadtatásra: rendszer.gergo', '2024-12-05 09:00:10', 2),
(516, 504, 'Feladatot elküldte elfogadtatásra: rendszer.gergo', '2024-12-05 09:00:15', 2),
(517, 498, 'rendszer.gergo a státuszt Beszerzés alatt-ra változtatta', '2024-12-05 09:00:22', 2),
(518, 504, 'Feladatot elfogadta: muszaki.vilma', '2024-12-05 09:00:38', 4),
(519, 507, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 09:01:26', 4),
(520, 508, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 09:01:43', 4),
(521, 508, 'Feladatot elvállalta: muszaki.vilma', '2024-12-05 09:01:52', 4),
(522, 508, 'Feladat kiosztását változtatta: muszaki.vilma', '2024-12-05 09:01:52', 4),
(523, 508, 'Feladatot megkapta: muszaki.vilma', '2024-12-05 09:01:52', 4),
(524, 508, 'Feladatot elvégezte: muszaki.vilma', '2024-12-05 09:01:54', 4),
(525, 509, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 09:02:44', 4),
(526, 510, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 09:03:05', 4),
(527, 511, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 09:04:51', 4),
(528, 512, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 09:07:48', 1),
(529, 513, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 09:08:44', 1),
(530, 514, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 09:10:03', 1),
(531, 515, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 09:10:48', 4),
(532, 516, 'Új hibabejelentés létrehozta: muszaki.vilma', '2024-12-05 09:11:51', 4),
(533, 517, 'Új hibabejelentés létrehozta: tanar.juli', '2024-12-05 09:34:13', 1),
(534, 518, 'Új hibabejelentés létrehozta: muszaki.vilma', '2025-02-16 11:22:54', 4),
(535, 518, 'Feladatot elvállalta: rendszer.gergo', '2025-02-16 11:23:30', 2),
(536, 517, 'Feladatott kiosztotta: muszaki.vilma', '2025-02-16 11:24:17', 4),
(537, 517, 'Feladatot megkapta: rendszer.gergo', '2025-02-16 11:24:17', 2),
(538, 518, 'muszaki.vilma a státuszt módosítása \'Nyitott\' értékre', '2025-02-18 12:35:53', 4);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` varchar(255) NOT NULL,
  `images` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT 'Egyéb',
  `reported_by` int(255) DEFAULT NULL,
  `assigned_to` int(255) DEFAULT NULL,
  `report_status` varchar(50) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `c_location` tinyint(1) DEFAULT 0 COMMENT '0=location,1=custom',
  `show_to_user` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `title`, `description`, `created_at`, `location`, `images`, `tags`, `reported_by`, `assigned_to`, `report_status`, `priority`, `c_location`, `show_to_user`) VALUES
(477, 'Elromlott a monitor', 'Nem kapcsol be a tanári monitor', '2024-12-02 08:18:56', '022', 'uploads/broken-computer-monitor.jpg', 'Hardver', 4, NULL, 'Nyitott', 3, 0, 1),
(478, 'Nem jó a projektor', 'Error-t ír ki', '2024-12-05 08:19:20', '025', '', 'Hardver,Egyéb', 4, NULL, 'Nyitott', 4, 0, 1),
(479, 'képernyő hiba', 'nem kap jelet a képernyő', '2024-12-05 08:20:01', '101 - informatika tanári', '', 'Szoftver,Hardver', 1, 2, 'Elvállalt', 4, 0, 1),
(480, 'Eldugult az egyik wc', 'Folyik ki a folyosóra', '2024-11-20 08:20:39', 'emeleti fiu WC 3. fülke', '', 'Egyéb', 4, NULL, 'Nyitott', 5, 1, 1),
(481, 'kivetítő hiba', 'kivetítő nem indul el', '2024-12-05 08:20:46', '024', '', 'Szoftver', 1, NULL, 'Nyitott', 3, 0, 1),
(482, 'Nincs egy gépnél egér', '12. gépnél nincsen egér', '2024-12-05 08:21:36', '113 - tanári szoba', '', 'Hardver', 4, NULL, 'Nyitott', 2, 0, 1),
(483, 'Eltört egy szék', 'Eltört egy szék és nincs elég szék', '2024-12-05 08:22:30', '116', '', 'Egyéb', 2, NULL, 'Nyitott', 3, 0, 1),
(484, 'kábel törés', 'megtört számítógép kábel', '2024-11-19 08:22:30', '022', 'uploads/letöltés.jpg', 'Hardver', 1, NULL, 'Nyitott', 2, 0, 1),
(485, 'kékhalál', 'nem mukszik', '2024-12-05 08:28:03', '1/4 - Igazgatóhelyettesi iroda', 'uploads/letöltés (1).jpg', 'Szoftver', 1, NULL, 'Nyitott', 5, 0, 1),
(486, 'billentyűzet hiba', 'nem tudok írni', '2024-12-03 08:29:29', '101 - informatika tanári', 'uploads/letöltés (2).jpg', 'Hardver', 1, NULL, 'Nyitott', 2, 0, 1),
(487, 'Eldugult wc', 'Eldugult a wc a második emelet női mosdójában.', '2024-12-02 08:35:01', 'Földszinti női wc', '', 'Egyéb', 1, 2, 'Elvállalt', 3, 1, 1),
(488, 'Eltörött székek', 'Amire beértem a terembe, az a látvány fogadott, hogy darabokban áll a tanári szék', '2024-12-05 08:37:12', '43 - Vegyes forgácsoló', '', 'Hardver,Egyéb', 1, NULL, 'Nyitott', 2, 0, 1),
(489, 'Nem kapcsol be a gépem', 'valamiért nem kapcsol be a gépem, szükségem lesz egy kis segítségre', '2024-12-05 08:37:56', '105 - matematika tanári', '', 'Szoftver,Hardver', 1, NULL, 'Nyitott', 4, 0, 1),
(490, 'nem tölt be a gép', '20 perce ezt írja', '2024-12-05 08:39:44', '1/2 - Igazgatóhelyettesi iroda', 'uploads/Windows_XP_BSOD.png', 'Szoftver', 2, NULL, 'Nyitott', 4, 0, 1),
(491, 'Elromlott az egerem.', 'Valószínüleg elromlott az egerem jobb billentyűje.', '2024-12-05 08:40:09', '101 - informatika tanári', '', 'Hardver', 4, NULL, 'Nyitott', 4, 0, 1),
(492, 'Nincs net', 'nincs net', '2024-12-05 08:40:53', '212', '', 'Hálózati', 2, NULL, 'Nyitott', 4, 0, 1),
(493, 'Alaplap hiba merült fel', 'Meghalt az alaplap az informatika tanári szobában, és azóta használhatatlan a gép', '2024-12-05 08:41:09', '101 - informatika tanári', '', 'Hardver', 1, NULL, 'Nyitott', 5, 0, 1),
(494, 'Nincs net', 'nincsen internet', '2024-12-05 08:43:41', '103', '', 'Hálózati', 3, NULL, 'Nyitott', 3, 0, 1),
(495, 'Nem nyílik az ajtó', 'A kilincs meg van rongálva', '2024-12-05 08:46:28', '025', 'uploads/door-handle-broke-off.jpg', 'Egyéb', 3, NULL, 'Nyitott', 4, 0, 1),
(496, 'Rossz a fal', 'Van valami a hiba a falba', '2024-12-05 08:47:53', '14', 'uploads/istockphoto-153738994-612x612.jpg,uploads/businessman-crashed-wall-by-his-hand-2A4P4NH.jpg', 'Egyéb', 3, 2, 'Elvállalt', 3, 0, 1),
(497, 'eltört az ablak', 'valaki betörte', '2024-12-05 08:50:04', '207', 'uploads/istockphoto-1402628166-612x612.jpg', '', 3, 2, 'Elvállalt', 3, 0, 1),
(498, 'nem jó', 'hibás', '2024-12-05 08:50:18', '102', '', 'Hardver,Hálózati', 3, 2, 'Beszerzés alatt', 3, 0, 1),
(499, 'elört', 'nem jó', '2024-12-05 08:50:27', '12', '', '', 3, 4, 'Elfogadásra vár', 3, 0, 1),
(500, 'hibás', 'egyszer csak hibás lett', '2024-12-05 08:50:51', '212', '', 'Szoftver', 3, NULL, 'Nyitott', 1, 0, 1),
(501, 'Történt kis baleset', 'csak jöttek. egyszerre', '2024-12-05 08:53:25', 'Több helyen', 'uploads/Car-Crashes-Into-Second-Story-Window.jpg,uploads/PHOTO-2022-08-24-09-37-04__1_.jpg', 'Egyéb', 3, NULL, 'Nyitott', 5, 1, 1),
(502, 'rossz', 'nem jo', '2024-12-05 08:57:51', '102', '', 'Hardver', 2, 2, 'Elvállalt', 2, 0, 1),
(503, 'hibás', 'nem jó', '2024-12-05 08:58:02', '102', '', '', 2, 2, 'Elvállalt', 4, 0, 1),
(504, 'rossz lett a monitor', 'nem mukodik', '2024-12-05 08:58:24', '102', '', 'Hardver', 2, 2, 'Archivált', 3, 0, 1),
(505, 'elszakadt a függöny', 'lejött', '2024-12-05 08:58:45', '10 - humán tanári', '', 'Egyéb', 1, 2, 'Elfogadásra vár', 1, 0, 1),
(506, 'csengő nem működik', 'nem volt kicsöngetés', '2024-12-05 08:59:46', 'K202', '', 'Egyéb', 1, 2, 'Elfogadásra vár', 4, 0, 1),
(507, 'elromlott rossz', 'egyszer csak rossz lett a semmibol', '2024-12-05 09:01:26', '212', '', '', 4, NULL, 'Nyitott', 3, 0, 1),
(508, 'nem kapcsol be', '30 perce várok', '2024-12-05 09:01:43', '117', '', 'Hálózati', 4, 4, 'Archivált', 4, 0, 1),
(509, 'nagyon rossz nem jo', 'nem jó', '2024-12-05 09:02:44', 'torna fala', 'uploads/businessman-crashed-wall-by-his-hand-2A4P4NH.jpg,uploads/istockphoto-153738994-612x612.jpg', 'Egyéb', 4, NULL, 'Nyitott', 5, 1, 1),
(510, 'rossz', 'nem jó', '2024-12-05 09:03:05', '025', 'uploads/Car-Crashes-Into-Second-Story-Window.jpg', '', 4, NULL, 'Nyitott', 3, 0, 1),
(511, 'meleg van', 'tul erős a légkondi', '2024-12-05 09:04:51', '101 - informatika tanári', '', 'Hardver', 4, NULL, 'Nyitott', 3, 0, 1),
(512, 'Valami leesett', 'égből jött', '2024-12-05 09:07:48', '11 - humán tanári', '', '', 1, NULL, 'Nyitott', 3, 0, 1),
(513, 'MÁR MEGINT METEOR', 'MEGINT METEOR ESETT', '2024-12-05 09:08:44', '025', '', '', 1, NULL, 'Nyitott', 4, 0, 1),
(514, 'rossz a kocsi', 'nem jó', '2024-12-05 09:10:03', 'tető', 'uploads/Car-Crashes-Into-Second-Story-Window.jpg', '', 1, NULL, 'Nyitott', 3, 1, 1),
(515, 'leesett egy meteor', 'lepottyant', '2024-12-05 09:10:48', '10 - humán tanári', '', 'Hardver', 4, NULL, 'Nyitott', 4, 0, 1),
(516, 'Erős a légkondi', 'Meleg van', '2024-12-05 09:11:51', '102', 'uploads/images.jpg', '', 4, NULL, 'Nyitott', 3, 0, 1),
(517, 'elromlott egy eger', 'valamiert nem kapcsol be', '2024-12-05 09:34:13', 'folyoson', 'uploads/images.jpg', 'Szoftver', 1, 2, 'Elvállalt', 5, 1, 1),
(518, 'El romlott a 32 es monitor', 'nem kapol be', '2025-02-16 11:22:54', 'rajka', '', 'Szoftver,Hardver', 4, NULL, 'Nyitott', 5, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `group_id`) VALUES
(1, 'tanar.juli', 'asd', 1),
(2, 'rendszer.gergo', 'asd', 2),
(3, 'rendszer.gabor', 'asd', 2),
(4, 'muszaki.vilma', 'asd', 3),
(5, 'senki', 'asd', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `helyszinek`
--
ALTER TABLE `helyszinek`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `kezelo` (`kezelo`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `fk_reported_by` (`reported_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `helyszinek`
--
ALTER TABLE `helyszinek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=539;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=519;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_ibfk_2` FOREIGN KEY (`kezelo`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reported_by` FOREIGN KEY (`reported_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);
--
-- Database: `image`
--
CREATE DATABASE IF NOT EXISTS `image` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `image`;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `image_names` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `image_names`) VALUES
(48, '3.png,1.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2019-10-21 13:37:09', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
