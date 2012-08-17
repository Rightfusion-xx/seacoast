<?php

if(!isset($num_listings))
{
    $num_listings=MAX_DISPLAY_SEARCH_RESULTS  ;
}

  $listing_split = new splitPageResults($listing_sql, $num_listings, 'p.products_id');
  $list_text='';
  
  $useCategories[]='';
  $ailmentCategories[]='';
  $departmentCategories[]='';

   if ($listing_split->number_of_rows > 0) { //Results found

    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);

    while ($listing = tep_db_fetch_array($listing_query)) {

        if(!(int)$_REQUEST['manufacturers_id'])
        {
          $lastprod=$listing['manufacturers_name'].' '.$listing['products_name'];
        }
        else
        {
          $lastprod=$listing['products_name'];
        }

        if($rad=strpos($lastprod,'('))
        {                     
          $lastprod=trim(substr($lastprod, 0, $rad-1));
        }

      $rows++;
      if($rows==1)
      {
        $firstprod=$lastprod;

      }
      $listing_text.=renderRegularProd($listing,$rows);

    }

   if(!$disableoutput) echo $listing_text;

  } elseif(!$disableoutput) {

      echo '<b>Unfortunately, no resources were found.</b>';

  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) && $listing_split->number_of_pages>1) {

$paging='<br style="clear:both;"/>
             <table border="0" width="97%" cellspacing="0" cellpadding="2">
             <tr>
             <td class="smallText">';
$paging.=$listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS).
                '</td>
                <td class="smallText" align="right">';
    
$paging.=TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))).
                              '</td></tr></table>';
  }
  
    if(!$disableoutput) echo $paging;
?>