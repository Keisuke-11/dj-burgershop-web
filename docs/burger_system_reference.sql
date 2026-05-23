-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 07, 2026 at 09:52 AM
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
-- Database: `burger_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `LastLoginDate` datetime DEFAULT NULL,
  `FeedbackCount` int(5) NOT NULL DEFAULT 0,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `CustomerType` enum('Walk-in','Reservation') DEFAULT 'Walk-in',
  `CustomerTag` varchar(50) NOT NULL DEFAULT 'New Customer',
  `TotalOrdersCount` int(10) NOT NULL DEFAULT 0,
  `LastTransactionDate` datetime DEFAULT NULL,
  `ReservationCount` int(10) NOT NULL DEFAULT 0,
  `CreatedDate` datetime NOT NULL DEFAULT current_timestamp(),
  `SatisfactionRating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `LastReservationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `FirstName`, `LastName`, `Email`, `Password`, `LastLoginDate`, `FeedbackCount`, `ContactNumber`, `Address`, `CustomerType`, `CustomerTag`, `TotalOrdersCount`, `LastTransactionDate`, `ReservationCount`, `CreatedDate`, `SatisfactionRating`, `LastReservationDate`) VALUES
(1, 'Maria', 'Santos', 'maria@gmail.com', 'Maria123', '2025-04-30 10:15:00', 3, '09171234567', 'Blk 4 Lot 2 Sampaguita St., Quezon City', 'Walk-in', 'Regular Customer', 12, '2025-04-30 10:15:00', 2, '2024-11-01 08:00:00', 4.50, '2025-03-15'),
(2, 'Jose', 'Reyes', NULL, NULL, '2025-04-28 14:30:00', 1, '09281234568', '123 Rizal Ave., Manila', 'Walk-in', 'Regular Customer', 8, '2025-04-28 14:30:00', 0, '2024-12-05 09:00:00', 4.00, NULL),
(3, 'Ana', 'Cruz', NULL, NULL, '2025-04-25 11:00:00', 5, '09391234569', '45 Mabini St., Caloocan City', 'Reservation', 'Regular Customer', 20, '2025-04-25 11:00:00', 4, '2024-10-10 07:30:00', 4.80, '2025-04-10'),
(4, 'Carlos', 'Garcia', NULL, NULL, '2025-04-20 09:45:00', 0, '09501234570', '78 Del Pilar St., Pasay City', 'Walk-in', 'New Customer', 3, '2025-04-20 09:45:00', 0, '2025-03-01 10:00:00', 3.50, NULL),
(5, 'Rosa', 'Mendoza', NULL, NULL, '2025-04-18 16:00:00', 2, '09611234571', '12 Luna St., Makati City', 'Reservation', 'Regular Customer', 15, '2025-04-18 16:00:00', 3, '2024-09-20 08:00:00', 4.20, '2025-04-01'),
(6, 'Miguel', 'Torres', NULL, NULL, '2025-04-15 12:30:00', 1, '09721234572', '99 Bonifacio St., Mandaluyong', 'Walk-in', 'New Customer', 5, '2025-04-15 12:30:00', 0, '2025-02-14 09:00:00', 3.80, NULL),
(7, 'Liza', 'Ramos', NULL, NULL, '2025-04-10 08:00:00', 4, '09831234573', '33 Aguinaldo Ave., Cavite City', 'Reservation', 'Regular Customer', 18, '2025-04-10 08:00:00', 5, '2024-08-30 07:00:00', 4.90, '2025-04-05'),
(8, 'Ramon', 'Flores', NULL, NULL, '2025-04-08 13:15:00', 0, '09941234574', '21 Katipunan Rd., Quezon City', 'Walk-in', 'New Customer', 2, '2025-04-08 13:15:00', 0, '2025-04-01 11:00:00', 0.00, NULL),
(9, 'Elena', 'Villanueva', NULL, NULL, '2025-04-05 17:45:00', 2, '09051234575', '55 Shaw Blvd., Mandaluyong', 'Walk-in', 'Regular Customer', 9, '2025-04-05 17:45:00', 1, '2024-11-11 08:30:00', 4.10, '2025-02-20'),
(10, 'Danilo', 'Aquino', NULL, NULL, '2025-03-30 10:00:00', 3, '09161234576', '8 EDSA, Pasig City', 'Reservation', 'Regular Customer', 11, '2025-03-30 10:00:00', 2, '2024-07-07 09:00:00', 4.60, '2025-03-20'),
(11, 'Patricia', 'Bautista', NULL, NULL, '2025-03-25 14:00:00', 1, '09271234577', '67 Taft Ave., Manila', 'Walk-in', 'New Customer', 4, '2025-03-25 14:00:00', 0, '2025-01-15 10:30:00', 3.70, NULL),
(12, 'Roberto', 'Dela Cruz', NULL, NULL, '2025-03-20 09:30:00', 0, '09381234578', '14 Commonwealth Ave., Quezon City', 'Walk-in', 'New Customer', 1, '2025-03-20 09:30:00', 0, '2025-03-15 08:00:00', 0.00, NULL),
(13, 'Gloria', 'Pascual', NULL, NULL, '2025-03-15 15:00:00', 6, '09491234579', '30 Aurora Blvd., Quezon City', 'Reservation', 'Regular Customer', 25, '2025-03-15 15:00:00', 6, '2024-06-01 07:30:00', 4.95, '2025-03-10'),
(14, 'Fernando', 'Castillo', NULL, NULL, '2025-03-10 11:00:00', 1, '09601234580', '77 España St., Sampaloc, Manila', 'Walk-in', 'Regular Customer', 7, '2025-03-10 11:00:00', 0, '2024-12-20 09:00:00', 4.30, NULL),
(15, 'Josephine', 'Navarro', NULL, NULL, '2025-03-05 08:45:00', 2, '09711234581', '5 Mayon St., Quezon City', 'Reservation', 'Regular Customer', 13, '2025-03-05 08:45:00', 3, '2024-05-18 08:00:00', 4.40, '2025-02-25'),
(16, 'Lebron', 'James', 'lebronjames@gmail.com', '$2y$12$OkDEzQrfzl7AjK92b4eJ2OX8fkY5TkMSdeuQBmOCudKfekjwvxSwm', NULL, 0, '09886545589', NULL, 'Reservation', 'New Customer', 0, '2026-05-07 15:33:10', 1, '2026-05-06 19:47:39', 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_feedback`
--

