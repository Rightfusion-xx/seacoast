<?php

//echo $_SERVER['REQUEST_URI'] ; exit();

// Need to match the first /test/ as the processor command
if(preg_match('/\/([a-z0-9-]+)\//', $_SERVER['REQUEST_URI'],$processor))
{
 
    $processor=$processor[0];

}
if(preg_match('/\/([a-z0-9-]+$|[a-z0-9-]+-p\d+$)/', $_SERVER['REQUEST_URI'],$url_title))
{
    $url_title=str_replace('/','',$url_title[0]);

}
if(preg_match('/(-p\d+$)/',$_SERVER['REQUEST_URI'],$pagenum))                                         
{
    $pagenum=substr($pagenum[0],2);  
    $url_title=preg_replace('/-p'.$pagenum.'$/','',$url_title);
}
else
{
    $pagenum=1;                                                                                              
}

if(strlen($processor))
{
    $_GET=array();
}  
               
if($pagenum>1)
{
    $_REQUEST['page']=$pagenum;  
    $_GET['page']=$pagenum;  

}



switch($processor)
{
    case '/images/':
        exit();
    case '/remedies/':
        header("HTTP/1.0 200 OK");  
        $_REQUEST['use']=$url_title; 
        $_GET['use']=$url_title; 
        $_SERVER['PHP_SELF']='/natural_uses.php';  
        $modURL=true;
        include('/natural_uses.php');
        exit();
        break;
        
    case '/ailment/':
        header("HTTP/1.0 200 OK");  
        $_REQUEST['remedy']=$url_title; 
        $_GET['remedy']=$url_title; 
        $_SERVER['PHP_SELF']='/ailments.php';  
        $modURL=true;
        include('/ailments.php');
        exit();
        break;
        
    case '/use/':
        header("HTTP/1.0 200 OK");  
        $_REQUEST['benefits']=$url_title; 
        $_GET['benefits']=$url_title; 
        $_SERVER['PHP_SELF']='/departments.php';  
        $modURL=true;
        include('/departments.php');
        exit();
        break;
        
    case '/supplement/':
        header("HTTP/1.0 200 OK");  
        preg_match('/(-\d+$|-\d+-p\d+$)/i', $url_title, $matches);
        preg_match('/-\d+/i',$matches[0],$matches);
        $cPath=substr($matches[0],1);
        $_REQUEST['products_id']=(int)$cPath; 
        $_GET['products_id']=(int)$cPath; 
        $_SERVER['PHP_SELF']='/product_info.php';  
        $modURL=true;
        include('/product_info.php');
        exit();
        break;
        
        
     case '/guide/':
        header("HTTP/1.0 200 OK");  
        preg_match('/(-\d+$|-\d+-p\d+$)/i', $url_title, $matches);
        preg_match('/-\d+/i',$matches[0],$matches);
        $cPath=substr($matches[0],1);
        $_REQUEST['cPath']=(int)$cPath; 
        $_GET['cPath']=(int)$cPath; 
        $_SERVER['PHP_SELF']='/index.php';  
        $modURL=true;
        include('/index.php');
        exit();
        break;
        
        
     case '/naturalist/':
        header("HTTP/1.0 200 OK");  
        preg_match('/(-\d+$|-\d+-p\d+$)/i', $url_title, $matches);
        preg_match('/-\d+/i',$matches[0],$matches);
        $cPath=substr($matches[0],1);
        $_REQUEST['manufacturers_id']=(int)$cPath; 
        $_GET['manufacturers_id']=(int)$cPath; 
        $_SERVER['PHP_SELF']='/index.php';  
        $modURL=true;
        include('/index.php');
        exit();
        break;
    
    
    
    default:
    
   header("HTTP/1.0 404 Not Found"); 
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

   

?>
      <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>
<title>Seacoast Vitamins | Long Gone...</title>
 <meta name="Description" content="A little 404 not found problem..." />
 <meta name="Keywords" content="404, not found" /> 
 <meta name="robots" content="noodp" /> 
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="/stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<div id="content">

                   <h1>Hmmmm, this page was never here...</h1>
                   
                   <p>
                   <b>Try a search, instead:</b>
                   
                   <form action="/topic.php" method="get">
                    <input type="text" name="health" size="40"><br/>
                    <input type="submit" value="Search">
                   </form>  
                   </p>

              

               </div>


<?php 

require(DIR_WS_INCLUDES . 'footer.php'); ?>

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php');


} ?>
