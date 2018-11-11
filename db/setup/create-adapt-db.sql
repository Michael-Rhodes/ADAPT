/* ADAPT-DB, Active Detection of Advanced Persistent Threats (Database)
 * File: setup/create-adapt-db.sql
 * Author: Chris Partridge
 *
 * Creates database for ADAPT, per current spec. Wipe any `adapt` database before running.
 */

CREATE DATABASE `adapt` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

CREATE TABLE `activity_log` (
  `Timestamp` mediumint(9) NOT NULL,
  `PatternID` varchar(64) NOT NULL,
  `Data` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `attack_patterns` (
  `ID` varchar(64) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Phase` varchar(32) NOT NULL,
  `URL` varchar(128) NOT NULL,
  `ExternalID` varchar(16) NOT NULL,
  KEY `FastAccessByID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `attack_phases` (
  `Order` int(10) unsigned NOT NULL,
  `Name` varchar(32) NOT NULL,
  PRIMARY KEY (`Order`),
  UNIQUE KEY `Name_UNIQUE` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `known_groups` (
  `ID` varchar(64) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Aliases` varchar(512) NOT NULL,
  `URL` varchar(128) NOT NULL,
  `ExternalID` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name_UNIQUE` (`Name`),
  UNIQUE KEY `ExternalID_UNIQUE` (`ExternalID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `known_malware` (
  `ID` varchar(64) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Aliases` varchar(512) NOT NULL,
  `URL` varchar(128) NOT NULL,
  `ExternalID` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name_UNIQUE` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `known_relationships` (
  `ID` varchar(64) NOT NULL,
  `SourceID` varchar(64) NOT NULL,
  `Type` varchar(16) NOT NULL,
  `TargetID` varchar(64) NOT NULL,
  `Description` varchar(1024) NOT NULL,
  KEY `QuickAccess_SourceID` (`SourceID`),
  KEY `QuickAccess_TargetID` (`TargetID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `witnessed` (
  `ID` varchar(64) NOT NULL,
  `ExternalID` varchar(64) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `TimeUTC` varchar(32) DEFAULT NULL,
  `ComputerName` varchar(256) DEFAULT NULL,
  KEY `FastAccessByID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
