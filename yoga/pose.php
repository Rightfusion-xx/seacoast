<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  $yoga_query=tep_db_query('SELECT * FROM yoga.poses where poseid="'.tep_db_prepare_input($_REQUEST['posture']).'" order by posename asc;');
  
  if(!$yoga_info=tep_db_fetch_array($yoga_query))
  {
        redir301('/yoga/');
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo $yoga_info['posename']; ?> <?php echo $yoga_info['translation']; ?> Tutorial Asana Pose Posture</title>
<meta name="Keywords" content="<?php echo $yoga_info['posename']; ?>,<?php echo $yoga_info['translation']; ?>, <?php echo $yoga_info['uses']; ?>"/>
<meta name="Description" content="Step by step <?php echo $yoga_info['posename']; ?> yoga pose instructions including video and <?php echo $yoga_info['school']; ?> details."/>

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
    
    
        <h1><?php echo $yoga_info['posename']; ?> <?php echo $yoga_info['translation']; ?></h1>
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
        <p><a href="/yoga/myo.php" style="font-size:12pt;font-weight:bold;">Yoga Videos Online - Sign Up Now!</a></p>
        <p>
            <?php echo $yoga_info['overview']; ?>
        </p>
        <h2>Online Yoga Videos - Try it Free!</h2>
        <p>
            <ul>
                <li>Practice from home or while travelling</li>
                <li>Get expert advice & learn from pros</li>
                <li>Instant access to extensive yoga <b>videos</b> library</li>
                <li>It's <b>FREE</b> for 10 days!</li>
            </ul>            For more information, videos, and step by step instructions on <?php echo $yoga_info['posename']; ?> yoga posture (<?php echo $yoga_info['translation']; ?>),
            visit the Seacoast Yoga video library. Sign up <b>FREE</b> for 10 days and watch unlimited DVD quality yoga videos from the comfort of your home. Explore methods and schools, such as <?php echo $yoga_info['school']; ?> yoga and 
            learn from in-depth teachers. Ask questions and get answers from real experts.

        </p>
        <p>
            <a href="/yoga/myo.php" style="font-size:10pt;font-weight:bold;">More Information...</a>
        </p>        
        
        <h2>Details</h2>
        
        <p>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td>Skill Level</td><td width="10"></td><td><b><?php echo $yoga_info['difficulty']; ?></b></td>
                </tr>
                <tr>
                    <td>Routine Sequence </td><td width="10"></td><td><b><?php echo $yoga_info['sequence']; ?></b></td>
                </tr>
                <tr>
                    <td>School of Yoga </td><td width="10"></td><td><b><?php echo $yoga_info['school']; ?></b></td>
                </tr>
            </table>
        </p>

        
        <h2>Benefits</h2>
        <p>
            <ul>
                <?php 
                    $uses=split(',',str_replace(', ',',',str_replace('  ',' ',$yoga_info['use'])));
                    $benefit_links='';
                    $poseposture=false;
                    foreach($uses as $usename)
                    { ?>
                        <li><?php echo ucwords($usename)?></li>
                      <?php
                      
                      $benefits.='<a href="/yoga/benefits.php?use='.urlencode(strtolower($usename)).'">'.ucwords($usename);
                      if($poseposture){
                        $benefits.=' Posture'; }else{
                        $benefits.=' Pose'; }
                      $benefits.='</a> &nbsp;';
                      $poseposture=!$poseposture;
                      
                    }
                ?>
            </ul>
        </p>

        <h2>Related Yoga Information</h2>
        <p>
            <?php echo $benefits; ?>
        </p>
        <p>
            <a href="/yoga/method.php?school=<?php echo urlencode(strtolower($yoga_info['school']))?>">More <?php echo $yoga_info['school']; ?> Yoga Poses & Postures</a>
        </p>
        <p>
            <a href="/yoga/routine.php?sequence=<?php echo urlencode(strtolower($yoga_info['sequence']))?>">Find <?php echo $yoga_info['sequence']; ?> Routines & Sequences</a>
        </p>
        <p>
            <a href="/yoga/skill.php?difficulty=<?php echo urlencode(strtolower($yoga_info['difficulty']))?>">See All <?php echo $yoga_info['difficulty']; ?> Yoga Asanas</a>
        </p>
        
        
        <?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/render_pills.php');?>
        <?php render_pills(tep_db_query('SELECT products_name, left(products_head_desc_tag,300) as products_description, p.products_id FROM products p join products_description pd on pd.products_id=p.products_id where products_head_keywords_tag like \'%'.str_replace(',','%\' or products_head_keywords_tag like \'%',str_replace('  ',' ',$yoga_info['use'])).'%\' limit 0,25'));?>

</div>

</td>
      
<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/column_right.php'); ?>    



<form action="/yoga/myo.php">
    <input type="submit" style="background:orange;font-size:10pt;font-weight:bold;word-wrap:break-word;width:190px;margin-left:20px;" value="Online Yoga Videos"/>
</form>
<!-- right_navigation_eof //-->
     </TABLE>
     
</TD></TR></TABLE>
<!-- body_eof //-->

<!-- footer //-->
<?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
