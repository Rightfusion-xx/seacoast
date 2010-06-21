<?php

require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);


$url=str_replace('index.php','',$_SERVER['REQUEST_URI']);
if(strpos($url,';')>0) {$url=parse_section($url.'<eof>',';','<eof>');}
if(substr($url,-1,1)!='/') redir301($url.'/');

//Get category, if any.
$category=str_replace('-',' ',ucwords(parse_section($url.'<eof>','brands/','/')));
$page='';
if(strlen($category)>0){$hasCats=true;}else{$hasCats=false;}


if($hasCats)
{
    $cats_query = tep_db_query('select * from products_new where products_manufacturer like "'.$category.'%" order by products_name asc');
}
else
{
    $cats_query = tep_db_query('select * from products_new group by products_manufacturer order by products_manufacturer asc');

}


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title>Nutritional Supplement Brands <?php if($hasCats){echo ' | '.$category.'';} ?></title>
<meta name="description" content="Nutritional supplements <?php if($hasCats){echo ' from '.$category.'';} ?>"/> 
<meta name="keywords" content="discount supplements<?php if($hasCats){echo ', '.$category.'';} ?>"/> 

<link rel="stylesheet" type="text/css" href="/stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

    <div id="content">
    <h1>Nutritional Supplements<?php if($hasCats){echo ' From '.$category.'';} ?></h1>

    <?php
        
        
        if($hasCats){
            while($cats_db=tep_db_fetch_array($cats_query))
            {
                ?>
                <p>
                    <b><a href="/cheapest/<?php echo $cats_db['products_id']?>-<?php echo format_seo_url($cats_db['products_name']);?>"><?php echo $cats_db['products_name'];?></a></b>
                    <br />
                    <?php echo UCWords(strtolower($cats_db['products_manufacturer']))?>
                
                </p>
            
                <?php
            }
            echo '<p><b><a href="/brands/">View All Brands & Manufacturers</a></b></p>';
        }
        else{
        
            ?>
            
                <p>
                    Please select from the following manufacturers.
                </p>
            
            <?php
            while($cats_db=tep_db_fetch_array($cats_query))
            {
                ?>
                <p>
                    <b><a href="/brands/<?php echo format_seo_url($cats_db['products_manufacturer']);?>"><?php echo UCWords(strtolower($cats_db['products_manufacturer']));?></a></b>
                    <br />
                    <?php echo UCWords(strtolower($cats_db['products_manufacturer']))?>
                
                </p>
            
                <?php
            }            
            
            
            }
                    
                    
    ?>
                   
   
                     
    </div>
                     
                   
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

