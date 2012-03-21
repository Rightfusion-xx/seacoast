<?php
/*
  $Id: header_tags_popup_help.php,v 1.0 2005/09/22 
   
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

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
   <td class="popupText"><p><b>Wofür werden HTTA, HTDA, HTKA and HTCA genutzt?</b><br><br>
    Headertags besitzen ein Standardset an Tags. Sie können Ihr eigenes Tagset für jede Seite definieren (Dieses wird mit einem Standardtagset erzeugt.).

<pre>
HT = Headertags  
T  = Titel 
A  = Alle 
D  = Beschreibung
K  = Keywords
C  = Kategorien *
</pre>  
<b>* Hinweis:</b> Die HTCA Option funktioniert nur für index und product_info Seiten.
Für die index Seiten sollte der Kategorienname im Titel angezeigt werden. Für die product_info Seiten, wird, wenn aktiviert der Text aus dem Textfeld an den Titel, die Bewschreibung und die Keywords angehängt.<br><br>

Wenn HTTA aktiviert wurde, werden die Headertiteltag überall angezeigt.
(Standardtitel plus den von Ihnen definierten Titel).<br><br>

Wenn Sie die Option aktivieren werden also beide Titel angezeigt.
Wenn also beispielsweise Ihr Titel "mein" lautet und der osCommerce Standardtitel "osCommerce" passiert folgendes.<br>
<pre>
Wenn HTTA aktiviert ist sieht der Titel so aus:
 mein Titel
Wenn HTTA deaktiviert ist sieht der Titel so aus:
 mein
</pre>
</p>
<p>Wenn der Name des bereichs in <font color="red">rot</font> dargestellt wird bedeutet das das die datei nicht den erforderlichen Headertagcode besitzt. Bitte sehen Sie sich die datei Install_Catalog.txt für weitere Instruktionen an.</p>
  </td>
 </tr> 
</table>
