-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-10-2022 a las 21:22:28
-- Versión del servidor: 10.5.16-MariaDB
-- Versión de PHP: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id19671728_clientes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elneflis`
--

CREATE TABLE `elneflis` (
  `id` int(11) DEFAULT NULL,
  `streaming` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `nombres` varchar(9) CHARACTER SET utf8 DEFAULT NULL,
  `apellidos` varchar(9) CHARACTER SET utf8 DEFAULT NULL,
  `whatsapp` varchar(74) CHARACTER SET utf8 DEFAULT NULL,
  `correo` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `contraseña` varchar(14) CHARACTER SET utf8 DEFAULT NULL,
  `perfil` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `deben` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `elneflis`
--

INSERT INTO `elneflis` (`id`, `streaming`, `nombres`, `apellidos`, `whatsapp`, `correo`, `contraseña`, `perfil`, `deben`) VALUES
(1, 'youtube', 'steven', 'dueñas', 'messenger', 'stivendueñas37', NULL, 'yuli woo', '2022-11-05 00:00:00'),
(2, 'youtube', 'andres', 'henao', 'henao', 'henaoandres543', NULL, 'yuli woo', '2022-10-18 00:00:00'),
(3, 'youtube', 'orlando', 'miranda', 'esposo de angela', 'orlandomanuelmirandalopez', NULL, 'yuli woo', '2022-10-04 00:00:00'),
(4, 'youtube', 'juan ', 'camilo', 'juc  youtube', 'jucamilogal7@gmail.com', 'empty', 'yuli woo', '2022-10-18 00:00:00'),
(5, 'youtube', NULL, NULL, NULL, 'jucamilogal7@gmail.com', 'empty', 'yuli woo', NULL),
(6, 'youtube', 'santiago ', 'viasus', 'viasus', 'santiagogonzalesviasus', NULL, 'among us', '2022-11-02 00:00:00'),
(7, 'youtube', 'laura', 'avila', 'laura hermana', 'lauraavilasierra.las', NULL, 'among us', '2022-10-27 00:00:00'),
(8, 'youtube', 'tania', 'acevedo', 'messenger', 'yely201513@gmail.com', NULL, 'among us', '2022-09-17 00:00:00'),
(9, 'youtube', 'oscar', 'bravo', 'osquitar', 'oscarbravo4710', NULL, 'among us', '2022-11-01 00:00:00'),
(10, 'youtube', NULL, NULL, NULL, 'oscarbravo4711', NULL, 'among us', '2022-09-02 00:00:00'),
(11, 'youtube', 'andres', 'felipe', 'pipo', 'Tgac1712@gmail.com', NULL, 'esteban ', '2022-10-14 00:00:00'),
(12, 'youtube', 'edward', 'yt', 'edward yt', 'Edwardfguerrerom@gmail.com', NULL, 'esteban ', '2022-10-22 00:00:00'),
(13, 'youtube', 'fabian ', 'sanchez', 'laura hermana', 'fabiansanchez1203@gmail.com', NULL, 'esteban ', '2022-10-27 00:00:00'),
(14, 'youtube', NULL, NULL, NULL, 'Edwardfguerrerom@gmail.com', NULL, 'esteban ', NULL),
(15, 'youtube', NULL, NULL, NULL, 'fabiansanchez1203@gmail.com', NULL, 'esteban ', NULL),
(16, 'star +', 'fabian', 'youtube', 'fabian youtube', 'Buttahowie189@outlook.com', 'STR@0110', '7880', '2022-10-27 00:00:00'),
(17, 'star +', NULL, NULL, NULL, 'Buttahowie189@outlook.com', 'STR@0110', '7881', NULL),
(18, 'star +', 'pablo', 'avila', 'del', 'Buttahowie189@outlook.com', 'STR@0110', '7882', '2022-10-26 00:00:00'),
(19, 'star +', 'jair ', 'vasquez', 'jair starman', 'Buttahowie189@outlook.com', 'STR@0110', '7883', '2022-10-12 00:00:00'),
(20, 'star +', 'jair ', 'vasquez', 'jair starman', 'Buttahowie189@outlook.com', 'STR@0111', '7884', '2022-10-12 00:00:00'),
(21, 'star +', 'liz', 'rodriguez', 'liz', 'Buttahowie189@outlook.com', 'STR@0110', '7885', '2022-10-05 00:00:00'),
(22, 'star +', NULL, NULL, NULL, 'Buttahowie189@outlook.com', 'STR@0110', '7886', NULL),
(23, 'star +', NULL, NULL, NULL, 'spotinetstar+iuhbv@gmail.com', 'Star818722', '7977', NULL),
(24, 'star +', 'carlos', 'laura', 'carlos laura', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '7977', '2022-10-18 00:00:00'),
(25, 'star +', 'walter', 'florez', 'waltermanteco', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '7977', '2022-10-27 00:00:00'),
(26, 'star +', 'steven', 'duenas', 'messenger', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '7978', '2022-10-05 00:00:00'),
(27, 'star +', 'cesar', 'cesar', 'cesar', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '7979', '2022-10-21 00:00:00'),
(28, 'star +', 'jonathan', 'm', 'mjohnatan', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '7880', '2022-11-01 00:00:00'),
(29, 'star +', 'alex', 'erika', 'erikaprima', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '7881', '2022-10-08 00:00:00'),
(30, 'disney', 'julian', 'rodriguez', 'instagram', 'spotinetdisney+jq91282@gmail.com', 'Disney919344', '1308', '2022-10-22 00:00:00'),
(31, 'disney', 'rafael', 'rafa', 'venecoman', 'spotinetdisney+jq91282@gmail.com', 'Disney919344', '7979', '2022-10-10 00:00:00'),
(32, 'disney', 'costanza', 'costanza', 'costanza', 'spotinetdisney+jq91282@gmail.com', 'Disney919344', '7878', '2022-10-15 00:00:00'),
(33, 'disney', 'liz', 'rodriguez', 'liz', 'spotinetdisney+jq91282@gmail.com', 'Disney919344', '7984', '2022-10-03 00:00:00'),
(34, 'disney', 'eduardo', 'hbo', 'eduardo hbo', 'spotinetdisney+jq91282@gmail.com', 'Disney919344', '7986', '2022-10-30 00:00:00'),
(35, 'disney', 'brayan', 'tania ', 'tania', 'spotinetdisney+jq91282@gmail.com', 'Disney919344', NULL, '2022-11-03 00:00:00'),
(36, 'disney', 'martha', 'martha', 'martha', 'spotinetdisney+jq91282@gmail.com', 'Disney919345', NULL, '2022-10-09 00:00:00'),
(37, 'disney', 'cesar', 'cesar', 'cesar', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '5511', '2022-10-21 00:00:00'),
(38, 'disney', 'gustavo', 'netflix ', 'gustavo netflix', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '0.467', '2022-10-19 00:00:00'),
(39, 'disney', 'japt', 'puentes', 'mjonathan', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '19007', '2022-11-02 00:00:00'),
(40, 'disney', 'ariatna', 'tania ', 'ariadna tn', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '19008', '2022-11-01 00:00:00'),
(41, 'disney', 'nikol', 'jose', 'netflix4k jose', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '1', '2022-10-16 00:00:00'),
(42, 'disney', 'veronica', 'jose', 'netflix4k jose', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '1', '2022-10-15 00:00:00'),
(43, 'disney', 'leidy', 'velazquez', 'https://api.whatsapp.com/send?phone=573205637683&text=hola%20como%20vas%3F', 'spotinetstar+iuhbv@gmail.com', 'Star818722', '2', '2022-10-21 00:00:00'),
(44, 'hbo', 'lady', 'velaquez', 'https://api.whatsapp.com/send?phone=573205637683&text=hola%20como%20vas%3F', 'hbospotinet+kev96378@gmail.com', 'hbomax3333', '10', '2022-10-21 00:00:00'),
(45, 'hbo', 'laura', 'hermana', 'laura hermana', 'hbospotinet+kev96378@gmail.com', 'hbomax3334', '11', '2022-11-02 00:00:00'),
(46, 'hbo', 'tatiana', 'luisa', 'tatiana roa', 'hbospotinet+kev96378@gmail.com', 'hbomax3335', '12', '2022-11-03 00:00:00'),
(47, 'hbo', 'tania', 'carrillo', 'tania', 'hbospotinet+kev96378@gmail.com', 'hbomax3336', '13', '2022-11-03 00:00:00'),
(48, 'hbo', NULL, NULL, NULL, 'hbospotinet+kev96378@gmail.com', 'hbomax3337', '14', NULL),
(49, 'hbo', 'jonathan', 'puentes', 'mjonathan', 'hbospotinet+elt78978@gmail.com', 'hbomax00912', '6', '2022-10-31 00:00:00'),
(50, 'hbo', 'cesar', 'cesar', 'cesar', 'hbospotinet+elt78978@gmail.com', 'hbomax00912', '7', '2022-10-14 00:00:00'),
(51, 'hbo', 'daniela', 'carrillo', 'daniela carrillo', 'hbospotinet+elt78978@gmail.com', 'hbomax00912', '9', '2022-10-12 00:00:00'),
(52, 'hbo', 'erika', 'velandia', 'erikaprima', 'hbospotinet+elt78978@gmail.com', 'hbomax00912', '15', '2022-10-30 00:00:00'),
(53, 'hbo', 'eduardo', 'hbo', 'eduardo hbo', 'hbospotinet+elt78978@gmail.com', 'hbomax00913', '16', '2022-10-30 00:00:00'),
(54, 'hbo', 'liz', 'rodriguez', 'liz', 'sspotinethbo+7899ed@gmail.com', 'hbomax112233', '10', '2022-10-03 00:00:00'),
(55, 'hbo', 'miguel ', 'angel', 'miguel', 'sspotinethbo+7899ed@gmail.com', 'hbomax112233', '11', '2022-10-29 00:00:00'),
(56, 'hbo', 'andres', 'henao', 'henao', 'sspotinethbo+7899ed@gmail.com', 'hbomax112233', '12', '2022-10-18 00:00:00'),
(57, 'hbo', 'juluan', 'rodriguez', 'instagram', 'sspotinethbo+7899ed@gmail.com', 'hbomax112233', '15', '2022-10-21 00:00:00'),
(58, 'hbo', 'geral', 'andres', 'geral andres', 'sspotinethbo+7899ed@gmail.com', 'hbomax112233', '16', '2022-11-04 00:00:00'),
(59, 'amazon', 'valeria', 'suarez', 'messenger', 'juliopert57734@gmail.com', 'prime000', 'net12344', '2022-10-16 00:00:00'),
(60, 'amazon', 'oliver ', 'prime', 'oliver prime', 'juliopert57734@gmail.com', 'prime000', 'net12345', '2022-10-11 00:00:00'),
(61, 'amazon', 'geral', 'andres', 'geral andres', 'juliopert57734@gmail.com', 'prime000', 'net12345', '2022-11-04 00:00:00'),
(62, 'amazon', 'liz', 'rodriguez', 'liz', 'juliopert57734@gmail.com', 'prime000', 'net12345', '2022-10-03 00:00:00'),
(63, 'amazon', 'jonathan', 'senior', 'jonathansenior', 'juliopert57734@gmail.com', 'prime000', 'net12344', '2022-11-02 00:00:00'),
(64, 'amazon', 'anderson', 'siarez', 'valeria suarez', 'juliopert57734@gmail.com', 'prime000', 'net12345', '2022-10-25 00:00:00'),
(65, 'spotify', 'walter', 'florez', 'waltermanteco', 'bowspotify873@yopmail.com', 'user1234', NULL, '2022-11-11 00:00:00'),
(66, 'spotify', 'mamadeliz', 'rodriguez', 'liz', 'Pilarbenavidez@gmail.com ', 'Maniesdios12', NULL, '2022-11-28 00:00:00'),
(67, 'spotify', 'liz', 'rodriguez', 'liz', 'Lizrodriguez.job@gmail.com', 'Lizymani', NULL, '2022-12-11 00:00:00'),
(68, 'spotify', 'juluan', 'rodriguez', 'jules', 'ulianrodriguez135790@outlook.com', 'PanchisJules22', NULL, '2022-11-22 00:00:00'),
(69, 'spotify', 'kevin', 'cuellar', 'kevin tutub', 'kevincuellar@misena.edu.co', 'susanamadre', NULL, '2022-10-04 00:00:00'),
(70, 'spotify', 'giovanni', 'cristina', 'giovanni', 'spotieddx55q@yopmail.com', 'user1234', NULL, '2022-12-27 00:00:00'),
(71, 'spotify', 'luisa', 'daza', 'uicha', 'tralspotify2132@yopmail.com', 'user1234', NULL, '2022-11-02 00:00:00'),
(72, 'spotify', 'walter', 'florez', 'waltermanteco', 'alespotify748@yopmail.com', 'user1234', NULL, '2022-10-25 00:00:00'),
(73, 'spotify', 'angelo', 'tania ', 'tania', 'alespotify748@yopmail.com', 'user1235', NULL, '2023-01-02 00:00:00'),
(74, 'netflix', 'paola', 'sasasa', 'paola sasa ', 'spotinetshop+1111@gmail.com', '151515', '9876', '2022-10-11 00:00:00'),
(75, 'netflix', 'estiven ', 'acevedo', 'estifen', 'spotinetshop+1111@gmail.com', '151515', '2727', '2022-10-12 00:00:00'),
(76, 'netflix', 'leidy', 'velazquez', 'lady velasquez', 'spotinetshop+1111@gmail.com', '151515', '2624', '2022-10-21 00:00:00'),
(77, 'netflix', 'rafa', 'venecoman', 'venecoman', 'spotinetshop+1111@gmail.com', '151515', '1111', '2022-10-10 00:00:00'),
(78, 'netflix', 'diana', 'pardo', 'instagram', 'spotinetshop+1111@gmail.com', '151515', NULL, '2022-10-02 00:00:00'),
(79, 'netflix', 'luz', 'luz', 'luz', 'jordimemesmomazosdick@gmail.com', '232323', NULL, '2022-10-26 00:00:00'),
(80, 'netflix', 'blanca', 'pimpon', 'lilian netflix', 'jordimemesmomazosdick@gmail.com', '232323', NULL, '2022-12-26 00:00:00'),
(81, 'netflix', 'juan', 'camilo', 'juc  youtube', 'jordimemesmomazosdick@gmail.com', '232323', NULL, '2022-10-18 00:00:00'),
(82, 'netflix', 'numil', 'nuñez', 'numil', 'jordimemesmomazosdick@gmail.com', '232323', NULL, '2022-10-15 00:00:00'),
(83, 'netflix', 'mauricio', 'luz', 'luz', 'jordimemesmomazosdick@gmail.com', '232323', NULL, '2022-10-26 00:00:00'),
(84, 'netflix', 'micchelle', 'hernandez', 'michelle net', 'yaristizabal948@gmail.com', '151515', '1111', '2022-10-28 00:00:00'),
(85, 'netflix', 'ximena', 'hector', 'hector', 'yaristizabal948@gmail.com', '151515', NULL, '2022-10-29 00:00:00'),
(86, 'netflix', 'miguel ', 'angel', 'miguel', 'yaristizabal948@gmail.com', '151515', '922', '2022-10-29 00:00:00'),
(87, 'netflix', 'hector', 'hermano', 'hector', 'yaristizabal948@gmail.com', '151515', '423', '2022-10-24 00:00:00'),
(88, 'netflix', 'fernanda', 'arboleda', 'venecoman', 'yaristizabal948@gmail.com', '151515', '4443', '2022-10-19 00:00:00'),
(89, 'netflix', 'carlos', 'laura', 'carlos', 'ireneavila2050@gmail.com', '181818', '5441', '2022-10-27 00:00:00'),
(90, 'netflix', 'juan', 'pablo', 'charrito negro', 'ireneavila2050@gmail.com', '181818', '9035', '2022-10-27 00:00:00'),
(91, 'netflix', 'geral', 'geral', 'andres', 'ireneavila2050@gmail.com', '181818', '5716', '2022-11-04 00:00:00'),
(92, 'netflix', 'anyi', 'geral', 'andres', 'ireneavila2050@gmail.com', '181818', '3289', '2022-11-04 00:00:00'),
(93, 'netflix', 'merry', 'carrillo', 'daniela carrillo', 'ireneavila2050@gmail.com', '181818', '2222', '2022-10-05 00:00:00'),
(94, 'netflix', 'pedtro', 'carrillo', 'daniela carrillo', 'estebanavila182@gmail.com', '181818', '1479', '2022-10-05 00:00:00'),
(95, 'netflix', 'alejandra', 'tania ', 'alejandratn', 'estebanavila182@gmail.com', '181818', '1480', '2022-10-20 00:00:00'),
(96, 'netflix', 'cata', 'cry cata', 'cry cata', 'estebanavila182@gmail.com', '181818', '1236', '2022-10-30 00:00:00'),
(97, 'netflix', 'gustavo', 'netflix ', 'gustavo netflix', 'estebanavila182@gmail.com', '181818', '2001', '2022-10-20 00:00:00'),
(98, 'netflix', 'adriatna', 'tania ', 'adriana tn', 'estebanavila182@gmail.com', '181818', '2805', '2022-11-01 00:00:00'),
(99, 'netflix', NULL, NULL, NULL, 'estebanavila6324@gmail.com', '151515', '5441', NULL),
(100, 'netflix', 'daniela', 'daniela', 'daniela netflix', 'estebanavila6324@gmail.com', '151515', '357', '2022-10-18 00:00:00'),
(101, 'netflix', 'chiqui ', 'acevedo', 'estifen', 'estebanavila6324@gmail.com', '151515', '3010', '2022-10-04 00:00:00'),
(102, 'netflix', NULL, NULL, NULL, 'estebanavila6324@gmail.com', '151515', '712', NULL),
(103, 'netflix', 'andres', 'henao', 'henao', 'estebanavila6324@gmail.com', '151515', '6454', '2022-11-01 00:00:00'),
(104, 'netflix', 'anderson', 'anderson', 'messenger', 'munardaza2003@gmail.com', '151515', '8946', '2022-10-25 00:00:00'),
(105, 'netflix', 'alejandro', 'cyc', 'alejandro CYC', 'munardaza2003@gmail.com', '151515', '6290', '2022-10-27 00:00:00'),
(106, 'netflix', 'costanza', 'costanza', 'costanza', 'munardaza2003@gmail.com', '151515', '922', '2022-10-15 00:00:00'),
(107, 'netflix', 'rony', 'venecoman', 'roiner', 'munardaza2003@gmail.com', '151515', '1418', '2022-10-20 00:00:00'),
(108, 'netflix', 'jonathan', 'puentes', 'mjonathan', 'munardaza2003@gmail.com', '151515', '8141', '2022-11-02 00:00:00'),
(109, 'netflix', 'brayan', 'tania ', 'tania', 'yuliplay688@gmail.com', '181818', '2805', '2022-11-03 00:00:00'),
(110, 'netflix', 'diego', 'saraza', 'instagram', 'yuliplay688@gmail.com', '181818', '1959', '2022-11-04 00:00:00'),
(111, 'netflix', 'daniela', 'carrillo', 'daniela carrillo', 'yuliplay688@gmail.com', '181818', '2624', '2022-10-17 00:00:00'),
(112, 'netflix', 'martha', 'martha', 'martha', 'yuliplay688@gmail.com', '181818', '1233', '2022-10-09 00:00:00'),
(113, 'netflix', 'Mayha', 'Mayha', 'martha', 'yuliplay688@gmail.com', '181818', '525', '2022-10-09 00:00:00');

ALTER TABLE `elneflis`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `directivos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
