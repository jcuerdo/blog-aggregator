CREATE TABLE `post` (
  `title` varchar(240) DEFAULT NULL,
  `slag` varchar(240) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `link` varchar(512) DEFAULT NULL,
  `content` text,
  `image` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`slag`),
  KEY `slag` (`slag`),
  KEY `DateIndex` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `rss` (
  `url` varchar(300) DEFAULT NULL,
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
