SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `darklibrary` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `darklibrary`;

CREATE TABLE `Invitations` (
  `idInvitation` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `code` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Permissions` (
  `idPermission` int(11) NOT NULL,
  `description` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Settings` (
  `idOperationMode` int(11) NOT NULL,
  `approvalVote` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Settings` (`idOperationMode`, `approvalVote`) VALUES
(1, 1);

CREATE TABLE `Users` (
  `idUser` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `Invitations`
  ADD PRIMARY KEY (`idInvitation`),
  ADD KEY `idUser` (`idUser`);

ALTER TABLE `Permissions`
  ADD PRIMARY KEY (`idPermission`);

ALTER TABLE `Settings`
  ADD PRIMARY KEY (`idOperationMode`);

ALTER TABLE `Users`
  ADD PRIMARY KEY (`idUser`);


ALTER TABLE `Invitations`
  MODIFY `idInvitation` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Permissions`
  MODIFY `idPermission` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Settings`
  MODIFY `idOperationMode` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Invitations`
  ADD CONSTRAINT `fk_idUser_Users_Invitations` FOREIGN KEY (`idUser`) REFERENCES `Users` (`idUser`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
