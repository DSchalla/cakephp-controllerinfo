CREATE TABLE IF NOT EXISTS `controllerinfo_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` text NOT NULL,
  `properties` text NOT NULL,
  `methods` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
