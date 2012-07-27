<?php
set_time_limit(1200);
//$_SERVER['DOCUMENT_ROOT']='..';
require('../includes/application_top.php');

$fileToWrite = './products-sitemap.xml';

$newline="\r\n";
// Set headers as Text XML

header('Content-type: text/xml');

// Here are the initial XML tags

// save to file
$file=fopen($fileToWrite,'w+');

ob_clean();
fwrite($file,utf8_encode("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>".$newline."<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".$newline));

// Get all product info
$products=products::all(array('conditions'=>'products_status=1'));
// echo $products->count();exit();
$index=0;
foreach($products as &$product)
{
    $tname=preg_replace('/[^A-Za-z0-9-()|,\s]/i', '', $product->productsdescription->products_name);

    $tname=preg_split('/([^a-z0-9-\s].+)/i', $tname, 2, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY );
    $tmisc=$tname[1];
    $shortname=trim($tname[0]);
    $new_price = tep_get_products_special_price($product->products_id);

    //Get price
    if ($new_price != '')
    {
        $price=($new_price);
    }
    else
    {
        $price=$product->products_price;
    }
    //Calculate membership discounts
    if($product->manufacturers_id==69)
    {
        $cm_price=$price*.75; //25% Off
    }
    elseif(!strpos($product->productsdescription->products_name,'*'))
    {
        $cm_price=$price*.85; //15% Off
    }
    else
    {
        $cm_price=$price;
    }

    $test_url = (
        str_replace(
            '//',
            '/',
            "/" . seo_url_title(!is_array($tname) ? $tname : $tname[0]) . "/" . urlencode($product->manufacturer->manufacturers_name) . "/" . seo_url_title($tmisc) . "/p" . $product->productsdescription->products_id
        )
    );

    $Q = "
        SELECT
            op.*,
            o.*,
            p.*,
            IFNULL(o.date_purchased, IFNULL(p.products_last_modified, products_date_added)) AS lastmod
        FROM products p
        LEFT OUTER JOIN orders_products op ON (op.products_id=p.products_id)
        LEFT OUTER JOIN orders o ON (o.orders_id=op.orders_id)
        WHERE
            p.products_id=".$product->products_id."
        ORDER BY date_purchased DESC
        LIMIT 0,1
    ";
    $prod = tep_db_fetch_array(
        tep_db_query($Q)
    );
    $lastmod= $prod['lastmod'];
    //echo "<pre>";var_dump($lastmod);exit();
    /*
    if(is_null($prod['date_purchased']))
    {
        if(is_null($prod['products_last_modified']))
        {
            $lastmod=$prod['products_date_added'];
        }
        else
        {
            $lastmod=$prod['products_last_modified'];
        }
    }
    else
    {
        $lastmod=$prod['date_purchased'] ;
    }
*/
    if($prod['products_available']<>0 or $prod['products_id']=='9861')
    {
        $priority=0.9;
    }
    else
    {
        $priority=0.1;
    }

    // Calculate change frequency
    $numdays=(time()-$lastmod)/(60 * 60 * 24);
    if($numdays < 1)
    {
        $changefreq='daily';
    }
    elseif($numdays >= 1 && $numdays <= 29)
    {
        $changefreq='weekly';
    }
    else
    {
        $changefreq='monthly';
    }  //if lastmod is greater than week, change frequency.

    fwrite(
        $file,
        utf8_encode(
            "\t<url>".$newline.
            "\t\t<loc>http://www.seacoast.com". $test_url. "</loc>".$newline.
            "\t\t<lastmod>".date('Y-m-d',strtotime($lastmod))."</lastmod>".$newline.
            "\t\t<changefreq>".$changefreq."</changefreq>".$newline.
            "\t\t<priority>".$priority."</priority>".$newline.
            "\t</url>".$newline
        )
    );
    unset($products[$index]);
    $index++;
    if($index > 10) break;
}

// End XML Tags

$data='</urlset>';
fwrite($file,utf8_encode($data));
fclose($file);

echo file_get_contents($fileToWrite);

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