<?php
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');


$orderids=Array();

if(!isset($_REQUEST['pull_time']))
{
  $pull_time=date('Y-m-d H:i:s', time()-2626560);
}
else
{
  $pull_time=$_REQUEST['pull_time'];
}




  $query="select distinct o.customers_name, value, osh.orders_id, o.payment_method from orders_status_history osh
          join orders o on o.orders_id=osh.orders_id
          join orders_total ot on ot.orders_id=o.orders_id and ot.class='ot_total'
          where orders_status_id=3 and
          osh.date_added>='" . $pull_time . "'
          order by osh.date_added desc";


  $query_link=tep_db_query($query);





  

?>

<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Shipped Orders Reconciler</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

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
                    <h2>Reconcile Orders After <input type="text" name="pull_time" value="<?php echo $pull_time;?>"/>

                      <input type="submit" value=">>"/>



                    </h2>  </form>
		               <?php 
                               
                                 $order_results=Array();
                                 $freebies=0;

                                 while($results=tep_db_fetch_array($query_link)){
                                 
                                 $order_details=Array();
                                 // get all order transactions.
                                 $trans=tep_db_query('select * from authnet_transactions where orders_id='. (int)$results['orders_id']);

                                 // get order details
                                 $order_details['Order Id']='<a target="_blank" href="orders.php?action=edit&oID='.$results['orders_id'].'">'.$results['orders_id'].' / ' . $results['customers_name'] . '</a>';
                                 $order_details['Payment Method']=$results['payment_method'];
                                 $order_details['Invoice Amt']=number_format($results['value'],2);
                                 $order_details['Amt Charged']=0;


                                 while($ot=tep_db_fetch_array($trans))
                                 {
                                   $tran=unserialize(gzuncompress($ot['results']));
                                    if($tran[0]=='1')
                                    {
                                      //take in to account transaction because the transaction was a success
                                      if($tran[11]=='auth_capture')
                                      {
                                        $order_details['Amt Charged']+=$tran[9];
                                      }
                                      else
                                      {
                                        $order_details['Amt Charged']-=$tran[9];
                                      }
                                
                                    }

                                 }
                                 
                                 $order_details['Difference']=number_format($order_details['Invoice Amt']-$order_details['Amt Charged'],2);
                                 
                                 if($order_details['Difference']>0 && $order_details['Payment Method']=='Credit Card')
                                 {
                                   array_push($order_results, $order_details);
                                   $freebies+=$order_details['Difference'];
                                 }
                                 
                                 }
                                 



		?>
		    <table width="100%">
		           <tr>            <?php
                                  foreach(array_keys($order_results[0]) as $item)
                                 {
                                   echo '<td>',$item,'</td>';
                                 }
                                 ?>
                           </tr>
                           
                           <?php 

                           foreach($order_results as $results)
                           {

                             echo '<tr>';
                             foreach(array_keys($results) as $item)
                                 {
                                   echo '<td>',$results[$item],'</td>';
                                 }

                             echo '</tr>';

                           
                           }



                           
                           ?>
                           <tr>

                           </tr>
                           </table>
                           
                           <b>Total Freebies:</b> $<?php echo number_format($freebies,2);  ?>





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