CREATE TABLE `customer_feedback` (
  `FeedbackID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ReservationID` int(11) DEFAULT NULL,
  `FeedbackType` enum('Order','Reservation','General') NOT NULL DEFAULT 'General',
  `OverallRating` int(11) NOT NULL,
  `FoodTasteRating` int(11) DEFAULT NULL,
  `PortionSizeRating` int(11) DEFAULT NULL,
  `ServiceRating` int(11) DEFAULT NULL,
  `AmbienceRating` int(11) DEFAULT NULL,
  `CleanlinessRating` int(11) DEFAULT NULL,
  `FoodTasteComment` text DEFAULT NULL,
  `PortionSizeComment` text DEFAULT NULL,
  `ServiceComment` text DEFAULT NULL,
  `AmbienceComment` text DEFAULT NULL,
  `CleanlinessComment` text DEFAULT NULL,
  `ReviewMessage` text DEFAULT NULL,
  `IsAnonymous` tinyint(1) NOT NULL DEFAULT 0,
  `Status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `CreatedDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_feedback`
--

INSERT INTO `customer_feedback` (`FeedbackID`, `CustomerID`, `OrderID`, `ReservationID`, `FeedbackType`, `OverallRating`, `FoodTasteRating`, `PortionSizeRating`, `ServiceRating`, `AmbienceRating`, `CleanlinessRating`, `FoodTasteComment`, `PortionSizeComment`, `ServiceComment`, `AmbienceComment`, `CleanlinessComment`, `ReviewMessage`, `IsAnonymous`, `Status`, `CreatedDate`) VALUES
(1, 16, NULL, NULL, 'General', 4, 4, NULL, NULL, NULL, NULL, 'Masarap', 'Malaki', 'Mabait', 'Maayos', 'Malinis', 'Ayos naman', 0, 'Pending', '2026-05-07 12:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `HireDate` date NOT NULL,
  `Position` varchar(50) NOT NULL,
  `MaritalStatus` enum('Single','Married','Separated','Divorced','Widowed') NOT NULL DEFAULT 'Single',
  `EmploymentStatus` enum('Active','On Leave','Resigned') NOT NULL DEFAULT 'Active',
  `EmploymentType` enum('Full-time','Part-time','Contract') NOT NULL DEFAULT 'Full-time',
  `EmergencyContact` varchar(100) DEFAULT NULL,
  `WorkShift` enum('Morning','Evening','Split') DEFAULT NULL,
  `Password` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `FirstName`, `LastName`, `Gender`, `DateOfBirth`, `ContactNumber`, `Email`, `Address`, `HireDate`, `Position`, `MaritalStatus`, `EmploymentStatus`, `EmploymentType`, `EmergencyContact`, `WorkShift`, `Password`) VALUES
(1, 'Kenneth', 'Villagen', 'Male', '1998-03-12', '09171110001', 'kenneth.villagen@djsburger.com', 'Blk 1 Lot 5 Bayan St., Quezon City', '2022-01-10', 'Administrator', 'Single', 'Active', 'Full-time', 'Ivan Villagen - 09171110099', 'Morning', 'fcf7bb6d546cfb82d2e55486984ae7a1862a666acb441e0cf8b4ed34a4fcf9d7'),
(2, 'Sarah', 'Manalo', 'Female', '2000-06-25', '09281110002', 'sarah.manalo@djsburger.com', '23 Laging Handa St., Quezon City', '2022-03-15', 'Cashier', 'Single', 'Active', 'Full-time', 'Mark Manalo - 09281110088', 'Morning', '466bca4d8b66572101f49654e233593eed067dab6665f92cc210b10e735fa702'),
(3, 'Jason', 'Ocampo', 'Male', '1999-11-08', '09391110003', 'jason.ocampo@djsburger.com', '10 Bohol Ave., Quezon City', '2022-05-20', 'Cook', 'Single', 'Active', 'Full-time', 'Linda Ocampo - 09391110077', 'Morning', '709734391c541b971f2ad0eb740e1b7f78bac597ca1f79e337aa4242ef5ff4ee'),
(4, 'Maricel', 'Espinosa', 'Female', '2001-02-14', '09501110004', 'maricel.espinosa@djsburger.com', '88 P. Tuazon Blvd., Cubao, Quezon City', '2023-01-05', 'Waitstaff', 'Single', 'Active', 'Part-time', 'Juan Espinosa - 09501110066', 'Evening', 'cd11eb3b18fe3b0a24512e5c5427cac14c8edbcbb1a73b95f6d3460f87b02e7f'),
(5, 'Ronaldo', 'Delos Reyes', 'Male', '1997-08-30', '09611110005', 'ronaldo.delosreyes@djsburger.com', '45 Gen. Luna St., Pasig City', '2021-11-01', 'Cook', 'Married', 'Active', 'Full-time', 'Cynthia Delos Reyes - 09611110055', 'Morning', '5845ba62af489d62c5152265a11ef0c86d8f808052026559c0d91b12470c545a'),
(6, 'Abegail', 'Fernandez', 'Female', '2002-05-19', '09721110006', 'abegail.fernandez@djsburger.com', '7 Kamias Rd., Quezon City', '2023-06-10', 'Waitstaff', 'Single', 'Active', 'Part-time', 'Raul Fernandez - 09721110044', 'Split', '2cda3316df576aa1f6d0bf133ee97e187008c30f123663873810142ed87ce915'),
(7, 'Marco', 'Lim', 'Male', '1995-12-01', '09831110007', 'marco.lim@djsburger.com', '200 Scout Tuason St., Quezon City', '2020-08-15', 'Supervisor', 'Married', 'Active', 'Full-time', 'Teresa Lim - 09831110033', 'Morning', 'ed2cc9268ca870703e5d80af70ff054a760e69acb1374abcaea763812c61268e'),
(8, 'Clarissa', 'Tan', 'Female', '2000-09-22', '09941110008', 'clarissa.tan@djsburger.com', '11 Maginhawa St., Quezon City', '2023-09-01', 'Cashier', 'Single', 'Active', 'Full-time', 'Benito Tan - 09941110022', 'Evening', 'd5dfd15fe64ba1ea4685b4b8d1202e87f3d0a3522fa72c245ec8e8877be8ab96'),
(9, 'Eduardo', 'Pascua', 'Male', '1996-04-17', '09051110009', 'eduardo.pascua@djsburger.com', '56 Maliksi St., Caloocan City', '2022-07-20', 'Delivery Rider', 'Single', 'On Leave', 'Full-time', 'Nena Pascua - 09051110011', 'Evening', 'c78e773792a37a331ff1f2bd2239fa345ec48c66b25e5a6b25b5e4265e4b8d3d'),
(10, 'Sophia', 'Aguilar', 'Female', '2003-01-30', '09161110010', 'sophia.aguilar@djsburger.com', '3 Sikatuna Village, Quezon City', '2024-02-01', 'Waitstaff', 'Single', 'Active', 'Part-time', 'Renato Aguilar - 09161110000', 'Split', '');

-- --------------------------------------------------------

--
-- Table structure for table `employeeattendance`
--

CREATE TABLE `employeeattendance` (
  `AttendanceID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `TimeIn` time DEFAULT NULL,
  `TimeOut` time DEFAULT NULL,
  `Status` enum('Present','Absent','Late','On Leave') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeeattendance`
--

INSERT INTO `employeeattendance` (`AttendanceID`, `EmployeeID`, `Date`, `TimeIn`, `TimeOut`, `Status`) VALUES
(1, 1, '2025-04-28', '08:02:00', '17:05:00', 'Present'),
(2, 2, '2025-04-28', '07:58:00', '17:00:00', 'Present'),
(3, 3, '2025-04-28', '08:10:00', '17:15:00', 'Present'),
(4, 4, '2025-04-28', '14:00:00', '22:00:00', 'Present'),
(5, 5, '2025-04-28', '08:00:00', '17:00:00', 'Present'),
(6, 6, '2025-04-28', '08:30:00', '17:00:00', 'Late'),
(7, 7, '2025-04-28', '07:55:00', '17:00:00', 'Present'),
(8, 8, '2025-04-28', '14:05:00', '22:00:00', 'Present'),
(9, 9, '2025-04-28', NULL, NULL, 'On Leave'),
(10, 10, '2025-04-28', '07:50:00', '14:30:00', 'Present'),
(11, 1, '2025-04-29', '08:01:00', '17:03:00', 'Present'),
(12, 2, '2025-04-29', '08:00:00', '17:00:00', 'Present'),
(13, 3, '2025-04-29', '08:05:00', '17:10:00', 'Present'),
(14, 4, '2025-04-29', '14:00:00', '22:00:00', 'Present'),
(15, 5, '2025-04-29', NULL, NULL, 'Absent'),
(16, 6, '2025-04-29', '08:00:00', '17:00:00', 'Present'),
(17, 7, '2025-04-29', '07:58:00', '17:00:00', 'Present'),
(18, 8, '2025-04-29', '14:00:00', '22:05:00', 'Present'),
(19, 9, '2025-04-29', NULL, NULL, 'On Leave'),
(20, 10, '2025-04-29', '09:00:00', '15:00:00', 'Late');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `InventoryID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `StockQuantity` int(11) NOT NULL DEFAULT 0,
  `ReorderLevel` int(11) NOT NULL DEFAULT 10,
  `UnitType` varchar(50) DEFAULT NULL,
  `LastRestockedDate` datetime DEFAULT NULL,
  `ExpirationDate` date DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `Remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`InventoryID`, `ProductID`, `StockQuantity`, `ReorderLevel`, `UnitType`, `LastRestockedDate`, `ExpirationDate`, `SupplierID`, `Remarks`) VALUES
(1, 1, 85, 20, 'piece', '2025-04-28 08:00:00', '2025-05-05', 1, NULL),
(2, 2, 60, 15, 'piece', '2025-04-28 08:00:00', '2025-05-05', 1, NULL),
(3, 3, 70, 20, 'piece', '2025-04-28 08:00:00', '2025-05-05', 1, 'Check spice sauce stock weekly'),
(4, 4, 55, 15, 'piece', '2025-04-27 08:00:00', '2025-05-04', 1, NULL),
(5, 5, 40, 10, 'piece', '2025-04-27 08:00:00', '2025-05-04', 1, NULL),
(6, 6, 75, 20, 'piece', '2025-04-28 08:00:00', '2025-05-05', 1, NULL),
(7, 7, 50, 15, 'pack', '2025-04-26 08:00:00', '2025-05-10', 3, NULL),
(8, 8, 30, 10, 'piece', '2025-04-26 08:00:00', '2025-05-08', 3, NULL),
(9, 9, 45, 15, 'piece', '2025-04-26 08:00:00', '2025-05-08', 3, NULL),
(10, 10, 25, 10, 'piece', '2025-04-25 08:00:00', '2025-05-03', 3, 'Made to order — do not pre-bake'),
(11, 11, 100, 30, 'scoop', '2025-04-25 08:00:00', '2025-05-15', 3, NULL),
(12, 12, 200, 50, 'liter', '2025-04-29 08:00:00', '2025-05-06', 4, 'Bottomless — monitor daily usage'),
(13, 13, 90, 25, 'liter', '2025-04-29 08:00:00', '2025-05-06', 4, NULL),
(14, 14, 60, 20, 'cup', '2025-04-28 08:00:00', '2025-05-04', 4, NULL),
(15, 15, 80, 20, 'piece', '2025-04-27 08:00:00', '2025-05-05', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(4) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`OrderDetailID`, `OrderID`, `ProductID`, `Quantity`, `UnitPrice`, `TotalPrice`) VALUES
(1, 1, 1, 2, 149.00, 298.00),
(2, 1, 12, 2, 69.00, 138.00),
(3, 1, 7, 1, 99.00, 99.00),
(4, 1, 10, 1, 89.00, 89.00),
(5, 2, 6, 1, 159.00, 159.00),
(6, 2, 13, 1, 59.00, 59.00),
(7, 3, 2, 2, 199.00, 398.00),
(8, 3, 12, 2, 69.00, 138.00),
(9, 3, 7, 1, 99.00, 99.00),
(10, 3, 8, 1, 89.00, 89.00),
(11, 3, 11, 1, 59.00, 59.00),
(12, 3, 13, 1, 59.00, 59.00),
(13, 4, 1, 1, 149.00, 149.00),
(14, 5, 3, 1, 169.00, 169.00),
(15, 5, 12, 2, 69.00, 138.00),
(16, 5, 9, 1, 79.00, 79.00),
(17, 6, 4, 1, 179.00, 179.00),
(18, 6, 13, 1, 59.00, 59.00),
(19, 7, 1, 1, 149.00, 149.00),
(20, 7, 6, 1, 159.00, 159.00),
(21, 7, 15, 1, 119.00, 119.00),
(22, 7, 12, 2, 69.00, 138.00),
(23, 7, 10, 1, 89.00, 89.00),
(24, 8, 2, 1, 199.00, 199.00),
(25, 8, 12, 1, 69.00, 69.00),
(26, 8, 10, 1, 89.00, 89.00),
(27, 9, 3, 1, 169.00, 169.00),
(28, 9, 13, 1, 59.00, 59.00),
(29, 10, 5, 1, 189.00, 189.00),
(30, 10, 7, 1, 99.00, 99.00),
(31, 10, 12, 2, 69.00, 138.00),
(32, 10, 11, 1, 59.00, 59.00),
(33, 11, 3, 1, 169.00, 169.00),
(34, 12, 1, 1, 149.00, 149.00),
(35, 12, 14, 1, 99.00, 99.00),
(36, 13, 2, 2, 199.00, 398.00),
(37, 13, 1, 2, 149.00, 298.00),
(38, 13, 15, 1, 119.00, 119.00),
(39, 13, 12, 3, 69.00, 207.00),
(40, 13, 7, 1, 99.00, 99.00),
(41, 14, 6, 1, 159.00, 159.00),
(42, 14, 13, 1, 59.00, 59.00),
(43, 15, 4, 1, 179.00, 179.00),
(44, 15, 12, 2, 69.00, 138.00),
(45, 15, 9, 1, 79.00, 79.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderType` enum('Dine-in','Take out') NOT NULL,
  `ReceiptNumber` varchar(20) DEFAULT NULL,
  `NumberOfDiners` int(3) DEFAULT NULL,
  `OrderDate` date NOT NULL,
  `OrderTime` time NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `ItemsOrderedCount` int(4) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `OrderStatus` enum('Preparing','Served','Completed','Cancelled') NOT NULL DEFAULT 'Preparing',
  `Remarks` text DEFAULT NULL,
  `OrderPriority` enum('Normal','Rush') NOT NULL DEFAULT 'Normal',
  `PreparationTimeEstimate` int(4) DEFAULT NULL,
  `SpecialRequestFlag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderType`, `ReceiptNumber`, `NumberOfDiners`, `OrderDate`, `OrderTime`, `EmployeeID`, `ItemsOrderedCount`, `TotalAmount`, `OrderStatus`, `Remarks`, `OrderPriority`, `PreparationTimeEstimate`, `SpecialRequestFlag`) VALUES
