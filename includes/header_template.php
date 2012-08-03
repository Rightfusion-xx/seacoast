<link href="/font/fonts.css" rel="stylesheet">
<link href="/css/main.css" rel="stylesheet">
<div class="container">
   <div class="row">
      <div class="span12">
         <div class="header row-fluid">
          <ul class="span6 left-menu">
              <li><a href="/brand.php" title="Our Brands" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'brand.php') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>Our Brands</a></li>
              <?php if(!$_SESSION['cm_is_member']):?>
                  <li><a href="/community/" title="Join Vitamins-Direct" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'community') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>Join Vitamins-Direct</a></li>
              <?php endif; ?>
              <li><a href="/shipping.php" title="" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'shipping.php') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>FAQ & Shipping</a></li>
          </ul>
          <div class="span6 blue">
              <ul class="right-menu">
                  <li class="first"><a href="/brand.php" title="Our Brands" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'brand.php') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>Our Brands</a></li>
                  <?php if(!$_SESSION['cm_is_member']):?>
                  <li class="second"><a href="/community/" title="Join Vitamins-Direct" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'community') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>Join Vitamins-Direct</a></li>
                  <?php endif; ?>
                  <li class="last"><a href="/shipping.php" title="" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'shipping.php') != false) ? ' active': '')?>"><i class="left"></i><i class="right"></i>FAQ & Shipping</a></li>
              </ul>
              <div class="clear"></div>
              <span class="country relative"><i></i>800.555.6792</span>
              <span class="world relative"><i></i>(1+) 702.508.9054</span>
          </div>
          <div class="clear"></div>
      </div>

         <div class="user-controls row-fluid">

           <div class="span6 relative search big">
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

           <div class="span6 controllers big">
              <a href="<?php echo (tep_session_is_registered('customer_id') ? tep_href_link(FILENAME_LOGOFF, '', 'SSL') : tep_href_link(FILENAME_ACCOUNT, '', 'SSL'))?>" class="log-button fl-right relative">
                  <i class="left"></i>
                  <i class="right"></i>
                  <label></label>
                  <span><?php echo (tep_session_is_registered('customer_id') ? HEADER_TITLE_LOGOFF : HEADER_TITLE_LOGIN)?></span>
              </a>
              <div class="account-control fl-right relative<?php echo (tep_session_is_registered('customer_id') ? ' account-button-on':'')?>">
                  <div class="basket relative fl-right">
                      <label></label>
                      <p class="relative">
                          <i class="left"></i>
                          <i class="right"></i>
                          <?php if($cart->count_contents() > 0):?>
                              <a href="/shopping_cart.php">
                                  <span class="items"><?php echo $cart->count_contents()?> <span>item<?php if($cart->count_contents()>1) echo 's';?>:</span></span>
                                  <strong>$<?php echo number_format($cart->show_total(),2);?></strong>
                                  <span>(Savings of <strong>$<?php echo number_format($cart->show_savings(),2); ?></strong>)</span>
                              </a>
                          <?php else:?>
                            <a href="javascript:;">
                                <span>Shopping cart empty</span>
                            </a>
                          <?php endif;?>
                      </p>
                  </div>
                  <?php if(tep_session_is_registered('customer_id')):?>
                      <a class="relative account-button fl-left<?php echo ((stristr($_SERVER['PHP_SELF'], FILENAME_ACCOUNT) != false) ? ' active': '')?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>">
                          <i class="left"></i>
                          <i class="right"></i>
                          <label></label>
                      </a>
                  <?php endif;?>
              </div>
           </div>

           <div class="span12 search controllers relative">
               <a href="<?php echo HTTP_SERVER?>" class="logo fl-left" title="Vitamins, Exclusive Discounts, Direct to You."></a>
               <div class="min-width-align relative<?php echo (tep_session_is_registered('customer_id') ? ' narrow-width':'')?>"">
                   <div class="search-container fl-left relative<?php echo (tep_session_is_registered('customer_id') ? ' narrow':'')?>">
                       <form action="/topic.php" method="get" style="display:inline;" id="width_change">
                           <input id="search_input_1" type="text" name="health" maxlength="250" onFocus="changeSubmitSmall()" onBlur="resetSubmitSmall()" value="<?php echo htmlentities($_REQUEST['health'])?>">
                           <input type="submit" value="" tytle="Search Seacoast" onclick="this.parentNode.submit();">
                           <div id="expansion_button" onclick="resizeInput()"></div>
                       </form>
                       <script type="text/javascript">
                           function changeSubmitSmall(){
                               document.getElementById("search_input_1").setAttribute("class", "focused");
                           }
                           function resetSubmitSmall(){
                               document.getElementById("search_input_1").removeAttribute("class", "focused");
                               document.getElementById("width_change").removeAttribute("class", "width-223");
                               document.getElementById("expansion_button").setAttribute("style", "display: block;");
                           }
                           function resizeInput(){
                               document.getElementById("width_change").setAttribute("class", "width-223");
                               document.getElementById("expansion_button").setAttribute("style", "display: none;");
                           }
                       </script>
                   </div>
                   <a href="<?php echo (tep_session_is_registered('customer_id') ? tep_href_link(FILENAME_LOGOFF, '', 'SSL') : tep_href_link(FILENAME_ACCOUNT, '', 'SSL'))?>" class="log-button fl-right relative">
                       <i class="left"></i>
                       <i class="right"></i>
                       <label></label>
                       <span><?php echo (tep_session_is_registered('customer_id') ? HEADER_TITLE_LOGOFF : HEADER_TITLE_LOGIN)?></span>
                   </a>
                   <div class="account-control fl-right relative<?php echo (tep_session_is_registered('customer_id') ? ' account-button-on':'')?>">
                       <div class="basket relative fl-right">
                           <label></label>
                           <p class="relative">
                               <i class="left"></i>
                               <i class="right"></i>
                               <?php if($cart->count_contents() > 0):?>
                               <a href="/shopping_cart.php">
                                   <span class="items"><?php echo $cart->count_contents()?> <span>item<?php if($cart->count_contents()>1) echo 's';?>:</span></span>
                                   <strong>$<?php echo number_format($cart->show_total(),2);?></strong>
                                   <span>(Savings of <strong>$<?php echo number_format($cart->show_savings(),2); ?></strong>)</span>
                               </a>
                               <?php else:?>
                                   <a href="javascript:;" title="">
                                        <span>Shopping cart empty</span>
                                   </a>
                               <?php endif;?>
                           </p>
                       </div>
                       <?php if(tep_session_is_registered('customer_id')):?>
                       <a class="relative account-button fl-left<?php echo ((stristr($_SERVER['PHP_SELF'], FILENAME_ACCOUNT) != false) ? ' active': '')?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>">
                           <i class="left"></i>
                           <i class="right"></i>
                           <label></label>
                       </a>
                       <?php endif;?>
                   </div>
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

