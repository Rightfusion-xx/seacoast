<?php

/*

  $Id: links_submit.php,v 1.00 2003/10/03 Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



// needs to be included earlier to set the success message in the messageStack

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS_SUBMIT);



  $process = false;

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {

    $process = true;



    $links_title = tep_db_prepare_input($HTTP_POST_VARS['links_title']);

    $links_url = tep_db_prepare_input($HTTP_POST_VARS['links_url']);

    $links_category = tep_db_prepare_input($HTTP_POST_VARS['links_category']);

    $links_description = tep_db_prepare_input($HTTP_POST_VARS['links_description']);

    $links_image = tep_db_prepare_input($HTTP_POST_VARS['links_image']);

    $links_contact_name = tep_db_prepare_input($HTTP_POST_VARS['links_contact_name']);

    $links_contact_email = tep_db_prepare_input($HTTP_POST_VARS['links_contact_email']);

    $links_reciprocal_url = tep_db_prepare_input($HTTP_POST_VARS['links_reciprocal_url']);



    $error = false;



    if (strlen($links_title) < ENTRY_LINKS_TITLE_MIN_LENGTH) {

      $error = true;



      $messageStack->add('submit_link', ENTRY_LINKS_TITLE_ERROR);

    }



    if (strlen($links_url) < ENTRY_LINKS_URL_MIN_LENGTH) {

      $error = true;



      $messageStack->add('submit_link', ENTRY_LINKS_URL_ERROR);

    }



    if (strlen($links_description) < ENTRY_LINKS_DESCRIPTION_MIN_LENGTH) {

      $error = true;



      $messageStack->add('submit_link', ENTRY_LINKS_DESCRIPTION_ERROR);

    }



    if (strlen($links_contact_name) < ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH) {

      $error = true;



      $messageStack->add('submit_link', ENTRY_LINKS_CONTACT_NAME_ERROR);

    }



    if (strlen($links_contact_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {

      $error = true;



      $messageStack->add('submit_link', ENTRY_EMAIL_ADDRESS_ERROR);

    } elseif (tep_validate_email($links_contact_email) == false) {

      $error = true;



      $messageStack->add('submit_link', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);

    }



    if (strlen($links_reciprocal_url) < ENTRY_LINKS_URL_MIN_LENGTH) {

      $error = true;



      $messageStack->add('submit_link', ENTRY_LINKS_RECIPROCAL_URL_ERROR);

    }



    if ($error == false) {

      if($links_image == 'http://') {

        $links_image = '';

      }



      // default values

      $links_date_added = 'now()';

      $links_status = '1'; // Pending approval

      $links_rating = '0'; 



      $sql_data_array = array('links_url' => $links_url,

                              'links_image_url' => $links_image,

                              'links_contact_name' => $links_contact_name,

                              'links_contact_email' => $links_contact_email,

                              'links_reciprocal_url' => $links_reciprocal_url, 

                              'links_date_added' => $links_date_added, 

                              'links_status' => $links_status, 

                              'links_rating' => $links_rating);



      tep_db_perform(TABLE_LINKS, $sql_data_array);



      $links_id = tep_db_insert_id();



      $categories_query = tep_db_query("select link_categories_id from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_name = '" . $links_category . "' and language_id = '" . (int)$languages_id . "'");



      $categories = tep_db_fetch_array($categories_query);

      $link_categories_id = $categories['link_categories_id'];



      tep_db_query("insert into " . TABLE_LINKS_TO_LINK_CATEGORIES . " (links_id, link_categories_id) values ('" . (int)$links_id . "', '" . (int)$link_categories_id . "')");



      $language_id = $languages_id;



      $sql_data_array = array('links_id' => $links_id, 

                              'language_id' => $language_id, 

                              'links_title' => $links_title,

                              'links_description' => $links_description);



      tep_db_perform(TABLE_LINKS_DESCRIPTION, $sql_data_array);



// build the message content

      $name = $links_contact_name;



      $email_text = sprintf(EMAIL_GREET_NONE, $links_contact_name);



      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;



      tep_mail($name, $links_contact_email, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);



      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_OWNER_SUBJECT, EMAIL_OWNER_TEXT, $name, $links_contact_email);



      tep_redirect(tep_href_link(FILENAME_LINKS_SUBMIT_SUCCESS, '', 'SSL'));

    }

  }



  // links breadcrumb

  $breadcrumb->add(NAVBAR_TITLE_1, FILENAME_LINKS);



  if (isset($HTTP_GET_VARS['lPath'])) {

    $link_categories_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$HTTP_GET_VARS['lPath'] . "' and language_id = '" . (int)$languages_id . "'");

    $link_categories_value = tep_db_fetch_array($link_categories_query);



    $breadcrumb->add($link_categories_value['link_categories_name'], FILENAME_LINKS . '?lPath=' . $lPath);

  } 



  $breadcrumb->add(NAVBAR_TITLE_2);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script language="javascript"><!--

var form = "";

var submitted = false;

var error = false;

var error_message = "";



function check_input(field_name, field_size, message) {

  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {

    var field_value = form.elements[field_name].value;



    if (field_value == '' || field_value.length < field_size) {

      error_message = error_message + "* " + message + "\n";

      error = true;

    }

  }

}



function check_form(form_name) {

  if (submitted == true) {

    alert("<?php echo JS_ERROR_SUBMITTED; ?>");

    return false;

  }



  error = false;

  form = form_name;

  error_message = "<?php echo JS_ERROR; ?>";



  check_input("links_title", <?php echo ENTRY_LINKS_TITLE_MIN_LENGTH; ?>, "<?php echo ENTRY_LINKS_TITLE_ERROR; ?>");

  check_input("links_url", <?php echo ENTRY_LINKS_URL_MIN_LENGTH; ?>, "<?php echo ENTRY_LINKS_URL_ERROR; ?>");

  check_input("links_description", <?php echo ENTRY_LINKS_DESCRIPTION_MIN_LENGTH; ?>, "<?php echo ENTRY_LINKS_DESCRIPTION_ERROR; ?>");

  check_input("links_contact_name", <?php echo ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_LINKS_CONTACT_NAME_ERROR; ?>");

  check_input("links_contact_email", <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>, "<?php echo ENTRY_EMAIL_ADDRESS_ERROR; ?>");

  check_input("links_reciprocal_url", <?php echo ENTRY_LINKS_URL_MIN_LENGTH; ?>, "<?php echo ENTRY_LINKS_RECIPROCAL_URL_ERROR; ?>");



  if (error == true) {

    alert(error_message);

    return false;

  } else {

    submitted = true;

    return true;

  }

}



function popupWindow(url) {

  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=320,screenX=150,screenY=150,top=150,left=150')

}

//--></script>

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

    <td width="100%" valign="top"><?php echo tep_draw_form('submit_link', tep_href_link(FILENAME_LINKS_SUBMIT, '', 'SSL'), 'post', 'onSubmit="return check_form(submit_link);"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

                <td class="pageHeading" align="right">&nbsp;</td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="smallText"><br><?php echo TEXT_MAIN; ?></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  if ($messageStack->size('submit_link') > 0) {

?>

      <tr>

        <td><?php echo $messageStack->output('submit_link'); ?></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  }

?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td class="main"><b><?php echo CATEGORY_WEBSITE; ?></b></td>

           <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="60%" cellspacing="2" cellpadding="2">

              <tr>

                <td class="main" width="25%"><?php echo ENTRY_LINKS_TITLE; ?></td>

                <td class="main"><?php echo tep_draw_input_field('links_title') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_TITLE_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_TITLE_TEXT . '</span>': ''); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo ENTRY_LINKS_URL; ?></td>

                <td class="main"><?php echo tep_draw_input_field('links_url', 'http://') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_URL_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_URL_TEXT . '</span>': ''); ?></td>

              </tr>

<?php

  //link category drop-down list

  $categories_array = array();

  $categories_query = tep_db_query("select lcd.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lcd.language_id = '" . (int)$languages_id . "'order by lcd.link_categories_name");

  while ($categories_values = tep_db_fetch_array($categories_query)) {

    $categories_array[] = array('id' => $categories_values['link_categories_name'], 'text' => $categories_values['link_categories_name']);

  }



  if (isset($HTTP_GET_VARS['lPath'])) {

    $current_categories_id = $HTTP_GET_VARS['lPath'];



    $current_categories_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id ='" . (int)$current_categories_id . "' and language_id ='" . (int)$languages_id . "'");

    if ($categories = tep_db_fetch_array($current_categories_query)) {

      $default_category = $categories['link_categories_name'];

    } else {

      $default_category = '';

    }

  }

?>

              <tr>

                <td class="main"><?php echo ENTRY_LINKS_CATEGORY; ?></td>

                <td class="main">

<?php

    echo tep_draw_pull_down_menu('links_category', $categories_array, $default_category);



    if (tep_not_null(ENTRY_LINKS_CATEGORY_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_LINKS_CATEGORY_TEXT;

?>

                </td>

              </tr>

              <tr>

                <td class="main" valign="top"><?php echo ENTRY_LINKS_DESCRIPTION; ?></td>

                <td class="main"><?php echo tep_draw_textarea_field('links_description', 'hard', 20, 5) . '&nbsp;' . (tep_not_null(ENTRY_LINKS_DESCRIPTION_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_DESCRIPTION_TEXT . '</span>': ''); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo ENTRY_LINKS_IMAGE; ?></td>

                <td class="main"><?php echo tep_draw_input_field('links_image', 'http://') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_IMAGE_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_IMAGE_TEXT . '</span>': ''); ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_LINKS_HELP) . '\')">' . TEXT_LINKS_HELP_LINK . '</a>'; ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><b><?php echo CATEGORY_CONTACT; ?></b></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table width="60%" border="0" cellspacing="2" cellpadding="2">

              <tr>

                <td class="main" width="25%"><?php echo ENTRY_LINKS_CONTACT_NAME; ?></td>

                <td class="main"><?php echo tep_draw_input_field('links_contact_name') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_CONTACT_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_CONTACT_NAME_TEXT . '</span>': ''); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>

                <td class="main"><?php echo tep_draw_input_field('links_contact_email') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><b><?php echo CATEGORY_RECIPROCAL; ?></b></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table width="60%" border="0" cellspacing="2" cellpadding="2">

              <tr>

                <td class="main" width="25%"><?php echo ENTRY_LINKS_RECIPROCAL_URL; ?></td>

                <td class="main"><?php echo tep_draw_input_field('links_reciprocal_url', 'http://') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_RECIPROCAL_URL_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_RECIPROCAL_URL_TEXT . '</span>': ''); ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_LINKS_HELP) . '\')">' . TEXT_LINKS_HELP_LINK . '</a>'; ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

          <tr class="infoBoxContents">

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>

                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

    </table></form></td>

<!-- body_text_eof //-->

    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- right_navigation //-->

<?php include(DIR_WS_INCLUDES . 'column_right.php'); ?>

<!-- right_navigation_eof //-->

    </table></td>

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