(1, 1, 'Dine-in', 'RCP-20250401-001', 3, '2025-04-01', '11:30:00', 2, 4, 556.00, 'Completed', NULL, 'Normal', 15, 0),
(2, 2, 'Take out', 'RCP-20250401-002', NULL, '2025-04-01', '12:00:00', 2, 2, 268.00, 'Completed', 'Extra sauce on the side', 'Normal', 10, 1),
(3, 3, 'Dine-in', 'RCP-20250402-001', 5, '2025-04-02', '13:15:00', 8, 6, 847.00, 'Completed', NULL, 'Normal', 20, 0),
(4, 4, 'Take out', 'RCP-20250403-001', NULL, '2025-04-03', '10:45:00', 2, 1, 149.00, 'Completed', NULL, 'Normal', 8, 0),
(5, 5, 'Dine-in', 'RCP-20250403-002', 2, '2025-04-03', '19:00:00', 8, 3, 417.00, 'Completed', 'No onions please', 'Normal', 12, 1),
(6, 6, 'Take out', 'RCP-20250405-001', NULL, '2025-04-05', '14:30:00', 2, 2, 288.00, 'Completed', NULL, 'Rush', 10, 0),
(7, 7, 'Dine-in', 'RCP-20250407-001', 4, '2025-04-07', '12:00:00', 8, 5, 685.00, 'Completed', NULL, 'Normal', 18, 0),
(8, 8, 'Dine-in', 'RCP-20250410-001', 2, '2025-04-10', '20:00:00', 8, 3, 407.00, 'Completed', 'Celebrate birthday — candle', 'Normal', 15, 1),
(9, 9, 'Take out', 'RCP-20250412-001', NULL, '2025-04-12', '09:30:00', 2, 2, 228.00, 'Completed', NULL, 'Normal', 8, 0),
(10, 10, 'Dine-in', 'RCP-20250415-001', 3, '2025-04-15', '18:30:00', 8, 4, 606.00, 'Completed', NULL, 'Normal', 15, 0),
(11, 11, 'Take out', 'RCP-20250418-001', NULL, '2025-04-18', '11:00:00', 2, 1, 169.00, 'Completed', NULL, 'Rush', 8, 0),
(12, 12, 'Dine-in', 'RCP-20250420-001', 1, '2025-04-20', '13:00:00', 8, 2, 248.00, 'Completed', NULL, 'Normal', 10, 0),
(13, 13, 'Dine-in', 'RCP-20250425-001', 6, '2025-04-25', '19:30:00', 8, 7, 978.00, 'Completed', 'Company dinner — split bill', 'Normal', 25, 1),
(14, NULL, 'Take out', 'RCP-20250428-001', NULL, '2025-04-28', '10:00:00', 2, 2, 268.00, 'Completed', NULL, 'Normal', 10, 0),
(15, 1, 'Dine-in', 'RCP-20250430-001', 2, '2025-04-30', '12:30:00', 8, 3, 407.00, 'Completed', NULL, 'Normal', 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `PaymentDate` datetime NOT NULL DEFAULT current_timestamp(),
  `PaymentMethod` enum('Cash','GCash') NOT NULL DEFAULT 'Cash',
  `PaymentStatus` enum('Pending','Completed','Refunded','Failed') NOT NULL DEFAULT 'Pending',
  `AmountPaid` decimal(10,2) NOT NULL,
  `PaymentSource` varchar(50) DEFAULT 'Website',
  `ProofOfPayment` varchar(255) DEFAULT NULL,
  `ReceiptFileName` varchar(255) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  `TransactionID` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PaymentID`, `OrderID`, `PaymentDate`, `PaymentMethod`, `PaymentStatus`, `AmountPaid`) VALUES
(1, 1, '2025-04-01 12:15:00', 'Cash', 'Completed', 556.00),
(2, 2, '2025-04-01 12:30:00', 'Cash', 'Completed', 268.00),
(3, 3, '2025-04-02 14:00:00', 'Cash', 'Completed', 847.00),
(4, 4, '2025-04-03 11:00:00', 'Cash', 'Completed', 149.00),
(5, 5, '2025-04-03 19:45:00', 'Cash', 'Completed', 417.00),
(6, 6, '2025-04-05 14:50:00', 'Cash', 'Completed', 288.00),
(7, 7, '2025-04-07 13:00:00', 'Cash', 'Completed', 685.00),
(8, 8, '2025-04-10 20:45:00', 'Cash', 'Completed', 407.00),
(9, 9, '2025-04-12 09:50:00', 'Cash', 'Completed', 228.00),
(10, 10, '2025-04-15 19:15:00', 'Cash', 'Completed', 606.00),
(11, 11, '2025-04-18 11:15:00', 'Cash', 'Completed', 169.00),
(12, 12, '2025-04-20 13:30:00', 'Cash', 'Completed', 248.00),
(13, 13, '2025-04-25 20:30:00', 'Cash', 'Completed', 978.00),
(14, 14, '2025-04-28 10:20:00', 'Cash', 'Completed', 268.00),
(15, 15, '2025-04-30 13:00:00', 'Cash', 'Completed', 407.00);

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `PayrollID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `PayPeriodStart` date NOT NULL,
  `PayPeriodEnd` date NOT NULL,
  `BaseSalary` decimal(10,2) NOT NULL,
  `OvertimePay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `NetPay` decimal(10,2) NOT NULL,
  `PaymentDate` date NOT NULL DEFAULT curdate(),
  `Status` enum('Paid','Unpaid','Pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`PayrollID`, `EmployeeID`, `PayPeriodStart`, `PayPeriodEnd`, `BaseSalary`, `OvertimePay`, `Deductions`, `NetPay`, `PaymentDate`, `Status`) VALUES
(1, 1, '2025-04-01', '2025-04-30', 25000.00, 500.00, 1500.00, 24000.00, '2025-04-30', 'Paid'),
(2, 2, '2025-04-01', '2025-04-30', 18000.00, 200.00, 1100.00, 17100.00, '2025-04-30', 'Paid'),
(3, 3, '2025-04-01', '2025-04-30', 20000.00, 750.00, 1300.00, 19450.00, '2025-04-30', 'Paid'),
(4, 4, '2025-04-01', '2025-04-30', 12000.00, 0.00, 800.00, 11200.00, '2025-04-30', 'Paid'),
(5, 5, '2025-04-01', '2025-04-30', 20000.00, 1000.00, 1300.00, 19700.00, '2025-04-30', 'Paid'),
(6, 6, '2025-04-01', '2025-04-30', 12000.00, 0.00, 800.00, 11200.00, '2025-04-30', 'Paid'),
(7, 7, '2025-04-01', '2025-04-30', 22000.00, 300.00, 1400.00, 20900.00, '2025-04-30', 'Paid'),
(8, 8, '2025-04-01', '2025-04-30', 18000.00, 0.00, 1100.00, 16900.00, '2025-04-30', 'Paid'),
(9, 9, '2025-04-01', '2025-04-30', 18000.00, 0.00, 1100.00, 16900.00, '2025-04-30', 'Pending'),
(10, 10, '2025-04-01', '2025-04-30', 12000.00, 0.00, 800.00, 11200.00, '2025-04-30', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Category` enum('Appetizer','Main','Dessert','Drink') NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Availability` enum('Available','Not Available') NOT NULL DEFAULT 'Available',
  `ServingSize` enum('Regular','Large') DEFAULT NULL,
  `DateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  `LastUpdated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Notes` varchar(255) DEFAULT NULL,
  `PopularityTag` enum('Best Seller','Regular') NOT NULL DEFAULT 'Regular',
  `MealTime` enum('Breakfast','Lunch','Dinner','All Day') DEFAULT NULL,
  `OrderCount` int(10) NOT NULL DEFAULT 0,
  `Image` varchar(255) DEFAULT NULL,
  `SpicyLevel` enum('Mild','Medium','Hot','None') NOT NULL DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Category`, `Description`, `Price`, `Availability`, `ServingSize`, `DateAdded`, `LastUpdated`, `Notes`, `PopularityTag`, `MealTime`, `OrderCount`, `Image`, `SpicyLevel`) VALUES
(1, 'DJ Classic Burger', 'Main', 'Juicy beef patty with lettuce, tomato, and house sauce', 149.00, 'Available', 'Regular', '2024-01-10 08:00:00', '2026-05-06 16:18:25', NULL, 'Best Seller', 'All Day', 320, 'djclassic.jpg', 'None'),
(2, 'Double Smash Burger', 'Main', 'Two smashed beef patties with cheddar and caramelized onions', 199.00, 'Available', 'Large', '2024-01-10 08:00:00', '2026-05-06 16:18:25', 'Customer favorite', 'Best Seller', 'All Day', 210, 'doublesmash.jpg', 'None'),
(3, 'Spicy Inferno Burger', 'Main', 'Crispy fried chicken with ghost pepper sauce', 169.00, 'Available', 'Regular', '2024-01-15 08:00:00', '2026-05-06 16:18:25', 'Very spicy — diner advisory', 'Best Seller', 'All Day', 180, 'spicyinferno.jpg', 'Hot'),
(4, 'BBQ Bacon Burger', 'Main', 'Beef patty with crispy bacon, BBQ sauce, and pickles', 179.00, 'Available', 'Regular', '2024-01-15 08:00:00', '2026-05-06 16:18:25', NULL, 'Regular', 'Lunch', 145, 'bbqbacon.jpg', 'Mild'),
(5, 'Mushroom Swiss Burger', 'Main', 'Beef patty topped with sautéed mushrooms and Swiss cheese', 189.00, 'Available', 'Regular', '2024-02-01 08:00:00', '2026-05-06 16:18:25', NULL, 'Regular', 'All Day', 90, 'mushroomswiss.jpg', 'None'),
(6, 'Crispy Chicken Sandwich', 'Main', 'Crispy fried chicken breast in a toasted brioche bun', 159.00, 'Available', 'Regular', '2024-02-01 08:00:00', '2026-05-06 16:18:25', NULL, 'Best Seller', 'All Day', 260, 'crispychicken.jpg', 'None'),
(7, 'Loaded Nachos', 'Appetizer', 'Tortilla chips topped with cheese sauce, jalapeños, and salsa', 99.00, 'Available', 'Regular', '2024-02-10 08:00:00', '2026-05-06 16:18:25', 'Good for sharing', 'Best Seller', 'All Day', 195, 'loadednachos.jpg', 'Medium'),
(8, 'Mozzarella Sticks', 'Appetizer', 'Fried mozzarella sticks served with marinara dipping sauce', 89.00, 'Available', 'Regular', '2024-02-10 08:00:00', '2026-05-06 16:18:25', NULL, 'Regular', 'All Day', 120, 'mozzarella.jpg', 'None'),
(9, 'Onion Rings', 'Appetizer', 'Golden fried onion rings with ranch dipping sauce', 79.00, 'Available', 'Regular', '2024-02-15 08:00:00', '2026-05-06 16:18:25', NULL, 'Regular', 'All Day', 98, 'onionrings.jpg', 'None'),
(10, 'Chocolate Lava Cake', 'Dessert', 'Warm chocolate cake with molten center, served with ice cream', 89.00, 'Available', 'Regular', '2024-03-01 08:00:00', '2026-05-06 16:18:25', 'Made to order — 10 min wait', 'Best Seller', 'All Day', 150, 'lavacake.jpg', 'None'),
(11, 'Vanilla Ice Cream', 'Dessert', 'Two scoops of creamy vanilla ice cream', 59.00, 'Available', 'Regular', '2024-03-01 08:00:00', '2026-05-06 16:18:25', NULL, 'Regular', 'All Day', 80, 'icecream.jpg', 'None'),
(12, 'Bottomless Iced Tea', 'Drink', 'Bottomless brewed iced tea — lemon or peach flavor', 69.00, 'Available', 'Large', '2024-01-10 08:00:00', '2026-05-06 16:18:25', 'Bottomless for dine-in only', 'Best Seller', 'All Day', 410, 'icedtea.jpg', 'None'),
(13, 'Fresh Lemonade', 'Drink', 'Freshly squeezed lemonade with a hint of mint', 59.00, 'Available', 'Regular', '2024-01-10 08:00:00', '2026-05-06 16:18:25', NULL, 'Regular', 'All Day', 175, 'lemonade.jpg', 'None'),
(14, 'Salted Caramel Shake', 'Drink', 'Thick milkshake with salted caramel drizzle', 99.00, 'Available', 'Large', '2024-03-15 08:00:00', '2026-05-06 16:18:25', NULL, 'Regular', 'All Day', 110, 'caramelshake.jpg', 'None'),
(15, 'Buffalo Wings (6pcs)', 'Appetizer', '6-piece crispy chicken wings tossed in buffalo sauce', 119.00, 'Available', 'Regular', '2024-04-01 08:00:00', '2026-05-06 16:18:25', NULL, 'Best Seller', 'All Day', 230, 'buffalowings.jpg', 'Medium');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `ReservationID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `ReservationCode` varchar(20) DEFAULT NULL,
  `ReservationDate` date NOT NULL,
  `ReservationTime` time NOT NULL,
  `NumberOfCustomers` int(4) NOT NULL,
  `SpecialRequests` text DEFAULT NULL,
  `ReservationStatus` enum('Pending','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Pending',
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `AssignedStaffID` int(11) DEFAULT NULL,
  `Notes` varchar(255) DEFAULT NULL,
  `SeatType` enum('Indoor','Outdoor') NOT NULL,
  `UpdatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `TableNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`ReservationID`, `CustomerID`, `ReservationCode`, `ReservationDate`, `ReservationTime`, `NumberOfCustomers`, `SpecialRequests`, `ReservationStatus`, `CreatedAt`, `AssignedStaffID`, `Notes`, `SeatType`, `UpdatedAt`, `TableNumber`) VALUES
(1, 3, 'RES-2025-0001', '2025-05-10', '12:00:00', 30, 'Vegetarian option for 2 guests', 'Confirmed', '2025-04-20 09:00:00', 4, 'Deposit received — ₱2,000', 'Indoor', '2025-04-22 10:00:00', 'T-01'),
(2, 5, 'RES-2025-0002', '2025-05-15', '18:00:00', 50, 'Birthday celebration — request for cake table', 'Confirmed', '2025-04-22 10:30:00', 6, 'Coordinate with florist for table setup', 'Indoor', '2025-04-23 08:00:00', 'T-02'),
(3, 7, 'RES-2025-0003', '2025-05-18', '11:00:00', 15, NULL, 'Confirmed', '2025-04-25 14:00:00', 4, NULL, 'Outdoor', '2025-04-25 14:00:00', 'T-05'),
(4, 10, 'RES-2025-0004', '2025-05-20', '19:30:00', 20, 'Allergy note: 2 guests are lactose intolerant', 'Pending', '2025-04-27 11:00:00', NULL, 'Awaiting staff assignment', 'Indoor', '2025-04-27 11:00:00', NULL),
(5, 13, 'RES-2025-0005', '2025-05-22', '12:00:00', 60, 'Company anniversary — need projector setup area', 'Confirmed', '2025-04-28 08:00:00', 7, 'VIP guests — prepare extra seating', 'Indoor', '2025-04-29 09:00:00', 'T-01'),
(6, 15, 'RES-2025-0006', '2025-05-25', '17:00:00', 10, 'Request for outdoor garden area', 'Confirmed', '2025-04-28 15:00:00', 6, NULL, 'Outdoor', '2025-04-28 15:00:00', 'T-06'),
(7, 1, 'RES-2025-0007', '2025-06-01', '13:00:00', 8, NULL, 'Pending', '2025-04-30 10:00:00', NULL, NULL, 'Indoor', '2025-04-30 10:00:00', NULL),
(8, 9, 'RES-2025-0008', '2025-06-05', '18:30:00', 25, 'Debut celebration — request for photo wall backdrop', 'Pending', '2025-04-30 11:00:00', NULL, 'Customer will visit for Ocular', 'Indoor', '2025-04-30 11:00:00', NULL),
(9, 4, 'RES-2025-0009', '2025-04-15', '12:00:00', 5, NULL, 'Completed', '2025-04-10 09:00:00', 4, NULL, 'Outdoor', '2025-04-15 14:00:00', 'T-04'),
(10, 2, 'RES-2025-0010', '2025-04-20', '19:00:00', 12, 'Need separate table for kids', 'Cancelled', '2025-04-12 10:00:00', 6, 'Cancelled by customer — full refund', 'Indoor', '2025-04-14 09:00:00', NULL),
(11, 16, NULL, '2026-05-07', '10:00:00', 50, 'No Spice', 'Pending', '2026-05-07 15:33:10', NULL, 'Event: birthday | Phone: 09886545589 | Delivery: Pickup | Address: ', 'Indoor', '2026-05-07 15:33:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservationpayment`
--

CREATE TABLE `reservationpayment` (
  `ReservationPaymentID` int(11) NOT NULL,
  `ReservationID` int(11) NOT NULL,
  `PaymentDate` datetime NOT NULL DEFAULT current_timestamp(),
  `PaymentMethod` enum('Cash') NOT NULL DEFAULT 'Cash',
  `PaymentStatus` enum('Pending','Completed','Refunded','Failed') NOT NULL DEFAULT 'Pending',
  `AmountPaid` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservationpayment`
--

INSERT INTO `reservationpayment` (`ReservationPaymentID`, `ReservationID`, `PaymentDate`, `PaymentMethod`, `PaymentStatus`, `AmountPaid`) VALUES
(1, 1, '2025-04-20 09:30:00', 'Cash', 'Completed', 2000.00),
(2, 2, '2025-04-22 11:00:00', 'Cash', 'Completed', 3000.00),
(3, 3, '2025-04-25 14:30:00', 'Cash', 'Completed', 1500.00),
(4, 5, '2025-04-28 08:30:00', 'Cash', 'Completed', 5000.00),
(5, 6, '2025-04-28 15:30:00', 'Cash', 'Completed', 1000.00),
(6, 9, '2025-04-10 09:15:00', 'Cash', 'Completed', 750.00),
(7, 10, '2025-04-12 10:30:00', 'Cash', 'Refunded', 500.00),
(8, 7, '2025-04-30 10:30:00', 'Cash', 'Pending', 0.00),
(9, 11, '2026-05-07 15:33:10', 'Cash', 'Pending', 368.00);

-- --------------------------------------------------------

--
-- Table structure for table `reservation_items`
--

CREATE TABLE `reservation_items` (
  `ReservationItemID` int(11) NOT NULL,
  `ReservationID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation_items`
--

INSERT INTO `reservation_items` (`ReservationItemID`, `ReservationID`, `ProductID`, `ProductName`, `Quantity`, `UnitPrice`, `TotalPrice`) VALUES
(1, 11, 2, 'Double Smash Burger', 1, 199.00, 199.00),
(2, 11, 3, 'Spicy Inferno Burger', 1, 169.00, 169.00);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SupplierID` int(11) NOT NULL,
  `SupplierName` varchar(100) NOT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SupplierID`, `SupplierName`, `ContactNumber`, `Email`) VALUES
(1, 'Prime Meat Distributors', '02-8123-4567', 'sales@primemeat.ph'),
(2, 'Fresh Bun Bakery Supply', '02-8234-5678', 'orders@freshbun.ph'),
(3, 'QC Veggie & Produce', '02-8345-6789', 'supply@qcveggie.ph'),
(4, 'Beverages Central Corp.', '02-8456-7890', 'info@beveragescentral.ph'),
(5, 'Golden Fryer Ingredients', '02-8567-8901', 'orders@goldenfryer.ph');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `fk_feedback_customer` (`CustomerID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `uq_employee_email` (`Email`);

--
-- Indexes for table `employeeattendance`
--
ALTER TABLE `employeeattendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `fk_attendance_employee` (`EmployeeID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`InventoryID`),
  ADD KEY `fk_inventory_product` (`ProductID`),
  ADD KEY `fk_inventory_supplier` (`SupplierID`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `fk_orderdetails_order` (`OrderID`),
  ADD KEY `fk_orderdetails_product` (`ProductID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD UNIQUE KEY `uq_orders_receipt` (`ReceiptNumber`),
  ADD KEY `fk_orders_customer` (`CustomerID`),
  ADD KEY `fk_orders_employee` (`EmployeeID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `fk_payment_order` (`OrderID`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`PayrollID`),
  ADD KEY `fk_payroll_employee` (`EmployeeID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ReservationID`),
  ADD UNIQUE KEY `uq_reservation_code` (`ReservationCode`),
  ADD KEY `fk_reservation_customer` (`CustomerID`),
  ADD KEY `fk_reservation_staff` (`AssignedStaffID`);

--
-- Indexes for table `reservationpayment`
--
ALTER TABLE `reservationpayment`
  ADD PRIMARY KEY (`ReservationPaymentID`),
  ADD KEY `fk_respayment_reservation` (`ReservationID`);

--
-- Indexes for table `reservation_items`
--
ALTER TABLE `reservation_items`
  ADD PRIMARY KEY (`ReservationItemID`),
  ADD KEY `fk_resitems_reservation` (`ReservationID`),
  ADD KEY `fk_resitems_product` (`ProductID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SupplierID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employeeattendance`
--
ALTER TABLE `employeeattendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `InventoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `PayrollID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservationpayment`
--
ALTER TABLE `reservationpayment`
  MODIFY `ReservationPaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reservation_items`
--
ALTER TABLE `reservation_items`
  MODIFY `ReservationItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD CONSTRAINT `fk_feedback_customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE;

--
-- Constraints for table `employeeattendance`
--
ALTER TABLE `employeeattendance`
  ADD CONSTRAINT `fk_attendance_employee` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_product` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`),
  ADD CONSTRAINT `fk_inventory_supplier` FOREIGN KEY (`SupplierID`) REFERENCES `supplier` (`SupplierID`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `fk_orderdetails_order` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `fk_orderdetails_product` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `fk_orders_employee` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `fk_payroll_employee` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `fk_reservation_staff` FOREIGN KEY (`AssignedStaffID`) REFERENCES `employee` (`EmployeeID`);

--
-- Constraints for table `reservationpayment`
--
ALTER TABLE `reservationpayment`
  ADD CONSTRAINT `fk_respayment_reservation` FOREIGN KEY (`ReservationID`) REFERENCES `reservation` (`ReservationID`);

--
-- Constraints for table `reservation_items`
--
ALTER TABLE `reservation_items`
  ADD CONSTRAINT `fk_resitems_product` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_resitems_reservation` FOREIGN KEY (`ReservationID`) REFERENCES `reservation` (`ReservationID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
