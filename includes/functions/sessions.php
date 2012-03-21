<?php


  if (STORE_SESSIONS == 'mysql') {
    //if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 180*24*60*60; //expire in 180 days
    //}

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
      $value = tep_db_fetch_array($value_query);

      if (isset($value['value'])) {
        return $value['value'];
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE, $cart;

      $expiry = time() + $SESS_LIFE;
      $value = $val;
 //only save session if something is in cart.
    if (is_object($cart) && $cart->been_used)
    {
      
        $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
        $check = tep_db_fetch_array($check_query);

        if ($check['total'] > 0) {
          return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
        } else {
          return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
        }
      
      }
    }

    function _sess_destroy($key) {
      return true; //tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime) {
      //tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function tep_session_start() {
    $session_started= session_start();
    if($session_started)
    {
        register_session_globals();
        return true;                
    }
    else
    {
        return false;
    }
  }

  function tep_session_register($variable) {
    global $session_started;

    if ($session_started == true) {
      $_SESSION[$variable]=&$GLOBALS[$variable];
      return true;
    } else {
      return false;
    }
  }

  function tep_session_is_registered($variable) {

    if(isset($_SESSION[$variable]))
    {
      //Register the variable globally
      $GLOBALS[$variable]=&$_SESSION[$variable];
      return true;
    }
    else
    {
      return false;
    }
  }

  function tep_session_unregister($variable) {
    unset($_SESSION[$variable]);
    unset($GLOBALS[$variable]);
    return(true);
  }

  function tep_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function tep_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function tep_session_close() {
      return session_write_close();
 }

  function tep_session_destroy() {
    return session_destroy();
  }

  function tep_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

  function tep_session_recreate() {
 
       $session_backup = $_SESSION;

      unset($_COOKIE[tep_session_name()]);

      tep_session_destroy();

      if (STORE_SESSIONS == 'mysql') {
        session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
      }

      tep_session_start();

      $_SESSION = $session_backup;
      register_session_globals();
      unset($session_backup);
  }
  
  
  function refresh_user_info()
  {
  		global $customer_id;
  		
  		tep_session_unregister('user_info_updated');
  	  	tep_session_unregister('cm_expiration');
  	  	tep_session_unregister('cm_renew');
  	  	tep_session_unregister('cm_savings');
  	  	tep_session_unregister('cm_member_since');
        tep_session_unregister('billing');
        tep_session_unregister('payment');
        tep_session_unregister('comments');
        tep_session_unregister('shipping');
  
        $_SESSION['cm_is_member']=strtotime($tmp['cm_expiration'])>=time() ? 1 : 0;
        tep_session_register('cm_is_member');

  	  	if(tep_session_is_registered('customer_id'))
  	  	{
  	  		$tmp=tep_db_fetch_array(tep_db_query('select *, (select date_purchased from orders o join orders_products op on op.orders_id=o.orders_id where o.customers_id='.(int)$customer_id.'
  	  			and (op.products_id='.CM_FTPID.' or op.products_id='.CM_PID.') order by date_purchased asc limit 0,1) as member_date, (select
  	  			sum(value) from orders_total ot join orders o on o.orders_id=ot.orders_id where class="ot_savings" and o.customers_id='.(int)$customer_id.') as
  	  			total_savings
  	  			 from customers_info where customers_info_id='.(int)$customer_id));
  	  		$_SESSION['user_info_updated']="true";
  	  		$_SESSION['cm_expiration']=$tmp['cm_expiration'];
  	  		$_SESSION['cm_renew']=$tmp['cm_renew'];
  	  		$_SESSION['cm_savings']=$tmp['total_savings'];
  	  		$_SESSION['cm_member_since']=date("M-d, Y",strtotime($tmp['member_date']));
  
	  		$_SESSION['cm_is_member']=strtotime($tmp['cm_expiration'])>=time() ? 1 : 0;
            
            register_session_globals(); 	  		
  	  	
  	  	}
  	
  }
  
  function register_session_globals()
  {
      // This is a workaround function for register_globals being turned off, and deprecated in PHP 5.3 and greater
      
      foreach(array_keys($_SESSION) as $item)
      {
          tep_session_is_registered($item);
      }
      
  }
  
?>
