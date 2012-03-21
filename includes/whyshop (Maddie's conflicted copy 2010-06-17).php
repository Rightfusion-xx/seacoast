

<h1>Vitamins - Direct To Members from Seacoast Vitamins</h1>
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td valign="top">
<br/>
<h2  style="clear:both;">Movers & Shakers - Best Selling & Featured Supplements</h2>

<?php
  $useCategories[]='';
  $ailmentCategories[]='';
  $departmentCategories[]='';

    $select_column_list = '';
          $select_column_list .= ' p.products_id, products_image, p.products_price, p.products_msrp, pd.products_name, pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'pd.products_head_desc_tag as product_desc, pd.products_isspecial, ';
          $select_column_list .= 'm.manufacturers_name, m.manufacturers_id, products_image, pd.products_ailments, pd.products_uses, pd.products_departments, ';


$listing_sql='select ' . $select_column_list .'  sum(op.products_quantity)  as velocity,IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price 
    from products p join products_description pd on pd.products_id=p.products_id join orders_products op on op.products_id=p.products_id
	  join orders o on o.orders_id=op.orders_id join manufacturers m on m.manufacturers_id=p.manufacturers_id
    LEFT OUTER join specials s on s.products_id=p.products_id
    where op.products_id=p.products_id and date_purchased>=curdate()- INTERVAL 3 DAY and orders_status=3
    group by p.products_id
    order by velocity desc
    limit 0,25';
        
    $rows=1;
   $listing_sql=tep_db_query($listing_sql);
   while($product_info=tep_db_fetch_array($listing_sql))
   {
         $listing_text='';
         //Get all related ailments, uses, and departments.
      $tempuses=preg_split('/,/',str_replace(', ',',',$product_info['products_uses']));
      foreach($tempuses as $tempitem)
      {
        if(array_search($tempitem,$useCategories)==false && strlen($tempitem)>2){
          array_push($useCategories,ucwords($tempitem));
        }
      }
      
      $tempuses=preg_split('/,/',str_replace(', ',',',$product_info['products_ailments']));
      foreach($tempuses as $tempitem)
      {
        if(array_search($tempitem,$ailmentCategories)==false && strlen($tempitem)>2){
          array_push($ailmentCategories,ucwords($tempitem));
        }
      }   
      
      $tempuses=preg_split('/,/',str_replace(', ',',',$product_info['products_departments']));
      foreach($tempuses as $tempitem)
      {
        if(array_search($tempitem,$departmentCategories)==false && strlen($tempitem)>2){
          array_push($departmentCategories,ucwords($tempitem));
        }
      }   
   
      
      $rows++;

      $product_image_path='';
      
     
        $product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);

  


      $listing_text.='<div id="prod'.$rows.'" class="';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.='mini-product_isspecial';
      }else{
        $listing_text.='mini-product_regular';
      }
      $listing_text.='">';
            
            $listing_text.='<div style="float:left;" class="mini-listing-image">';
            if(strlen($product_image_path)>0){
             $listing_text.='<img src="'. $product_image_path . '" width="50" style="margin:5px;" ALIGN="left" />';
            }
            $listing_text.='</div>';

            $listing_text.= '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '"><b>' . $product_info['products_name'] . '</b></a>';
            
            $listing_text.='<br/><span style="color:#66CC00;font-weight:bold;">';
            if (tep_not_null($product_info['specials_new_products_price'])) {

              $listing_text.= '&nbsp;<s>' .  $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['specials_new_products_price'])/$product_info['products_msrp']*100);}
            } else {

              $listing_text.= '&nbsp;' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['products_price'])/$product_info['products_msrp']*100);}

            }

           $listing_text.='</span> from <b>';
           $listing_text.= $product_info['manufacturers_name'].'</b>' ;

            if($discountpct>0){
             $listing_text .= '<br/><span style="color:#ff0000;font-weight:bold;">'.$discountpct.'% Off</span>';
            }
   
           $listing_text.='<br style="clear:both;"/>';



  
      $listing_text.='</div>';

    	       
    echo $listing_text;
    $listing_text='';
		        
      
      $rows++;
   
   
   }
   
    


?>
</td>
<td valign="top">

