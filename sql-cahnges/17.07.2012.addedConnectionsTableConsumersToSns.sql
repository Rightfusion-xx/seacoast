CREATE TABLE IF NOT EXISTS `customers_sns` (
  `customers_id` int(11) NOT NULL,
  `customers_sn_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customers_sn_type` enum('facebook','twitter','google') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`customers_id`,`customers_sn_key`,`customers_sn_type`),
  KEY `customers_sn_key` (`customers_sn_key`),
  KEY `customers_id` (`customers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;