<?php
// /catalog/includes/languages/english/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.3
// Add META TAGS and Modify TITLE
//
// DEFINITIONS FOR /includes/languages/english/header_tags.php

// Define your email address to appear on all pages
define('HEAD_REPLY_TAG_ALL',STORE_OWNER_EMAIL_ADDRESS);

// For all pages not defined or left blank, and for products not defined
// These are included unless you set the toggle switch in each section below to OFF ( '0' )
// The HEAD_TITLE_TAG_ALL is included BEFORE the specific one for the page
// The HEAD_DESC_TAG_ALL is included AFTER the specific one for the page
// The HEAD_KEY_TAG_ALL is included AFTER the specific one for the page
define('HEAD_TITLE_TAG_ALL','Seacoast Vitamins, Highest Quality Vitamin Supplements, Service, and Knowledge');
define('HEAD_DESC_TAG_ALL','Seacoast Vitamins - the vitamins store that provides quality vitamins, minerals, herbs, and all your nutritional supplement needs at everday low prices. Free health information.');
define('HEAD_KEY_TAG_ALL','vitamins, herbs, herbal extracts, alternative medicines, homeopathic, health, natural, nutritional supplements, healthy living, ADHD, cancer, prostate, diabetes, arthritis');

// DEFINE TAGS FOR INDIVIDUAL PAGES

// index.php
define('HTTA_DEFAULT_ON','0'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_DEFAULT_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_DEFAULT_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_DEFAULT', 'Seacoast Vitamins, Highest Quality Vitamin Supplements, Service, and Knowledge');
define('HEAD_DESC_TAG_DEFAULT','Seacoast Vitamins - the vitamins store that provides quality vitamins, minerals, herbs, and all your nutritional supplement needs at everday low prices. Free health information.');
define('HEAD_KEY_TAG_DEFAULT','vitamins, herbs, herbal extracts, alternative medicines, homeopathic, health, natural, nutritional supplements, healthy living, ADHD, cancer, prostate, diabetes, arthritis');

// product_info.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_INFO_ON','0');
define('HTKA_PRODUCT_INFO_ON','0');
define('HTDA_PRODUCT_INFO_ON','0');
define('HEAD_TITLE_TAG_PRODUCT_INFO','Seacoast Vitamins, Highest Quality Vitamin Supplements, Service, and Knowledge');
define('HEAD_DESC_TAG_PRODUCT_INFO','Seacoast Vitamins - the vitamins store that provides quality vitamins, minerals, herbs, and all your nutritional supplement needs at everday low prices. Free health information.');
define('HEAD_KEY_TAG_PRODUCT_INFO','');

// products_new.php - whats_new
define('HTTA_WHATS_NEW_ON','0');
define('HTKA_WHATS_NEW_ON','0');
define('HTDA_WHATS_NEW_ON','0');
define('HEAD_TITLE_TAG_WHATS_NEW','Seacoast Vitamins, Highest Quality Vitamin Supplements, Service, and Knowledge');
define('HEAD_DESC_TAG_WHATS_NEW','New Products at Seacoast Vitamins, the friendliest vitamins store on the web.');
define('HEAD_KEY_TAG_WHATS_NEW','');

// specials.php
// If HEAD_KEY_TAG_SPECIALS is left blank, it will build the keywords from the products_names of all products on special
define('HTTA_SPECIALS_ON','0');
define('HTKA_SPECIALS_ON','0');
define('HTDA_SPECIALS_ON','0');
define('HEAD_TITLE_TAG_SPECIALS','Seacoast Vitamins, Highest Quality Vitamin Supplements, Service, and Knowledge');
define('HEAD_DESC_TAG_SPECIALS','Special Offers at Seacoast Vitamins, the friendliest vitamin store on the web.');
define('HEAD_KEY_TAG_SPECIALS','');

// product_reviews_info.php and product_reviews.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_REVIEWS_INFO_ON','0');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','0');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','0');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','Seacoast Vitamins, Highest Quality Vitamin Supplements, Service, and Knowledge');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','Read customer comments at Seacoast Vitamins');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','');

?>
