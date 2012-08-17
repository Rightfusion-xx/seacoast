<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  //$drug_query=tep_db_query('SELECT d.drugid, tradename, rxotc, potency, unit, company, dosage, rx.units, activeingredients FROM drugs.drugs d join drugs.firms f on 
  //                              f.firmid=d.labelcode join drugs.packaging p on p.drugid=d.drugid 
  //                              join drugs.formulation rx on rx.drugid=d.drugid
  //                              where rx.drugid='. $_REQUEST['drug'].';');
  
  $drug_query=tep_db_query('SELECT rxotc, tradename, activeingredients FROM drugs.drugs d join drugs.formulation rx on rx.drugid=d.drugid where tradename="'.tep_db_prepare_input($_REQUEST['drug']).'" limit 0,1;');
  //echo('SELECT tradename, activeingredients FROM drugs.drugs d join drugs.formulation rx on rx.drugid=d.drugid where tradename="'.tep_db_prepare_input($_REQUEST['drug']).'" limit 0,1;');
  //exit(0);
  if(!$drug_info=tep_db_fetch_array($drug_query)){redir301('/alternative-medicine/');}
  
  $activeingredients=$drug_info['activeingredients'];
  $tradename=ucwords(strtolower($drug_info['tradename']));
  if ($drug_info['rxotc']=='O'){$drugadmin= 'Over-the-Counter ';}else{$drugadmin= 'Prescription ';};
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>

  <title>Alternatives to <?php echo ucwords(strtolower($drug_info['tradename'])).' (' . ucwords(strtolower($drug_info['activeingredients']));?>)</title>
<meta name="description" content="Low cost, natural alternatives to <?php echo $drugadmin?> based <?php echo $drug_info['tradename'];?>"/>
<meta name="keywords" content="<?php echo $drug_info['tradename'];?>"/>
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
    
    <h1>Alternatives to <?php echo ucwords(strtolower($drug_info['tradename'])).' (' . ucwords(strtolower($drug_info['activeingredients']));?>)</h1>
    <p>
        <ul>
        <?php 
        do
        {
            echo '<li>';
            echo $drugadmin;
            echo $tradename;
            echo '</li>';
           
        } while ($drug_info=tep_db_fetch_array($drug_query));
      ?>
      </ul>
    </p>
    <h2>Similar & Generic Drugs</h2>
        <p><ul>
            <?php 
                $similar_drugs_query=tep_db_query('SELECT tradename from drugs.drugs d join drugs.formulation rx on rx.drugid=d.drugid WHERE activeingredients="'.$activeingredients.'" group by tradename order by tradename asc;');
                while($similar_drugs=tep_db_fetch_array($similar_drugs_query))
                {
                    echo '<li><a href="/alternative-medicine/drug_info.php?drug='.strtolower(urlencode($similar_drugs['tradename'])).'">Alternatives to '.ucwords(strtolower($similar_drugs['tradename'])).' '.ucwords(strtolower($similar_drugs['activeingredients'])).'</a></li>';
                }
            ?>
            </ul>
        </p>
        
    <h2>Companies Who Produce <?php echo $tradename;?></h2>
        <p><ul>
            <?php 
                $similar_drugs_query=tep_db_query('SELECT company from drugs.drugs d join drugs.firms f on f.firmid=d.labelcode WHERE tradename="'.$tradename.'" group by company order by company asc;');
                while($similar_drugs=tep_db_fetch_array($similar_drugs_query))
                {
                    echo '<li><a href="/alternative-medicine/company_info.php?firm='.strtolower(urlencode($similar_drugs['company'])).'">'.ucwords(strtolower($similar_drugs['company'])).' '.$tradename.'</a></li>';
                }
            ?>
            </ul>
        </p>        
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
