<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  $state=$_REQUEST['studio'];
  
  if(strlen($state)!=2){
    redir301('/yoga/yogafinder.php');
  }
  
  //Get State Info
  $mcd_query=tep_db_query("select (SELECT COUNT(*) FROM locations.yogastudios WHERE stateabbr='".$state."') as numstudios,
                s.statename, s.stateabbr from 
                locations.states s
                WHERE s.stateabbr='".$state."'");
                
   
               
                
   $state_info = tep_db_fetch_array($mcd_query);
   
?>

<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>

  <title><?php echo $state_info['statename']; ?> (<?php echo $state_info['stateabbr']; ?>) Yoga Studios</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="keywords" content="yoga studios <?php echo $state_info['statename']; ?> <?php echo $state_info['stateabbr']; ?>"/>
<meta name="description" content="Find <?php echo $state_info['statename']; ?> yoga studios and education centers (<?php echo $state_info['stateabbr']; ?>)."/>
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
    
    <h1><?php echo $state_info['statename']; ?> (<?php echo $state_info['stateabbr']; ?>) Yoga Studios & Education Centers</h1>
    <p>
        Locate over <?php echo $state_info['numstudios']-1; ?> yoga studios and learning centers
        to practice at your own pace within the state of <?php echo $state_info['statename']; ?>. 
        Many popular cities in <?php echo $state_info['statename']; ?> include
        
        <?php 
            $pop_query=tep_db_query("select count(*) as num, zc.cityname, zc.stateabbr from 
                        locations.yogastudios ys JOIN locations.zipcodes zc
                        on zc.zipcode=left(ys.postalcode,5)
                        WHERE ys.stateabbr='".$state."'
                        group by cityname, zc.stateabbr
                        order by num desc, cityname asc
                        LIMIT 0,3");
           while($pop_studios = tep_db_fetch_array($pop_query))
           {
                echo '<a href="/yoga/yf_city.php?studio='.strtolower(urlencode($pop_studios['cityname'])).','.strtolower(urlencode($pop_studios['stateabbr'])).'">'.ucwords($pop_studios['cityname']).'</a>, ';
           }
         
        ?>
        <?php echo $state_info['stateabbr']; ?> to study yoga and develop your poses and asanas.
    </p>
    
    
    
   <h2>Favorite <?php echo $state_info['statename']; ?> Yoga Studios</h2>
    <p>The most popular yoga studios in <?php echo $state_info['statename']; ?> that you can rely on are below.</p>
    <p>
    <ul style="width:610px;">
    <?php 
        $mcd_query=tep_db_query("select companyname, recordid from 
                    locations.yogastudios ys 
                    where stateabbr='".$state."'
                    order by sales desc LIMIT 0,25
                    ");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li style="display:inline;width:300px;float:left;"><span style="width:300px;"><a href="/yoga/yf_studio.php?yoga='.strtolower(urlencode($drug['recordid'])).'">'.ucwords(strtolower($drug['companyname'])).'</a></span></li>';
       }
                    
        
    ?>        
        </ul>  
       </p>
  <br style="clear:both;"/><br/>
    <h2>Select A City</h2>
    <p>Provided below are the cities of the state of <?php echo $state_info['statename']; ?> to locate yoga studios. Simply select a city to locate yoga studios in it.</p>
    <ul style="width:610px;">
    <?php 
        $mcd_query=tep_db_query("select cityname, stateabbr, statename from 
                    locations.zipcodes ys 
                    where stateabbr='".$state."'
                    group by cityname, stateabbr, statename
                    order by cityname asc
                    ");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li style="display:inline;width:300px;float:left;"><span style="width:300px;"><a href="/yoga/yf_city.php?studio='.strtolower(urlencode($drug['cityname'])).','.strtolower(urlencode($drug['stateabbr'])).'">'.ucwords(strtolower($drug['cityname'])).', '.ucwords(strtolower($drug['statename'])).' Yoga Studios</a></span></li>';
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
