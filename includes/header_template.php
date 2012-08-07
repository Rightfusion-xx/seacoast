<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
<link href="/font/fonts.css" rel="stylesheet">
<link href="/css/main.css" rel="stylesheet">
<div class="container">
   <div class="row">
      <div class="span12">
         <div class="header row">
          <ul class="span6">
              <li><a href="/brand.php" title="Our Brands" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'brand.php') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>Our Brands</a></li>
              <?php if(!$_SESSION['cm_is_member']):?>
                  <li><a href="/community/" title="Join Vitamins-Direct" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'community') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>Join Vitamins-Direct</a></li>
              <?php endif; ?>
              <li><a href="/shipping.php" title="" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'shipping.php') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>FAQ & Shipping</a></li>
              <?php if(tep_session_is_registered('customer_id')):?>
                  <li>
                      <a class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], FILENAME_ACCOUNT) != false) ? ' active': '')?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>">
                          <i class="left"></i><i class="right"></i>My Account
                      </a>
                  </li>
              <?php endif;?>
          </ul>
          <div class="span6 blue">
              <span class="country relative"><i></i>800.555.6792</span>
              <span class="world relative"><i></i>(1+) 702.508.9054</span>
          </div>
          <div class="clear"></div>
      </div>

         <div class="user-controls row">

           <div class="span7">
              <a href="<?php echo HTTP_SERVER?>" class="logo fl-left" title="Vitamins, Exclusive Discounts, Direct to You."></a>
              <div class="search-container fl-left relative">
                  <form action="/topic.php" method="get" style="display:inline;">
                    <input id="search_input" type="text" name="health" maxlength="250" onFocus="changeSubmit()" onBlur="resetSubmit()" value="<?php echo htmlentities($_REQUEST['health'])?>">
                    <input type="submit" value="" tytle="Search Seacoast" onclick="this.parentNode.submit();">
                  </form>
                  <script type="text/javascript">
                     function changeSubmit(){
                         document.getElementById("search_input").setAttribute("class", "focused");
                     }
                     function resetSubmit(){
                        document.getElementById("search_input").removeAttribute("class", "focused");
                     }
                  </script>
              </div>
           </div>

           <div class="span5">
              <a href="<?php echo (tep_session_is_registered('customer_id') ? tep_href_link(FILENAME_LOGOFF, '', 'SSL') : tep_href_link(FILENAME_ACCOUNT, '', 'SSL'))?>" class="log-button fl-right relative">
                  <i class="left"></i>
                  <i class="right"></i>
                  <label></label>
                  <?php echo (tep_session_is_registered('customer_id') ? HEADER_TITLE_LOGOFF : HEADER_TITLE_LOGIN)?>
              </a>

              <div class="basket relative fl-right">
                  <label></label>
                  <p class="relative">
                      <i class="left"></i>
                      <i class="right"></i>
                      <?php if($cart->count_contents() > 0):?>
                          <a href="/shopping_cart.php">
                              <span><?php echo $cart->count_contents()?> item<?php if($cart->count_contents()>1) echo 's';?>:</span>
                              <strong>$<?php echo number_format($cart->show_total(),2);?></strong>
                              <span>(Savings of <strong>$<?php echo number_format($cart->show_savings(),2); ?></strong>)</span>
                          </a>
                      <?php else:?>
                          <span>Shopping cart empty</span>
                      <?php endif;?>
                  </p>
              </div>
           </div>
           <div class="clear"></div>
      </div>
        <div class="content row">
            <h2 class="span12"><?php echo(!empty($_REQUEST['page_caption']) ? $_REQUEST['page_caption'] :'Vitamins - Direct To Members from Seacoast Vitamins')?></h2>
        </div>
      </div>
   </div>
    <?php echo $messageStack->output('top_messages');?>
</div>