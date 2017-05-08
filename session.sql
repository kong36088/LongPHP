/*If you want to store your session in database */

CREATE TABLE IF NOT EXISTS `long_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `long_sessions_timestamp` (`timestamp`)
);