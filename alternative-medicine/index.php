<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>

  <title>Prescription Drug Alternative Medicine Database</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
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
    
    <h1>Alternative Medicine Database</h1>
    <p>
        Seacoast Natural Health presents natural nutritional and herbal therapy alternatives to prescription drugs and pharmaceuticals.
    </p>
    
    <h2>Most Common Drugs</h2>
    <ol>
    <?php 
        $mcd_query=tep_db_query("select count(*) as num, tradename from 
                    drugs.drugs d 
                    group by tradename
                    order by num desc, tradename asc
                    LIMIT 0,50");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li><a href="/alternative-medicine/drug_info.php?drug='.strtolower(urlencode($drug['tradename'])).'">Alternatives to '.ucwords(strtolower($drug['tradename'])).'</a></li>';
       }
                    
        
    ?>
        
        
    </ol>
    <h2>Most Common Active Ingredients</h2>
    <ol>
    <?php 
        $mcd_query=tep_db_query("select count(*) as num, activeingredients from 
                    drugs.formulation rx
                    group by activeingredients
                    order by num desc, activeingredients asc
                    LIMIT 0,50");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li><a href="/alternative-medicine/drug_ingredients.php?active='.strtolower(urlencode($drug['activeingredients'])).'">Drugs Containing '.ucwords(strtolower($drug['activeingredients'])).'</a></li>';
       }
                    
        
    ?>
        
        
    </ol>    
    <h2>Most Popular Alternatives to Prescriptions</h2>
    
    </div>
    


</td>
      
<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php //require(DIR_WS_INCLUDES . 'column_right.php'); ?>
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
