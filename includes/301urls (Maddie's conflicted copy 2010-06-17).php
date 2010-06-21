<?php
    define('NO_HTTPS',',404.php,fake.php,product_info.php,index-hidden.php,index.php,product_reviews.php,topic.php,health_library.php,ailments.php,natural_uses.php,departments.php,404-1.php,cheapest,zyflamend,symptoms,ailments-diseases,health-guides');
            if($_REQUEST['action']!='buy_now' && strlen($_GET['fgwpm'])<1 && strlen($_GET['fgnpm'])<1 && strlen($_GET['ck'])<1){
            //Get URL and parse for bad SEO formats
            
            $url='';
            
            $script=substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],'/')+1);
            
            
            /*
            if(strpos($_SERVER['REQUEST_URI'].'?','?'))>0{
           		$script=substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'],'/')+1,strpos($_SERVER['REQUEST_URI'].'?','?')-1);
            }else{
           		$script=substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'],'/')+1,strpos($_SERVER['REQUEST_URI'].'?','?')-1);
            	
            }

			*/
            
            
           	 //$script=substr($script,0,strpos($_SERVER['REQUEST_URI'],'?')-1);

            //begin contructing url. Check for https
            //echo($_REQUEST['products_id']);
            //exit();
            
            if($_SERVER['HTTPS']!='off' && strpos(NO_HTTPS,$script)>0)
            {
                $url='http://';
                $redirbadurl=true;
            }
            elseif($_SERVER['HTTPS']!='off')
            {
                $url='https://';
            }
            else
            {
                $url='http://';
            }
            
            
            //check for www.
            if(substr($_SERVER['HTTP_HOST'],0,3)!='www')
            {
                $url=$url.'www.'.$_SERVER['HTTP_HOST'];
                $redirbadurl=true;
            }
            else
            {
                $url=$url.$_SERVER['HTTP_HOST'];
            }
            
            $url=$url . $_SERVER['REQUEST_URI'] ;
            

            //check querystring params
			if(strpos ($url, '?products_id=Array')>0)
			{
				$url=str_replace('?products_id=Array','',$url);
				$redirbadurl=true;
					
			}
			if(strpos ($url, '?health=Zyflamend')>0)
			{
				$url='/zyflamend/';
				$redirbadurl=true;
					
			}
			if(strpos ($_REQUEST['cPath'], '_')>0)
			{
				$url=str_replace('cPath='. $_REQUEST['cPath'], 'cPath='. substr($_REQUEST['cPath'], strrpos($_REQUEST['cPath'], '_')+1),$url);
				$redirbadurl=true;
					
			}
            if(strpos($url,'osCsid')>0)
            {
                $url=str_replace('&osCsid=' . $_GET['osCsid'], '',$url);
                $url=str_replace('osCsid=' . $_GET['osCsid'] . '&', '',$url);
                $url=str_replace('osCsid=' . $_GET['osCsid'] . '', '',$url);
                $redirbadurl=true;
            }
            if(strpos($url,'oscsid')>0)
            {
                $url=str_replace('&oscsid=' . $_GET['oscsid'], '',$url);
                $url=str_replace('oscsid=' . $_GET['oscsid'] . '&', '',$url);
                $url=str_replace('oscsid=' . $_GET['oscsid'] . '', '',$url);
                $redirbadurl=true;
            }
            if(strpos($url,'reviews_id')>0)
            {
                $url=str_replace('&reviews_id=', 'review',$url);
                $url=str_replace('reviews_id=', 'review',$url);
                $url=str_replace('products_id=' . $_GET['products_id'], '',$url);
                $redirbadurl=true;
            }
            
            if(strlen($_REQUEST['cPath'])>0 && strlen($_REQUEST['manufacturers_id'])>0 && $_REQUEST['action']!='buy_now')
            {
                $url=substr($url,0,strpos($url,'?')+1) . 'manufacturers_id=' . $_REQUEST['manufacturers_id'];
                $redirbadurl=true;
            }
            
            if(strlen($_REQUEST['products_id'])>0 && strlen($_REQUEST['manufacturers_id'])>0 && $_REQUEST['action']!='buy_now')
            {
                $url=substr($url,0,strpos($url,'?')+1) . 'products_id=' . $_REQUEST['products_id'];
                $redirbadurl=true;
            }
            
            if(strlen($_REQUEST['products_id'])>0 && strlen($_REQUEST['cPath'])>0 && $_REQUEST['action']!='buy_now')
            {
                $url=substr($url,0,strpos($url,'?')+1) . 'products_id=' . $_REQUEST['products_id'];
                $redirbadurl=true;
            }
            if(strlen($_REQUEST['products_id'])>0 && $_REQUEST['page']!='')
            {
                $url=substr($url,0,strpos($url,'?')+1) . 'products_id=' . $_REQUEST['products_id'];
                $redirbadurl=true;
            }
            if(strpos($_SERVER['REQUEST_URI'],'product_info.php')>0 && sizeof($_GET)>1 && $_REQUEST['action']!='add_product')
            {
                $url=substr($url,0,strpos($url,'?')+1) . 'products_id=' . $_REQUEST['products_id'];
                $redirbadurl=true;
            }
            
            if(strpos($_SERVER['REQUEST_URI'],'health_library.php/health_library.php')>0)
            {
                $url='/health_library.php?article='. $_REQUEST['article'];
                $redirbadurl=true;
            }

            //Redirect Healthnotes Pages
            if(strpos($_SERVER['REQUEST_URI'],'healthnotes.php'))
            {
                if(isset($_REQUEST['ContentID']))
                {
                    $url="health_library.php?article=".$_REQUEST['ContentID'];
                    $redirbadurl=true;
                }
                if(isset($_REQUEST['contentid']))
                {
                    $url="health_library.php?article=".$_REQUEST['contentid'];
                    $redirbadurl=true;
                }
                if(isset($_REQUEST['page']))
                {
                    $url="health_library.php?page=".$_REQUEST['contentid'];
                    $redirbadurl=true;
                }
                if(isset($_REQUEST['Page']))
                {
                    $url="health_library.php?page=".$_REQUEST['contentid'];
                    $redirbadurl=true;
                }
            }
            
            //echo($url . ' - ' . $redirbadurl);
            
            //exit();
            
            if($redirbadurl){
                redir301($url);
            }
            }
            //Release $URL
            $url='';




?>