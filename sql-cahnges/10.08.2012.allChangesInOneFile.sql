/**
 * THis table stores data about connect social systems profiles. Right now we added only facebook integration, but it also can be used for other social systems.
 */
CREATE TABLE IF NOT EXISTS `customers_sns` (
  `customers_id` int(11) NOT NULL,
  `customers_sn_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customers_sn_type` enum('facebook','twitter','google') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`customers_id`,`customers_sn_key`,`customers_sn_type`),
  KEY `customers_sn_key` (`customers_sn_key`),
  KEY `customers_id` (`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/**
 * THis flag would be set to yes after publishing shopping cart to facebook and set to no after order creation.
 * SO in this way you can publish your shopping cart and get free shipping, but to get free shipping for next order, you will need to publish your shoppingcart again.
 */
ALTER TABLE  `customers` ADD  `customers_basket_published` ENUM(  'yes',  'no' ) NULL DEFAULT  'no';

/**
 * THis table contains texts that could be shown on a search page based on keword. This texts can be managed from admin side.
 */
CREATE TABLE IF NOT EXISTS `top_messages` (
`message_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`message_keyword` VARCHAR( 255 ) NULL ,
`message_text` TEXT NULL
) ENGINE = MYISAM ;


/**
 * This is a messages that added by me to different project's pages.
 * Probably it would be better some locales table and move all messages to there.
 * Also here added constanc that enabling free shipping if your shoppingcart contains more then "FREE_SHIPPING_CART_SIZE" products. 0 value means disabled.
 */
INSERT INTO `configuration`
  (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`)
VALUES
  (NULL, 'Cart size to get free shipping', 'FREE_SHIPPING_CART_SIZE', '0', 'how match products must be present in shopping cart to get free shiiping', 1, 0, '2012-07-25 17:21:04', '0000-00-00 00:00:00', NULL, NULL),
  (NULL, 'Shoppingcart publish message', 'SHOPPINGCART_PUBLISH_MESSAGE', 'I just got free shipping on my Seacoast Vitamins, and you can, too! Here''s my shopping cart!', 'Shoppingcart publish message', 1, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
  (NULL, 'Get free shipping message', 'GET_FREE_SHIPPING_MESSAGE', 'Publish your shopping cart to facebook and a free shipping in U.S.', 'Get free shipping message', 1, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
  (NULL, 'Free shipping enabled message', 'GET_FREE_SHIPPING_ENABLED_MESSAGE', 'You''ve already got a free shipping', 'Free shipping enabled message', 1, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
  (NULL, 'publish message on shopping cart page', 'PUBLISH_MESSAGE_ON_SH_PAGE', 'We''ve detected you are in the US. For free shipping in the Lower 48, publish your shopping cart to Facebook.', 'publish message on shopping cart page', 1, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
  (NULL, 'Shopping cart published message', 'SHOPPINGCART_PUBLISHED_MESSAGE', 'Shopping cart published', 'Shopping cart published message', 1, NULL, NULL, '2012-08-06 09:26:28', '', '');


/**
 *  HEre are the tables for newsletters
 *
 */


/**
 * A newsletters categoris table. will be used to allow customers to subscribe/unsubscribe to different newsletters.
 */
 CREATE TABLE IF NOT EXISTS `newsletter_categories` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `category_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_description` text COLLATE utf8_unicode_ci NOT NULL,
  `is_enabled` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/**
 * Will contains data about wich custoner subscribed to wuch newsletter categories
 */
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `customer_id` int(10) NOT NULL,
  `newsletter_id` int(10) NOT NULL,
  PRIMARY KEY (`customer_id`,`newsletter_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/**
 * I've added to newsletter categories an item that reflects the only one newsletter that exists in system now.
 */
INSERT INTO `newsletter_categories` (`category_id`, `category_title`, `category_description`, `is_enabled`) VALUES
(1, 'General Newsletter', '<p><span style="font-size: xx-small;">This Newsletter                          contains New Product Information, Health Related Articles,                          Vaulable Discount Coupons! We will never share your information with anyone.<br /> </span> <a href="http://test.seacoastvitamins.com/Catalog/privacy.php" target="_blank">Policy/Spam                          Notice </a></p>', 'yes'),;

/**
 * A newsletter items table.
 */
CREATE TABLE IF NOT EXISTS `newsletters` (
  `newsletters_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category_id` int(10) NOT NULL,
  PRIMARY KEY (`newsletters_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MYISAM  DEFAULT CHARSET=utf8;