<?php

function related_product_categories($pID)
{
  
  global $page_links;
$needcomma = false;
$related_product_categories = tep_db_query ("SELECT DISTINCT pc.categories_id, cd.categories_name FROM products_to_categories pc JOIN categories_description cd ON pc.categories_id=cd.categories_id WHERE products_id=" . (int)$pID);
while ($results = tep_db_fetch_array($related_product_categories))
{
if ($needcomma){ 
echo ', ';      
}

   if($mflink=link_exists('/index.php?cPath='.$results['categories_id'],$page_links)){
  ?><a href="<?php echo $mflink; ?>"><?php   
$needcomma = true; 
 echo $results['categories_name'].'</a>';   }

}
}
?>                           