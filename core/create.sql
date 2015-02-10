

#---------------------------------------------------------------------------------------------------------------------------Globální tabulky


CREATE TABLE `[mpx]users` (
 `id` int(11) NOT NULL , 
 `aac` tinyint(1) NOT NULL COMMENT 'aktuální?' , 
 `name` varchar(200) COLLATE utf8_czech_ci NOT NULL , 
 `password` varchar(64) COLLATE utf8_czech_ci NOT NULL COMMENT 'md5' , 
 `email` varchar(200) COLLATE utf8_czech_ci NOT NULL , 
 `sendmail` tinyint(1) NOT NULL COMMENT 'Přihlášen k odesílání novinek?' , 
 `fbid` bigint(20) NOT NULL COMMENT 'ID na Facebooku' , 
 `fbdata` text COLLATE utf8_czech_ci NOT NULL COMMENT 'Serializované data z FB' , 
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY `id` (`id`),
 KEY `aac` (`aac`),
 KEY `name` (`name`),
 KEY `password` (`password`),
 KEY `email` (`email`),
 KEY `sendmail` (`sendmail`),
 KEY `fbid` (`fbid`),
 KEY `created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Uživatelské účty' ;


#----------------------------------------------


CREATE TABLE `[mpx]lang` (
 `lang` varchar(10) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL COMMENT 'Zkratka jakyka' , 
 `key` varchar(200) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL , 
 `value` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL , 
 `font` varchar(100) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL COMMENT '?experimentální' , 
 `author` int(11) NOT NULL , 
 `description` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL , 
 `time` int(11) NOT NULL , 
 UNIQUE KEY `langkey` (`lang`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Tabulka všech textů aplikace' ;


#----------------------------------------------


CREATE TABLE `[mpx]key` (
 `key` varchar(11) COLLATE utf8_czech_ci NOT NULL , 
 `reward` text COLLATE utf8_czech_ci NOT NULL COMMENT 'Odměna za kód' , 
 `id` int(11) NOT NULL COMMENT 'Kdo ho použil - id z [mpx]objects' , 
 `time_create` int(11) NOT NULL , 
 `time_used` int(11) NOT NULL , 
 PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Bonusové kódy' ;


#----------------------------------------------


CREATE TABLE `[mpx]emails` (
 `id` int(11) NOT NULL,
 `to` int(11) NOT NULL , 
 `subject` varchar(400) COLLATE utf8_czech_ci NOT NULL , 
 `text` text COLLATE utf8_czech_ci NOT NULL , 
 `world` text COLLATE utf8_czech_ci NOT NULL , 
 `key` varchar(64) COLLATE utf8_czech_ci NOT NULL , 
 `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Kdy bylo vloženo do DB',
 `try` int(5) NOT NULL COMMENT 'Počet pokusů?' , 
 `stop` timestamp NULL DEFAULT NULL COMMENT 'Odesláno' , 
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Všechny odeslané e-maily' ;



#---------------------------------------------------------------------------------------------------------------------------Tabulky světa


CREATE TABLE `[mpx]config` (
 `key` varchar(200) CHARACTER SET latin1 NOT NULL , 
 `value` text CHARACTER SET latin1 NOT NULL , 
 `description` text CHARACTER SET latin1 NOT NULL , 
 PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Konfigurace světa' ;


#----------------------------------------------


CREATE TABLE `[mpx]objects` (
 `id` int(11) NOT NULL,
 `name` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL , 
 `type` varchar(100) COLLATE utf8_czech_ci DEFAULT 'hybrid' COMMENT 'prime=modul,user=hráč,town,town2=pevnost,building,tree,rock',
 `userid` int(11) NOT NULL COMMENT 'Id připojeného uživatele/bota' , 
 `origin` text COLLATE utf8_czech_ci COMMENT 'seznam modulů, ze kterých objekt vzniknul',
 `fp` int(11) NOT NULL COMMENT 'Function Percent - život budovy je fp/fs ' , 
 `func` text COLLATE utf8_czech_ci COMMENT 'Funkce',
 `hold` text COLLATE utf8_czech_ci COMMENT 'Surky',
 `res` text COLLATE utf8_czech_ci COMMENT '3D model',
 `profile` text COLLATE utf8_czech_ci COMMENT 'Profil',
 `set` text COLLATE utf8_czech_ci COMMENT 'Nastavení',
 `own` int(11) DEFAULT NULL COMMENT 'Vlastník' , 
 `t` int(11) DEFAULT NULL COMMENT 'Čas změn dat' ,
 `pt` int(11) NOT NULL DEFAULT 0 COMMENT  'Herní čas',
 PRIMARY KEY (`id`),
 KEY `name` (`name`),
 KEY `type` (`type`),
 KEY `userid` (`userid`),
 KEY `fp` (`fp`),
 FULLTEXT KEY `func` (`func`),
 FULLTEXT KEY `res` (`res`),
 FULLTEXT KEY `profile` (`profile`),
 KEY `own` (`own`),
 KEY `t` (`t`),
 KEY `pt` (`pt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Data všech objektů - uživatelé(ne email a heslo) , města, budovy, stromy, skály, šablony a návrhy budov ' ;


#----------------------------------------------


CREATE TABLE `[mpx]positions` (
 `id` int(11) NOT NULL DEFAULT '0' COMMENT 'ID z [mpx]objects',
 `ww` int(11) NOT NULL DEFAULT '1' COMMENT 'Podsvět',
 `x` decimal(11,3) NOT NULL DEFAULT '0.000' ,
 `y` decimal(11,3) NOT NULL DEFAULT '0.000' ,
 `traceid` int(11) NOT NULL DEFAULT '0' COMMENT 'Původní ID před před/po změně',
 `starttime` int(11) NOT NULL DEFAULT '0' COMMENT 'Od kdy objekt existuje',
 `readytime` int(11) NOT NULL COMMENT 'Kdy je objekt postaven/připraven plnit funkce', 
 `stoptime` int(11) NOT NULL DEFAULT '0' COMMENT 'Do kdy objekt existuje',
 KEY `id` (`id`),
 KEY `ww` (`ww`),
 KEY `x` (`x`),
 KEY `y` (`y`),
 KEY `traceid` (`traceid`),
 KEY `starttime` (`starttime`),
 KEY `readytime` (`readytime`),
 KEY `stoptime` (`stoptime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Pozice objektů na mapě i v čase' ;


#----------------------------------------------


CREATE TABLE `[mpx]map` (
 `x` int(11) NOT NULL ,
 `y` int(11) NOT NULL ,
 `ww` int(11) NOT NULL COMMENT 'Podsvět' ,
 `terrain` varchar(100) COLLATE utf8_czech_ci NOT NULL COMMENT 'Terén z design/terrain' ,
 `name` varchar(100) COLLATE utf8_czech_ci NOT NULL COMMENT 'nepoužíváné' ,
 UNIQUE KEY `position` (`x`,`y`,`ww`),
 KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Mapa světa - terény' ;


#----------------------------------------------


CREATE TABLE `[mpx]quest` (
 `name` varchar(200) COLLATE utf8_czech_ci NOT NULL , 
 `quest` int(11) NOT NULL COMMENT 'ID questu - Např. tutorial' , 
 `questi` int(11) NOT NULL COMMENT 'ID podquestu - Např. Postavit stavitele' , 
 `limit` int(3) NOT NULL COMMENT '?' , 
 `cond1` text COLLATE utf8_czech_ci NOT NULL COMMENT ' PHP podmínka aby se dal quest přijmout' , 
 `cond2` text COLLATE utf8_czech_ci NOT NULL COMMENT 'PHP podmínka splněné questu' , 
 `description` text COLLATE utf8_czech_ci NOT NULL COMMENT 'Text questu' , 
 `image` text COLLATE utf8_czech_ci NOT NULL , 
 `author` text COLLATE utf8_czech_ci NOT NULL COMMENT 'ID hráče' , 
 `reward` text COLLATE utf8_czech_ci NOT NULL COMMENT 'Odměna v hold' , 
 UNIQUE KEY `idi` (`quest`,`questi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Definice questů z tutoriálu' ;


#----------------------------------------------


CREATE TABLE `[mpx]questt` (
 `id` int(11) NOT NULL COMMENT 'ID města' , 
 `quest` int(11) NOT NULL COMMENT 'ID questu' , 
 `questi` int(11) NOT NULL COMMENT 'ID podquestu' , 
 `time1` int(11) NOT NULL COMMENT 'Čas přijetí questu' , 
 `time2` int(11) NOT NULL COMMENT 'Čas dokončení questu' , 
 UNIQUE KEY `idq` (`id`,`quest`,`time1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Přiřazení questů k městům.' ;


#----------------------------------------------


CREATE TABLE `[mpx]text` (
 `id` int(11) NOT NULL ,
 `idle` int(11) NOT NULL DEFAULT '0' COMMENT 'Vlákno zpráv',
 `type` enum('chat','message','forum','report','write') COLLATE utf8_czech_ci NOT NULL DEFAULT 'chat',
 `new` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Ještě nepřečtené',
 `from` int(11) NOT NULL , 
 `to` int(11) NOT NULL , 
 `title` text COLLATE utf8_czech_ci NOT NULL , 
 `text` text COLLATE utf8_czech_ci NOT NULL , 
 `time` int(11) NOT NULL COMMENT 'Odesláno' , 
 `timestop` int(11) NOT NULL COMMENT 'Přečteno' , 
 PRIMARY KEY (`id`),
 KEY `from` (`from`),
 KEY `to` (`to`),
 KEY `time` (`time`),
 KEY `idle` (`idle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Zprávy a hlášení' ;


#----------------------------------------------


CREATE TABLE `[mpx]memory` (
 `id` varchar(43) COLLATE utf8_czech_ci NOT NULL , 
 `key` varchar(100) COLLATE utf8_czech_ci NOT NULL , 
 `value` text COLLATE utf8_czech_ci NOT NULL , 
 `time` int(11) NOT NULL , 
 UNIQUE KEY `id` (`id`,`key`),
 KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='vlastní SESSION světa' ;


#----------------------------------------------


CREATE TABLE `[mpx]log` (
 `time` int(11) NOT NULL ,
 `ip` varchar(20) COLLATE utf8_czech_ci NOT NULL , 
 `user_agent` text COLLATE utf8_czech_ci NOT NULL , 
 `townssessid` varchar(32) COLLATE utf8_czech_ci NOT NULL COMMENT 'moje ID session' ,
 `uri` text COLLATE utf8_czech_ci NOT NULL COMMENT 'HTTP URI' ,
 `lang` varchar(4) COLLATE utf8_czech_ci NOT NULL ,
 `adminname` varchar(20) NOT NULL ,
 `userid` int(20) NOT NULL ,
 `logid` int(20) NOT NULL , 
 `useid` int(20) NOT NULL , 
 `aacid` int(20) NOT NULL COMMENT 'Jaký objekt na mapě?' , 
 `function` varchar(20) COLLATE utf8_czech_ci NOT NULL , 
 `params` text COLLATE utf8_czech_ci NOT NULL , 
 `output` text COLLATE utf8_czech_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Log' ;


#---------------------------------------------------------------------------------------------------------------------------Pomocné tabulky světa


CREATE TABLE `[mpx]objects_tmp` (
 `id` int(11) NOT NULL COMMENT 'ID z [mpx]objects',
 `fs` int(11) NOT NULL COMMENT 'Function Summary - odvozeno z func - celková hodnota funkcí' , 
 `fc` text COLLATE utf8_czech_ci NOT NULL COMMENT 'Function count - celková hodnota funkcí v surovinách' , 
 `fr` int(11) NOT NULL COMMENT 'Function resource - hodnota hold' , 
 `fx` int(11) NOT NULL COMMENT 'FP+FR' , 
 `superown` int(11) DEFAULT NULL COMMENT 'Vlastník (vlastníka)' , 
 `expand` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT 'Dálka Expanze',
 `block` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT 'Hradba?',
 `attack` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT 'Vzdálenost útoku',
 `create_lastused` int(11) NOT NULL COMMENT 'Naposledy použiá funkce create' , 
 `create_lastobject` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '' COMMENT 'Který objekt staví',
 `create2_lastused` int(11) NOT NULL , 
 `create2_lastobject` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
 `create3_lastused` int(11) NOT NULL , 
 `create3_lastobject` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
 `create4_lastused` int(11) NOT NULL , 
 `create4_lastobject` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
 PRIMARY KEY (`id`),
 KEY `fs` (`fs`),
 KEY `superown` (`superown`),
 KEY `expand` (`expand`),
 KEY `block` (`block`),
 KEY `attack` (`attack`),
 KEY `create_lastused` (`create_lastused`),
 KEY `create_lastobject` (`create_lastobject`),
 KEY `create2_lastused` (`create_lastused`),
 KEY `create2_lastobject` (`create_lastobject`),
 KEY `create3_lastused` (`create_lastused`),
 KEY `create3_lastobject` (`create_lastobject`),
 KEY `create4_lastused` (`create_lastused`),
 KEY `create4_lastobject` (`create_lastobject`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='To co se dá z [mpx]objects vyvodit.' ;


#----------------------------------------------


CREATE TABLE `[mpx]timeplan` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `click` int(11) NOT NULL COMMENT 'HTTP spojení' , 
 `key` varchar(100) COLLATE utf8_czech_ci NOT NULL COMMENT 'první parametr t()' , 
 `text` text COLLATE utf8_czech_ci NOT NULL COMMENT 'druhý parametr t()' , 
 `ms` decimal(8,3) NOT NULL COMMENT 'trvání v milisekundách' , 
 `uri` text COLLATE utf8_czech_ci NOT NULL COMMENT 'HTTP URI' , 
 `logid` int(11) NOT NULL , 
 `useid` int(11) NOT NULL , 
 `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `key` (`key`),
 KEY `click` (`click`),
 KEY `ms` (`ms`),
 KEY `logid` (`logid`),
 KEY `useid` (`useid`),
 KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT ='Analýza rychlosti běhu' ;


#---------------------------------------------------------------------------------------------------------------------------Pohledy světa


CREATE VIEW `[mpx]pos_obj` AS
SELECT
[mpx]objects.`id` AS `id`,
[mpx]objects.`name` AS `name`,
[mpx]objects.`type` AS `type`,
[mpx]objects.`userid` AS `userid`,
[mpx]objects.`origin` AS `origin`,
[mpx]objects.`fp` AS `fp`,
[mpx]objects.`func` AS `func`,
[mpx]objects.`hold` AS `hold`,
[mpx]objects.`res` AS `res`,
[mpx]objects.`profile` AS `profile`,
[mpx]objects.`set` AS `set`,
[mpx]objects.`own` AS `own`,
0 AS `in`,
0 AS `hard`,
[mpx]objects.`t` AS `t`,
[mpx]objects.`pt` AS `pt`,
[mpx]objects_tmp.`fs` AS `fs`,
[mpx]objects_tmp.`fc` AS `fc`,
[mpx]objects_tmp.`fr` AS `fr`,
[mpx]objects_tmp.`fx` AS `fx`,
[mpx]objects_tmp.`superown` AS `superown`,
[mpx]objects_tmp.`expand` AS `expand`,
[mpx]objects_tmp.`block` AS `block`,
[mpx]objects_tmp.`attack` AS `attack`,
[mpx]objects_tmp.`create_lastused` AS `create_lastused`,
[mpx]objects_tmp.`create_lastobject` AS `create_lastobject`,
[mpx]objects_tmp.`create2_lastused` AS `create2_lastused`,
[mpx]objects_tmp.`create2_lastobject` AS `create2_lastobject`,
[mpx]objects_tmp.`create3_lastused` AS `create3_lastused`,
[mpx]objects_tmp.`create3_lastobject` AS `create3_lastobject`,
[mpx]objects_tmp.`create4_lastused` AS `create4_lastused`,
[mpx]objects_tmp.`create4_lastobject` AS `create4_lastobject`,
[mpx]positions.`ww` AS `ww`,
[mpx]positions.`x` AS `x`,
[mpx]positions.`y` AS `y`,
[mpx]positions.`traceid` AS `traceid`,
[mpx]positions.`starttime` AS `starttime`,
[mpx]positions.`readytime` AS `readytime`,
[mpx]positions.`stoptime` AS `stoptime`
FROM [mpx]positions
LEFT JOIN [mpx]objects ON [mpx]positions.id=[mpx]objects.id
LEFT JOIN [mpx]objects_tmp ON [mpx]positions.id=[mpx]objects_tmp.id
;
--COMMENT = 'Spojení tabulek objects, objects_tmp a positions'
--parametr in a hard je zatím provizorní a vždy 0

#---------------------------------------------------------------------------------------------------------------------------

