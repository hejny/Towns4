CREATE TABLE IF NOT EXISTS `[mpx]map` (
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `ww` int(11) NOT NULL,
  `terrain` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  UNIQUE KEY `position` (`x`,`y`,`ww`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
