<?php
// /catalog/includes/languages/espanol/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.5.2
// Add META TAGS and Modify TITLE
//
// DEFINITIONS FOR /includes/languages/espanol/header_tags.php
// Traduction to spanish by nicko107 www.oscommerce-tutoriales.com

// Define aqui la direccion de tu correo electronico que debe aparecer en todas las paginas
define('HEAD_REPLY_TAG_ALL',STORE_OWNER_EMAIL_ADDRESS);

// Para todas las paginas no definidas o que se deja en blanco, y para los productos no definidos
// Esto sera incluido a menos que modifique en cada seccion de abajo a OFF ( '0' )
// ElHEAD_TITLE_TAG_ALL sera incluido DESPUES del especificado para cada pagina
// El HEAD_DESC_TAG_ALL sera incluido DESPUES del especificado para cada pagina
// El HEAD_KEY_TAG_ALL sera incluido DESPUES del especificado para cada pagina
define('HEAD_TITLE_TAG_ALL','osCommerce : ');
define('HEAD_DESC_TAG_ALL','osCommerce : What\'s New Here? - Hardware Software DVD Movies');
define('HEAD_KEY_TAG_ALL','Hardware Software DVD Movies What\'s New Here?');

// ETIQUETAS DEFINES PARA CADA PAGINA INDIVIDUAL / SECCION
// incluimos aqui ademas el de contribuciones que no vienen instaladas con osCommerce, por si las instala en un futuro.
// allprods.php
define('HTTA_ALLPRODS_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_ALLPRODS_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_ALLPRODS_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ALLPRODS', '');
define('HEAD_DESC_TAG_ALLPRODS','');
define('HEAD_KEY_TAG_ALLPRODS','');

// all_products.php
define('HTTA_ALL_PRODUCTS_ON','1');
define('HTDA_ALL_PRODUCTS_ON','1');
define('HTKA_ALL_PRODUCTS_ON','1');
define('HEAD_TITLE_TAG_ALL_PRODUCTS','Todos los Productos -');
define('HEAD_DESC_TAG_ALL_PRODUCTS','Todos los Productos');
define('HEAD_KEY_TAG_ALL_PRODUCTS','todos los productos');

// Contribucion Todos los fabricantes
// allmanufacturers.php
define('HTTA_ALLMANUFACTURERS_ON','1');
define('HTDA_ALLMANUFACTURERS_ON','1');
define('HTKA_ALLMANUFACTURERS_ON','1');
define('HEAD_TITLE_TAG_ALLMANUFACTURERS','Todos los Fabricantes -');
define('HEAD_DESC_TAG_ALLMANUFACTURERS','Todos los Fabricantes -');
define('HEAD_KEY_TAG_ALLMANUFACTURERS','todos fabricantes');

// dynamic_sitemap.php
define('HTTA_DYNAMIC_SITEMAP_ON','1');
define('HTDA_DYNAMIC_SITEMAP_ON','1');
define('HTKA_DYNAMIC_SITEMAP_ON','1');
define('HEAD_TITLE_TAG_DYNAMIC_SITEMAP','Mapa del Sitio -');
define('HEAD_DESC_TAG_DYNAMIC_SITEMAP','Mapa del Sitio');
define('HEAD_KEY_TAG_DYNAMIC_SITEMAP','Mapa del Sitio');

// Contribucion Featured Products - Productos Destacados
// featured_products.php
define('HTTA_FEATURED_PRODUCTS_ON','1');
define('HTDA_FEATURED_PRODUCTS_ON','1');
define('HTKA_FEATURED_PRODUCTS_ON','1');
define('HEAD_TITLE_TAG_FEATURED_PRODUCTS','Productos Destacados -');
define('HEAD_DESC_TAG_FEATURED_PRODUCTS','Productos Destacados');
define('HEAD_KEY_TAG_FEATURED_PRODUCTS','Productos Destacados');

// index.php
define('HTTA_DEFAULT_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_DEFAULT_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_DEFAULT_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HTTA_CAT_DEFAULT_ON', '0'); //Include HEADE_TITLE_DEFAULT in CATEGORY DISPLAY
define('HEAD_TITLE_TAG_DEFAULT', 'Home Page ');
define('HEAD_DESC_TAG_DEFAULT','- osCommerce : What\'s New Here? - Hardware Software DVD Movies');
define('HEAD_KEY_TAG_DEFAULT','- Hardware Software DVD Movies What\'s New Here?');

// product_info.php - si se deja en blanco en la tabla de products_description se usaran estos valores
define('HTTA_PRODUCT_INFO_ON','0');
define('HTKA_PRODUCT_INFO_ON','0');
define('HTDA_PRODUCT_INFO_ON','0');
define('HTTA_CAT_PRODUCT_DEFAULT_ON', '0');
define('HEAD_TITLE_TAG_PRODUCT_INFO','');
define('HEAD_DESC_TAG_PRODUCT_INFO','');
define('HEAD_KEY_TAG_PRODUCT_INFO','');

// products_new.php - whats_new
define('HTTA_WHATS_NEW_ON','1');
define('HTKA_WHATS_NEW_ON','1');
define('HTDA_WHATS_NEW_ON','1');
define('HEAD_TITLE_TAG_WHATS_NEW','Productos Nuevos');
define('HEAD_DESC_TAG_WHATS_NEW','');
define('HEAD_KEY_TAG_WHATS_NEW','');

// specials.php
// Si deja en blanco HEAD_KEY_TAG_SPECIALS, creara los keywords desde el nombre del producto de todos los productos en oferta
define('HTTA_SPECIALS_ON','1');
define('HTKA_SPECIALS_ON','1');
define('HTDA_SPECIALS_ON','1');
define('HEAD_TITLE_TAG_SPECIALS','Ofertas');
define('HEAD_DESC_TAG_SPECIALS','');
define('HEAD_KEY_TAG_SPECIALS','');

// product_reviews_info.php y product_reviews.php - si los deja en blanco en la descripcion estos valores seran los que se usen
define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','');

?>
