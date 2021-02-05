-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: secretbu.mysql.ukraine.com.ua
-- Generation Time: Feb 05, 2021 at 08:53 AM
-- Server version: 5.7.16-10-log
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secretbu_nptest`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `user_name` varchar(32) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviews_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `price`, `user_name`, `date`, `reviews_total`) VALUES
(1, 'Samsung Galaxy Tab S 8.4 16GB', 640, 'NetPeaker', '2021-02-04 17:39:17', 2),
(2, 'Gazer Tegra Note 7', 210, 'Ivan Popov', '2021-02-04 18:14:53', 2),
(3, 'Монітор 23\" Dell E2314H Black', 175, 'Ліля', '2021-02-05 03:26:26', 0),
(4, 'Комп`ютер Everest Game ', 1320, 'Admin', '2021-02-05 07:24:40', 7),
(5, 'HP Pavillion RT5', 1119, 'Володимир Шевченко', '2021-02-05 08:49:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_review`
--

DROP TABLE IF EXISTS `product_review`;
CREATE TABLE `product_review` (
  `product_review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_review`
--

INSERT INTO `product_review` (`product_review_id`, `product_id`, `user_name`, `rating`, `comment`, `date`) VALUES
(1, 4, 'Інґеборґа Дапкунайте', 7, 'Нічо так, але могло бути і краще', '2021-02-05 06:17:27'),
(2, 4, 'Вадік Рабіновіч', 10, 'Вставай страна огромная!', '2021-02-05 06:28:31'),
(3, 4, 'Haddaway', 2, 'What is love?\r\nBaby don\'t hurt me!\r\nDon\'t hurt me!\r\nNo more!', '2021-02-05 06:30:04'),
(4, 2, 'NetPeak', 10, 'Чудово виконане тестове завдання, чи не так?:)', '2021-02-05 06:36:13'),
(5, 2, 'Dabee', 10, 'Я дуууже старався!:)', '2021-02-05 06:36:31'),
(6, 5, 'Володимир Шевченко', 5, 'Я більше люблю Lenovo;)', '2021-02-05 06:49:39');

--
-- Triggers `product_review`
--
DROP TRIGGER IF EXISTS `update_reviews_total`;
DELIMITER $$
CREATE TRIGGER `update_reviews_total` AFTER INSERT ON `product_review` FOR EACH ROW BEGIN
       DECLARE id_exists Boolean;
       SELECT 1
       INTO @id_exists
       FROM product
       WHERE product.product_id = NEW.product_id;
       IF @id_exists = 1
       THEN
           UPDATE product
           SET reviews_total = reviews_total + 1
           WHERE product_id = NEW.product_id;
        END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_review`
--
ALTER TABLE `product_review`
  ADD PRIMARY KEY (`product_review_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_review`
--
ALTER TABLE `product_review`
  MODIFY `product_review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_review`
--
ALTER TABLE `product_review`
  ADD CONSTRAINT `product_review_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
