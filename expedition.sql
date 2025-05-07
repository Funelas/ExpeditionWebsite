-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 05:29 AM
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
-- Database: `expedition`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `name`, `email`, `address`, `password`, `account_type`) VALUES
(3, 'admin', 'admin', '', 'admin', 1),
(4, '123', 'shokohen@email.com', '1111 triet St.', 'shoko', 2),
(6, 'Macky', 'macky@sushi.com', '1239 Jahoo St.', 'woof', 2),
(7, 'Nana', 'na@na.com', 'nanananana', 'aha', 2),
(8, 'Nana', 'na@nana.com', 'nanananana', 'aha', 2),
(9, 'Nanana', 'nana@nana.com', 'nananannana', 'nana', 2),
(10, 'nananananan', 'nana@nananana.com', 'ananananana', 'nana', 2),
(11, 'nananananan', 'nana@nanananana.com', 'ananananana', 'nana', 2),
(12, 'nananananan', 'nana@nananananana.com', 'ananananana', 'nana', 2),
(13, 'nananananan', 'nana@nanananananana.com', 'ananananana', 'nana', 2),
(15, 'HAHHAHA', 'haha@haha.com', 'haha haha', 'haha', 2),
(17, 'Aqira', 'aqira.sheek@email.com', '123 Mori St.', 'Mori', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `account_id`, `product_id`, `product_name`, `price`, `quantity`, `product_img`) VALUES
(0, 2, 5, 'Expedition DirtBlaze XT', 25799, 1, '../images/MountainBike5.jpg'),
(0, 17, 3, 'Expedition TrailCrusher 29er', 27899, 1, '../images/MountainBike3.jpg'),
(0, 19, 2, 'Expedition RockMaster X', 22199, 1, '../images/MountainBike2.jpg'),
(0, 6, 3, 'Expedition TrailCrusher 29er', 27899, 1, '../images/MountainBike3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `stock`, `description`, `price`, `category`, `image`) VALUES
(1, 'Expedition MountainRunner Pro', 4, 'Features a lightweight alloy frame designed for agility on steep trails. Equipped with 21-speed Shimano gears for precise control. Dual hydraulic disc brakes ensure reliable stopping power.', 1, 'Mountain', '../images/MountainBike1.jpg'),
(2, 'Expedition RockMaster X', 3, 'Built with a reinforced steel frame for maximum durability. Front suspension absorbs shocks on rocky paths. Wide, knobby tires enhance traction on uneven terrain.', 22199, 'Mountain', '../images/MountainBike2.jpg'),
(3, 'Expedition TrailCrusher 29er', 3, 'Equipped with 29-inch wheels for improved stability and obstacle clearance. The hydraulic disc brakes offer consistent stopping power in wet and dry conditions. Designed for rugged, long-distance rides.', 27899, 'Mountain', '../images/MountainBike3.jpg'),
(4, 'Expedition ClimbKing Elite', 2, 'Full suspension system for superior shock absorption on downhill slopes. The 24-speed drivetrain offers versatility for climbing and sprinting. Designed to conquer rough mountain paths.', 30499, 'Mountain', '../images/MountainBike4.jpg'),
(5, 'Expedition DirtBlaze XT', 3, 'Rugged aluminum frame built to withstand tough off-road conditions. Mechanical disc brakes offer dependable braking on loose and muddy surfaces. Oversized tires ensure maximum grip on challenging trails.', 25799, 'Mountain', '../images/MountainBike5.jpg'),
(6, 'Expedition PeakRider Pro', 2, 'High-performance carbon fiber frame for lightweight endurance. Precision Shimano shifters ensure smooth gear changes on steep climbs. Ideal for both aggressive trail riding and technical ascents.', 33299, 'Mountain', '../images/MountainBike6.jpg'),
(7, 'Expedition Speedster Aero', 4, 'Aerodynamic carbon frame designed to reduce drag. Fitted with lightweight alloy wheels for fast acceleration. Shimano 18-speed drivetrain for smooth shifting on flat roads.', 38499, 'Road', '../images/RoadBike1.jpg'),
(8, 'Expedition RoadRacer Pro', 2, 'Ultra-light aluminum frame for speed and agility. Drop handlebars enhance aerodynamics and comfort during long rides. Equipped with precision caliper brakes for controlled deceleration.', 31999, 'Road', '../images/RoadBike2.jpg'),
(9, 'Expedition Endurance Glide', 4, 'Endurance geometry for long-distance comfort. Carbon fork absorbs vibrations from rough roads. Ergonomic saddle designed for extended riding sessions.', 35299, 'Road', '../images/RoadBike3.jpg'),
(10, 'Expedition SprintMaster Elite', 4, 'Designed for sprints with a stiff alloy frame and aggressive geometry. Shimano 22-speed groupset for rapid gear changes. Slick, narrow tires ensure minimal rolling resistance.', 42799, 'Road', '../images/RoadBike4.jpg'),
(11, 'Expedition UltraLight Racer', 1, 'Featherweight carbon construction optimized for racing. Internal cable routing for a sleek, aerodynamic look. High-pressure tires provide maximum speed on smooth surfaces.', 45999, 'Road', '../images/RoadBike5.jpg'),
(12, 'Expedition Commuter Roadster', 3, 'Compact geometry for urban commuting. Flat handlebars for improved maneuverability in traffic. Reflective sidewall tires enhance visibility during low-light rides.', 29499, 'Road', '../images/RoadBike6.jpg'),
(13, 'Expedition GravelMaster Pro', 3, 'Rugged alloy frame with endurance geometry for long gravel rides. Wide, all-terrain tires for stability on loose surfaces. Shimano GRX drivetrain for smooth shifting on mixed terrain.', 39499, 'Gravel', '../images/GravelBike1.jpg'),
(14, 'Expedition TrailGrinder X', 2, 'Carbon fork for vibration damping on rough roads. Flared drop handlebars for better control on descents. Tubeless-ready tires reduce punctures during off-road adventures.', 36899, 'Gravel', '../images/GravelBike2.jpg'),
(15, 'Expedition AllRoad Explorer', 5, 'Sturdy chromoly steel frame designed for loaded touring. Mounting points for racks and fenders for added versatility. Wide-range 2x10 gearing to tackle steep gravel climbs.', 34299, 'Gravel', '../images/GravelBike3.jpg'),
(16, 'Expedition AdventureCruiser', 5, 'Durable aluminum frame optimized for bikepacking. Ergonomic grips for enhanced comfort on long rides. Mechanical disc brakes for reliable stopping power on unpredictable terrain.', 31799, 'Gravel', '../images/GravelBike4.jpg'),
(17, 'Expedition GravelRider Elite', 3, 'Lightweight frame engineered for speed on gravel paths. Carbon seat post for added comfort on bumpy trails. 38mm tires for grip without sacrificing efficiency.', 42199, 'Gravel', '../images/GravelBike5.jpg'),
(23, 'Expedition TrailWanderer GX', 3, 'Versatile frame geometry suitable for both trail and road use. Gravel-specific handlebar for enhanced control on technical sections. Powerful hydraulic brakes for quick stops.', 40599, 'Gravel', '../images/GravelBike6.jpg'),
(26, 'Expedition AeroGuard Helmet', 13, 'Aerodynamic design with rear ventilation. Lightweight shell reduces wind resistance. Ideal for road cycling.', 3299, 'Accessory', '../images/helmet1.jpg'),
(27, 'Expedition TrailShield MTB Helmet', 15, 'Extended rear coverage for trail protection. Adjustable visor blocks sun and debris. Perfect for rugged off-road rides.', 2499, 'Accessory', '../images/helmet2.jpg'),
(28, 'Expedition CityRider Commuter Helmet', 5, ' Sleek design with built-in rear light for visibility. Removable padding for easy cleaning. Suitable for urban cyclists.', 1799, 'Accessory', '../images/helmet3.jpg'),
(29, 'Expedition GripMax Flat Pedals', 16, 'Wide platform with anti-slip pins for secure footing. Durable aluminum body for tough rides. Ideal for MTB and trail.', 1299, 'Accessory', '../images/pedal1.jpg'),
(30, 'Expedition RoadPro Clipless Pedals', 10, 'Lightweight composite body with easy clip-in system. SPD compatibility for efficient power transfer. Best for road bikes.', 2199, 'Accessory', '../images/pedal3.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
