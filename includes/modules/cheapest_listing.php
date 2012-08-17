<?php
$HTTP_GET_VARS['page']=$page;
  $listing_split = new splitPageResults($cats_query, 50, '*');
  $list_text='';
  


  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {

?>


<?php

  }


  
  if ($listing_split->number_of_rows > 0) { //Results found

    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    
    while ($listing = tep_db_fetch_array($listing_query)) {
    


      $product_image_path='';
      
      

      $product_image_path = $listing['products_image']; 



      $listing_text.='<p><div id="prod'.$rows.'" class="';
      if($listing['products_isspecial']=='1')
      {
        $listing_text.='product_isspecial';
      }else{
        $listing_text.='product_regular';
      }
      $listing_text.='">';
            
            $listing_text.='<div class="listing-image">';
            if(strlen($product_image_path)>0){
             $listing_text.='<img src="'. $product_image_path . '" width="50" style="margin:5px;" ALIGN="left" />';
            }
            $listing_text.='</div>';

            $listing_text.= '&nbsp;<a href="/cheapest/' . $listing['products_id'] . '-'.format_seo_url($listing['products_name']).'"><b>' . $listing['products_name'] . '</b></a>';
            if($listing['products_isspecial']=='1')
            {
              $listing_text.='&nbsp;-&nbsp;<span style="color:#FF0000;font-weight:bold;font-style:italic;">Top Pick</span>';
            }
            

           $listing_text.='</span> from <b>';
           $listing_text.= ucwords(strtolower($listing['products_manufacturer'])).'</b>' ;

            $listing_text.='<br style="clear:both;">';
            if(strlen($listing['products_head_desc_tag'])>0 && substr($products_description,0,200)!=substr($listing['products_head_desc_tag'],0,200)){$listing_text.='<div style="clear:both;border:dashed 1px #cccccc;margin:3px;">' . substr($listing['products_head_desc_tag'],0,200) . '...</div>';}



  
      $listing_text.='</div></li></ol></p>';
      $products_description=$listing['products_head_desc_tag'];

    }

?>



<?php

   echo $listing_text;

  } else {

   ?>

      <b>Unfortunately, no resources were found.</b>
  <?php

  }



  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) && $listing_split->number_of_pages>1) {

?>
<table border="0" width="97%" cellspacing="0" cellpadding="2">

  <tr>

    <td class="smallText">
      <?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>
    </td>

    <td class="smallText" align="right">
      <?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_cheapest_links(100, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
    </td>

  </tr>

</table>

<?php

  }

?>
<?php
//Display Ailments
if(is_array($departmentCategories)&&$departmentCategories[1]!=''){
?>
<h2>Related Departments</h2><ul style="width:97%;">
  <?php
        sort($departmentCategories);      
        array_shift($departmentCategories);
        foreach($departmentCategories as $tempitem){
          echo '<li style="width:190px;float:left;margin-left:10px;"><a href="/departments.php?benefits='.urlencode(strtolower($tempitem)).'">'.$tempitem.'</a></li>';
          }
   ?>
</ul><br style="clear:both;"/><?php   }
   
//Display useCategories
if(is_array($useCategories)&&$useCategories[1]!=''){?>
<h2>Uses & Indications</h2><ul style="width:97%;">
  <?php
        sort($useCategories);
        array_shift($useCategories);
        foreach($useCategories as $tempitem){
          echo '<li style="width:190px;float:left;margin-left:10px;"><a href="/natural_uses.php?use='.urlencode(strtolower($tempitem)).'">'.$tempitem.'</a></li>';
          }
   ?>
</ul><br style="clear:both;"/><?php   }
   
//Display Ailments
if(is_array($ailmentCategories)&&$ailmentCategories[1]!=''){
?>
<h2>Ailments & Concerns</h2><ul style="width:97%;">
  <?php
        sort($ailmentCategories);
        array_shift($ailmentCategories);
        foreach($ailmentCategories as $tempitem){
          echo '<li style="width:190px;float:left;margin-left:10px;"><a href="ailments.php?remedy='.urlencode(strtolower($tempitem)).'">'.$tempitem.'</a></li>';
          }
   ?>
</ul><br style="clear:both;"/><br/><?php   }
   

?>