CREATE TABLE `[mpx]quest` (
  `name` varchar(200) NOT NULL,
  `quest` int(11) NOT NULL,
  `questi` int(11) NOT NULL,
  `limit` int(3) NOT NULL,
  `cond1` text NOT NULL,
  `cond2` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  `author` text NOT NULL,
  `reward` text NOT NULL,
  UNIQUE KEY `idi` (`id`,`i`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
