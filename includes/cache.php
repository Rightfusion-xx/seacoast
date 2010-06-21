<?

//check cache
require_once "Cache/Lite.php";

class megacache
{
   private $instance_name, $global_timeout, $cache;


   function megacache($global_timeout=600, $instance_name=$_SERVER['PHP_SELF']) //Default refresh=10 min.
   {
     $this->instance_name=$instance_name;
     $this->global_timeout=$global_timeout;

     $options = array(
        'cacheDir' => 'c:\\tmp\\',
        'lifeTime' => $this->global_timeout,
        'pearErrorMode' => CACHE_LITE_ERROR_DIE
    );

    $this->cache = new Cache_Lite($options);

   }

   /*

$cacheable=false;
$cache_safe=Array(
                  '/index-hidden.php'=>  600, //10 minutes
                  '/index.php'=>  600, //10 minutes
                  '/product_info.php'=> 86400, //1 day
                  '/topic.php'=>86400,
                  '/health_library.php'=>86400,
                  '/natural_uses.php'=>86400
                  );

     */



  function doCache($sectionname='')
  {

    if ($data = $this->cache->get($this->global_instance . $sectionname) {
        // Cache hit !
        // Content is in $data

        echo gzuncompress($data);
        return(true);
    }
    else
    {
      ob_flush(); //Flush buffer headers, etc, and begin caching the content.
      return(false);
  
    }
  }
  
  function addCache($sectionname='')
  {
      // No valid cache found (you have to make and save the page)
  
      if(is_object($this->cache))
      {
       $this->cache->save(gzcompress(ob_get_contents(),9),$this->global_instance . $sectionname);
      }
  
  }

}


?>