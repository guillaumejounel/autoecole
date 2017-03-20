SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"£
SET time_zone = "+00:00"£


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */£
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */£
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */£
/*!40101 SET NAMES utf8mb4 */£


DROP TABLE `eleves`£

CREATE TABLE `eleves` (
  `ideleve` int(11) NOT NULL,
  `nom` varchar(30) CHARACTER SET utf8 NOT NULL,
  `prenom` varchar(30) CHARACTER SET utf8 NOT NULL,
  `dateNaiss` date NOT NULL,
  `dateInscription` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8£

TRUNCATE TABLE `eleves`£

INSERT INTO `eleves` (`ideleve`, `nom`, `prenom`, `dateNaiss`, `dateInscription`) VALUES
(90, 'Skywalker', 'Anakin', '1946-12-20', '2015-12-01'),
(91, 'Fett', 'Boba', '1978-05-21', '2015-12-02'),
(92, 'Force', 'Yoda', '1903-01-23', '2015-12-05'),
(93, 'Skywalker', 'Luke', '1980-03-12', '2015-12-06'),
(94, 'Solo', 'Han', '1956-06-16', '2015-12-10'),
(95, 'Organa', 'Leia', '1983-07-20', '2015-12-12'),
(96, 'D2', 'R2', '1992-08-09', '2015-12-13'),
(97, 'Dooku', 'Comte', '1979-10-05', '2015-12-15'),
(98, 'Maul', 'Dark', '1986-11-09', '2015-12-17'),
(99, 'Kenobi', 'Obi-Wan', '1963-04-02', '2015-12-20'),
(100, 'Le Hutt', 'Jabba', '1900-05-01', '2015-12-21'),
(101, 'Binks', 'Jar Jar', '1990-08-06', '2015-12-24'),
(102, 'Fett', 'Jango', '1978-04-24', '2015-12-25'),
(103, 'Jinn', 'Qui-Gon', '1977-02-06', '2015-12-27'),
(104, 'Rrwah', 'Chewbacca', '1995-05-20', '2015-12-30'),
(105, 'Vador', 'Dark', '1946-12-20', '2016-01-01'),
(107, 'Motti', 'GÃ©nÃ©ral', '1956-04-20', '2016-01-01'),
(108, 'Skywalker', 'Shmi', '1970-01-04', '2016-01-01'),
(109, 'Lars', 'Owen', '1956-07-05', '2016-01-01'),
(110, 'Hux', 'GÃ©nÃ©ral', '1985-05-04', '2016-01-01'),
(111, 'Gunray', 'Nute', '1946-01-04', '2016-01-01'),
(112, 'Dameron', 'Poe', '1987-06-20', '2016-01-01'),
(113, 'Ren', 'Kylo', '1989-01-24', '2016-01-01')£

DROP TABLE `inscription`£

CREATE TABLE `inscription` (
  `idseances` int(11) NOT NULL,
  `ideleve` int(11) NOT NULL,
  `nbfautes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8£

TRUNCATE TABLE `inscription`£

INSERT INTO `inscription` (`idseances`, `ideleve`, `nbfautes`) VALUES
(63, 90, 3),
(63, 92, 1),
(63, 93, 2),
(63, 94, 12),
(63, 102, 32),
(63, 113, 8),
(65, 94, 24),
(65, 95, 4),
(65, 97, 6),
(65, 98, 9),
(65, 101, 1),
(65, 105, 8),
(65, 107, 3),
(65, 108, 2),
(65, 112, 0),
(66, 90, 1),
(66, 94, 2),
(66, 99, 4),
(66, 103, 9),
(66, 107, 6),
(66, 111, 12),
(66, 112, 0),
(67, 90, 1),
(67, 91, 3),
(67, 92, 0),
(67, 93, 2),
(67, 94, 7),
(67, 95, 10),
(67, 96, 23),
(67, 97, 2),
(67, 98, 3),
(67, 100, 1),
(67, 101, 24),
(67, 102, 30),
(67, 103, 12),
(67, 104, 39),
(67, 105, 1),
(67, 109, 5),
(67, 110, 4),
(67, 111, 3),
(67, 112, 2),
(67, 113, 12),
(68, 91, 12),
(68, 95, 23),
(68, 96, 1),
(68, 104, 3),
(68, 110, 2),
(68, 113, 16),
(69, 90, 1),
(69, 92, 1),
(69, 98, 6),
(69, 102, 21),
(69, 113, 8),
(70, 90, 4),
(70, 93, 2),
(70, 98, 1),
(70, 100, 18),
(70, 101, 22),
(70, 102, 9),
(70, 105, 0),
(70, 113, 7),
(71, 90, 1),
(71, 92, 0),
(71, 93, 2),
(71, 97, 7),
(71, 98, 4),
(71, 99, 3),
(71, 103, 15),
(71, 105, 1),
(71, 107, 9),
(71, 111, 12),
(71, 113, 23),
(72, 91, 3),
(72, 92, 19),
(72, 93, 8),
(72, 96, 3),
(72, 97, 12),
(72, 98, 34),
(72, 99, 3),
(72, 102, 7),
(72, 103, 3),
(72, 104, 5),
(72, 105, 19),
(72, 108, 21),
(72, 111, 12),
(72, 112, 2),
(73, 90, 0),
(73, 91, 0),
(73, 92, 0),
(73, 93, 0),
(73, 96, 0),
(73, 97, 0),
(73, 98, 0),
(73, 100, 0),
(73, 101, 0),
(73, 103, 0),
(73, 104, 0),
(73, 109, 0),
(73, 110, 0),
(73, 112, 0),
(74, 90, 5),
(74, 94, 3),
(74, 95, 6),
(74, 96, 8),
(74, 97, 2),
(74, 98, 7),
(74, 99, 9),
(74, 102, 12),
(74, 104, 3),
(74, 105, 5),
(74, 108, 9),
(74, 109, 12),
(74, 112, 23),
(74, 113, 8),
(75, 90, 4),
(75, 98, 6),
(75, 99, 12),
(75, 105, 8),
(75, 108, 9),
(75, 109, 1),
(75, 112, 4),
(75, 113, 6),
(76, 91, 2),
(76, 92, 4),
(76, 96, 9),
(76, 98, 8),
(76, 100, 4),
(76, 101, 5),
(76, 103, 3),
(76, 104, 5),
(76, 109, 2),
(76, 110, 1),
(76, 112, 8),
(76, 113, 4),
(77, 90, 4),
(77, 91, 8),
(77, 92, 9),
(77, 94, 12),
(77, 95, 2),
(77, 96, 9),
(77, 97, 8),
(77, 98, 4),
(77, 99, 3),
(77, 100, 9),
(77, 103, 12),
(77, 104, 4),
(77, 105, 3),
(77, 107, 19),
(77, 108, 1),
(77, 109, 23),
(77, 110, 2),
(77, 111, 21),
(77, 112, 5),
(77, 113, 9),
(78, 105, 0),
(78, 113, 0),
(79, 90, 0),
(79, 92, 0),
(79, 105, 0),
(79, 108, 0),
(79, 113, 0),
(80, 90, 0),
(80, 96, 0),
(80, 100, 0),
(80, 105, 0),
(80, 110, 0),
(81, 91, 0),
(81, 101, 0),
(81, 112, 0),
(82, 96, 0),
(82, 104, 0),
(84, 92, 0),
(84, 98, 0),
(84, 103, 0),
(85, 112, 0),
(86, 90, 0),
(86, 92, 0),
(86, 94, 0),
(86, 97, 0),
(86, 98, 0),
(86, 105, 0),
(86, 113, 0)£

DROP TABLE `seances`£

CREATE TABLE `seances` (
  `ID` int(11) NOT NULL,
  `id_theme` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `nb_inscrits` int(11) NOT NULL,
  `note` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8£

TRUNCATE TABLE `seances`£

INSERT INTO `seances` (`ID`, `id_theme`, `date`, `heure`, `nb_inscrits`, `note`) VALUES
(63, 34, '2015-12-03', '14:00:00', 6, 1),
(65, 35, '2015-10-24', '16:00:00', 9, 1),
(66, 32, '2015-11-30', '18:00:00', 7, 1),
(67, 31, '2015-12-24', '20:00:00', 20, 1),
(68, 33, '2015-11-23', '22:00:00', 6, 1),
(69, 31, '2015-12-30', '12:00:00', 5, 1),
(70, 34, '2015-11-04', '10:00:00', 8, 1),
(71, 31, '2015-10-23', '10:00:00', 11, 1),
(72, 35, '2015-04-23', '10:00:00', 14, 1),
(73, 32, '2016-01-01', '10:00:00', 14, 0),
(74, 33, '2015-06-03', '10:00:00', 14, 1),
(75, 35, '2015-07-14', '10:00:00', 8, 1),
(76, 32, '2015-08-25', '10:00:00', 12, 1),
(77, 33, '2015-09-11', '10:00:00', 20, 1),
(78, 31, '2016-01-10', '10:00:00', 2, 0),
(79, 31, '2015-12-25', '16:00:00', 5, 0),
(80, 35, '2016-01-11', '09:00:00', 5, 0),
(81, 32, '2016-01-05', '19:00:00', 3, 0),
(82, 33, '2016-01-14', '17:00:00', 2, 0),
(83, 34, '2016-01-15', '20:30:00', 0, 0),
(84, 34, CURRENT_DATE+ interval '10 day', 3, 0),
(85, 32, CURRENT_DATE+ interval '6 day', '05:00:00', 1, 0),
(86, 34, CURRENT_DATE+ interval '3 day', '15:00:00', 7, 0);£


DROP TABLE `themes`£

CREATE TABLE `themes` (
  `idtheme` int(11) NOT NULL,
  `nom` varchar(30) CHARACTER SET utf8 NOT NULL,
  `supprime` tinyint(1) NOT NULL,
  `descriptif` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8£

TRUNCATE TABLE `themes`£

INSERT INTO `themes` (`idtheme`, `nom`, `supprime`, `descriptif`) VALUES
(31, 'Maniement du sabre laser', 0, 'Le maniement du sabre laser est une compÃ©tence essentielle Ã  tout Jedi dÃ©sirant combattre le cÃ´tÃ© obscur de la force.'),
(32, 'Pilotage vaisseau spatial', 0, 'L&#039;école dispose d&#039;un vaisseau spatial supersonique : apprenez Ã  Ã©chapper Ã  vos ennemis et Ã   atterrir Ã  la vitesse de la lumiÃ¨re.'),
(33, 'Tir au vaisseau spatial', 0, 'Devenez un pro du tir au vaisseau et apprenez notamment Ã  vous dÃ©barrasser efficacement des vaisseaux du Nouvel Ordre en cas de poursuite intergalactique.'),
(34, 'Usage de la Force', 0, 'Contrôlez votre force : ateliers de déracinage d&#039;arbre ainsi que de persuasion de citadins Ã  vous offrir un verre.'),
(35, 'Panneaux intergalactiques', 0, 'Sachez respecter les vitesses et les prioritÃ©s selon les voies galactiques que vous empruntez.'),
(36, 'Passage au côté Obscur d la Fo', 1, '...')£

ALTER TABLE `eleves`
  ADD PRIMARY KEY (`ideleve`)£

ALTER TABLE `inscription`
  ADD PRIMARY KEY (`idseances`,`ideleve`)£

ALTER TABLE `seances`
  ADD PRIMARY KEY (`ID`)£

ALTER TABLE `themes`
  ADD PRIMARY KEY (`idtheme`)£

ALTER TABLE `eleves`
  MODIFY COLUMN `ideleve` INT auto_increment£

ALTER TABLE `inscription`
  MODIFY COLUMN `idseances` INT auto_increment£

ALTER TABLE `seances`
  MODIFY COLUMN `ID` INT auto_increment£

ALTER TABLE `themes`
  MODIFY COLUMN `idtheme` INT auto_increment£


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */£
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */£
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */£
