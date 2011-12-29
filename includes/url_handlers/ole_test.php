<?php


  require(DIR_WS_FUNCTIONS . '/render_products.php');
  require(DIR_WS_FUNCTIONS . '/search_tools.php');  

 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $handler['title_tag'] ?></title>
<meta name="keywords" content="<?php echo $searchterm; ?>"/>
<meta name="description" content="<?php echo $handler['description_tag']?>"/>
<link rel="stylesheet" type="text/css" href="stylesheet_ole.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">



<!-- body //-->

    <div class="container_12">
        <div class="grid_9 push_3" style="margin-bottom:1em;">
            <div id="header_utilities">
                   <span id="account_utils" style="display:inline;text-align:right;float:right; margin-right:10px; margin-top:10px;white-space:nowrap;">
              <span style="text-align:right">

                <?php if (tep_session_is_registered('customer_id')) { ?>
                <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation"> 
                <?php echo HEADER_TITLE_LOGOFF; ?>
                </a> &nbsp;|&nbsp; 
                <?php } ?>
                <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation">
                <?php echo tep_session_is_registered('customer_id') ? 'My Account' : 'Log In To Your Account'; ?>
                
                </a><?php if($cart->count_contents()>0){?> &nbsp;|&nbsp; 
                <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation">
                Shopping Cart
                </a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>" class="headerNavigation"> 
                <?php echo HEADER_TITLE_CHECKOUT; ?>
                </a> &nbsp;&nbsp; <?php } ?></span>

            </span>
            <br/> <br/>
          
        </div>
        
		<h1>Olive Leaf Extract
            <br/>Supplements for Immune Health</h1>
        
        </div>
        
        <div class="grid_3 pull_9" style="margin-bottom:1em;">
        
            $HEADER$
        </div>
        
        <div class="grid_5">
            <b>Get to know</b>
        </div>
        
        <div class="grid_4">
            <b>Benefits?</b>
            <p>
                <ul>
                    <li>teasdfasdf asdf asdf we sca asdf 1</li>
                    <li>asdf wefwe sdasadc awe</li>
                    <li>asdfasdf ssds</li>
                </ul>
            </p>
        </div>
        
        
        
        
    </div>
        
        
        
        
        
        
		
		  
		
		
      
		                      
		                     
		    
		</div>
		<br style="clear:both";/><br/>
		


<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer_ole.php'); ?>
<!-- footer_eof //-->
<br>


</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

