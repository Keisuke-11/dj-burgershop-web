-- Run this script in phpMyAdmin or your MySQL client to update the burger_system database

-- 1. Add missing Email and Password columns to customer table for login functionality
ALTER TABLE `customer`
ADD COLUMN `Email` VARCHAR(100) NULL AFTER `LastName`,
ADD COLUMN `Password` VARCHAR(255) NULL AFTER `Email`;

-- 2. Drop the ProductsSelection column as you requested
ALTER TABLE `reservation`
DROP COLUMN `ProductsSelection`;

-- 3. Create the reservation_items table to store individual ordered products
CREATE TABLE `reservation_items` (
  `ReservationItemID` int(11) NOT NULL AUTO_INCREMENT,
  `ReservationID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ReservationItemID`),
  KEY `fk_resitems_reservation` (`ReservationID`),
  KEY `fk_resitems_product` (`ProductID`),
  CONSTRAINT `fk_resitems_reservation` FOREIGN KEY (`ReservationID`) REFERENCES `reservation` (`ReservationID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_resitems_product` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Ensure customer type matches the enum 'Reservation' and 'Walk-in'
-- Note: 'Online' was used in the previous codebase, mapping it to 'Reservation'
ALTER TABLE `customer` 
MODIFY COLUMN `CustomerType` ENUM('Walk-in', 'Reservation') DEFAULT 'Walk-in';

-- 5. Add Notes column if it doesn't exist to store extra info (Delivery, Event Type)
ALTER TABLE `reservation`
ADD COLUMN IF NOT EXISTS `Notes` TEXT NULL;
