<?php
/*
  $Id: header_tags.php,v 1.6 2005/04/10 14:07:36 hpdl Exp $
  Created by Jack York from http://www.oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Translation by: maxime
*/
define('HEADING_TITLE_CONTROLLER', 'Contr�leur de m�tatags');
define('HEADING_TITLE_ENGLISH', 'M�tatags - Fran�ais');
define('HEADING_TITLE_FILL_TAGS', 'M�tatags - Recopier tags');
define('TEXT_INFORMATION_ADD_PAGE', '<b>Ajouter une nouvelle page</b> - Cette option ajoute le code dans les pages mentionn�es
ci-dessous. Ceci n\'ajoute pas de page. Pour ajouter une page, entrez le nom du fichier (avec ou sans l\'extension .php).');
define('TEXT_INFORMATION_DELETE_PAGE', '<b>Effacer une page</b> - Cette option efface le code de la page
ci-dessous.');
define('TEXT_INFORMATION_CHECK_PAGES', '<b>V�rifier pages manquantes</b> - Cette option vous permet de v�rifier quels fichiers dans votre
boutique n\'a pas de m�tatags. Toutes les pages ne doivent pas avoir de m�tatags. Par exemple,
les pages qui utilisent SSL comme Login ou Create Account. Pour voir les pages, cliquer sur mise � jour et s�lectionner dans la lsite d�roulante.');

define('TEXT_PAGE_TAGS', 'Afin que le contr�leur de m�tatag affiche les informations sur une page, une entr�e pour cette
page doit �tre faite dans les fichiers includes/header_tags.php et includes/languages/french/header_tags.php
(o� french est la langue utilis�e). Ceci vous pemettra d\'ajouter, effacer
et v�riier le code dans ces fichiers.');
define('TEXT_ENGLISH_TAGS', 'Le but du cont�leur de m�tatag est de donner pour chacune des pages de votre boutique
un titre et des m�ta-tags uniques.Faites les changements n�cessaires ci-apr�s.');
define('TEXT_FILL_TAGS', 'Cette option vous permet de recopier les m�ta tags ajout� par le contr�leur.
S�lectionner les param�tres appropri�s pour les tags des cat�gories et des produits
et cliquer sur mise � jour. Si vous s�lectionnez Recopier seulement les tags vides, les tags d�j�
existant ne seront pas effac�s.');
// header_tags_controller.php & header_tags_english.php
define('HEADING_TITLE_CONTROLLER_EXPLAIN', '(Explication)');
define('HEADING_TITLE_CONTROLLER_TITLE', 'Titre :');
define('HEADING_TITLE_CONTROLLER_DESCRIPTION', 'Description :');
define('HEADING_TITLE_CONTROLLER_KEYWORDS', 'Mots-cl�s(s) :');
define('HEADING_TITLE_CONTROLLER_PAGENAME', 'Nom de la page :');
define('HEADING_TITLE_CONTROLLER_PAGENAME_ERROR', 'Ce nom de pag existe d�j� -> ');
// header_tags_english.php
define('HEADING_TITLE_CONTROLLER_DEFAULT_TITLE', 'Titre par d�faut :');
define('HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION', 'Description par d�faut :');
define('HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS', 'Mots-cl�(s) par d�faut :');
// header_tags_fill_tags.php
define('HEADING_TITLE_CONTROLLER_CATEGORIES', 'CATEGORIES');
define('HEADING_TITLE_CONTROLLER_PRODUCTS', 'PRODUITS');
define('HEADING_TITLE_CONTROLLER_SKIPALL', 'Passer toutes les balises');
define('HEADING_TITLE_CONTROLLER_FILLONLY', 'Recopier seulement les balises vides');
define('HEADING_TITLE_CONTROLLER_FILLALL', 'Recopier toutes les balises');
define('HEADING_TITLE_CONTROLLER_CLEARALL', 'Effacer toutes les balises');
?>