<?php


  if (isset($HTTP_GET_VARS['products_id'])) {
    $reviews_query = tep_db_query("
    SELECT DISTINCT *, (SELECT COUNT(customers_name) FROM reviews WHERE customers_name=r.customers_name AND products_id=r.products_id) AS cnt
FROM reviews r JOIN reviews_description rd ON rd.reviews_id=r.reviews_id where r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' ORDER BY cnt DESC, date_added DESC");
    $num_reviews = tep_db_num_rows($reviews_query);
    if ($num_reviews >= 1) {
	
      
      while ($reviews = tep_db_fetch_array($reviews_query)) {
      
        $review.='<table border="1" style="border-style:dotted; border-width: 1px; border-collapse:collapse; margin-bottom:1em;" width="100%"><tr><td nowrap width="15%" style="border-style:dotted;" rowspan="2" valign="top">';
        $review.='Reviewed by <br/>' . $reviews['customers_name'] . '<br/>'. draw_stars($reviews['reviews_rating']) . '<br/> on '. date('M j, Y', strtotime($reviews['date_added'])).'.</td>';
       if(strlen($reviews['use'])>0){$review.= '<td style="border-style:dotted;"><em><b>How it\'s used: ' . $reviews['use'] .'</b></em>';}
           $review.='</tr><tr><td valign="top" width="100%" style="border-style:dotted;">';
         $review.=''.$reviews['reviews_text'].'</td></tr></table>';

          if(is_null($first_review))
          {
              $first_review=$review;
              $review='';
          }
      }

  }

    }

?>