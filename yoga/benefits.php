<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  if(isset($_REQUEST['use'])){
  $yoga_query=tep_db_query('SELECT * FROM yoga.poses where poses.use like \'%'.tep_db_input($_REQUEST['use']).'%\' order by posename asc;');
      if(!$yoga_info=tep_db_fetch_array($yoga_query))
      {
            $isDefault=true;
      }
      else
      {
            $isDefault=false;
            $use=ucwords($_REQUEST['use']);
      }   } 
      else {$isDefault=true; }

  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<?php if($isDefault){ ?>
      <title>Benefits of Yoga Asanas</title>
    <meta name="Keywords" content="yoga benefits"/>
    <meta name="Description" content="Search for thousands of benefits provided by today's yoga poses, asanas & postures."/>

<?php }else{ ?>

<title>Yoga for <?php echo $use; ?></title>
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
    <h1>Benefits of Yoga</h1>
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
        Each yoga pose, asana and posture features benefits of health, relaxation, meditation or breathing
        to target specific parts of your body and character. Certain asanas may even help relieve health problems
        or stimulate your body's immune system to produce positive results 
    </p>
    <h2/>Most Popular Benefits</h2>
    <p><ul>
    <?php
          $yoga_query=tep_db_query('SELECT left(poses.use,instr(poses.use,",")-1) as benefit FROM yoga.poses group by benefit order by benefit asc limit 0,10;');

          while($yoga_info=tep_db_fetch_array($yoga_query))
          {
               ?><li><a href="/yoga/benefits.php?use=<?php echo urlencode(strtolower($yoga_info['benefit']))?>">Yoga for <?php echo ucwords($yoga_info['benefit']); ?></a></li> 
                <?php
          }
        
    
    ?></ul>
    
    </p>
    
    <h2>Favorite Asanas for Heatlh</h2>
    <p>
    <ul>
    <?php
          $yoga_query=tep_db_query('SELECT translation, posename, poseid, school, difficulty FROM yoga.poses order by length("use") asc limit 0,10;');

          while($yoga_info=tep_db_fetch_array($yoga_query))
          {
               ?>               <li><a href="/yoga/pose.php?posture=<?php echo $yoga_info['poseid']?>"><?php echo $yoga_info['posename']; ?> <b><?php echo $yoga_info['translation']; ?></b> <?php echo $yoga_info['school']; ?></a> - <?php echo $yoga_info['difficulty']; ?></li> 
                <?php
          }
        
    
    ?></ul>
    </p>
    <?php }else{ ?>
        
        <h1>Yoga for <?php echo $use; ?></h1>
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
            Practicing the following poses, asanas and postures 
            allows you to focus your routine on <?php echo strtolower($use); ?>.
        </p>
        
           <ul>  <?php 
        
   
        
        do{
            ?>
               <li><a href="/yoga/pose.php?posture=<?php echo $yoga_info['poseid']?>"><?php echo $yoga_info['posename']; ?> <b><?php echo $yoga_info['translation']; ?></b> <?php echo $yoga_info['school']; ?></a> - <?php echo $yoga_info['difficulty']; ?></li> 
                
            <?php     
        
        }while($yoga_info=tep_db_fetch_array($yoga_query)); ?>
        </ul>
        
        <?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/render_pills.php');?>
        <?php render_pills(tep_db_query('SELECT products_name, left(products_head_desc_tag,300) as products_description, p.products_id FROM products p join products_description pd on pd.products_id=p.products_id where products_head_keywords_tag like \'%'.$use.'%\' limit 0,25'));?>
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
