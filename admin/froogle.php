<?php
  $system_login=true; 
  set_time_limit(1200);
  ob_start();
  
  $_SERVER['DOCUMENT_ROOT']='..';
  require('includes/application_top.php');  
  // Set headers as Text XML
  
  //header('Content-type: text/xml');
  
  // Here are the initial XML tags
ob_clean();
$file=fopen('c:/seacoast/www/feeds/froogle.xml','w+'); 

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>   
<rss version ="2.0" xmlns:g="http://base.google.com/ns/1.0"> 
 
<channel> 
    <title>Seacoast.com Froogle Feed.</title> 
    <description>A complete list of all Seacoast.com products.</description> 
    <link>http://www.seacoast.com/</link> 
<?php

    //ob_flush();
  
  //Get all product info
  $products=products::all(array('conditions'=>'products_status=1'));
  
  foreach($products as $product)
  {
      
      $tname=preg_replace('/[^A-Za-z0-9-()|,\s]/i','',$product->productsdescription->products_name);
      $tname=preg_split('/([^a-z0-9-\s].+)/i',$tname,2,PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY );
      $tmisc=$tname[1];
      $shortname=trim($tname[0]);
      $new_price = tep_get_products_special_price($product->products_id);
    
      //Get price
      if ($new_price != '')
        { $price=($new_price);}
          else
          { $price=$product->products_price;}
          
          
      //Calculate membership discounts
      if($product->manufacturers_id==69)
      {
        $cm_price=$price*.75; //25% Off
      }
      elseif(!strpos($product->productsdescription->products_name,'*'))
      {
        $cm_price=$price*.85; //15% Off 
      }
      else {
        $cm_price=$price;
      }
      ?>
      <item>
      <g:id><?php echo xml_entities($product->products_id);?></g:id>
      <title><?php echo xml_entities($shortname), ', ', xml_entities($product->manufacturer->manufacturers_name);?></title>
      <link>http://www.seacoast.com/supplement/-<?php echo $product->products_id;?></link>
      <g:price><?php echo number_format($cm_price,2);?></g:price>
      <description><?php 
      
        //echo '<![CDATA[',xml_entities(preg_replace('/&#([0-9]){2};/', ' ',$product->productsdescription->products_description)),']]>'
        echo xml_entities($product->manufacturer->manufacturers_name. ' '. $product->productsdescription->products_name);
        
        ?> 
      
      </description>
    <?php if(strlen($product->products_image)>0){?>
        <g:image_link>http://www.seacoast.com/images/<?php echo xml_entities($product->products_image);?></g:image_link>
    <?php }?>
    <g:condition>new</g:condition>
    <g:brand><?php echo xml_entities($product->manufacturer->manufacturers_name);?></g:brand>
    <?php if(strlen($product->products_upc)==12){ ?>
        <g:gtin><?php echo $product->products_upc?></g:gtin>
    <?php } ?>
    
    <?php if($product->products_available>0){?><g:availability>in stock</g:availability><?php }?>
    <?php if($product->products_weight>0){?><g:shipping_weight><?php echo $product->products_weight;?> lbs</g:shipping_weight><?php } ?>
    <g:product_type>Health &amp; Beauty &gt; Health Care &gt; Fitness &amp; Nutrition &gt; Vitamins &amp; Supplements</g:product_type>



</item>
<?php
//$buffer=ob_get_clean(); 

/*
$tidy_opt=Array(
                'bare'=>false,
                'clean'=>true,
                'lower-literals'=>true,
                'merge-divs'=>'auto',
                'merge-spans'=>'auto',
                'input-xml'=>true,
                'output-xml'=>true,
                'output-encoding'=>'utf8',
                'tidy-mark'=>false,
                'doctype'=>'strict' ,
                'drop-proprietary-attributes'=>false,
                'logical-emphasis'=>true,
                'enclose-block-text'=>false,
                'alt-text'=>'',
                'wrap'=>0,
                'quote-ampersand'=>'no'
                );
           
                                                        
$tidy = new tidy();
$output = utf8_encode($tidy->repairString($buffer,$tidy_opt));  
*/

// save to file

$output .= utf8_encode(ob_get_clean());


ob_flush();                     
  }
  

  // End XML tags  
?>


</channel>
</rss>


<?php

$output .= utf8_encode(ob_get_clean());  
fwrite($file,$output);  
fclose($file);


redir301('http://www.seacoast.com'.'/feeds/froogle.xml');
ob_flush();
exit();   


    function xml_entities($text, $charset = 'Windows-1252'){
     // Debug and Test
    // $text = "test &amp; &trade; &amp;trade; abc &reg; &amp;reg; &#45;";
    
    // First we encode html characters that are also invalid in xml
    $text = htmlentities($text, ENT_COMPAT, $charset, false);
    
    // XML character entity array from Wiki
    // Note: &apos; is useless in UTF-8 or in UTF-16
    $arr_xml_special_char = array("&quot;","&amp;","&apos;","&lt;","&gt;");
    
    // Building the regex string to exclude all strings with xml special char
    $arr_xml_special_char_regex = "(?";
    foreach($arr_xml_special_char as $key => $value){
        $arr_xml_special_char_regex .= "(?!$value)";
    }
    $arr_xml_special_char_regex .= ")";
    
    // Scan the array for &something_not_xml; syntax
    $pattern = "/$arr_xml_special_char_regex&([a-zA-Z0-9]+;)/";
    
    // Replace the &something_not_xml; with &amp;something_not_xml;
    $replacement = '&amp;${1}';
    return preg_replace($pattern, $replacement, $text);
}
?>
