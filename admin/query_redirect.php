<?php


  require('includes/application_top.php');

tep_session_register('searchtype');
$searchtype=$_REQUEST['searchtype'];


flush();

$params=Array();

if($_REQUEST['msearch']=='')
{
 header( 'Location: '.$_SERVER['HTTP_REFERER']) ;
 exit();
}

$params['msearch']=$_REQUEST['msearch'];
$params['searchtype']=$_REQUEST['searchtype'];


switch($_REQUEST['searchtype'])
{
  case 'product'  :
       $params['search']=$_REQUEST['msearch'];
       go_there($params,'/categories.php');
  
  
       break;
       
  case 'customer':
       $params['search']=$_REQUEST['msearch'];
       go_there($params,'/customers.php');
  

       break;
  case 'order':
       $params['oID']=$_REQUEST['msearch'];
       $params['action']='edit';
       go_there($params,'/orders.php');
  
  
       break;

  default:
  
       header( 'Location: '.$_SERVER['HTTP_REFERER']) ;
       exit();




}



function go_there($params, $url) 
{
 $pq='';
 foreach(array_keys($params) as $item)
 {
   $pq.=$item.'='.$params[$item].'&';
 }

 header( 'Location: '.$url .'?'.$pq ) ;
exit();

}



?>
