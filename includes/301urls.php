<?php
    define('NO_HTTPS',',yf_city.php,yf_studio.php,similar_picks.php,404.php,fake.php,product_info.php,index-hidden.php,index.php,product_reviews.php,topic.php,health_library.php,ailments.php,natural_uses.php,departments.php,404-1.php,cheapest,zyflamend,symptoms,ailments-diseases,health-guides');
            if($_REQUEST['action']!='buy_now' && !$do_admin){
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
            if($_SERVER['HTTP_HOST']!=HTTP_COOKIE_DOMAIN)
            {
                $url=$url.HTTP_COOKIE_DOMAIN;
                $redirbadurl=true;
            }
            else
            {
                $url=$url.$_SERVER['HTTP_HOST'];
            }
            
            $url=$url . $_SERVER['REQUEST_URI'] ;

            //check querystring params
            if(strpos ($url, '/Store/')>0)
            {
                $url=str_replace('/Store/','/',$url);
                $redirbadurl=true;
                    
            }            
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
            if(strpos($url,'fgwpm')>0 || strpos($url,'fgnpm')>0)
            {
                $url=str_replace('&fgnpm=' . $_GET['fgnpm'], '',$url);
                $url=str_replace('fgnpm=' . $_GET['fgnpm'] . '&', '',$url);
                $url=str_replace('fgnpm=' . $_GET['fgnpm'] . '', '',$url);
                $url=str_replace('&fgwpm=' . $_GET['fgwpm'], '',$url);
                $url=str_replace('fgwpm=' . $_GET['fgwpm'] . '&', '',$url);
                $url=str_replace('fgwpm=' . $_GET['fgwpm'] . '', '',$url);
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
            
            try     // Run some tests on the Request string which may fail if they are an array.
            {
                
                if(@strlen($_REQUEST['products_id'])>0 && @strlen($_REQUEST['manufacturers_id'])>0 && $_REQUEST['action']!='buy_now')
                {
                    $url=substr($url,0,strpos($url,'?')+1) . 'products_id=' . $_REQUEST['products_id'];
                    $redirbadurl=true;
                }
            
            
                if(@strlen($_REQUEST['products_id'])>0 && @strlen($_REQUEST['cPath'])>0 && $_REQUEST['action']!='buy_now')
                {
                    $url=substr($url,0,strpos($url,'?')+1) . 'products_id=' . $_REQUEST['products_id'];
                    $redirbadurl=true;
                }
                if(@strlen($_REQUEST['products_id'])>0 && $_REQUEST['page']!='')
                {
                    $url=substr($url,0,strpos($url,'?')+1) . 'products_id=' . $_REQUEST['products_id'];
                    $redirbadurl=true;
                }
            }
            catch(Exception $e)
            {
                //Do nothing, and keep processing.
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
            
            if($do_admin && $_REQUEST['HTTPS']=='off')
            {
                redir301(HTTPS_SERVER,$_SERVER['REQUEST_URI']);
            }
            //Release $URL
            $url='';




?>