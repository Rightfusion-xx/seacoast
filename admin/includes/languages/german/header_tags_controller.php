<?php
/*
  $Id: header_tags.php,v 1.6 2005/04/10 14:07:36 hpdl Exp $
  Created by Jack York from http://www.oscommerce-solution.com
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('HEADING_TITLE_CONTROLLER', 'Header Tags Controller');
define('HEADING_TITLE_ENGLISH', 'Header Tags - Deutsch');
define('HEADING_TITLE_FILL_TAGS', 'Header Tags - Tags ausf�llen');
define('TEXT_INFORMATION_ADD_PAGE', '<b>neue Seite hinzuf�gen</b> - Diese option f�gt den Code f�r eine Seite in die Dateien die vorher angegeben wurden. Es wird keine aktuelle Seite hinzugef�gt. Um eine Seite hinzuzuf�gen geben Sie bitte den Namen der Datei an an, mit oder ohne der PHP Endung.');
define('TEXT_INFORMATION_DELETE_PAGE', '<b>Neue Seite l�schen</b> - Diese Option entfernt den Code f�r die angegebenen Seiten.'); 
define('TEXT_INFORMATION_CHECK_PAGES', '<b>�berpr�fe fehlende Seiten</b> - Diese option erlaubt es zu �berpr�fen welche Seiten keinen Eintrag in den angegebenen Dateien haben. Nicht alle Seiten sollten Eintr�ge haben. Beispielsweise nicht die Loginseiten oder die Accounterstellungsseiten unter SSL. Um die Seiten zu sehen klicken Sie auf Update und w�hlen die Drop Down Liste.'); 

define('TEXT_PAGE_TAGS', 'Um Informationen auf einer Seite durch Headertags anzeigen zu k�nnen ist ein Eintrag f�r die Seite in den Dateien  includes/header_tags.php and includes/languages/german/header_tags.php  notwendig. Die Optionen dieser Seite erlauben es Ihnen Code f�r diese Dateien hinzuzuf�gen, zu editieren oder zu l�schen.');
define('TEXT_ENGLISH_TAGS', 'Der Hauptzweck der Header tags ist es jeder Seite des Shops einen einzigartigen Titel und einzigartige Metatags zu geben. Die Standardeinstellungen k�nnen das nicht und m�ssen daher ver�ndert werden. �ndern Sie die Einstellungen um Ihr Hauptkeyword f�r den Shop nutzen zu k�nnen. Die individuellen Bereiche wurden nach der Seite die Sie darstellt benannt. Um den Titel der Seite zu ver�ndern muss der Titel der Sektion ge�ndert werden. Wenn der Titel einer Seite wird in <font color="red">rot</font> dargestellt, bedeutet das das die Datei nicht die erforderlichen Headertags besitzt und der Titel und die Metatags nicht so angezeigt werden wie hier angegeben.');
define('TEXT_FILL_TAGS', 'Diese Option erlaubt das Ausf�llen der Metatags mit Headertags. W�hlen Sie die passenden Einstellungen f�r Kategorien- und Produkttags und klicken Sie dann auf Update. Wenn Sie "Nur leere Tags bef�llen" gew�hlt haben werden Tags die bereits bef�llt sind nicht �berschrieben. Wenn die "Bef�lle Produktmetatagbeschreibung mit Produktbeschreibung" Option gew�hlt wurde wird der metadescription Tag mit der produktbeschreibung bef�llt. Wenn Sie eine Zahl in das L�ngenfeld eingebgeben haben wird die Beschreibung auf diese Anzahl Zeichen gek�rzt.');

// header_tags_controller.php & header_tags_german.php
define('HEADING_TITLE_CONTROLLER_EXPLAIN', '(Erkl�rung)');
define('HEADING_TITLE_CONTROLLER_TITLE', 'Titel:');
define('HEADING_TITLE_CONTROLLER_DESCRIPTION', 'Beschreibung:');
define('HEADING_TITLE_CONTROLLER_KEYWORDS', 'Keyword(s):');
define('HEADING_TITLE_CONTROLLER_PAGENAME', 'Seitenname:');
define('HEADING_TITLE_CONTROLLER_PAGENAME_ERROR', 'Seitenname wurde bereits angegeben -> ');
define('HEADING_TITLE_CONTROLLER_PAGENAME_INVALID_ERROR', 'Seitenname ist fehlerhaft -> ');
define('HEADING_TITLE_CONTROLLER_NO_DELETE_ERROR', 'L�schen von %s ist nicht erlaubt');

// header_tags_german.php
define('HEADING_TITLE_CONTROLLER_DEFAULT_TITLE', 'Standardtitel:');
define('HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION', 'Standardbeschreibung:');
define('HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS', 'Standardkeyword(s):');
// header_tags_fill_tags.php
define('HEADING_TITLE_CONTROLLER_CATEGORIES', 'CATEGORIES');
define('HEADING_TITLE_CONTROLLER_MANUFACTURERS', 'MANUFACTURERS');
define('HEADING_TITLE_CONTROLLER_PRODUCTS', 'PRODUCTS');
define('HEADING_TITLE_CONTROLLER_SKIPALL', 'Alle Tags �bergehen');
define('HEADING_TITLE_CONTROLLER_FILLONLY', 'Nur leere Tags bef�llen');
define('HEADING_TITLE_CONTROLLER_FILLALL', 'All Tags bef�llen');
define('HEADING_TITLE_CONTROLLER_CLEARALL', 'Alle Tags l�schen');
?>
