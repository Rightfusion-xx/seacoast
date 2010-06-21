<?php


  if (isset($HTTP_GET_VARS['products_id'])) {
    $reviews_query = tep_db_query("select * from reviews r join reviews_description rd on rd.reviews_id=r.reviews_id where r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' order by date_added desc");
    $num_reviews = tep_db_num_rows($reviews_query);
    if ($num_reviews >= 1) {
	
	
?>
<!-- also_purchased_products //-->
<div id="customer_reviews">

      <h2>Customer Reviews for <?php echo $product_info['products_name']?></h2>
<?php
      
      while ($reviews = tep_db_fetch_array($reviews_query)) {
      ?>
      <div style="width:400px;float:left;margin-right:20px;">
        <p>
        <b>Review by <?php echo $reviews['customers_name'];?></b><?php echo draw_stars($reviews['reviews_rating']);?>
          <?php if(strlen($reviews['use'])>0){echo '<br/><em><b>How it\'s used: ' . $reviews['use'] .'</b></em>';}?>
        <?php echo '<br />'.$reviews['reviews_text'];?>
      </b></p>
      </div>
     <?php
        
      }
      ?>
  <?php
  }

    }

?>
