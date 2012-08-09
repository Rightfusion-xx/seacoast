<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>

  <title>Yoga Studio Finder</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="keywords" content="yoga, yoga studios, asanas, poses, yoga postures, yoga health"/>
<meta name="description" content="Locate thousands of Yoga Studios and Learning Centers around the country."/>
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
    
<td valign="top" colspan="3" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->

    <td width="100%" valign="top">
    <div id="content">
    
    <h1>Yoga Studio and Yoga Learning Center Finder</h1>
    <p>
        Seacoast Natural Health brings you the most complete and comprehensive collection of Yoga studios and locations
        around the country, from Gnome Alaska, to Miami Florida. Start with the Yoga Finder to locate
        a studio or education center near you, and to explore new methods and schools.
    </p>
    
    <h2>Most Popular Cities</h2>
    <p>Some of our favorite cities for practicing yoga also happen to be
    some of the most popular places. Take a Yoga vacation or just see what
    else is out there by checking out our most popular city destinations.</p>
    <ol>
    <?php 
        $mcd_query=tep_db_query("select count(*) as num, zc.cityname, zc.stateabbr, s.statename from 
                    locations.yogastudios ys JOIN locations.zipcodes zc
                    on zc.zipcode=left(ys.postalcode,5) JOIN locations.states s on s.stateabbr=zc.stateabbr
                    group by cityname,zc.stateabbr, s.statename
                    order by num desc, cityname asc
                    LIMIT 0,25");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li><a href="/yoga/yf_city.php?studio='.strtolower(urlencode($drug['cityname'])).','.strtolower(urlencode($drug['stateabbr'])).'">'.ucwords($drug['cityname']).', '.ucwords($drug['statename']).' Yoga Studios</a></li>';
       }
                    
        
    ?>
    </ol>
    
  
    <h2>Select A State</h2>
    <p>Seacoast provides yoga studio locations across the country. Select a state below for 
    locations, phone numbers and times.</p>
    <ul style="width:610px;">
    <?php 
        $mcd_query=tep_db_query("select statename, stateabbr from 
                    locations.states ys 
                    order by statename
                    ");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li style="display:inline;width:300px;float:left;"><span style="width:300px;"><a href="/yoga/yf_state.php?studio='.strtolower(urlencode($drug['stateabbr'])).'">'.ucwords(strtolower($drug['statename'])).' Yoga Studios</a></span></li>';
       }
                    
        
    ?>        
        </ol>
        
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
