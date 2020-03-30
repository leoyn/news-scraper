

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE `article` (
  `guid` varchar(300) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `url` varchar(300) NOT NULL,
  `publicationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `article`
  ADD PRIMARY KEY (`guid`);
ALTER TABLE `article` ADD FULLTEXT KEY `title` (`title`,`description`);
ALTER TABLE `article` ADD FULLTEXT KEY `title_2` (`title`,`description`);