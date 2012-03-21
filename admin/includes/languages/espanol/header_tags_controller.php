<?php
/*
  $Id: header_tags.php,v 1.6 2005/04/10 14:07:36 hpdl Exp $
  Created by Jack York from http://www.oscommerce-solution.com
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  Traduction to spanish by nicko107 www.oscommerce-tutoriales.com

  Released under the GNU General Public License
*/
define('HEADING_TITLE_CONTROLLER', 'Header Tags Controller');
define('HEADING_TITLE_ENGLISH', 'Header Tags');
define('HEADING_TITLE_ESPANOL', 'Header Tags - Espa&ntilde;ol');
define('HEADING_TITLE_FILL_TAGS', 'Header Tags - Rellenar Tags');
define('TEXT_INFORMATION_ADD_PAGE', '<b>A&ntilde;adir una Nueva Pagina</b> - Esta opcion a&ntilde;ade el codigo a la pagina en el archivo que indique en la primera casilla (Nombre de la pagina), debe saber el nombre del archivo (e.g. para la pagina de ofertas el nombre del archivo es specials.php). Compruebe que no esta incluido ya en la pagina de Control de Texto del menu Header Tags.  Para a&ntilde;adirlo a una pagina, introduzca el nombre del archivo, con o sin la extension .php. En las casillas siguientes puede escribir un titulo especifico solo para esa pagina (e.g. Ofertas para la pagina de specials.php) y una descripcion y keywords especificos (e.g. ofertas, descuentos,...). Vea mas abajo en Comprobar paginas que falten para saber en cual falta aun el codigo.');
define('TEXT_INFORMATION_DELETE_PAGE', '<b>Borrar una Pagina</b> - Esta opcion borra el codigo de una pagina de un archivo seleccionado en el siguiente desplegable.'); 
define('TEXT_INFORMATION_CHECK_PAGES', '<b>Comprabar Paginas que falten</b> - Esta opcion permite comprobar que archivos de su catalogo no tienen entradas, a traves de los ficheros que apareceran en el desplegable de mas abajo. Nota no todas las paginas tienen entradas hechas. Por ejemplo,
cualquier pagina que use SSL como la de login o Crear una Cuenta. Para ver que paginas no tienen codigo aun, pulse en el boton Actualizar de debajo, y cuando recarge la pagina apareceran esos archivos en el desplegable, seleccione la lista desplegable para verlos.'); 

define('TEXT_PAGE_TAGS', 'Primero para que Header Tags muestre la informacion de los Tags en una pagina, debe insertar codido en los archivos includes/header_tags.php e includes/languages/espanol/header_tags.php 
(o en el archivo de la carpeta del idioma que usted use). Las opciones de esta pagina le permitiran a&ntilde;adir, borrar
y comprobar el codigo en esos archivos desde el admin sin tener que editar esos archivos directamente.');
define('TEXT_ENGLISH_TAGS', 'El proposito principal de Header Tags es dar a cada una de las paginas de su catalogo un unico titulo y meta tags personalizado. Las opciones que vienen por defecto al instalar osCommerce solo muestran un titulo fijo en todo el catalogo, y no dan ningun Tag (etiqueta) de descripcion ni de keywords. En esta pagina es donde podra crear esas etiquetas con Header Tags. Cambielo para que use las palabras que usted quiera que use su catalogo. <br><br>
Esta pagina se divide en dos partes, la primera , las tres casillas para el titulo y Tags por defecto, y el resto las secciones donde configurar cada pagina, identificadas por el nombre del archivo (index: para la portada, categorias y subcategorias; product_info: la pagina de descripcion de producto;...). Las tres primeras casillas que se encuentran debajo (titulo por defecto, descripcion por defecto y keywords por defecto) son las generales y aparecen en las paginas en las que se active las casillas HTTA, HTDA y HTKA respectivamente, HTTA (para que muestre el titulo por defecto) HTDA (para que muestre tambien la descripcion dada por defecto) y HTKA (para que muestre tambien los keywords dados por defecto), estas casillas estan en la cabecera de cada seccion que se ve mas abajo<br>
Las otras casillas que estan debajo son de cada seccion y corresponde cada seccion a un archivo que muestra una pagina del catalogo, sirven para personalizar el titulo y tags de cada pagina, e.g.: index corresponde a la portada y las categorias, product_info a la pagina de descripcion del producto, specials a la de ofertas, etc...<br>Usted puede rellenar las etiquetas de cada una de esas paginas, y aparecera lo que usted inserte ahi en el titulo y los Tags (description y keywords) de esa pagina en particular, si ademas marca las casillas HTTA, HTDA y HTKA se uniran los datos de las casillas por defecto, titulo, descripcion y keywords que haya puesto en la seccion por defecto (las tres primeras casillas de la lista, (para mas informacion sobre las otras casillas HTDA,... y demas pulse en Ayuda).<br>
Y el orden es el siguiente, primero el titulo de la pagina en particular que escriba en su seccion correspondiente, y despues aparecera el titulo de la seccion por defecto, e.g. en la pagina de Productos Nuevos aparecera: Nuevos Productos osCommerce, si no marca HTTA para products_news entonces solo aparecera: Nuevos Productos<br>
En el caso de la seccion del index si los datos que va a poner son los mismos que en el de la seccion por defecto entonces no marque esas casillas (HTDA,...) para que no se dupliquen los datos de titulo y los Tags.<br>
Nota: si el titulo de una seccion aparece en <font color="red">rojo</font>, eso significa que o bien no existe ese archivo o el archivo no tiene el codigo de Header Tags instalado en el, no hiso el cambio en ese archivo del codigo del titulo (title) por el de Header Tags, y como resultado no se muestran el titulo ni los meta tags aunque los haya definido aqui. Ese codigo era la modificacion de cambiar el codigo de la linea title de cada archivo de la carpeta catalog que se indico en las intrucciones.');
define('TEXT_FILL_TAGS', 'Esta opcion permite rellenar los meta tags con los insertados por 
Header Tags en el menu Control de Texto de Header Tags. Seleccione la opcion apropiada que desea tanto para los Tags de las categorias, como para las paginas de Fabricantes y las paginas de Productos y despues pulse el boton de Actualizar. Si selecciona: Rellenar solo los Tags vacios, entonces solo los tags que esten vacios se llenaran, los que ya esten rellenados no se sobreescribiran. Si la casilla de la pregunta - Fill products meta description with Products Description - esta marcada en Yes, entonces el meta tag de la descripcion (meta description) en la pagina de producto se rellenara con parte de la descripcion que tenga el producto. Y si introduce un numero en la casilla de cantidad de palabras (length), la descripcion se cortara en esa cantidad de palabras.');

// header_tags_controller.php & header_tags_espanol.php
define('HEADING_TITLE_CONTROLLER_EXPLAIN', '(Ayuda)');
define('HEADING_TITLE_CONTROLLER_TITLE', 'Titulo (Title):');
define('HEADING_TITLE_CONTROLLER_DESCRIPTION', 'Descripcion (Description):');
define('HEADING_TITLE_CONTROLLER_KEYWORDS', 'Palabras Clave (Keyword(s)):');
define('HEADING_TITLE_CONTROLLER_PAGENAME', 'Nombre de la Pagina:');
define('HEADING_TITLE_CONTROLLER_PAGENAME_ERROR', 'Nombre de la Pagina si ya se ha introducido -> ');
define('HEADING_TITLE_CONTROLLER_PAGENAME_INVALID_ERROR', 'Nombre de la Pagina no es correcto -> ');
define('HEADING_TITLE_CONTROLLER_NO_DELETE_ERROR', 'Borrar %s no esta permitido');

// header_tags_espanol.php
define('HEADING_TITLE_CONTROLLER_DEFAULT_TITLE', 'Titulo por Defecto:');
define('HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION', 'Descripcion (Description) por defecto:');
define('HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS', 'Palabras Clave (Keyword(s)) por defecto:');
// header_tags_fill_tags.php
define('HEADING_TITLE_CONTROLLER_CATEGORIES', 'CATEGORIAS');
define('HEADING_TITLE_CONTROLLER_MANUFACTURERS', 'FABRICANTES');
define('HEADING_TITLE_CONTROLLER_PRODUCTS', 'PRODUCTOS');
define('HEADING_TITLE_CONTROLLER_SKIPALL', 'Quitar todas los Tags');
define('HEADING_TITLE_CONTROLLER_FILLONLY', 'Rellene solo los Tags vacios');
define('HEADING_TITLE_CONTROLLER_FILLALL', 'Rellenar todos los Tags');
define('HEADING_TITLE_CONTROLLER_CLEARALL', 'Borrar todos los Tags');
?>
