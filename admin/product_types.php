<?php
/*
  $Id: stats_sales_report.php,v 0.01 2002/11/27 19:02:22 cwi Exp $

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  $types_query=tep_db_query('SELECT products_keywords from products');
  $results=Array();
  
  while($type=tep_db_fetch_array($types_query))
  {
    $items=split(',',$type['products_keywords']);
    foreach($items as $item)
    {

      $results[trim($item)]+=1;

    }

  }
  print_r($results); asort($results);
 

  

?>

<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Product Types</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<SCRIPT LANGUAGE="JavaScript1.2" SRC="jsgraph/graph.js"></SCRIPT>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
    	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
     <h1>product Types</h1>
     
     <?php 
     foreach(array_keys($results) as $item)
     {
       echo $results[$item],'=',$item,'<br/>';
     }


     ?>



		</td>
<!-- body_text_eof //-->
	</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
