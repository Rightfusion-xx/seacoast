<?php
/*
  $Id: stats_sales_report.php,v 0.01 2002/11/27 19:02:22 cwi Exp $

  Released under the GNU General Public License
*/
$dinfo=getdate();

if(!isset($_REQUEST['year']) || !is_numeric($_REQUEST['year']))
{
  $_REQUEST['year']=$dinfo['year'];
  $_REQUEST['month']=$dinfo['mon'];
}


  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $query='select date_format(date_purchased,"%m/%d/%Y") as "Order Date", o.orders_id as "Order#",
          customers_name as Customer, products_quantity + "x" as "Qty",
          format(final_price*products_quantity,2) as "Amount", os.orders_status_name as "Status"
          from orders o
          JOIN orders_products op on op.orders_id=o.orders_id
          join orders_status os on os.orders_status_id=o.orders_status
          where month(o.date_purchased)='.$_REQUEST['month'].' and year(o.date_purchased)='.$_REQUEST['year'].'
          and op.products_name like "%prostasol%"
          and os.orders_status_id=3 order by o.orders_id asc';

  $query_link=tep_db_query($query);

  $results=tep_db_fetch_array($query_link);



  

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Prostasol Product Performance Report</title>
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
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
	        </table>
		</td>
<!-- body_text //-->
		<td width="100%" valign="top">
                    <form action="stats_prostasol_sales.php" method="get">
                    <h2>Sales Report - Month of <select name="month">
                      <?php for($i=1;$i<=12;$i++)
                      {
                            $timestamp = mktime(0, 0, 0, $i, 1, 2005);

                        echo '<option value="',$i,'" ',($i==$_REQUEST['month'])?'selected':'','>',date("M", $timestamp),'</option>';
                      }?></select> <select name="year">
                      

                      <?php for($i=2009;$i<=$dinfo['year'];$i++)
                      {
                        echo '<option value="',$i,'" ',($i==$_REQUEST['year'])?'selected':'','>',$i,'</option>';
                      }?></select>
                      
                      <input type="submit" value=">>"/>



                    </h2>  </form>
		               <?php if($results){
		?>
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
                             $qty+=$results['Qty'];
                             $amount+=$results['Amount'];
                             echo '<tr>';
                             foreach(array_keys($results) as $item)
                                 {
                                   echo '<td>',$results[$item],'</td>';
                                 }
                                 
                             echo '</tr>';

                           
                           }while($results=tep_db_fetch_array($query_link))  ;

                           

                           
                           ?>
                           <tr>

                           </tr>
                           </table>
                           
                           <p>
                              <b>Quantity Sold:</b> <?php echo $qty;?></b><br/>
                              <b>Total:</b> $<?php echo number_format($amount,2);?></b><br/>
                              <?php
                              if($_REQUEST['month']>=8 && $_REQUEST['year']>=2010 || $_REQUEST['year']>2010)
                              {
                                $total_commission=number_format($qty*12.5,2);
                              }
                              else
                              {
                                $total_commission=number_format($qty*10,2);
                              }
                              ?>

                              <b>Total Commission:</b> $<?php echo $total_commission;?></b><br/>
                           </p>
                           

                           <?php
                            }
                           
                             $query='select date_format(date_purchased,"%m/%d/%Y") as "Order Date", o.orders_id as "Order#",
                                    customers_name as Customer, products_quantity + "x" as "Qty",
                                    format(final_price*products_quantity,2) as "Amount", os.orders_status_name as "Status"
                                    from orders o
                                    JOIN orders_products op on op.orders_id=o.orders_id 
                                    join orders_status os on os.orders_status_id=o.orders_status
                                    where month(o.date_purchased)='.$_REQUEST['month'].' and year(o.date_purchased)='.$_REQUEST['year'].'
                                    and op.products_name like "%prostasol%"
                                    and os.orders_status_id<>3 order by o.orders_id asc';
                          
                            $query_link=tep_db_query($query);
                            
                            $results=tep_db_fetch_array($query_link);
                            
                            if($results)
                            {     ?>
                              <table width="100%">
		           <tr> <?php
                              echo '<h2>Incomplete Transactions</h2>';
                              foreach(array_keys($results) as $item)
                                   {
                                     echo '<td>',$item,'</td>';
                                   }
                                   ?>
                             </tr>
                             
                             <?php 
                             do
                             { 
                               $qty+=$results['Qty'];
                               $amount+=$results['Amount'];
                               echo '<tr>';
                               foreach(array_keys($results) as $item)
                                   {
                                     echo '<td>',$results[$item],'</td>';
                                   }
                                   
                               echo '</tr>';
                             
                             
                             }while($results=tep_db_fetch_array($query_link))  ;


                              
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
