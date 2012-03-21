<?php

require(DIR_WS_CLASSES . 'authnet.php');
 

class payment
{
	
	private $gatewayprovider;
	
	function payment($transid)
	{
		$gatewayprovider=new authnet($transid);
		
	}
}


?>