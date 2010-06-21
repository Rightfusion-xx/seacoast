<!-- related Cat //-->
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => '<p><b>Related Categories</b></p>');

            new contentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array(); 
	  $info_box_contents[$row][$col] = array('align' => 'left',);
	{  
 include(DIR_WS_MODULES . 'products_categories.php');?><?php related_product_categories($_REQUEST['products_id']);
	      $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        } 
		 new contentBox($info_box_contents); 
}
            
?>
	

<!-- related Cat //-->
