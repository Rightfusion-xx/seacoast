<?php




$url=str_replace('index.php','',$_SERVER['REQUEST_URI']);
if(strpos($url,';')>0) {$url=parse_section($url.'<eof>',';','<eof>');}
if(substr($url,-1,1)!='/') redir301($url.'/');

//Get category, if any.
$category=ucwords(parse_section($url.'<eof>','cheapest/','/'));
$page=parse_section($url,'/pg','/');
if($page=='' || !(is_numeric($page)))
{
    $page=1;
}
$limit= $page*50-50;
$pglimit=' limit ' . $limit . ',50 ';

if(strlen($category)>0){$hasCats=true;}else{$hasCats=false;}

if($hasCats && $category=='Numeric')
{
    $cats_query = ('select * from products_new p where left(products_name,1) REGEXP ("[0-9]") order by products_name asc ');

}
elseif($hasCats)
{
    $cats_query = ('select * from products_new p where products_name like "'.$category.'%" order by products_name asc ');
}
else
{
    $cats_query = ('select * from products_new p where 1=2 order by products_name asc limit 0,10');

}


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title>Cheapest Supplements <?php if($hasCats){echo ' Starting With "'.$category.'"';} ?></title>
<meta name="description" content="Discounted supplements <?php if($hasCats){echo ' beginning with '.$category.'';} ?>"/> 
<meta name="keywords" content="Health, Guides<?php if($hasCats){echo ', '.$category.'';} ?>"/> 

<link rel="stylesheet" type="text/css" href="/stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

    <div id="content">
    <h1>Discount Supplements<?php if($hasCats){echo ' Starting With "'.$category.'"';} ?></h1>

    <?php
        
        	include(DIR_WS_MODULES . 'cheapest_listing.php'); 
        	
        
        
        if(!$hasCats){
            ?>
            <p>
            Seacoast Vitamins provides reviews and price comparisons on over 15,000 natural health products, including
            new, first time to market items. Complete product details as well as comprehensive price information from 
            Seacoast Vitamins and our partners make this the premier site for natural health needs, including vitamins and
            nutritional supplements.
            </p>
            <h2>New Reviews & Comparisons</h2>
            <p>
               <?php include(DIR_WS_MODULES . 'new_cheapest_products.php'); ?>

            </p>
            
            <?php }
            
        if (!$hasCats)
        {
            $top_searches_query=tep_db_query('select query, hits as ttlhits from site_queries where param_id = "/cheapest/" order by ttlhits desc limit 0,15');
            
            
        }
        else
        {
            $top_searches_query=tep_db_query('select query, hits as ttlhits from site_queries where param_id = "'.tep_db_input($url).'" order by ttlhits desc limit 0,15');
      
        }
        
        ?>
        <h2>Most Popular Searches</h2>
        <ol>
        <?php
        while($top_searches=tep_db_fetch_array($top_searches_query))
        {
            echo '<li><a href="/topic.php?health='.urlencode(strtolower($top_searches['query'])).'">'.ucWords(strtolower($top_searches['query'])).'</a></li>';
        }
        
        echo '</ol>';
                    
                    
    ?>
                   
    <div id="render_alpha">
    <h2>New Vitamins Supplements Index</h2>
        <a href="/cheapest/numeric/">0-10</a> | 
        <?php
            for($i=ord('A');$i<=ord('Z');$i++)
            {
                ?>
                    <a href="/cheapest/<?php echo strtolower(chr($i));?>/"><?php echo chr($i);?></a>
                <?php
                if($i<ord('Z')){echo '|';}
            }
        ?>
    </div>                 
                     
                     
    </div>
                     
                   
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

