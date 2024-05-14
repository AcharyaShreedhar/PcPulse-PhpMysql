-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2023 at 04:44 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pcpulse`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE `Categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`CategoryID`, `CategoryName`) VALUES
(1, 'Laptops'),
(2, 'Tablets');

-- --------------------------------------------------------

--
-- Table structure for table `OrderDetails`
--

CREATE TABLE `OrderDetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE `Products` (
  `ProductID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `InStock` int(11) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `Brand` varchar(50) DEFAULT NULL,
  `Model` varchar(50) DEFAULT NULL,
  `UpdatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`ProductID`, `Name`, `Description`, `Price`, `InStock`, `CategoryID`, `ImageURL`, `Brand`, `Model`, `UpdatedBy`) VALUES
(1, 'HP Pavilion 15 updated', '15.6-inch laptop with AMD Ryzen 5 processor and 8GB RAM.updated', 850.00, 50, 2, 'hppavilion.jpg', 'HPupdated', 'Pavilion 156', 1),
(2, 'Acer Swift 3', '14-inch lightweight laptop with AMD Ryzen 7 processor and 8GB RAM.', 234.00, 23, 1, 'acer.jpg', 'Swift 3', 'Acer', 1),
(6, 'Acer Nitro 5', 'Acer Nitro 5 - Redefining Gaming Thrills with Power and Precision. Unleash gaming prowess with customizable RGB lighting and advanced cooling', 999.99, 50, 1, 'acer_aspire.jpg', 'Acer', 'Nitro', 1),
(7, 'HP Spectre x360', 'HP Spectre x360 - Unleash Creativity with a Convertible Masterpiece. Versatile modes, powerful performance, and an elegant design for the ultimate computing experience.', 1399.00, 30, 1, 'toshiba_tecra.jpg', 'HP', 'Spectre X360', 1),
(8, 'LG Ultra PC 17', 'LG Ultra PC 17 - Maximize Productivity with a Stunning 17-inch Display. Sleek design, powerful features, and ample screen real estate for content creators and professionals.', 2000.00, 100, 1, 'lg_ultra17.jpg', 'LG', 'Ultra 17', 1),
(9, 'Toshiba Satellite Radius 11', 'Toshiba Satellite Radius 11 - Versatile 2-in-1 for Dynamic Lifestyles. Compact, vibrant, and adaptive for work, entertainment, and creativity', 899.99, 50, 1, 'toshiba_satelite.jpg', 'Toshiba', 'Satellite Radius 11', 1),
(10, 'Microsoft Surface Go 3', 'Microsoft Surface Go 3 - Compact Powerhouse for On-the-Go Productivity. Lightweight, powerful, and versatile for daily tasks on the move.', 549.99, 75, 1, 'micro_go3.jpg', 'Microsoft', 'Surface Go', 1),
(11, 'Lenovo ThinkBook', 'Lenovo ThinkBook - Professional performance in a stylish package. Powerful features for business excellence on the go', 899.99, 75, 1, 'lenovo_1.jpg', 'Lenovo', 'Thinkbook', 1),
(12, 'MSI Thin GF63', 'MSI Thin GF63 - Powerhouse performance in an ultra-thin design. Speed and power for gaming, creating, and multitasking in a sleek design', 1099.99, 200, 1, ' Msi_thin.jpg', 'MSI', 'GF63', 1),
(13, 'Asus Vivobook S15', 'Asus Vivobook S15 - Redefine productivity with style and substance. Stunning display, innovative design, and high-performance capabilities for seamless work and creation', 899.99, 20, 1, 'asus_vivobook.jpg', 'Asus', 'Vivobook S15', 1),
(14, 'Logitech M220\r\n', 'Smooth and noiseless, the Logitech M220 Silent 1000 DPI mouse is an excellent pick for your laptop. You will never regret.', 20.00, 20, 2, 'lg_gram.jpg', 'Logitech', 'M220', 1),
(15, 'Lenovo IdeaPad', 'Lenovo IdeaPad - Versatile performance for everyday tasks. Efficient, stylish, and reliable for work, streaming, and staying connected', 2000.00, 200, 1, 'lenovoThink.jpg', 'Lenovo', 'IdeaPad', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Address` text DEFAULT NULL,
  `UserType` enum('Admin','Customer') DEFAULT 'Customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserID`, `FirstName`, `LastName`, `Email`, `Password`, `Address`, `UserType`) VALUES
(1, 'Shree Dhar', 'Acharya', 'spacharya2537@gmail.com', '$2y$10$VxZXTAUabLqvnxpjSg74xebkPvur5MH5EhlqrwXKt4t80D6kjfO3y', 'Brantford', 'Admin'),
(2, 'Asmita', 'Paudel', 'poudelasmita194@gmail.com', '$2y$10$MWcWI6yRa97KFXoiPUiBrOlLE/N7ZF4hBCyBuiKcg0JMxjT2sbNi6', 'Canada', 'Admin'),
(4, 'Prashant', 'Sahu', 'psahu@gmail.com', '$2y$10$PrOIYIw3.pjHyHqqfRcXZu5LvTRlwfc/oUqPEwKWrQq5x56lpQlS.', '7 Erie Avenue Brantford, ON, Canada', 'Customer'),
(5, 'Abhijit', 'Singh', 'asingh@gmail.com', '$2y$10$4lazdIAKSthwT9EdgWDTKex6HQHFjxEObhZlxkHOPmXIPyu0Fppna', '7 Erie Avenue Canada', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `OrderDetails`
--
ALTER TABLE `OrderDetails`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `UpdatedBy` (`UpdatedBy`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `OrderDetails`
--
ALTER TABLE `OrderDetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `OrderDetails`
--
ALTER TABLE `OrderDetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `Orders` (`OrderID`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`);

--
-- Constraints for table `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`);

--
-- Constraints for table `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `Categories` (`CategoryID`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`UpdatedBy`) REFERENCES `Users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
