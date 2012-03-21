<?php




  require('includes/application_top.php');



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_INFO);



  $article_check_query = tep_db_query("select count(*) as total from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_status = '1' and a.articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and ad.articles_id = a.articles_id and ad.language_id = '" . (int)$languages_id . "'");

  $article_check = tep_db_fetch_array($article_check_query);
  
  if ($article_check['total']>0)
  {
      $article_info_query = tep_db_query("select a.articles_id, a.articles_date_added, 
	  a.articles_date_available, a.authors_id, ad.articles_name, 
	  ad.articles_description, ad.articles_url, au.authors_name 
	  from " . TABLE_ARTICLES . " a
	  join " . TABLE_ARTICLES_DESCRIPTION . " ad on ad.articles_id = a.articles_id
	  left join " . TABLE_AUTHORS . " au on a.authors_id = au.authors_id 
	  where a.articles_status = '1' and 
	  a.articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' 
	  and ad.language_id = '" . (int)$languages_id . "'");

        $article_info = tep_db_fetch_array($article_info_query);



        tep_db_query("update " . TABLE_ARTICLES_DESCRIPTION . " set articles_viewed = articles_viewed+1 where articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and language_id = '" . (int)$languages_id . "'");



        $articles_name = $article_info['articles_name'];

        $articles_author_id = $article_info['authors_id'];

        $articles_author = $article_info['authors_name'];
    }

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">



  <title><?php echo $articles_name; ?></title>



<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script language="javascript"><!--

function popupWindow(url) {

  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')

}

//--></script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
<TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->

    <td width="100%" valign="top">
    <div id="content">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">

<?php

  if ($article_check['total'] < 1) {

?>

      <tr>

        <td class="pageHeading" ><?php echo HEADING_ARTICLE_NOT_FOUND; ?></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main" ><?php echo TEXT_ARTICLE_NOT_FOUND; ?></td>

      </tr>

<?php

  } else {


?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td valign="bottom"><h1><?php echo $articles_name; ?></h1></td>

             <td class="main" align="right" valign="bottom"><?php if (tep_not_null($articles_author)) echo TEXT_BY . '<a href="' . tep_href_link(FILENAME_ARTICLES,'authors_id=' . $articles_author_id) . '">' . $articles_author . '</a>'; ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main">

          <p><?php echo stripslashes($article_info['articles_description']); ?></p>

        </td>

      </tr>

<?php

    if (tep_not_null($article_info['articles_url'])) {

?>

      <tr>

        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($article_info['articles_url']), 'NONSSL', true, false)); ?></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

    }



    if ($article_info['articles_date_available'] > date('Y-m-d H:i:s')) {

?>

      <tr>

        <td align="left" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($article_info['articles_date_available'])); ?></td>

      </tr>

<?php

    } else {

?>

      <tr>

        <td align="left" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($article_info['articles_date_added'])); ?></td>

      </tr>

<?php

    }

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

  if (ENABLE_ARTICLE_REVIEWS == 'true') {

    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_ARTICLE_REVIEWS . " where articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and approved = '1'");

    $reviews = tep_db_fetch_array($reviews_query);

?>

      <tr>

        <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>

      </tr>

<?php

    if ($reviews['count'] <= 0) {

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?></td>

      </tr>

<?php

    } else {

?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a> '; ?><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params()) . '">' . tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; ?></td>

      </tr>

<?php

    }

  }

?>

      </tr></form>

<!-- tell_a_friend //-->

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

          <tr>

            <td>

<?php

  if (ENABLE_TELL_A_FRIEND_ARTICLE == 'true') {

    if (isset($HTTP_GET_VARS['articles_id'])) {

      $info_box_contents = array();

      $info_box_contents[] = array('text' => BOX_TEXT_TELL_A_FRIEND);



      new infoBoxHeading($info_box_contents, false, false);



      $info_box_contents = array();

      $info_box_contents[] = array('form' => tep_draw_form('tell_a_friend', tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get'),

                                   'align' => 'left',

                                   'text' => TEXT_TELL_A_FRIEND . '&nbsp;' . tep_draw_input_field('to_email_address', '', 'size="10" maxlength="30" style="width: ' . (BOX_WIDTH-30) . 'px"') . '&nbsp;' . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . tep_draw_hidden_field('articles_id', $HTTP_GET_VARS['articles_id']) . tep_hide_session_id() );



      new infoBox($info_box_contents);

    }

  }

?>

            </td>

          </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>          

<!-- tell_a_friend_eof //-->

      <tr>

        <td>

<?php

//added for cross-sell

   if ( (USE_CACHE == 'true') && !SID) {

     include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);

   } else {

     include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);

    }

   }

?>

        </td>

      </tr>

    </table></div></td>

<!-- body_text_eof //-->

   
<TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
<TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
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