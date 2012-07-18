ALTER TABLE  `customers` ADD  `customers_basket_published` ENUM(  'yes',  'no' ) NULL DEFAULT  'no';

CREATE TABLE IF NOT EXISTS `customers_basket_saved` (
  `saved_id` int(20) NOT NULL AUTO_INCREMENT,
  `saved_code` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `saved_content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`saved_id`),
  KEY `saved_code` (`saved_code`,`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;