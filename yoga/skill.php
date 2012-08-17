<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  $yoga_query=tep_db_query('SELECT * FROM yoga.poses where difficulty=\''.tep_db_input($_REQUEST['difficulty']).'\' order by posename asc;');

  if(!$yoga_info=tep_db_fetch_array($yoga_query))
  {
        $isDefault=true;
  }
  else
  {
        $isDefault=false;
        $skill=ucwords($_REQUEST['difficulty']);
  }
  
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<?php if($isDefault){ ?>
      <title>Yoga Difficulty Levels</title>
    <meta name="Keywords" content="yoga skill level"/>
    <meta name="Description" content="Find yoga poses & asanas by difficulty and skill level."/>

<?php }else{ ?>

<title><?php echo $skill; ?> Yoga Poses & Postures</title>
<meta name="Keywords" content="<?php echo $skill; ?> yoga"/>
<meta name="Description" content="<?php echo $skill; ?> level yoga instruction on numerous poses, postures & asanas."/>

<?php } ?>


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
    <TD>
</TD>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->

    <td width="100%" valign="top">
    <div id="content">
    
    <?php if($isDefault){?>
    <h1>Yoga Difficulty Levels</h1>
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
        There are any number of skills used and challenges created through the art of yoga. The following lists asanas, poses
        and postures according to difficulty rating and skill level. Use these ratings to increase of decrease the
        strenuousness of your routine, or to locate breathing and meditation instruction.
    </p>
    <h2/>Poses According to Difficulty</h2>
    <p><ul>
    <?php
          $yoga_query=tep_db_query('SELECT difficulty FROM yoga.poses group by difficulty order by difficulty asc;');

          while($yoga_info=tep_db_fetch_array($yoga_query))
          {
               ?><li><a href="/yoga/skill.php?difficulty=<?php echo urlencode(strtolower($yoga_info['difficulty']))?>"><?php echo $yoga_info['difficulty']; ?> Asanas</li> 
                <?php
          }
        
    
    ?></ul>
    
    </p>
    
    <?php }else{ ?>
        
        <h1><?php echo $skill; ?> Level Yoga</h1>
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
            These poses are <?php echo strtolower($skill); ?> level instructions.
        </p>
        
           <ul>  <?php 
        
   
        
        do{
            ?>
               <li><a href="/yoga/pose.php?posture=<?php echo $yoga_info['poseid']?>"><?php echo $yoga_info['posename']; ?> <b><?php echo $yoga_info['translation']; ?></b> <?php echo $yoga_info['school']; ?></a> - <?php echo $yoga_info['difficulty']; ?></li> 
                
            <?php     
        
        }while($yoga_info=tep_db_fetch_array($yoga_query)); ?>
        </ul>
        <p>
            <a href="/yoga/skill.php">Find other yoga skill levels</a>.
        </p>
    
    <?php } ?>

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