<div style="float:right;width:300;margin-left:20px;" id="hn_topics">

  	<link href="http://www.healthnotes.info/http/Styles/welcome.css" rel="stylesheet" /> 

    <script type="text/javascript" src="http://www.healthnotes.info/http/scripts/JumpNav.js"></script>
    <div id="welcomeBody">


      <table cellpadding="0" cellspacing="0" border="0" class="columns">


      <tr valign="top">
        <td> 

          <div id="group1">
            <div class="section">
      <h1 class="box"><a href="health_library.php?article=2402003">HEALTH GUIDE</a></h1>
      <form id="JumpConcerns" action="javascript:jump('JumpConcerns')" method="post">
        <div>Learn more about:</div>
        <select name="target" size="1" onChange="javascript:jump('JumpConcerns')">

          <option value="health_library.php?article=1171004">Bronchitis</option>
          <option value="health_library.php?article=1177000">Cancer Prevention and Diet</option>
          <option value="health_library.php?article=1200006">Depression</option>
          <option value="health_library.php?article=1028005">High Cholesterol</option>
          <option value="health_library.php?article=1033009">Hypertension</option>
          <option value="health_library.php?article=1222004">Insomnia</option>

          <option value="health_library.php?article=1041009">Menopause</option>
          <option value="health_library.php?article=3990003">Men&#8217;s Health Center</option>
          <option value="health_library.php?article=1043007">Migraine Headaches</option>
          <option value="health_library.php?article=1049006">Osteoarthritis</option>
          <option value="health_library.php?article=1050006">Osteoporosis</option>
          <option value="health_library.php?article=1249004">Premenstrual Syndrome</option>

          <option value="health_library.php?article=3991004">Women&#8217;s Health Center</option>
          <option value="health_library.php?article=2402003">See all concerns...</option>
        </select>
        <input type=submit value=GO class="button" onClick="javascript:jump('JumpConcerns')">
      </form>
      <p class="more"><a href="health_library.php?article=2402003">All health conditions</a></p>
    </div>

    <div class="focus">
      <h2><a href="health_library.php?article=2461007"><img src="http://www.healthnotes.info/http/images/vitamin_pic.gif" alt="" width="51" height="55" border="0" class="left">Vitamin Guide</a></h2>
      <p>Information about nutritional supplements</p>
    </div>
    <div class="focus">
      <h2><a href="health_library.php?article=2420007"><img src="http://www.healthnotes.info/http/images/herb_pic.gif" alt="" width="51" height="55" border="0" class="left">Herbal Remedies</a></h2>
      <p>The medicinal use of herbs&#8212;old and new</p>
    </div>

    <div class="focus">
      <h2><a href="health_library.php?article=2425002"><img src="http://www.healthnotes.info/http/images/homeo_pic.gif" alt="" width="51" height="55" border="0" class="left">Homeopathy</a></h2>
      <p>Nature&#8217;s subtle influences</p>
    </div>
    <div class="focus">
      <h2><a href="health_library.php?article=3994007"><img src="http://www.healthnotes.info/http/images/hl_weight_pic.gif" alt="" width="51" height="55" border="0" class="left">Weight Control</a></h2>

      <p>Look good and feel great</p>
    </div>

    <div class="focus">
      <h2><a href="health_library.php?article=3993001"><img src="http://www.healthnotes.info/http/images/hl_sports_pic.gif" alt="" width="51" height="55" border="0" class="left">Sports &amp; Fitness</a></h2>
      <p>Maximize performance, achieve fitness, go for it</p>
    </div>
    <div class="focus">
      <h2><a href="health_library.php?article=3991004"><img src="http://www.healthnotes.info/http/images/womens_pic.gif" alt="" width="51" height="55" border="0" class="left">Women&#8217;s Health</a></h2>

      <p>Common health conditions of interest to women</p>

    </div>
    <div class="focus">
      <h2><a href="health_library.php?article=3990003"><img src="http://www.healthnotes.info/http/images/mens_pic.gif" alt="" width="51" height="55" border="0" class="left">Men&#8217;s Health</a></h2>
      <p>Common health conditions of interest to men</p>
    </div>


      </div>
   </td></tr></table>
    <div id="nav_manufacturers" class="nav_box" style="margin-top:20px;">
  <div class="nav_header">
    Direct to Members
  </div>
  <div style="text-align:center;padding:10px;height:100%;">
   <b>Seacoast Vitamins, Direct!</b>
   <p>
   		Join Seacoast Vitamins-Direct FREE for 14-Days. <a href="/community/" style="font-weight:bold">Start now</a>.
   </p>
      </div></div>
    
    </div>
  
  
