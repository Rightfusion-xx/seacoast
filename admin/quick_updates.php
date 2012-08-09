<?php


  require('includes/application_top.php');
  
  $manufacturer=(int)$_REQUEST['manufacturer'];
  

  if(!tep_session_is_registered('stroke_rate'))
    {
        $stroke_rate=35; 
        tep_session_register('stroke_rate');
    } 
    
  if(!tep_session_is_registered('ship_pad'))
  {
      $ship_pad=0.35; 
      tep_session_register('ship_pad');
  }
  
  if($_REQUEST['stroke_rate'] && $_REQUEST['ship_pad'])
  {
      $stroke_rate=$_REQUEST['stroke_rate'];
      $ship_pad=$_REQUEST['ship_pad'];
      echo 'Updated';
      exit();
  }
  
  tep_session_register('stroke_rate');
  
  $columns=array('Manufacturer'=>array('manufacturers_name','label'),
                'Product Name'=>array('products_name','text',60),
                'SKU'=>array('products_sku','label'),
                'Status'=>array('','status'),
                'Weight (lbs)'=>array('products_weight','text',5),
                'Unit Cost'=>array('products_cost','text',6),
                'Expected Price'=>array('','expected_price'),
                
                'Price'=>array('products_price','text',6),
                'MSRP'=>array('products_msrp','text',6)) ;
  
  

 ($row_by_page) ? define('MAX_DISPLAY_ROW_BY_PAGE' , $row_by_page ) : $row_by_page = 1000; define('MAX_DISPLAY_ROW_BY_PAGE' , 1000 );
 
 $page=(int)$_REQUEST['page'];
 if($page>1)
 {
     $offset=$row_by_page*($page-1)+1;
 }
 
 
 $qu=new products_quick_update();   
 $qu_params=array();
 $qu_params['limit']=$rows_by_page;
 $qu_params['offset']=$offset;
 $qu_params['joins']=array('product_description','manufacturer');
 $qu_params['order']='products_name asc'; //default order by
 
 
                
  if($manufacturer)
  {
     
     $qu_params['conditions'].=' manufacturers.manufacturers_id='.(int)$manufacturer;
  }
  
  if($_REQUEST['action']=='update')
  {
      $record=$qu->find($_REQUEST['products_id']);
      $record->$_REQUEST['field']=$_REQUEST['value'];
      if($record->save())
      {
          echo 'Updated';
          
      }
      else
      {
          echo 'FAIL';
          
      }
      exit();
  }
  
  if($manufacturer)
  {
  
    $records=$qu->find('all',$qu_params);

  }
                
                

////Info Row pour le champ fabriquant
  $manufacturers_array = array(array('id' => '0', 'text' => NO_MANUFACTURER));
        $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
                $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                'text' => $manufacturers['manufacturers_name']);
        }

// Display the list of the manufacturers
function manufacturers_list(){
        global $manufacturer;

        $manufacturers_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m order by m.manufacturers_name ASC");
        $return_string = '<select name="manufacturer" onChange="this.form.submit();">';
        $return_string .= '<option value="' . 0 . '">' . TEXT_ALL_MANUFACTURERS . '</option>';
        while($manufacturers = tep_db_fetch_array($manufacturers_query)){
                $return_string .= '<option value="' . $manufacturers['manufacturers_id'] . '"';
                if($manufacturer && $manufacturers['manufacturers_id'] == $manufacturer) $return_string .= ' SELECTED';
                $return_string .= '>' . $manufacturers['manufacturers_name'] . '</option>';
        }
        $return_string .= '</select>';
        return $return_string;
}

if($_REQUEST['action']=="update")
{
    

     $count_item = array_count_values($item_updated);
     if ($count_item['updated'] > 0) $messageStack->add($count_item['updated'].' '.TEXT_PRODUCTS_UPDATED . " $count_update " . TEXT_QTY_UPDATED, 'success');
     break;

 }
 
 
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Catalog Quick Updates</title>
                                                                                                        
                                

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">  
<script type="text/javascript" src="/jquery/js/jquery-1.3.2.min.js"></script>   
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->


<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->

