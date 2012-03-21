<?php

function set_old_uri()
{
    global $old_uri;
    $old_uri=$_SERVER['PHP_SELF'];
    $first=true;
    foreach(array_keys($_GET) as $item)
    {
        if($first)
        {
            $old_uri.='?';
            $first=false;
        }
        else
        {
            $old_uri.='&';            
        }
        
        $old_uri.=$item .'='.$_GET[$item];
        
    }
}

require('includes/application_top.php');

if(preg_match('/\/404\.php/i',$_SERVER['REQUEST_URI']))
{
    redir301(HTTP_SERVER);
}
//echo $_SERVER['REQUEST_URI'] ; exit();

//This redirects some of the seacoast.com backlink juice to the home page.
if(preg_match('/\/~.+?/',$_SERVER['REQUEST_URI']))
{

    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://www.seacoast.com/');
    exit();
    
}

// Need to match the first /test/ as the processor command  
if(preg_match('/\/([a-z0-9-]+)\//', $_SERVER['REQUEST_URI'],$processor))
{
 
    $processor=$processor[0];


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

    // Test for and get product_id
    if(preg_match('/\/p([0-9]+)/',$_SERVER['REQUEST_URI'],$products_id))
    {
        $products_id=$products_id[1];
    }


    switch($processor)
    {
        case '/images/':
            exit();
            
        case '/health-guide/':
            header("HTTP/1.0 200 OK");
            $_SERVER['PHP_SELF']='/health_library.php';  
            $modURL=true;
            
            if(!$url_title)
            {
                $_SERVER['REQUEST_URI']='/health_library.php?article=10002479';
            }
            else
            {
                preg_match('/-(\d+)($|-)/',$url_title,$matches);
                $_REQUEST['article']=$matches[1];   
                $_GET['article']=$matches[1]; 
                //echo $matches[1];exit();
                if(preg_match('/-(\d+)-(.+)$/',$url_title,$matches))
                {
                    $_REQUEST['subcat']=$matches[2];
                    $_GET['subcat']=$matches[2];  
                    
                }
            }
            
            //$_SERVER['REQUEST_URI']='/catalog.php?page='.$pagenum;
            set_old_uri();
            include('/health_library.php');
            
            exit();
            break;
            
        case '/catalog/':
            header("HTTP/1.0 200 OK");  
            $_GET['page']=$pagenum; 
            $_REQUEST['page']=$pagenum; 
            $_SERVER['PHP_SELF']='/catalog.php';  
            $modURL=true;
            set_old_uri();
            //$_SERVER['REQUEST_URI']='/catalog.php?page='.$pagenum;
            include('/catalog.php');
            exit();
            break;
            
        case '/topic/':
            header("HTTP/1.0 200 OK");
            if(preg_match('/^([a-z0-9]{1})(-([1-9]{1}[0-9]*))?$/',$url_title,$matches))
            {
                $_GET['letter']=$matches[1];
                $_REQUEST['letter']=$matches[1];
                
                if($matches[3])
                {
                    $_GET['page']=$matches[3];
                    $_REQUEST['page']=$matches[3];    
                } 
                
            }
            else
            {
                // if $url_title is not empty, redirect
                if(strlen($url_title)>0)
                {
                    redir301('/topic/'); exit();
                }
            }
           
            $_SERVER['PHP_SELF']='/topic.php';  
            $modURL=true;
            set_old_uri();
            //$_SERVER['REQUEST_URI']='/catalog.php?page='.$pagenum;
            include('/search_topics.php');
            exit();
            break;
        case '/remedies/':
            header("HTTP/1.0 200 OK");  
            $_REQUEST['use']=$url_title; 
            $_GET['use']=$url_title; 
            $_SERVER['PHP_SELF']='/natural_uses.php';  
            $modURL=true;
            set_old_uri();
            include('/natural_uses.php');
            exit();
            break;
            
        case '/ailment/':
            header("HTTP/1.0 200 OK");  
            $_REQUEST['remedy']=$url_title; 
            $_GET['remedy']=$url_title; 
            $_SERVER['PHP_SELF']='/ailments.php';  
            $modURL=true;
            set_old_uri();
            include('/ailments.php');
            exit();
            break;
            
        case '/use/':
            header("HTTP/1.0 200 OK");  
            $_REQUEST['benefits']=$url_title; 
            $_GET['benefits']=$url_title; 
            $_SERVER['PHP_SELF']='/departments.php';  
            $modURL=true;
            set_old_uri();
            include('/departments.php');
            exit();
            break;
            
        case '/supplement/':
            header("HTTP/1.0 200 OK");  
            preg_match('/(-\d+$|-\d+-p\d+$)/i', $_SERVER['REQUEST_URI'], $matches);
            preg_match('/-\d+/i',$matches[0],$matches);
            $cPath=substr($matches[0],1);
            $_REQUEST['products_id']=(int)$cPath; 
            $_GET['products_id']=(int)$cPath; 
            $_SERVER['PHP_SELF']='/product_info2.php';  
            $modURL=true;
            set_old_uri();
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
            set_old_uri();
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
            set_old_uri();
            include('/index.php');
            exit();
            break;
        
        
        
        default:

            if($products_id)
            {
                
                header("HTTP/1.0 200 OK");
                $_SERVER['PHP_SELF']='/product_info2.php';
                $_REQUEST['products_id']=(int)$products_id;
                $_GET['products_id']=(int)$products_id;
                $modURL=true;
                set_old_uri();
                include('/product_info2.php');
                exit();
                break;

            }
        
        
    }
}

//check for hub pages before continuing.
        require_once('/hub.php'); 
    
   header("HTTP/1.0 404 Not Found"); 
  require_once('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

   

?>
      <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>
<title>Seacoast | This Page Isn't Real...</title>
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


?>
