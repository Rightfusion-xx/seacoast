<?php
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');


$orderids=Array();

if(!isset($_REQUEST['pull_time']))
{
  $pull_time=date('Y-m-d H:i:s', time()-86400);
}
else
{
  $pull_time=$_REQUEST['pull_time'];
}

$now=date('Y-m-d H:i:s', time());



  $query="select (select location from products_location pl where pl.products_id=p.products_id order by time_created desc limit 0,1) as Location,
          CASE WHEN (select count(*) from orders_status_history osh where osh.orders_id=o.orders_id and orders_status_id=4)>0 THEN 'B/O!' ELSE '' END as 'Backorder?',
          CASE when (select count(*) from orders_products op2 join orders o2 on o2.orders_id=op2.orders_id
          where o2.orders_id=o.orders_id and op2.products_id<>2531)>1 then 'C' else 'S' end

          as 'Order Size', o.orders_id as Invoice , concat(op.products_quantity, ' / ' , p.products_available, ' remain') as Quantity,
          m.manufacturers_name as Manufacturer, pd.products_name as Product from orders o
          join orders_products op on o.orders_id=op.orders_id
          join products p on p.products_id=op.products_id
          join manufacturers m on m.manufacturers_id=p.manufacturers_id
          join products_description pd on pd.products_id=p.products_id
          where o.date_purchased>='" . $pull_time . "'
          and orders_status=7
          and pd.products_name not like '%Seacoast Vitamins-Direct 14-Day Free Trial%'

          order by manufacturers_name, pd.products_name";

  $query_link=tep_db_query($query);
  
  $results=tep_db_fetch_array($query_link);



  

?>

<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Paid Orders Pull Sheet & Invoices</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<SCRIPT LANGUAGE="JavaScript1.2" SRC="jsgraph/graph.js"></SCRIPT>
<!-- header //-->
<div class="hide-when-print">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
</div>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
    	<td width="<?php echo BOX_WIDTH; ?>" valign="top" class="hide-when-print">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
	        </table>
		</td>
<!-- body_text //-->
		<td width="100%" valign="top">
                    <form action="<?php $_SERVER['PHP_SELF']?>" method="get">
                    <h2>Pick Sheet - Orders After <input type="text" name="pull_time" value="<?php echo $pull_time;?>"/>

                      <input type="submit" value=">>"/>



                    </h2>  </form>
		               <?php if($results){
		?>
		<b>Pulled at <?php echo $now?></b>
		    <table width="100%">
		           <tr>            <?php
                                  foreach(array_keys($results) as $item)
                                 {
                                   echo '<td>',$item,'</td>';
                                 }
                                 ?>
                           </tr>
                           
                           <?php 

                           do
                           {
                             //Add order number if applicaple
                             if(!in_array($results['Invoice'],$orderids))
                             {

                               array_push($orderids, $results['Invoice']);
                               }

                             echo '<tr>';
                             foreach(array_keys($results) as $item)
                                 {
                                   echo '<td>',$results[$item],'</td>';
                                 }
                                 
                             echo '</tr>';

                           
                           }while($results=tep_db_fetch_array($query_link))  ;
                           }


                           
                           ?>
                           <tr>

                           </tr>
                           </table>





		</td>
<!-- body_text_eof //-->
	</tr>
</table>
<!-- body_eof //-->
                           <?php

                                //asort($orderids);
                                foreach($orderids as $oID)
                                {
                                  echo '<div class="page-break"></div><div style="width:100%">';
                                  $order = new order($oID);
                                  require ('includes/invoice.php');
                                  echo '</div>';
                                }
                                


                           ?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>