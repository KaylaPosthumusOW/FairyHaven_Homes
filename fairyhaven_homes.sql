-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 09:15 AM
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
-- Database: `fairyhaven_homes`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `AgentId` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`AgentId`, `firstName`, `lastName`, `number`, `email`) VALUES
(1, 'Karen', 'Mitton', '092 435 6789', 'karen@gmail.com'),
(2, 'Mark', 'Nel', '097 432 1234', 'markNel@gmail.com'),
(3, 'Pieter', 'Bosman', '082 765 4265', 'pietB@gmail.com'),
(4, 'David', 'Brown', '076 892 7492', 'dbrown@gmail.com'),
(5, 'Michael', 'Phillips', '089 546 8769', 'mphillips@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `propType` enum('treehouse','flower-den','mountainside_cottage','underwater_cove','mushroom_house') DEFAULT NULL,
  `availableDate` date DEFAULT NULL,
  `totFloor` int(11) DEFAULT NULL,
  `floorSize` int(11) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `kitchens` int(11) DEFAULT NULL,
  `diningRooms` int(11) DEFAULT NULL,
  `basements` int(11) DEFAULT NULL,
  `wifi` tinyint(1) DEFAULT NULL,
  `airConditioning` tinyint(1) DEFAULT NULL,
  `floorHeating` tinyint(1) DEFAULT NULL,
  `pool` tinyint(1) DEFAULT NULL,
  `fitnessCentr` tinyint(1) DEFAULT NULL,
  `gardenServ` tinyint(1) DEFAULT NULL,
  `undercPark` tinyint(1) DEFAULT NULL,
  `gatedCom` tinyint(1) DEFAULT NULL,
  `forSaleRent` enum('sale','rent') DEFAULT NULL,
  `pricePm` decimal(10,2) DEFAULT NULL,
  `lotSize` int(11) DEFAULT NULL,
  `parkingSpace` int(11) DEFAULT NULL,
  `gardens` int(11) DEFAULT NULL,
  `patio` int(11) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `agentId` int(11) DEFAULT NULL,
  `streetAddress` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postalCode` varchar(20) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`id`, `title`, `description`, `propType`, `availableDate`, `totFloor`, `floorSize`, `bedrooms`, `bathrooms`, `kitchens`, `diningRooms`, `basements`, `wifi`, `airConditioning`, `floorHeating`, `pool`, `fitnessCentr`, `gardenServ`, `undercPark`, `gatedCom`, `forSaleRent`, `pricePm`, `lotSize`, `parkingSpace`, `gardens`, `patio`, `images`, `agentId`, `streetAddress`, `city`, `state`, `postalCode`, `status`) VALUES
(11, 'Enchanted Treehouse', 'Nestled high within the ancient limbs of a towering oak, this Enchanted Treehouse Retreat offers a perfect blend of magic and comfort. With its charming circular windows that glow warmly at dusk and an inviting wooden walkway, this home is the epitome of fairy-tale living.', 'treehouse', '2024-09-26', 2, 300, 2, 2, 1, 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 'sale', 23000000.00, 2000, 2, 4, 1, 'Fairy Tree House.png', 4, '1 Forrest Street', 'Centurion', 'Gauteng', '1324', 'approved'),
(12, 'Hidden Haven in the Rocks', 'Nestled within a serene rocky enclave, this charming fairy home is a testament to nature\'s beauty and the cozy allure of a life intertwined with the earth. With its moss-covered roof, rounded wooden door, and windows peeking out from a stone exterior, the house exudes warmth and mystery. The pathway leading to the entrance is softly lined with moss and pebbles, inviting visitors to imagine the magic within. ', 'mountainside_cottage', '2024-11-21', 1, 400, 2, 2, 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, 'sale', 21000000.00, 600, 1, 1, 1, 'Fairy home rocky.png', 2, '2 Rocky Street, mountain cove', 'Pilgrams Rest', 'Gauteng', '1765', 'approved'),
(13, 'Petal Perch', 'Perched gracefully within the delicate petals of a giant flower, this whimsical fairy home radiates a magical charm. The house, with its moss-covered roof adorned with colorful, blossom-like accents, blends seamlessly into its floral surroundings. The soft, pink petals cradle the structure, creating a serene and enchanting atmosphere. The curved wooden door and circular windows add to the cozy feel, while the vibrant miniature garden around the entrance brings the scene to life. ', 'flower-den', '2024-10-15', 2, 350, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 1, 0, 0, 'sale', 800000.00, 500, 1, 1, 1, 'Fairy home in a a flower.png', 2, '2 Daisy Lane', 'Kempton Park', 'Gauteng', '8798', 'approved'),
(15, 'Coral Reef Retreat', 'An enchanting underwater escape surrounded by vibrant coral reefs and exotic marine life. Enjoy panoramic views of the ocean floor from your glass-walled living room.', 'underwater_cove', '2024-11-12', 2, 500, 3, 2, 11, 1, 0, 1, 0, 0, 1, 0, 0, 0, 0, 'sale', 6400.00, 1000, 2, 0, 0, 'Firefly A fairy house underwater 51428.jpg', 1, '13 Doodles Street, Malongane ', 'Cape Town', 'Western-Cape', '4367', 'approved'),
(16, 'Blossom Bungalow', 'A charming and whimsical home nestled amidst a blooming garden, with colorful flowers and a cozy interior that seamlessly blends with nature.', 'flower-den', '2024-10-23', 1, 600, 2, 1, 1, 2, 1, 1, 1, 1, 0, 1, 1, 1, 0, 'sale', 12000500.00, 1100, 2, 5, 0, 'Firefly A fairy hous built in a dausy 63399.jpg', 2, '12 Daisy Street', 'Kimberley', 'Nothern-Cape', '8562', 'approved'),
(17, 'Summit Serenity', 'A cozy cottage perched high on the mountainside, offering breathtaking views of the valley below and a perfect blend of rustic charm and modern comfort.', 'mountainside_cottage', '2025-10-02', 3, 600, 4, 2, 1, 2, 1, 1, 1, 0, 0, 0, 1, 1, 1, 'sale', 5200.00, 1500, 2, 1, 0, 'Firefly A fairy house between rocks and mountains 51858.jpg', 1, '35 Sunnyside Street', 'Pilgrams Rest', 'Mpumalanga', '4561', 'approved'),
(18, 'Peak Paradise', 'A luxurious mountainside home featuring spacious living areas, floor-to-ceiling windows, and a wraparound deck to enjoy the stunning natural landscape.', 'mountainside_cottage', '2024-09-20', 3, 800, 5, 3, 2, 2, 0, 1, 0, 1, 0, 0, 1, 0, 1, 'sale', 32000000.00, 2100, 2, 3, 2, 'Firefly A 2 story fairy house between rocks and mountains 2236.jpg', 3, '22 Rocky Street', 'Sabie', 'Mpumalanga', '1260', 'approved'),
(19, 'Mycelium Manor', 'A charming and imaginative residence designed to look like a mushroom, complete with cozy, circular rooms and a picturesque garden with mushroom sculptures.', 'mushroom_house', '2024-09-28', 1, 550, 3, 2, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 0, 'sale', 4300.00, 1300, 2, 4, 1, 'Firefly A fairy house built in mushrooms 85266.jpg', 3, '5 Spore Crescent', 'Hogsback', 'Eastern Cape', '5721', 'approved'),
(20, 'Woodland Loft', 'A charming and modern treehouse with a sleek design, offering stunning views of the surrounding forest and a cozy, elevated living space.', 'treehouse', '2024-09-20', 2, 450, 4, 3, 2, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, 'sale', 23000000.00, 600, 0, 0, 0, 'Firefly A fairy house built in a tree 81368.jpg', NULL, '1 Forest Path', 'Dullstroom', 'Mpumalanga', '1110', 'pending'),
(22, 'Mushroom Red', 'Beautiful mushroom house with a big open forrest around you', 'mushroom_house', '2025-01-16', 2, 4500, 2, 2, 1, 1, 0, 1, 0, 1, 1, 0, 1, 0, 0, 'sale', 22000000.00, 1200, 1, 4, 0, 'Firefly A fairy house built in mushrooms 2236.jpg', NULL, '13 Apple Street', 'Kempton Park', 'Gauteng', '1619', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userType` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserId`, `firstname`, `surname`, `email`, `password`, `userType`) VALUES
(1, 'Milla', 'Muller', 'milla@gmail.com', '$2y$10$fhwJVXXSizT2iQMBwMJbuudGhf50vBVqtOsOmi1xmEq78/EAsBelC', 'user'),
(3, 'Kayla', 'Posthumus', 'kaylaposthu@gmail.com', '$2y$10$6RPhPxdKo9EgHx/fvYQ3JeUT58em.Aa2l69AbkjAW1mDeVWa8jdFi', 'admin'),
(7, 'Belinda', 'Mars', 'bmars@gmail.com', '$2y$10$wwxtpfAF39My6ARmF0qBOO6hdAKS8kpDxaplaJILw1qNLYghCws.m', 'user'),
(8, 'Elmarie', 'Grobler', 'elmarie@gmail.com', '$2y$10$5TaPgtP8FuuwfoT0BotYgeYAThTcStrGYKvrtWyvx/l2lWGx/CcwK', 'user'),
(9, 'Sonja', 'Posthumus', 'sonja@gmail.com', '$2y$10$PQU/cdNT/d7cgvza/mP7yuiMgB4Fd2P5aoEaLRlP1AHcQdxiF6OJS', 'user'),
(10, 'Deon', 'Castle', 'deon@levego.co.za', '$2y$10$/ok.6Q4H9r0VCHFkVaHIqelHYgKlzqvj41xaHwXJ1T0mI2TSuZbkm', 'user'),
(11, 'Matthew', 'Pretorius', 'pretoriusm@fhhadmin.co.za', '$2y$10$SberWdt00VbTK9htAbKoKeNWIWPEUBoSjIrlVBo3zXNzgCRfp3HYe', 'admin'),
(14, 'Tsungai', 'Katsuro', 'tsungai@gmail.com', '$2y$10$YZMhaqIC9uGjuXbVeu7aoeLKPtaEDUHKieY2XjoRTxec/.Fyk7gIa', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlistId` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `listingId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlistId`, `UserId`, `listingId`) VALUES
(13, 1, 11),
(38, 9, 19),
(41, 8, 13),
(43, 1, 17),
(44, 9, 13);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`AgentId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlistId`),
  ADD KEY `fk_user` (`UserId`),
  ADD KEY `fk_listing` (`listingId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `AgentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlistId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_listing` FOREIGN KEY (`listingId`) REFERENCES `listing` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
