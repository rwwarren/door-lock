DROP TABLE IF EXISTS `APIUsers`;
CREATE TABLE `APIUsers` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `APIKey` varchar(255) DEFAULT NULL,
  `IsActive` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ResetURLs`;
CREATE TABLE `ResetURLs` (
  `rowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned DEFAULT '0',
  `ResetURL` varchar(255) DEFAULT '0',
  `Expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isValid` int(11) DEFAULT '1',
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL DEFAULT '0',
  `AuthyID` mediumint(9) NOT NULL DEFAULT '0',
  `CardID` varchar(255) DEFAULT NULL,
  `IsAdmin` tinyint(4) NOT NULL DEFAULT '0',
  `IsActive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `DateJoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `Users` VALUES (1,'Test','test','*94BDCEBE19083CE2A1F959FD02F964C7AF4CFC29','test@test.com',123,'',1,1,'2012-10-27 00:07:05');
UNLOCK TABLES;

