<?php


  require('includes/application_top.php');
  $arrMessages[]='';
  $validurl=false;

  
  if($_REQUEST['action']=='validate')
    {
        if($_REQUEST['email']=='')
        {
            array_push($arrMessages,'Please enter a valid email address.');
        }elseif($_REQUEST['url']=='' || strpos($_REQUEST['url'],'seacoastvitamins.com')>0)
        {
            array_push($arrMessages,'Please enter a valid url.');
        }else
        {
            //is url or email in db?
            
            
            if(tep_db_fetch_array(tep_db_query('select * from backlinks where email="' . tep_db_input($_REQUEST['email']) . '"'))){
                array_push($arrMessages, 'The email address entered has already been used. You cannot receive more than one gift certificate per email address.');
            }elseif(tep_db_fetch_array(tep_db_query('select * from backlinks where url="' . tep_db_input($_REQUEST['url']) . '"')))
            {
                array_push($arrMessages, 'The URL entered has already been used. You cannot receive more than one gift certificate per URL.');
            }
            else{
            

                    //Try to validate URL
                    if(file_get_contents($_REQUEST['url'])){
                    $html=file_get_contents($_REQUEST['url']);}
                    //$robots=file_get_contents(substr($_REQUEST['url'],strpos($_REQUEST['url'],'/',8)).'/robots.txt');
                    //$pagename=substr($_REQUEST['url'],strpos($_REQUEST['url'],'/',8)).'';
                    if(!strpos($html,'http://www.seacoastvitamins.com/product_info.php'))
                    {
                        array_push($arrMessages, 'Unfortunately, we cannot find the link to SeacoastVitamins.com on this page. Make sure the URL links to your 
                        favorite product and that the URL entered is correct. If you continue to have trouble, contact <a href="mailto:help@seacoastvitamins.com">help@seacoastvitamins.com</a>.');
                    }
                    else
                    {
                        tep_db_query('insert into backlinks(email,url) values(\'' . tep_db_input($_REQUEST['email']) . '\',\''. tep_db_input($_REQUEST['url']) . '\')');
                        $validurl=true;
                    }
            }
        }
    }
    
    
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
  <title>Tell Your Friends About Seacoast for $10 Free</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="description" content="Join for free to Seacoast Vitamins for special discounts, instantly."/>
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
      </TABLE></TD>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->


    <td width="100%" valign="top">


<div id="content">
            <h1>$10 for Sharing SeacoastVitamins.com</h1>
            <?php if($validurl){?>
            <b><h3>Thank You!</h3></b>
            <p>
                A gift certificate will be forwarded to <?php echo $_REQUEST['email']?>. SeacoastVitamins greatly appreciates your support!
            </p>
            <?php }else{ ?>
                <?php render_messages($arrMessages);?>
                <p>Seacoast is so excited over our new line-up of natural health products and our fast growing community
                of healthy minded individuals like yourself that we're offering you $10 to share your healthy habits
                with everyone else!
                </p>
                <p>
                <b>Easy</b>
                <ol>
                <li><a href="/advanced_search.php" target="_blank">Locate your favorite product</a> on SeacoastVitamins.com and link to it from your blog, online journal, 
                personal editorials, or website. </li>
                <li>Enter the URL where we can find the link to Seacoast and we'll email you a <b>$10 gift certificate</b>.*</li>
                </ol>
                
                </p>
                <form action="/we_love_seacoast.php" method="post">
                    <table><tr><td>
                    <span style="width:200px;">URL:</span></td><td><input type="text" name="url" value="<?php echo $_REQUEST['url']?>" size="35"/> <i>ex. http://www.seacoastvitamins.com/mypage.html</i></td></tr><tr>
                    <td><span style="width:200px;">Email:</span></td><td><input type="text" name="email" value="<?php echo $_REQUEST['email']?>" size="35"/></td></tr><tr>
                    <td><input type="submit" value="Check Link"/></td>
                    </tr></table>
                    <input type="hidden" name="action" value="validate"/>
                </form>
                <p>
                <i>*Some restrictions apply. $10 certificate not valid on Prostasol or items marked with an asterisk (*). 1 certificate per email address only.</i>
                Offer good through October 15th, 2007. Gift certificates expire 30 days from date of issue.
                </p><?php } ?>
                </div>
            </td>

<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
     </TABLE></TD></TR></TABLE>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
