<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  $state=substr($_REQUEST['studio'],-2);
  $city=substr($_REQUEST['studio'],0,strrpos($_REQUEST['studio'],','));
  $search_radius=50.0;
  $studio_limit=100;
   
  if(strlen($state)!=2){
    redir301('/yoga/yogafinder.php');
  }
  
  //Get State Info
  $mcd_query=tep_db_query("select zc.cityname, zc.latitude,zc.longitude,
                zc.statename, zc.stateabbr from 
                locations.zipcodes zc
                WHERE zc.stateabbr='".tep_db_input($state)."' and zc.cityname='".tep_db_input($city)."'");
                                
   $city_info = tep_db_fetch_array($mcd_query);
   
   if(strlen($city_info['latitude'])<1){ redir301('/yoga/');}
   
   $city_studios_query=tep_db_query("select zc.cityname, zc.stateabbr, zc.statename, ys.recordid,
                                     ys.companyname, case when zc.cityname='".tep_db_input($city)."' and zc.stateabbr='".tep_db_input($state)."' then 0 else cast(
                                     3963.0 * acos(sin(zc.latitude/57.2958) * sin(".$city_info['latitude']."/57.2958) + cos(zc.latitude/57.2958) * cos(".$city_info['latitude']."/57.2958) *  cos(".$city_info['longitude']."/57.2958 -zc.longitude/57.2958)) as  signed) end as distance
                                     from locations.zipcodes zc
                                     join locations.yogastudios ys on left(ys.postalcode,5)=zc.zipcode
                                     where zc.latitude>=".$city_info['latitude']."-((".$search_radius."/24000.0)*360.0) and zc.latitude<=".$city_info['latitude']."+((".$search_radius."/24000.0)*360.0)
                                     and zc.longitude>=".$city_info['longitude']."-((".$search_radius."/24000.0)*360.0) and zc.longitude<=".$city_info['latitude']."+((".$search_radius."/24000.0)*360.0)
                                     and cast(
                                     3963.0 * acos(sin(zc.latitude/57.2958) * sin(".$city_info['latitude']."/57.2958) + cos(zc.latitude/57.2958) * cos(".$city_info['latitude']."/57.2958) *  cos(".$city_info['longitude']."/57.2958 -zc.longitude/57.2958)) as  signed) <=".$search_radius."
                                     group by zc.cityname, zc.stateabbr, zc.statename,ys.recordid,
                                     ys.companyname
                                     order by distance asc, ys.sales desc
                                     limit 0,".$studio_limit);
                                     
   $yf_state='<a href="/yoga/yf_state.php?studio='.strtolower(urlencode($city_info['stateabbr'])).'">Yoga Studios in '.ucwords(strtolower($city_info['statename'])).'</a>';
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>

  <title>Yoga Studios in <?php echo $city_info['cityname']; ?>, <?php echo $city_info['statename']; ?> (<?php echo $city_info['stateabbr']; ?>)</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="keywords" content="yoga studios <?php echo $city_info['cityname']; ?> <?php echo $city_info['statename']; ?> <?php echo $city_info['stateabbr']; ?>"/>
<meta name="description" content="Listing of Yoga Studios in <?php echo $city_info['cityname']; ?>, <?php echo $city_info['statename']; ?> (<?php echo $city_info['stateabbr']; ?>)."/>
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
    
    <h1>Yoga Studios in <?php echo $city_info['cityname']; ?>, <?php echo $city_info['statename']; ?> (<?php echo $city_info['stateabbr']; ?>)</h1>
    <p>Listed below are popular and favorite yoga practice destinations in the vicinity of <?php echo $city_info['cityname']; ?>, as well
    as other nearby cities located in <?php echo $city_info['statename']; ?>. There are dozens of professional yoga studios 
    and education centers in <?php echo $city_info['cityname']; ?>, <?php echo $city_info['stateabbr']; ?> for complete health
    and fitness.
    
    </p>
    <p>
        <?php
        while($studio_info=tep_db_fetch_array($city_studios_query))
        {
            echo '<p><b><a href="/yoga/yf_studio.php?yoga='.$studio_info['recordid'].'">'.$studio_info['companyname'].'</a></b>
            <br/>';
            if($studio_info['distance']>0){ echo $studio_info['distance'].' miles from ';}
            echo $city_info['cityname'].', '.$city_info['stateabbr'] . '
            <br/><i><a style="color:#aaaaaa;" href="/yoga/yf_studio.php?yoga='.$studio_info['recordid'].'">View Address & Phone...</a></i></p>';
            
            if($studio_info['distance']!=0 && !strpos($nearby_city_links,$studio_info['cityname'])){
                $nearby_city_links.='<p><a href="/yoga/yf_city.php?studio='.strtolower(urlencode($studio_info['cityname'])).','.strtolower(urlencode($studio_info['stateabbr'])).'">'.ucwords(strtolower($studio_info['cityname'])).', '.ucwords(strtolower($studio_info['statename'])).' Yoga Studios</a></p>';
            }
        }
        ?>
    </p>
    
  
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
