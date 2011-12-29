<?php

  set_time_limit(1200);
  ob_start();
  
  $_SERVER['DOCUMENT_ROOT']='..';
  require('../includes/application_top.php');  
  
  $newline="\r\n";
  // Set headers as Text XML
  
  //header('Content-type: text/xml');
  
  // Here are the initial XML tags

  // save to file
$file=fopen('froogle.xml','w+');
ob_clean();

$data="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>".$newline.
    "<rss version =\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\">".$newline. 
    "<channel>".$newline. 
        "<title>Seacoast.com Froogle Feed.</title>".$newline. 
        "<description>A complete list of all Seacoast.com products.</description>".$newline. 
        "<link>http://www.seacoast.com/</link>";

        
    fwrite($file,utf8_encode($data));
  
  // Get all product info
  $products=products::all(array('conditions'=>'products_status=1'));
  
                // echo $products->count();exit();
  $index=0;    
  foreach($products as &$product)
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
      
      $data = 
          "".$newline.
          "<item>".$newline.
          "<g:id>". xml_entities($product->products_id). "</g:id>".$newline.
          "<title>".$newline.xml_entities($shortname). ", ". xml_entities($product->manufacturer->manufacturers_name)."</title>".$newline.
          "<link>http://www.seacoast.com/supplement/-".$product->products_id."</link>".$newline.
          "<g:price>".number_format($cm_price,2)."</g:price>".$newline.
          "<description>".xml_entities($product->manufacturer->manufacturers_name. 
            " ". $product->productsdescription->products_name).
          "</description>" . $newline;
    if(strlen($product->products_image)>0){
        $data.="<g:image_link>http://www.seacoast.com/images/". xml_entities($product->products_image) ."</g:image_link>".$newline;
    }
    $data.="<g:condition>new</g:condition>".$newline;
    $data.="<g:brand>".xml_entities($product->manufacturer->manufacturers_name)."</g:brand>" . $newline;
    if(strlen($product->products_upc)==12){ 
        $data.="<g:gtin>". $product->products_upc ."</g:gtin>" . $newline;
    }
    
    if($product->products_available>0){ $data.="<g:availability>in stock</g:availability>".$newline; }
    if($product->products_weight>0){ $data.="<g:shipping_weight>".$product->products_weight." lbs</g:shipping_weight>" . $newline; }
    $data.="<g:product_type>Health &amp; Beauty &gt; Health Care &gt; Fitness &amp; Nutrition &gt; Vitamins &amp; Supplements</g:product_type>".$newline;



$data.="</item>".$newline;
fwrite($file,utf8_encode($data));
unset($products[$index]);
$index++;
  
               
  }
  
// End XML Tags

$data='</channel>';
$data.='</rss>';

fwrite($file,utf8_encode($data));

fclose($file);
                                 
redir301('froogle.xml');

exit();


//redir301('froogle.xml');
//ob_flush();


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
