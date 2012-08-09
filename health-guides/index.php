<?php


require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

$url=str_replace('index.php','',$_SERVER['REQUEST_URI']);
if(strpos($url,';')>0) {$url=parse_section($url.'<eof>',';','<eof>');}
if(substr($url,-1,1)!='/') redir301($url.'/');

//Get category, if any.
$category=ucwords(parse_section($url.'<eof>','health-guides/','/<eof>'));
if(strlen($category)>0){$hasCats=true;}else{$hasCats=false;}

if($hasCats)
{
    $cats_query = tep_db_query('select categories_name, c.categories_id from categories c join categories_description cd on 
        cd.categories_id=c.categories_id where cd.categories_name like "'.$category.'%" 
        union select products_departments as categories_name, "0" as categories_id from products_departments where
        products_departments like "'.$category.'%"
        order by categories_name asc');
}
else
{
    $cats_query = tep_db_query('select * from categories c join categories_description cd on 
    cd.categories_id=c.categories_id where c.parent_id<1 order by sort_order asc');
}

if(tep_db_num_rows($cats_query)<1){
    //No categories found, redirect.
    redir301('/health-guides/');
    }
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title>Health-Guides<?php if($hasCats){echo ' Starting With "'.$category.'"';} ?></title>
<meta name="description" content="Natural Health & Information Guides<?php if($hasCats){echo ' Starting With '.$category.'';} ?>"/> 
<meta name="keywords" content="Health, Guides<?php if($hasCats){echo ', '.$category.'';} ?>"/> 

<link rel="stylesheet" type="text/css" href="/stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

    <div id="content">
    <h1>Health-Guides<?php if($hasCats){echo ' Starting With "'.$category.'"';} ?></h1>

    <?php
        
        while($cats_db=tep_db_fetch_array($cats_query))
        {
            ?>

                <?php if($cats_db['categories_id']==0)
                {
                    ?>
                    <p> 
                        <b><a href="/departments.php?benefits=<?php echo urlencode(strtolower($cats_db['categories_name']));?>"><?php echo ucwords($cats_db['categories_name'])?></a></b>
                        <br />
                        <?php echo $cats_db['categories_htc_desc_tag']?>
                                                    
                    </p>
                    
                    <?php
    
                }
                else
                {
                    ?>
                    <p> 
                        <b><a href="/index.php?cPath=<?php echo $cats_db['categories_id'];?>"><?php echo $cats_db['categories_name']?></a></b>
                        <br />
                        <?php echo $cats_db['categories_htc_desc_tag']?>
                    
                    </p>
        
                    <?php
                }
                
        }
                    
                    
    ?>
                   
    <div id="render_alpha">
    <h2>Health Guides Index</h2>
        <?php
            for($i=ord('A');$i<=ord('Z');$i++)
            {
                ?>
                    <a href="/health-guides/<?php echo strtolower(chr($i));?>/"><?php echo chr($i);?></a>
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

