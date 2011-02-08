<?php
/*
  $Id: stats_sales_report.php,v 0.01 2002/11/27 19:02:22 cwi Exp $

  Released under the GNU General Public License
*/

  set_time_limit(60);
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  $bymonth=Array();
  $byprod=Array();
  $commissions=Array();
  
  $query='select products_date_added, p.products_id, pd.products_name from products p join products_description pd on pd.products_id=p.products_id left outer join lees_ids li on p.products_id=li.products_id where li.products_id is not null or p.author like "%lee%" or p.director like "%lee%"';
  $query_link=tep_db_query($query);

  while($newprods=tep_db_fetch_array($query_link))
  {
    
    $monthcreated=date('Y-m',strtotime($newprods['products_date_added']));
    $bymonth[$monthcreated]['new_products']+=1;
    if(strtotime($newprods['products_date_added'])>=strtotime('10-8-2009') )
    {
     $bymonth[$monthcreated]['125elig']+=1;
    }
    $byprod[$newprods['products_id']]['products_name']=$newprods['products_name'];

    $psales=tep_db_query('select * from orders_products po join orders o on o.orders_id=po.orders_id
                                 where (o.orders_status=3 or o.orders_status=4) and po.products_id='.(int)$newprods['products_id']);
                                 

    while($sales=tep_db_fetch_array($psales))
    {

      $psalemonth=date('Y-m',strtotime($sales['date_purchased']));
      $bymonth[$psalemonth]['orders_value']+=$sales['final_price']*$sales['products_quantity'];
      
      $byprod[$sales['products_id']][$psalemonth]['sales']+=$sales['final_price']*$sales['products_quantity'];
      
      //if product was added before aug 10, 2009 and sale was before january 1st, 2010, do 5% commission
      if(strtotime($newprods['products_date_added'])<strtotime('10-8-2009') && strtotime($sales['date_purchased'])<strtotime('1-1-2010'))
      {
        //$commissions[5]+=$sales['final_price']*$sales['products_quantity']*0.05;
        $byprod[$sales['products_id']][$psalemonth]['commission5']+=$sales['final_price']*$sales['products_quantity']*0.05;
        $bymonth[$psalemonth]['commission5']+=$sales['final_price']*$sales['products_quantity']*0.05;
        
      }
      //else, if product was added after aug 9 2009 and before April 1 2010, and sale was within 1 year of product being added, do 12.5% commission
      elseif(strtotime($newprods['products_date_added'])>=strtotime('10-8-2009') && strtotime($newprods['products_date_added'])<strtotime('1-4-2010') && strtotime($sales['date_purchased'])<strtotime($newprods['products_date_added'])+31536000)
      {
        //$commissions[125]+=$sales['final_price']*$sales['products_quantity']*0.125;
        $byprod[$sales['products_id']][$psalemonth]['commission125']+=$sales['final_price']*$sales['products_quantity']*0.125;
        $bymonth[$psalemonth]['commission125']+=$sales['final_price']*$sales['products_quantity']*0.125;  

      }
      //else, if product added after april 1, 2010, do 10% commission for 1 year.
      elseif(strtotime($newprods['products_date_added'])>=strtotime('1-4-2010') && strtotime($sales['date_purchased'])<strtotime($newprods['products_date_added'])+31536000)
      {
        $byprod[$sales['products_id']][$psalemonth]['commission10']+=$sales['final_price']*$sales['products_quantity']*0.10;
        $bymonth[$psalemonth]['commission10']+=$sales['final_price']*$sales['products_quantity']*0.10;

      }


    }
    


  }
  
// add $2 finders fee.
  foreach(array_keys($bymonth) as $month)
  {
    if(strtotime($month.'-01')>=strtotime('2009-12-01') && strtotime($month.'-01')<strtotime('2010-4-1'))
    {
      $bymonth[$month]['commissionbase2']=$bymonth[$month]['125elig']*2;
    }
    // if the commission is not larger than the finders fee, add the finders fee.
    elseif($bymonth[$month]['125elig']*2>$bymonth[$month]['commission125'])
    {
      $bymonth[$month]['commission125']=$bymonth[$month]['125elig']*2;
    }
  }  
  
  // add $4 finders fee.
  foreach(array_keys($bymonth) as $month)
  {
    if(strtotime($month.'-01')>=strtotime('2010-04-01'))
    {
      $bymonth[$month]['commissionbase4']=$bymonth[$month]['125elig']*4;
    }
   
  }  

  

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Lee Bronstein | Product Performance Report</title>
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
		<h2>Sales by Month</h2>
		    <table width="100%">
		           <tr>
		               <td>Month</td>
		               <td>Total Sales</td>
		               <td>New Products</td>
		               <td>5% Commission</td>
		               <td>12.5% Commission</td>
		               <td>10% Commission</td>
                       <td>$2 Base</td>
                       <td>$4 Base</td>
                           </tr>
                           
                           <?php 
                           ksort($bymonth);
                           while($item=current($bymonth)){ ?>
                           <tr>
                                 <td><?php echo key($bymonth)?></td>
                                 <td><?php echo '$', number_format($item['orders_value'],2)?></td>
                                 <td><?php echo $item['new_products']?></td>
                                 <td><?php echo '$', number_format($item['commission5'],2)?></td>
                                 <td><?php echo '$', number_format($item['commission125'],2)?></td>
                                 <td><?php echo '$', number_format($item['commission10'],2)?></td>
                                 <td><?php echo '$', number_format($item['commissionbase2'],2)?></td>
                                 <td><?php echo '$', number_format($item['commissionbase4'],2)?></td>

                           </tr>

                           
                           <?php 
                                 $sales_total+=$item['orders_value'];
                                 $newprods_total+=$item['new_products'];
                                 $comm5+=$item['commission5'];
                                 $comm125+=$item['commission125'];
                                 $comm10+=$item['commission10'];
                                 $commbase2+=$item['commissionbase2'];
                                 $commbase4+=$item['commissionbase4'];

                                 next($bymonth);} ?>
                           <tr>
                               <td>Totals</td>
                               <td><?php echo '$',number_format($sales_total,2);?></td>
                               <td><?php echo $newprods_total;?></td>
                               <td><?php echo '$',number_format($comm5,2);?></td>
                               <td><?php echo '$',number_format($comm125,2);?></td>
                               <td><?php echo '$',number_format($comm10,2);?></td>
                               <td><?php echo '$',number_format($commbase2,2);?></td>
                               <td><?php echo '$',number_format($commbase4,2);?></td>



		    </table>
		    
		    <h2>Sales by Product</h2>
		    <table>
		           <tr>
		               <td>Product ID</td>
		               <td>Product Name</td>
		               <?php foreach(array_keys($bymonth) as $item){?>
		               <td style="width:9em;text-align:center;"><?php echo $item?></td>
		               <?php } ?> 
                               <td style="width:9em;text-align:center;">5%</td>
                               <td style="width:9em;text-align:center;">12.5%</td>
                               <td style="width:9em;text-align:center;">10%</td>

                             </tr>
                             
                             <?php foreach(array_keys($byprod) as $item) { ?>
                                   <tr>
                                       <td><?php echo $item?></td>
                                       <td><a href="http://www.seacoastvitamins.com/product_info.php?products_id=<?php echo $item; ?>" target="blank"><?php echo $byprod[$item]['products_name']?></a></td>
                                       <?php foreach(array_keys($bymonth) as $item2){?>
                                       <td style="text-align:center;"><?php echo '$', number_format($byprod[$item][$item2]['sales'],2)?></td>
                                       <?php } ?>
                                       <td style="text-align:center;"><?php echo '$',number_format($byprod[$item][$item2]['commission5'],2);?></td>
                                       <td style="text-align:center;"><?php echo '$',number_format($byprod[$item][$item2]['commission125'],2);?></td>
                                       <td style="text-align:center;"><?php echo '$',number_format($byprod[$item][$item2]['commission10'],2);?></td>
                                       
                                   </tr>
                             <?php } ?>



		    </table>


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
