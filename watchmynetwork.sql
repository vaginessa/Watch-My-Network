SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
CREATE TABLE IF NOT EXISTS `browsingHistory` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nmapStartstr` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `nmapEndstr` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `instruction` varchar(300) COLLATE utf8_turkish_ci NOT NULL,
  `upIP` int(10) NOT NULL,
  `downIP` int(10) NOT NULL,
  `elapsed` varchar(30) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

CREATE TABLE IF NOT EXISTS `deviceInfo` (
  `ID` int(30) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ipv4Address` decimal(42,0) NOT NULL,
  `ipv6Address` decimal(42,0) NOT NULL,
  `macAddress` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `os` varchar(300) COLLATE utf8_turkish_ci NOT NULL,
  `macVendor` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  `reason` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  `hostName` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  `hostType` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `portInfo` (
  `ID` int(60) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `macAddress` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `ipv4Address` decimal(42,0) NOT NULL,
  `portID` int(11) NOT NULL,
  `status` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `service` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `reason` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  `reasonTTL` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=1 ;


ALTER TABLE `deviceInfo`
  ADD CONSTRAINT `deviceInfo_ibfk_1` FOREIGN KEY (`timestamp`) REFERENCES `browsingHistory` (`timestamp`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `portInfo`
  ADD CONSTRAINT `portInfo_ibfk_1` FOREIGN KEY (`timestamp`) REFERENCES `browsingHistory` (`timestamp`) ON DELETE CASCADE ON UPDATE CASCADE;
