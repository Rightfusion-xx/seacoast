<?php

/*

  $Id: database_tables.php,v 1.1 2003/06/20 00:18:30 hpdl Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



// define the database table names used in the project
define('TABLE_COUPONS', 'coupons');

define('TABLE_COUPONS_A', 'coupons_a');

  define('TABLE_ADDRESS_BOOK', 'address_book');

  define('TABLE_ADDRESS_FORMAT', 'address_format');

  define('TABLE_BANNERS', 'banners');

  define('TABLE_BANNERS_HISTORY', 'banners_history');

  define('TABLE_CATEGORIES', 'categories');

  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');

  define('TABLE_CONFIGURATION', 'configuration');

  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');

  define('TABLE_COUNTRIES', 'countries');

  define('TABLE_CURRENCIES', 'currencies');

  define('TABLE_CUSTOMERS', 'customers');

  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');

  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');

  define('TABLE_CUSTOMERS_INFO', 'customers_info');

  define('TABLE_LANGUAGES', 'languages');

  define('TABLE_MANUFACTURERS', 'manufacturers');

  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');

  define('TABLE_NEWSLETTERS', 'newsletters');

  define('TABLE_ORDERS', 'orders');

  define('TABLE_ORDERS_PRODUCTS', 'orders_products');

  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');

  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');

  define('TABLE_ORDERS_STATUS', 'orders_status');

  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');

  define('TABLE_ORDERS_TOTAL', 'orders_total');

  define('TABLE_PRODUCTS', 'products');

  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');

  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');

  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');

  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');

  define('TABLE_PRODUCTS_OPTIONS', 'products_options');

  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');

  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');

  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');

  define('TABLE_REVIEWS', 'reviews');

  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');

  define('TABLE_SESSIONS', 'sessions');

  define('TABLE_SPECIALS', 'specials');

  define('TABLE_TAX_CLASS', 'tax_class');

  define('TABLE_TAX_RATES', 'tax_rates');

  define('TABLE_GEO_ZONES', 'geo_zones');

  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');

  define('TABLE_WHOS_ONLINE', 'whos_online');

  define('TABLE_ZONES', 'zones');

  

// added for infobox skin manager

  define('TABLE_SKINS', 'skins');

  define('TABLE_INFOBOX_SKIN_MAPPING', 'skin_mapping');

 define('TABLE_ARTICLE_REVIEWS', 'article_reviews');

  define('TABLE_ARTICLE_REVIEWS_DESCRIPTION', 'article_reviews_description');

  define('TABLE_ARTICLES', 'articles');

  define('TABLE_ARTICLES_DESCRIPTION', 'articles_description');

  define('TABLE_ARTICLES_TO_TOPICS', 'articles_to_topics');

  define('TABLE_ARTICLES_XSELL', 'articles_xsell');

  define('TABLE_AUTHORS', 'authors');

  define('TABLE_AUTHORS_INFO', 'authors_info');

  define('TABLE_TOPICS', 'topics');

  define('TABLE_TOPICS_DESCRIPTION', 'topics_description');

  

// VJ Links Manager v1.00 begin

  define('TABLE_LINK_CATEGORIES', 'link_categories');

  define('TABLE_LINK_CATEGORIES_DESCRIPTION', 'link_categories_description');

  define('TABLE_LINKS', 'links');

  define('TABLE_LINKS_DESCRIPTION', 'links_description');

  define('TABLE_LINKS_TO_LINK_CATEGORIES', 'links_to_link_categories');

  define('TABLE_LINKS_STATUS', 'links_status');

// VJ Links Manager v1.00 end

define('TABLE_ORDERS_MAXMIND', 'orders_maxmind');



// START - Admin Notes

  define('TABLE_ADMIN_NOTES','admin_notes');

  define('TABLE_ADMIN_NOTES_TYPE','admin_notes_type');

// END - Admin Notes
    define('TABLE_SEO_GOOGLE', 'seo_google');
    define('TABLE_SEO_MSN', 'seo_msn');
    define('TABLE_SEO_YAHOO', 'seo_yahoo');
?>