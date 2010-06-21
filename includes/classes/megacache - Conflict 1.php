<?php

//check cache
require_once "Cache/Lite.php";

class megacache
{
   private $instance_name, $global_timeout, $cache, $buffer_loc;

   function megacache($global_timeout=600, $instance_name='') //Default refresh=10 min.
   {
     if($instance_name=='')
     {
       $instance_name=$_SERVER['REQUEST_URI'];
     }
     $this->instance_name=$instance_name;
     $this->global_timeout=$global_timeout;
     $this->buffer_loc=0;
     $this->retrieve_cache=true;

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



  function doCache($sectionname='', $flush=true, $updatetime=0)
  {

   if($this->retrieve_cache){
    if ($data = $this->cache->get($this->instance_name . $sectionname)) {

        // Cache hit !
        // Content is in $data
        
        // check script update vs cached data and last updated time
        if($updatetime<$this->cache->lastModified() && filemtime($_SERVER['SCRIPT_FILENAME'])<$this->cache->lastModified())
        {

          if($flush)
          {
            echo gzuncompress($data);
            return(true);
          }
          else
          {
            return(gzuncompress($data));
          }
        }

      }
   }
      
      // No cache found, or wasn't retrieved. Start anew.

      $this->buffer_loc=ob_get_length();
      if($flush)
      {
        //Flush buffer headers, etc, and begin caching the content.
      }
      return(false);
  
   
  }

  function addCache($sectionname='', $data='')
  {
      // No valid cache found (you have to make and save the page)
      if(is_object($this->cache))
      {
        if($data=='')
        {
         $data=substr(ob_get_contents(), $this->buffer_loc);
         //echo $data; exit;
         $this->cache->save(gzcompress($data,9),$this->instance_name . $sectionname);
        }
        else
        {
         $this->cache->save(gzcompress($data,9),$this->instance_name . $sectionname);
        }
      }
  
  }

}


?>