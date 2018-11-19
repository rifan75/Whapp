-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 19, 2018 at 09:24 AM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.1.20-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warehouse`
--

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', NULL, NULL),
(2, 'Admin', NULL, NULL),
(3, 'Manager', NULL, NULL),
(4, 'Staff', NULL, NULL);


--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `level`, `picture_path`, `active`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@admin.com', NULL, '1', 'users/images/picture.jpg', 1, '$2y$10$zAgAKIKJU/b6K97c.IiViOx8nobt.AueK5xDeJYHNJqFD5.J8VDFm', 'VjZfUiKJpVfAcC6kTIVqQKKvkxpzcvla2delbaDUfEvCY8pZaa5qOWuqCyo4', NULL, NULL, NULL),
(3, 'jekidut', 'jeki@gmail.com', NULL, '3', 'users/images/h3EzqSx1AHT_school_reunion.jpg', 1, '$2y$10$dUTyTd2I8j6.KuhWzjQVpuFK7dJobQpBzjwCIqY66bRsSbVrYtPbe', NULL, NULL, '2018-11-11 23:06:00', '2018-11-14 20:59:40'),
(4, 'luna', 'luba@gmail.com', NULL, '2', 'users/images/22D3ljRjywy_house.jpg', 1, '$2y$10$fTnF5gAuvQy/rRsHdam42utRvDzVD1wvK/RVN4BO4c9DaSKiyS1wO', NULL, NULL, '2018-11-12 14:29:27', '2018-11-14 21:01:35');

--
-- Dumping data for table `user_detail`
--

INSERT INTO `user_detail` (`id`, `user_id`, `hire_date`, `birth_date`, `gender`, `address`, `city`, `state`, `country`, `pos_code`, `phone`, `recorder`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL),
(2, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL),
(3, 4, '2018-11-19', '2000-11-19', 'Female', 'test', 'test', 'test', 'Antigua & Barbuda', '12544', '25466', 1, NULL, NULL, '2018-11-18 18:23:52');

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `address`, `city`, `state`, `country`, `pos_code`, `phone`, `email`, `contact_person`, `note`, `user_id`, `active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Toko Jaya', 'condet kampung tengah', 'jakarta timur', 'jakarta', 'Indonesia', '15234', '254887', 'test@gmail.com', 'jeki', 'test aja', 1, 1, NULL, '2018-11-09 19:40:35', '2018-11-09 20:27:05'),
(2, 'Toko aneh', 'suradita cisauk', 'tangerang', 'banten', 'Indonesia', '15224', '125468', 'test@gmail.com', 'luna', 'aneh aja', 1, 1, NULL, '2018-11-09 19:57:45', '2018-11-09 20:27:35'),
(3, 'Toko Apa Daya', 'condet batuampar', 'jakarta timur', 'jakarta', 'Indonesia', '15234', '254887', 'test@gmail.com', 'jeki', 'test', 1, 1, NULL, '2018-11-09 20:13:54', '2018-11-09 20:13:54');


--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `name`, `code`, `address`, `city`, `state`, `country`, `pos_code`, `phone`, `email`, `incharge`, `note`, `user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Warehouse', 'WH1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, 1, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
