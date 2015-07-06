CREATE TABLE IF NOT EXISTS `[mpx]text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idle` int(11) NOT NULL DEFAULT '0',
  `type` enum('chat','message','forum','report') COLLATE utf8_czech_ci NOT NULL DEFAULT 'chat',
  `new` tinyint(1) NOT NULL DEFAULT '1',
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `title` text COLLATE utf8_czech_ci NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `time` int(11) NOT NULL,
  `timestop` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1000 ;
