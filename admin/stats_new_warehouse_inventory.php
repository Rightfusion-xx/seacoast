                                      <?php
  
  	//CHange Headers
  	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=".date('Y').date('m').date('d').".csv"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");
	
  	require('includes/application_top.php');
	
	tep_db_query('drop table if exists stats_3060');
	tep_db_query('create table stats_3060
					select products_id, 0.000000 as avg_dailysales,
						0 as four_week_needs
					from orders_products op join orders o on o.orders_id=op.orders_id
					where o.date_purchased >= adddate(curdate(), interval -30 day) and (orders_status=2 or orders_status=7 
                                        or orders_status=3 or orders_status=4)
					group by products_id;');  //Take a list of all products that sold.
	tep_db_query('create index stats_products_id on stats_3060(products_id);');
	tep_db_query('update stats_3060 set avg_dailysales=(select sum(products_quantity) from orders_products op join orders o on
                             o.orders_id=op.orders_id
                             where o.date_purchased>=adddate(curdate(),interval -60 day) and stats_3060.products_id=op.products_id and 
                             (orders_status=2 or orders_status=7 or orders_status=3 or orders_status=4))/60.00;');

	tep_db_query('update stats_3060 set four_week_needs=case when (avg_dailysales*30)/3<3 then 3 else
                             (avg_dailysales*30)-mod(avg_dailysales*30, 3) end;');
	tep_db_query('delete from stats_3060 where four_week_needs<=4;');
	
	
	$inventory_query=tep_db_query('
		select s.products_id, s.avg_dailysales, s.four_week_needs, pd.products_name, p.products_sku, m.manufacturers_name,
		p.products_upc, p.products_msrp, p.products_price, products_available
		from products p, products_description pd, manufacturers m, stats_3060 s 
		where s.products_id=p.products_id and
		m.manufacturers_id=p.manufacturers_id 
			and p.products_id = pd.products_id 
			and four_week_needs>=3
		order by manufacturers_name asc, four_week_needs desc, products_name asc ;
			
	');
	
	
	$inventory=tep_db_fetch_array($inventory_query);
	
	foreach (array_keys($inventory) as $keyname)
	{
		echo $keyname .',';
		
	}
	
	echo "\n";
	
	do{
		foreach (array_keys($inventory) as $keyname)
		{
			echo '"'.str_replace('"','',$inventory[$keyname]).'",';
			
		} 
		echo "\n";
		
	}while($inventory=tep_db_fetch_array($inventory_query));
	
	
?>


