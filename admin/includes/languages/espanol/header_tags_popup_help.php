<?php
/*
  $Id: header_tags_popup_help.php,v 1.0 2005/09/22 
   
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  
  Traduction to spanish by nicko107 www.oscommerce-tutoriales.com - Demo working in: www.oscommerce-demo.com

  Copyright (c) 2003 osCommerce
  
  Released under the GNU General Public License
*/
?>
<style type="text/css">
.popupText {color: #000; font-size: 12px; } 
</style>
<table border="0" cellpadding="0" cellspacing="0" class="popupText">
  <tr><td><hr class="solid"></td></tr>
  <tr>
   <td class="popupText"><p><b>&iquest;Para que se usa HTTA, HTDA, HTKA y HTCA?</b><br><br>
    Header Tags viene con algunas etiquetas rellenadas por defecto al instalarse. Usted puede crear su propio
	sistema de etiquetas por cada pagina (ya viene con algunas creadas, como son la del index
	y las paginas de productos (products_news), las demas (specials, product_info,...) vienen vacias, puede usted rellenarlas 
	o modificar las que estan rellenas, luego pulse en el boton actualizar del final de la pagina ).

<pre>
HT = Header Tags  
T  = Title - Titulo
A  = All  - Todos
D  = Description - Descripcion
K  = Keywords - Palabras Clave
C  = Categories * - Categorias
</pre>  
<b>* Nota:</b> La opcion HTCA solo trabaja con el index ( que muestra la portada, categorias y sub-categorias) y las paginas de descripcion del producto ( product_info). 
En la pagina index, hace que el nombre de la categoria se muestre en el titulo de la pagina al entrar en una categoria. En la pagina de la descripcion del producto (product_info), si marca esta opcion HTCA, el texto que este en las casillas de product_info  se unira al titulo y a los Tags description y keywords, respectivamente, el resultado seria: de titulo de la pagina el nombre del producto mas lo que haya puesto de titulo en la seccion de product_info, y en las demas casillas ocurrira lo mismo<br><br>

Si marca la casilla de HTTA, entonces hara que Header Tags muestre el titulo por defecto en esa pagina de la seccion donde lo haya marcado
(el titulo sera el que usted haya puesto en la casilla de la seccion por defecto, seguido del que haya puesto en la casilla de la seccion, por ejemplo en la seccion specilas si ha escrito de titulo Ofertas y en la seccion por defecto deja osCommerce :, aparecera entonces de titulo de la pagina de ofertas lo siguiente: Ofertas osCommerce :, si no marca ahi la casilla HTTA solo aparecera: Ofertas).<br>
<pre>
Si marca la casilla HTTA, el titulo sera
 Misitio Oscommerce :
si no marca la casilla HTTA, el titulo sera
 Misitio
</pre>
</p>
<p>Si el nombre de la seccion aparece en <font color="red">rojo</font>, significa que en ese archivo de esa pagina no modifico el codigo del titulo colocando el codigo de Header Tags en el. Vea las instrucciones Install_Catalog.txt
para ver las intrucciones para poder hacerlo.</p>
  </td>
 </tr> 
</table>
