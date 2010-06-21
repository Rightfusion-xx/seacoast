<?php
/*
  $Id: links_setup.php,v 1.00 2003/10/02 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // check if links db already installed
  $links_check_query = tep_db_query("select * from configuration_group where configuration_group_title = 'Links'");

  if (tep_db_num_rows($links_check_query) > 0) {
    echo 'Looks like Links Manager is already installed. Aborting...';
    tep_exit();
  }

  $links_sql_array = array(array("DROP TABLE IF EXISTS link_categories"), 
                array("CREATE TABLE link_categories (link_categories_id int NOT NULL auto_increment, link_categories_image varchar(64), link_categories_sort_order int(3), link_categories_date_added datetime, link_categories_last_modified datetime,  link_categories_status tinyint(1) NOT NULL, PRIMARY KEY (link_categories_id), KEY idx_link_categories_date_added (link_categories_date_added))"), 
                array("DROP TABLE IF EXISTS link_categories_description"), 
                array("CREATE TABLE link_categories_description (link_categories_id int DEFAULT '0' NOT NULL, language_id int DEFAULT '1' NOT NULL, link_categories_name varchar(32) NOT NULL, link_categories_description text, PRIMARY KEY (link_categories_id, language_id), KEY idx_link_categories_name (link_categories_name))"), 
                array("DROP TABLE IF EXISTS links_to_link_categories"), 
                array("CREATE TABLE links_to_link_categories (links_id int NOT NULL, link_categories_id int NOT NULL, PRIMARY KEY (links_id,link_categories_id))"),
                array("DROP TABLE IF EXISTS links"),
                array("CREATE TABLE links (links_id int NOT NULL auto_increment, links_url varchar(255), links_reciprocal_url varchar(255), links_image_url varchar(255), links_contact_name varchar(64), links_contact_email varchar(96), links_date_added datetime NOT NULL, links_last_modified datetime, links_status tinyint(1) NOT NULL, links_clicked int NOT NULL default '0', links_rating tinyint(1) NOT NULL, PRIMARY KEY (links_id), KEY idx_links_date_added (links_date_added))"),
                array("DROP TABLE IF EXISTS links_description"),
                array("CREATE TABLE links_description (links_id int NOT NULL auto_increment, language_id int NOT NULL default '1', links_title varchar(64) NOT NULL default '', links_description text, PRIMARY KEY  (links_id,language_id), KEY links_title (links_title))"),
                array("DROP TABLE IF EXISTS links_status"),
                array("CREATE TABLE links_status (links_status_id int DEFAULT '0' NOT NULL, language_id int DEFAULT '1' NOT NULL, links_status_name varchar(32) NOT NULL, PRIMARY KEY (links_status_id, language_id), KEY idx_links_status_name (links_status_name))"));

  $db_error = false;

  // create tables
  foreach ($links_sql_array as $sql_array) {
    foreach ($sql_array as $value) {
      //echo $value . '<br>';
      if (tep_db_query($value) == false) {
        $db_error = true;
      }
    }
  }

  $links_sql_array = array(array("INSERT INTO links_status VALUES ( '1', '1', 'Pending')"),
                     array("INSERT INTO links_status VALUES ( '2', '1', 'Approved')"),
                     array("INSERT INTO links_status VALUES ( '3', '1', 'Disabled')"),
                     array("INSERT INTO links_status VALUES ( '4', '1', 'Waiting')"),

                     array("INSERT INTO links_status VALUES ( '1', '2', 'Pending')"),
                     array("INSERT INTO links_status VALUES ( '2', '2', 'Approved')"),
                     array("INSERT INTO links_status VALUES ( '3', '2', 'Disabled')"),
                     array("INSERT INTO links_status VALUES ( '4', '2', 'Waiting')"),

                     array("INSERT INTO links_status VALUES ( '1', '3', 'Pending')"),
                     array("INSERT INTO links_status VALUES ( '2', '3', 'Approved')"),
                     array("INSERT INTO links_status VALUES ( '3', '3', 'Disabled')"),
                     array("INSERT INTO links_status VALUES ( '4', '3', 'Waiting')"),

                     array("INSERT INTO links_status VALUES ( '1', '4', 'Pending')"),
                     array("INSERT INTO links_status VALUES ( '2', '4', 'Approved')"),
                     array("INSERT INTO links_status VALUES ( '3', '4', 'Disabled')"),
                     array("INSERT INTO links_status VALUES ( '4', '4', 'Waiting')"));

  $db_error = false;

  // create tables
  foreach ($links_sql_array as $sql_array) {
    foreach ($sql_array as $value) {
      if (tep_db_query($value) == false) {
        $db_error = true;
      }
    }
  }

  // create configuration group
  $group_query = "INSERT INTO configuration_group (configuration_group_title, configuration_group_description, sort_order) VALUES ('Links', 'Links Manager configuration options', '99')";

  if (tep_db_query($group_query) == false) {
    $db_error = true;
  }

  $configuration_group_id = tep_db_insert_id();

  // create configuration variables
  $config_sql_array = array(array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Click Count', 'ENABLE_LINKS_COUNT', 'True', 'Enable links click count.', '" . $configuration_group_id . "', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"), 
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Spider Friendly Links', 'ENABLE_SPIDER_FRIENDLY_LINKS', 'True', 'Enable spider friendly links (recommended).', '" . $configuration_group_id . "', '2', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"), 
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Links Image Width', 'LINKS_IMAGE_WIDTH', '120', 'Maximum width of the links image.', '" . $configuration_group_id . "', '3', now())"), 
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Links Image Height', 'LINKS_IMAGE_HEIGHT', '60', 'Maximum height of the links image.', '" . $configuration_group_id . "', '4', now())"), 
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Link Image', 'LINK_LIST_IMAGE', '1', 'Do you want to display the Link Image?', '" . $configuration_group_id . "', '5', now())"), 
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Link URL', 'LINK_LIST_URL', '4', 'Do you want to display the Link URL?', '" . $configuration_group_id . "', '6', now())"), 
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Link Title', 'LINK_LIST_TITLE', '2', 'Do you want to display the Link Title?', '" . $configuration_group_id . "', '7', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Link Title as links', 'TITLES_AS_LINKS', 'False', 'Make the links title a link.', '" . $configuration_group_id . "', '8', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Links Category images', 'SHOW_LINKS_CATEGORIES_IMAGE', 'True', 'Display the images for the Links Categories.', '" . $configuration_group_id . "', '9', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Link Description', 'LINK_LIST_DESCRIPTION', '3', 'Do you want to display the Link Description?', '" . $configuration_group_id . "', '10', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Link Click Count', 'LINK_LIST_COUNT', '5', 'Do you want to display the Link Click Count?', '" . $configuration_group_id . "', '11', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Link Title Minimum Length', 'ENTRY_LINKS_TITLE_MIN_LENGTH', '2', 'Minimum length of link title.', '" . $configuration_group_id . "', '12', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Link URL Minimum Length', 'ENTRY_LINKS_URL_MIN_LENGTH', '10', 'Minimum length of link URL.', '" . $configuration_group_id . "', '13', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Link Description Minimum Length', 'ENTRY_LINKS_DESCRIPTION_MIN_LENGTH', '10', 'Minimum length of link description.', '" . $configuration_group_id . "', '14', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Link Description Maximum Length', 'ENTRY_LINKS_DESCRIPTION_MAX_LENGTH', '200', 'Maximum length of link description.', '" . $configuration_group_id . "', '15', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Link Contact Name Minimum Length', 'ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH', '2', 'Minimum length of link contact name.', '" . $configuration_group_id . "', '16', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Links Check Phrase', 'LINKS_CHECK_PHRASE', '" . $HTTP_SERVER_VARS['SERVER_NAME'] . "', 'Phrase to look for, when you perform a link check.', '" . $configuration_group_id . "', '17', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Links open in new page', 'LINKS_OPEN_NEW_PAGE', 'True', 'Open links in new page when clicked.', '" . $configuration_group_id . "', '18', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"),
                      array("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Maximum Links to Display', 'MAX_LINKS_DISPLAY', '20', 'How many links should be displayed per page?', '" . $configuration_group_id . "', '19', now())"));


  foreach ($config_sql_array as $sql_array) {
    foreach ($sql_array as $value) {
      //echo $value . '<br>';
      if (tep_db_query($value) == false) {
        $db_error = true;
      }
    }
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
	  <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Links Manager Setup'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php
  if ($db_error == false) {
    echo 'Database successfully updated!!!';
  } else {
    echo 'Error encountered during database update.';
  }
?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