<td width="100%" valign="top">


  
        
            <h1>Quick Update</h1>

  
                   <table width="100%" cellspacing="0" cellpadding="0" border="1" bgcolor="#F3F9FB" bordercolor="#D1E7EF" height="100"><tr align="left"><td valign="middle">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr><td height="5"></td></tr>
                                        <tr align="center">
                                                <td class="smalltext"><?php echo tep_draw_form('row_by_page', FILENAME_QUICK_UPDATES, '', 'get'); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); echo tep_draw_hidden_field( 'cPath', $current_category_id);?></td>
                                                <td class="smallText"><?php // echo TEXT_MAXI_ROW_BY_PAGE . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('row_by_page', $row_bypage_array, $row_by_page, 'onChange="this.form.submit();"'); ?></form></td>
                                                <?php // echo tep_draw_form('categorie', FILENAME_QUICK_UPDATES, '', 'get'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); ?>
                                                <td class="smallText" align="center" valign="top"><?php // echo DISPLAY_CATEGORIES . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); ?></td></form>
                                                <?php echo tep_draw_form('manufacturers', FILENAME_QUICK_UPDATES, '', 'get'); //echo tep_draw_hidden_field( 'row_by_page', $row_by_page); //echo tep_draw_hidden_field( 'cPath', $current_category_id);?>
                                                <td class="smallText" align="center" valign="top"><?php echo DISPLAY_MANUFACTURERS . '&nbsp;&nbsp' . manufacturers_list(); ?></td></form>
                                        </tr>
                                </table>

                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr align="center">


                                                <td align="center">
                                                          <table border="0" cellspacing="0">
                                                                         <tr>
                                                                                   <td class="main"  align="center" valign="middle" nowrap> <?php echo 'Stroke Rate (%)'; ?></td>
                                                                                   <td align="center" valign="middle"> <input type="text" size="5" id="stroke-rate" value="<?php echo $stroke_rate?>"/></td>
                                                                                   <td class="main"  align="center" valign="middle" nowrap> <?php echo 'Rcving Ship Cost/Oz ($)'; ?></td>  
                                                                                   <td><input type="text" size="5" id="ship-pad" value="<?php echo $ship_pad?>"/> </td>
                                                                                   <script language="javascript">
                                                                                    function update_expected_prices()
                                                                                    {
                                                                                        var stroke_rate=$("#stroke-rate").val();
                                                                                        var ship_pad=$("#ship-pad").val(); 
                                                                                        if (stroke_rate>1){stroke_rate=stroke_rate/100;}
                                                                                        $("span[name*='expected_price']").each(function(i,e){
                                                                                            var pid=$(e).attr('name').substr(0,$(e).attr('name').indexOf('-'));
                                                                                            expected_price=$("#"+pid+"-products_cost").attr('value')/(1-stroke_rate)+($("#"+pid+"-products_weight").attr('value')*16*ship_pad);
                                                                                            $(e).text(expected_price.toFixed(2));
                                                                                            
                                                                                        });          
                                                                                        
                                                                                    }
                                                                                   </script>
                                                                                   <td class="smalltext" align="center" valign="middle">
                                                                                   
                                                                                   <input type="submit" onclick="$('#stroke_update').load('quick_updates.php?stroke_rate='+$('#stroke-rate').val()+'&ship_pad='+$('#ship-pad').val());update_expected_prices();return(false);"/>
                                                                                   <?php
                                                                                   
                                                                                 ?>
                                                                                 <?php  echo '<td class="smalltext" align="center" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_MARGE_INFO) . '</td>';?>
                                                                         </tr>
                                                                         <tr>
                                                                                   <td class="smalltext" align="center" valign="middle" colspan="3" nowrap>
                                                                                         <span id="stroke_update">On average, $0.35 per ounce covers UPS 3 Day Select.</span>
                                                                                   </td>
                                                                         </tr>
                                                               
                                                        </table>
                                                </td>
                                        </tr>
                                        <tr><td height="5"></td></tr>

                        </td></tr>
                        <br>
                        
                </td>
      </tr>
          <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
                  <td valign="top"><table border="0" bordercolor="#FF0000" width="100%" cellspacing="0" cellpadding="2">
                      <tr class="dataTableHeadingRow">          
          <?php
          
          foreach(array_keys($columns) as $item)
          {
              
                  ?>
                  

                        <td class="dataTableHeadingContent" align="left" valign="top"> 
                          <table border="0" cellspacing="0" cellpadding="0">
                            <tr class="dataTableHeadingRow">
                             <td class="dataTableHeadingContent" align="left" valign="middle">
                             <?php echo " <a href=\"" . tep_href_link( $_SERVER['PHP_SELF'], 'sort_by='.$item.' ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', 'Sort ' . $item . ' ' . ' Ascending')."</a>
                             <a href=\"" . tep_href_link(  $_SERVER['PHP_SELF'], 'cPath='. $current_category_id .'&sort_by='.$item.' DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', 'Sort ' . $item . ' ' . ' Descending')."</a>
                             <br>"  .$item . "</td>" ; ?>
                            </tr>
                          </table>
                        </td>
                  
                  
                  <?php
    
                  
              
              
          }
          
        
           
?>
          
                                       
                 <td class="dataTableHeadingContent" align="center" valign="middle">&nbsp;</td>
                <td class="dataTableHeadingContent" align="center" valign="middle">&nbsp;</td>
                </tr>
<?php

foreach($records as $record)
{
//// display infos per row


    echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'">';
    foreach($columns as $item)
    {
        
        if($item[0] && $item[1]=='text')
        {
            echo '<td class="smallText" align="left"><input type="text" onchange="$(\'span[name='.$record->products_id.'-status]\').load(\'quick_updates.php?action=update&products_id='.$record->products_id.'&field='.$item[0].'&value=\'+escape(this.value));update_expected_prices();" size="'.$item[2].'" id="'.$record->products_id.'-'.$item[0].'" value="'.$record->$item[0].'"></td>';
                    
        }
        else
        {
            if($item[0])
            {
                echo "<td class=\"smallText\" align=\"left\">" . $record->$item[0] . "</td>";     
            }
            else
            {
                echo "<td class=\"smallText\" align=\"left\"><span name=\"" . $record->products_id."-".$item[1] . "\"></span></td>";
            }
            
        }

     }
     echo "<td class=\"smallText\" align=\"left\"><a href=\"".tep_href_link (FILENAME_CATEGORIES, 'pID='.$record->products_id.'&action=new_product')."\">". tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', TEXT_IMAGE_SWITCH_EDIT) ."</a></td>\n";

     echo "</tr>";  
}



echo "</table>";

?>
<script language="javascript">
update_expected_prices();
</script>
          </td>
        </tr>
       </table></td>
      </tr>
<tr>
<td align="right">
<?php
				
                 //// display bottom page buttons
                echo '<a href="javascript:window.print()">' . PRINT_TEXT . '</a>&nbsp;&nbsp;';
             // echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
             // echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES,"row_by_page=$row_by_page") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
</tr>
</form>
            
          </tr>
        </table>
        
        </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
  </tr>
</table>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>