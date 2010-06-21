<?php
//Symptoms


require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
include_once('../includes/functions/render_products.php');

$url=str_replace('index.php','',$_SERVER['REQUEST_URI']);
if(strpos($url,';')>0) {$url=parse_section($url.'<eof>',';','<eof>');}
if(substr($url,-1,1)!='/') redir301($url.'/');

//Get category, if any.
$category=ucwords(parse_section($url.'<eof>','symptoms/','/<eof>'));
if(strlen($category)>0){$hasCats=true;}else{$hasCats=false;}

if($hasCats)
{
    $cats_query = tep_db_query('select * from products_uses where right(products_uses,length(products_uses)-instr(products_uses," ")) like "'.$category.'%" order by products_uses asc');
}
else
{
    $cats_query = tep_db_query('select * from products_uses order by count desc LIMIT 0,50;');
}

if(tep_db_num_rows($cats_query)<1){
    //No categories found, redirect.
    redir301('/symptoms/');
    }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title>Guides to Symptoms<?php if($hasCats){echo ' Starting With "'.$category.'"';} ?></title>
<meta name="description" content="Information on Health Symptoms<?php if($hasCats){echo ' Starting With '.$category.'';} ?>"/> 
<meta name="keywords" content="Symptoms, Guides<?php if($hasCats){echo ', '.$category.'';} ?>"/> 

<link rel="stylesheet" type="text/css" href="/stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

    <div id="content">
    <h1>Guides to Symptoms<?php if($hasCats){echo ' Starting With "'.$category.'"';} ?></h1>

    <?php
        $i=1;
        if(!$hasCats){echo '<h2>Browse Most Common Symptoms</h2><p>Below are the most commonly searched symptoms on Seacoast Vitamins. Included in each 
            <b>Symptom Guide</b> is complete detailed information for that symptom as well as product recommendations.</p>';}
        while($cats_db=tep_db_fetch_array($cats_query))
        {
            ?>
            <p>
                <?php echo $i;?>. <b><a href="/natural_uses.php?use=<?php echo urlencode(strtolower($cats_db['products_uses']));?>"><?php echo parse_section(ucwords(trim($cats_db['products_uses'])).'<eof>',' ','<eof>');?></a></b>
                <br />
                Health guides and detailed information on <b><?php echo parse_section(trim($cats_db['products_uses']).'<eof>',' ','<eof>');?></b>.
            
            </p>
        
            <?php
            $i++;
        }
                    
                    
    ?>
                   
    <div id="render_alpha">
    <h2>Symptoms Guides Index</h2>
        <?php
            for($i=ord('A');$i<=ord('Z');$i++)
            {
                ?>
                    <a href="/symptoms/<?php echo strtolower(chr($i));?>/"><?php echo chr($i);?></a>
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

