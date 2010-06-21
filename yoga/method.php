<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  $yoga_query=tep_db_query('SELECT * FROM yoga.poses where school=\''.tep_db_input($_REQUEST['school']).'\' order by posename asc;');

  if(!$yoga_info=tep_db_fetch_array($yoga_query))
  {
        $isDefault=true;
  }
  else
  {
        $isDefault=false;
        $method=ucwords($_REQUEST['school']);
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<?php if($isDefault){ ?>
      <title>Methods & Yoga Schools of Practice</title>
    <meta name="Keywords" content="yoga methods, yoga schools"/>
    <meta name="Description" content="All yoga methods & schools of practice."/>

<?php }else{ ?>

<title><?php echo $method; ?> Yoga Method</title>
<meta name="Keywords" content="<?php echo $use; ?> yoga"/>
<meta name="Description" content="Details on yoga poses for <?php echo strtolower($use); ?>."/>

<?php } ?>


<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

 

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD>

	 </TD>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->

    <td width="100%" valign="top">
    <div id="content">
    
    <?php if($isDefault){?>
    <h1>Yoga Methods & Schools of Practice</h1>
    <script type="text/javascript"><!--
google_ad_client = "pub-6691107876972130";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text_image";
//2007-09-12: Yoga
google_ad_channel = "5112816388";
//-->
</script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
    <p>
        Yoga involves vast influence from numerous teachers, trainers and schools of thought. Over hundreds of years, as well as in 
        recent history, yoga has evolved to include numerous methods that focus on the body in different manners. Some
        will focus on meditation and breathing, while others may focus on stretching and strength. Below are the different schools
        of practice to begin your routine from.
    </p>
    <h2/>All Methods & Practices</h2>
    <p><ul>
    <?php
          $yoga_query=tep_db_query('SELECT school FROM yoga.poses group by school order by school asc;');

          while($yoga_info=tep_db_fetch_array($yoga_query))
          {
               ?><li><a href="/yoga/method.php?school=<?php echo urlencode(strtolower($yoga_info['school']))?>"><?php echo $yoga_info['school']; ?> Method</li> 
                <?php
          }
        
    
    ?></ul>
    
    </p>
    
    <?php }else{ ?>
        
        <h1>Yoga for <?php echo $method; ?> Method</h1>
        <script type="text/javascript"><!--
google_ad_client = "pub-6691107876972130";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text_image";
//2007-09-12: Yoga
google_ad_channel = "5112816388";
//-->
</script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
        <p>
            Below are poses from the <?php echo $method; ?> method.
        </p>
        
           <ul>  <?php 
        
   
        
        do{
            ?>
               <li><a href="/yoga/pose.php?posture=<?php echo $yoga_info['poseid']?>"><?php echo $yoga_info['posename']; ?> <b><?php echo $yoga_info['translation']; ?></b> <?php echo $yoga_info['school']; ?></a> - <?php echo $yoga_info['difficulty']; ?></li> 
                
            <?php     
        
        }while($yoga_info=tep_db_fetch_array($yoga_query)); ?>
        </ul>
        <p>
            <a href="/yoga/method.php">Find other methods</a>.
        </p>
    
    <?php } ?>
</div>
</td>
      
<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/column_right.php'); ?>
<!-- right_navigation_eof //-->
     </TABLE></TD></TR></TABLE>
<!-- body_eof //-->

<!-- footer //-->
<?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
