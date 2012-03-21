<?php
  require('includes/application_top.php');
  
  // check for moded url
  redirect_moded_url();
  
  $page=(int)$_REQUEST['page'];
  $letter=substr($_REQUEST['letter'],0,1);
  
  $links_per_page=150;
  $params=Array();
  $params['limit']=$links_per_page;  
  $params['conditions']=array('LEFT(topic,1)=? and approved=1',$letter); 

  
 
  
  if($page && $letter)
  {
      // get results for the page that a particular letter is on
      $params['offset']=$links_per_page*$page-$links_per_page+1;
      $params['order']='topic asc';
      
  }
  elseif($letter)
  {
      // get hot results for first page.
      $params['order']='hits desc';
      $title="Natural Health Topics Starting with ".strtoupper($letter);
      $description="Following, all articles titled beginning with ". strtoupper($letter)." including the most viewed " . strtoupper($letter) . " articles.";
      
      
  }
  else      
  {
      // nothing found, get top links
      
      $main_page=true;
      $params['order']='hits desc';  
      $params['conditions']=array('approved=1'); 
      $title='Alternative Medicine & Health Topics';
      $description="The most viewed natural health articles and our complete alternative medicine library from A to Z.";
  }
  
  // get links
  $links=search_topic::find('all',$params);
  
  if(count($links)<1)
  {
      // no links found, so redirect
      redir301(HTTP_SERVER.'/topic/');
  }
  //print_r( $links);
  
  if(!$main_page)
  {
          
      
      // get letter pagination now
      $params['limit']=0;
      $params['conditions']='section="search-topics-'.$letter.'"';
      $params['order']='name asc';
      $params['offset']=0;
      
      $paging=pagination::find('all',$params);
      
      
      if(!is_array($paging) || count($paging)<1 || count($links)>=$links_per_page)
      {
          // what will the new uri be?
          $setpage=count($links)>=$links_per_page ? $page+1 : 1 ;
          $setpage='/search_topics.php?letter='.$letter.'&page='.$setpage; 
          
          // does link already exist?
          $test=pagination::find_by_uri($setpage);
          
          if(count($test)<1)
          {
              // no pages found, so create starter
              $np=new pagination();
              $np->section='search-topics-'.$letter;
              $np->name='Undiscovered';
              $np->uri=$setpage;
              $np->save();
              
          }
          $paging=pagination::find('all',$params);
          
      }
  }
  
  if(!$title)
  {
      $title="Alternative Medicine Topics from " . ucwords($links[0]->topic) . " to " . ucwords($links[count($links)-1]->topic);
      $description=ucwords($links[count($links)-1]->topic) . " and " . $links[0]->topic . " research and information articles and everything in between.";
  }
  
  
  
  
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo $title; ?> </title>
<meta name="description" content="<?php echo $description; ?>"/>
<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->


<div id="content">
    <h1><?php echo $title; ?></h1>
    
    <?php if($main_page){?>
    <p></p>
    
    <?php }?>
    
    <?php
    
    // display linkes
    $link_name=substr($links[0]->topic,0,3);
    foreach($links as $item)
    {
        echo '<a href="/topic.php?health='.strtolower(urlencode($item->topic)).'">'. ucwords($item->topic) .'</a> <br/>';
    }
    $link_name.=' - '.substr($item->topic,0,3);
    ?>
    
    <p>


<?php
if(!$main_page)
{
    

    // display pages
    foreach($paging as $item)
    {
        echo '<a href="'.$item->uri.'">'.$item->name.'</a> &nbsp; ';
    }   
    
}
?>      </p>  

<hr/>
    
    
<?php // select letter

for($i=0;$i<26;$i++)
{
    $ltr=chr(ord('A')+$i);
    echo '<a href="/search_topics.php?letter='.strtolower($ltr).'">'. $ltr . '</a> | ';
}
for($i=0;$i<=9;$i++)
{
    echo '<a href="/search_topics.php?letter='.$i.'">'. $i . '</a> | ';
}

?>
        
        

</div>
        
<?php

if($page>0) //update the pagination link name
{
    $paging=pagination::find_by_uri('/search_topics.php?letter='.$letter.'&page='.$page);
    $paging->name=$link_name;
    $paging->save();    
}

?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>


</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