<?php /*
<div id="header">

    <div class="container">
        <div  class="row">
            <div class="span4">
                <?php
                echo '<a href="'.HTTP_SERVER.'/"><img src="/images/seacoast_logo.png" border="0"
              alt="" title="Vitamins, Exclusive Discounts, Direct to You. " width="179" height="60">
              </a>';
                ?>
            </div>
            <div class="span4">
                <div style="text-align:center;">
                    <form name="quick_find" action="/topic.php" method="get" style="display:inline;">
                        <input type="text" name="health" size="10" maxlength="250" style="width: 200px" value="<?php echo $_REQUEST['health']?>"/>&nbsp;
                        <input class="primary" type="submit" value="Search" alt="Search Seacoast" title=" Search Seacoast "/>
                    </form>
                </div>
                <div style="text-align:center;"><a href="/brand.php">Our Brands</a>
                    <?php if(!$_SESSION['cm_is_member']) {?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/community/">Join Vitamins-Direct</a>   <?php } ?>
                </div>
            </div>
            <div class="span4" style="text-align:right">

                <?php if (tep_session_is_registered('customer_id')) { ?>
                <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation">
                    <?php echo HEADER_TITLE_LOGOFF; ?>
                </a> &nbsp;|&nbsp;
                <?php } ?>
                <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation">
                    <?php echo tep_session_is_registered('customer_id') ? 'My Account' : 'Log In To Your Account'; ?>

                </a>
                <?php if($cart->count_contents()>0){?> &nbsp;|&nbsp;
                <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation" rel="nofollow">
                    Shopping Cart
                </a>
                <?php } ?>


                <p style="font-size:10pt;color:#CC6600;font-weight:bold;text-align:right;">U.S. 800.555.6792<br/>
                    Int'l (1+) 702.508.9054</p>

            </div>




        </div>
    </div>






</div>

<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <ul class="nav">

                <li class="active"><a href="/shipping.php">FAQ & Shipping</a></li>
                <?php   if ($cart->count_contents() > 0 && $_SERVER['HTTPS']=='off' && !$hide_cart) {
                ?>
                <li >       <a href="/shopping_cart.php" style="color:#ffffff;"><i class="icon-shopping-cart icon-white">&nbsp;</i>&nbsp;&nbsp;View Shopping Cart.
                    <?php echo $cart->count_contents()?> item<?php if($cart->count_contents()>1) echo 's';?>: $<?php echo number_format($cart->show_total(),2);?>
                    &nbsp;&nbsp;
                    (<span>Savings of $<?php echo number_format($cart->show_savings(),2); ?></span>)    </a>
                </li>   <?php
            }else{
                ?>
                <li>
                    <a href="/pro-omega">What supplements are popular today? Nordic Naturals fish oil.</a>
                </li>
                <?php
            }


                ?>
            </ul>
        </div>
    </div>
</div>
<?php if($_SESSION['cm_is_member']){ ?><span style="color:#CC6600">
                 <div class="well" style="text-align:center;">
                     Member since <?php echo $_SESSION['cm_member_since']; ?>.
                     Total Savings of $<?php echo number_format($_SESSION['cm_savings'],2); ?></span>
</div>
<?php } ?>



<div id="header" >

    <div class="container">
        <div  class="row">
            <div class="span4">
                <?php
                echo '<a href="'.HTTP_SERVER.'/"><img src="/images/seacoast_logo.png" border="0"
              alt="" title="Vitamins, Exclusive Discounts, Direct to You. " width="179" height="60">
              </a>';
                ?>
            </div>
            <div class="span4">
                <div style="text-align:center;">
                    <form name="quick_find" action="/topic.php" method="get" style="display:inline;">
                        <input type="text" name="health" size="10" maxlength="250" style="width: 200px" value="<?php echo $_REQUEST['health']?>"/>&nbsp;
                        <input class="primary" type="submit" value="Search" alt="Search Seacoast" title=" Search Seacoast "/>
                    </form>
                </div>
                <div style="text-align:center;">
                    <a href="/brands">Our Brands</a>
                    <?php if(!$_SESSION['cm_is_member']) {?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/community/">Join Vitamins-Direct</a>   <?php } ?>
                </div>
            </div>
            <div class="span4" style="text-align:right">

                <?php if (tep_session_is_registered('customer_id')) { ?>
                <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation">
                    <?php echo HEADER_TITLE_LOGOFF; ?>
                </a> &nbsp;|&nbsp;
                <?php } ?>
                <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation">
                    <?php echo tep_session_is_registered('customer_id') ? 'My Account' : 'Log In To Your Account'; ?>

                </a>
                <?php if($cart->count_contents()>0){?> &nbsp;|&nbsp;
                <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation" rel="nofollow">
                    Shopping Cart
                </a>
                <?php } ?>


                <p style="font-size:10pt;color:#CC6600;font-weight:bold;text-align:right;">U.S. 800.555.6792<br/>
                    Int'l (1+) 702.508.9054</p>

            </div>




        </div>
    </div>
    <hr style="width:100%; height:1px; background-color:#333367;" />
    <?php
    if($do_admin && $authenticated)
    {
        ?>
        <div id="admin" class="green box">
            User: <?php echo substr($_SERVER['PHP_AUTH_USER'],0,strpos($_SERVER['PHP_AUTH_USER'],'@'));?>
        </div>

        <?php
    }
    ?>


</div>

<?php if ($cart->count_contents() > 0 && $_SERVER['HTTPS']=='off' && !$hide_cart): ?>

<div style="padding-top:17px; margin-bottom:40px;" class="container alert">
    <div class="row show-grid">
        <div style="padding-top:4px;" class="span2">
            <h5 style="display:inline;padding-left:5px;">Shopping Cart</h5>
        </div>
        <div class="span2">
            <input class="btn" type="button" value="View All Items" onClick="document.location='/shopping_cart.php';">
        </div>
        <div class="span5" style="padding-top:4px;">
            <h5 style="display:inline;padding-left:5px;">
                <?php echo $cart->count_contents()?> item<?php if($cart->count_contents()>1) echo 's';?>: $<?php echo number_format($cart->show_total(),2);?>
                &nbsp;&nbsp;
                (<span>Savings of $<?php echo number_format($cart->show_savings(),2); ?></span>)
            </h5>
        </div>
        <div class="span2" style="white-space: nowrap;">
            <input class="btn" type="button" value="Checkout Now" onClick="document.location='/checkout_shipping.php';">
            <input class="btn" type="button" value="Publish Shopping Cart" onClick="window.open('/publish_cart.php');">
        </div>

    </div>
</div>
<?php endif; ?>
*/