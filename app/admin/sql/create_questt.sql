CREATE TABLE `[mpx]questt` (
  `id` int(11) NOT NULL,
  `quest` int(11) NOT NULL,
  `questi` int(11) NOT NULL,
  `time1` int(11) NOT NULL,
  `time2` int(11) NOT NULL,
  UNIQUE KEY `idq` (`id`,`quest`,`time1`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