</div>

</td>
</tr>
</table>



<br style="clear:both;"/>


<h2>Hottest Offers (hurry, quantity limited!)</h2>

  <?php
    $products_die_q=tep_db_query('select m.manufacturers_id, products_dieqty, products_name, p.products_image, p.products_id, specials_new_products_price, products_msrp, manufacturers_name, cast(((products_msrp-specials_new_products_price)/products_msrp*100.00) as signed) as discountpct from
        products p join products_description pd on p.products_id=pd.products_id
        join manufacturers m on m.manufacturers_id=p.manufacturers_id
        join specials s on s.products_id=p.products_id
                group by p.products_id
        order by discountpct desc limit 0,12');
        
    while($products_die=tep_db_fetch_array($products_die_q))
    {
          $product_image_path='';
      
        if (file_exists (DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.gif')){
      $product_image_path = DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.gif';}
      elseif (file_exists (DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.jpg')){
      $product_image_path = DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.jpg';}
      elseif (file_exists (DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.bmp')){
      $product_image_path = DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.bmp';}
      elseif (file_exists (DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.png')){
      $product_image_path = DIR_WS_IMAGES.'products/'.$products_die['products_id'].'.png';}
      elseif  (tep_not_null($products_die['products_image'])){
      $product_image_path = DIR_WS_IMAGES.$products_die['products_image']; }
   	  elseif ($products_die['manufacturers_id']=='69'){
             $product_image_path = DIR_WS_IMAGES.'seacoast_logo.png';}
      ?>
      <div id="prod2" class="mini-product_regular">
        <div class="mini-listing-image">
          <?php if($product_image_path!=''){?><a href="/product_info.php?products_id=<?php echo $products_die['products_id']?>"><img src="<?php echo $product_image_path;?>" border="0" width="50" style="margin:5px;" ALIGN="left" /></a><?php } ?>
        </div>&nbsp;<a href="/product_info.php?products_id=<?php echo $products_die['products_id']?>">
          <b><?php echo $products_die['products_name'];?></b>
        </a><br/><span style="color:#66CC00;font-weight:bold;">&nbsp;$<?php echo number_format($products_die['specials_new_products_price'],2)?>&nbsp;</span> from <b><?php echo $products_die['manufacturers_name']?></b><br/><span style="color:#ff0000;font-weight:bold;"><?php echo $products_die['discountpct']?>% discount</span>
      </i>
          <br>
            <br style="clear:both;"/>
      </div>
      <?php 
    
    
    }
  
  ?>
<p style="clear:left;">
  <i>Special offers limited by quantity. <b>Order Now.</b><br/>
  </i>
</p>

<?php
//Display Ailments
if(is_array($departmentCategories)&&$departmentCategories[1]!=''){
?>
<h2>Popular Departments</h2>
<ul style="width:97%;">
  <?php
        sort($departmentCategories);      
        array_shift($departmentCategories);
        foreach($departmentCategories as $tempitem){
          echo '<li style="width:190px;float:left;margin-left:10px;"><a href="/departments.php?benefits='.urlencode(strtolower($tempitem)).'">'.$tempitem.'</a></li>';
          }
   ?>
</ul>
<br style="clear:both;"/>
<?php   }
   
//Display useCategories
if(1==2){
if(is_array($useCategories)&&$useCategories[1]!=''){?>
<h2>Common Uses & Indications</h2>
<ul style="width:97%;">
  <?php
        sort($useCategories);
        array_shift($useCategories);
        foreach($useCategories as $tempitem){
          echo '<li style="width:190px;float:left;margin-left:10px;"><a href="/natural_uses.php?use='.urlencode(strtolower($tempitem)).'">'.$tempitem.'</a></li>';
          }
   ?>
</ul>
<br style="clear:both;"/>
<?php   }
   
//Display Ailments
if(is_array($ailmentCategories)&&$ailmentCategories[1]!=''){
?>
<h2>Common Ailments & Concerns</h2>
<ul style="width:97%;">
  <?php
        sort($ailmentCategories);
        array_shift($ailmentCategories);
        foreach($ailmentCategories as $tempitem){
          echo '<li style="width:190px;float:left;margin-left:10px;"><a href="ailments.php?remedy='.urlencode(strtolower($tempitem)).'">'.$tempitem.'</a></li>';
          }
   ?>
</ul>
<br style="clear:both;"/>
<br/>
<?php   }}
   

?>
