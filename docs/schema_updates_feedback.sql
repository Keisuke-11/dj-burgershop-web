-- Run this script to add the missing customer_feedback table

CREATE TABLE `customer_feedback` (
  `FeedbackID` int(11) NOT NULL AUTO_INCREMENT,
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
  `CreatedDate` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`FeedbackID`),
  KEY `fk_feedback_customer` (`CustomerID`),
  CONSTRAINT `fk_feedback_customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
