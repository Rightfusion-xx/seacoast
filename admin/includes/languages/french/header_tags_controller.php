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
define('HEADING_TITLE_CONTROLLER', 'Contrôleur de métatags');
define('HEADING_TITLE_ENGLISH', 'Métatags - Français');
define('HEADING_TITLE_FILL_TAGS', 'Métatags - Recopier tags');
define('TEXT_INFORMATION_ADD_PAGE', '<b>Ajouter une nouvelle page</b> - Cette option ajoute le code dans les pages mentionnées
ci-dessous. Ceci n\'ajoute pas de page. Pour ajouter une page, entrez le nom du fichier (avec ou sans l\'extension .php).');
define('TEXT_INFORMATION_DELETE_PAGE', '<b>Effacer une page</b> - Cette option efface le code de la page
ci-dessous.');
define('TEXT_INFORMATION_CHECK_PAGES', '<b>Vérifier pages manquantes</b> - Cette option vous permet de vérifier quels fichiers dans votre
boutique n\'a pas de métatags. Toutes les pages ne doivent pas avoir de métatags. Par exemple,
les pages qui utilisent SSL comme Login ou Create Account. Pour voir les pages, cliquer sur mise à jour et sélectionner dans la lsite déroulante.');

define('TEXT_PAGE_TAGS', 'Afin que le contrôleur de métatag affiche les informations sur une page, une entrée pour cette
page doit être faite dans les fichiers includes/header_tags.php et includes/languages/french/header_tags.php
(où french est la langue utilisée). Ceci vous pemettra d\'ajouter, effacer
et vériier le code dans ces fichiers.');
define('TEXT_ENGLISH_TAGS', 'Le but du contôleur de métatag est de donner pour chacune des pages de votre boutique
un titre et des méta-tags uniques.Faites les changements nécessaires ci-après.');
define('TEXT_FILL_TAGS', 'Cette option vous permet de recopier les méta tags ajouté par le contrôleur.
Sélectionner les paramètres appropriés pour les tags des catégories et des produits
et cliquer sur mise à jour. Si vous sélectionnez Recopier seulement les tags vides, les tags déjà
existant ne seront pas effacés.');
// header_tags_controller.php & header_tags_english.php
define('HEADING_TITLE_CONTROLLER_EXPLAIN', '(Explication)');
define('HEADING_TITLE_CONTROLLER_TITLE', 'Titre :');
define('HEADING_TITLE_CONTROLLER_DESCRIPTION', 'Description :');
define('HEADING_TITLE_CONTROLLER_KEYWORDS', 'Mots-clés(s) :');
define('HEADING_TITLE_CONTROLLER_PAGENAME', 'Nom de la page :');
define('HEADING_TITLE_CONTROLLER_PAGENAME_ERROR', 'Ce nom de pag existe déjà -> ');
// header_tags_english.php
define('HEADING_TITLE_CONTROLLER_DEFAULT_TITLE', 'Titre par défaut :');
define('HEADING_TITLE_CONTROLLER_DEFAULT_DESCRIPTION', 'Description par défaut :');
define('HEADING_TITLE_CONTROLLER_DEFAULT_KEYWORDS', 'Mots-clé(s) par défaut :');
// header_tags_fill_tags.php
define('HEADING_TITLE_CONTROLLER_CATEGORIES', 'CATEGORIES');
define('HEADING_TITLE_CONTROLLER_PRODUCTS', 'PRODUITS');
define('HEADING_TITLE_CONTROLLER_SKIPALL', 'Passer toutes les balises');
define('HEADING_TITLE_CONTROLLER_FILLONLY', 'Recopier seulement les balises vides');
define('HEADING_TITLE_CONTROLLER_FILLALL', 'Recopier toutes les balises');
define('HEADING_TITLE_CONTROLLER_CLEARALL', 'Effacer toutes les balises');
?>