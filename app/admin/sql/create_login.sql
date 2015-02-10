CREATE TABLE IF NOT EXISTS `[mpx]login` (
  `id` int(11) NOT NULL,
  `method` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `key` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `time_create` int(11) NOT NULL,
  `time_change` int(11) NOT NULL,
  `time_use` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`,`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
