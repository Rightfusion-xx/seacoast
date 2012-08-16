<script type="text/javascript" src="/jquery/jquery-1.3.min.js"></script>
<div class="container">
   <div class="row">
      <div class="span12">
         <div class="header row-fluid">
          <ul class="span6 left-menu">
              <li><a href="/brand.php" title="Our Brands" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'brand.php') != false) ? ' active': '')?>"><em class="left">&nbsp;</em><em class="right">&nbsp;</em>Our Brands</a></li>
              <?php if(!$_SESSION['cm_is_member']):?>
                  <li><a href="/community/" title="Join Vitamins-Direct" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'community') != false) ? ' active': '')?>"><em class="left">&nbsp;</em><em class="right">&nbsp;</em>Join Vitamins-Direct</a></li>
              <?php endif; ?>
              <li><a href="/shipping.php" title="FAQ & Shipping" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'shipping.php') != false) ? ' active': '')?>"><em class="left">&nbsp;</em><em class="right">&nbsp;</em>FAQ & Shipping</a></li>
          </ul>
          <div class="span6 blue">
              <ul class="right-menu">
                  <li class="first"><a href="/brand.php" title="Our Brands" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'brand.php') != false) ? ' active': '')?>"><em class="left">&nbsp;</em><em class="right">&nbsp;</em>Our Brands</a></li>
                  <?php if(!$_SESSION['cm_is_member']):?>
                  <li class="second"><a href="/community/" title="Join Vitamins-Direct" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'community') != false) ? ' active': '')?>"><em class="left">&nbsp;</em><em class="right">&nbsp;</em>Join Vitamins-Direct</a></li>
                  <?php endif; ?>
                  <li class="last"><a href="/shipping.php" title="FAQ & Shipping" class="relative<?php echo ((stristr($_SERVER['PHP_SELF'], 'shipping.php') != false) ? ' active': '')?>"><em class="left">&nbsp;</em><em class="right">&nbsp;</em>FAQ & Shipping</a></li>
              </ul>
              <div class="clear"></div>
              <span class="country relative"><em>&nbsp;</em>800.555.6792</span>
              <span class="world relative"><em>&nbsp;</em>(1+) 702.508.9054</span>
          </div>
          <div class="clear"></div>
      </div>

         <div class="user-controls row-fluid">
           <div class="span6 relative search big">
              <a href="<?php echo HTTP_SERVER?>" class="logo fl-left" title="Vitamins, Exclusive Discounts, Direct to You."></a>
              <div class="search-container fl-left relative">
                  <form action="/topic.php" method="get" style="display:inline;">
                    <input id="search_input" type="text" name="health" maxlength="250" onFocus="changeSubmit()" onBlur="resetSubmit()" value="<?php echo htmlentities($_REQUEST['health'])?>">
                    <input type="submit" value="" title="Search Seacoast" onclick="this.parentNode.submit();">
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
              <a title="<?php echo (tep_session_is_registered('customer_id') ? HEADER_TITLE_LOGOFF : HEADER_TITLE_LOGIN)?>" href="<?php echo (tep_session_is_registered('customer_id') ? tep_href_link(FILENAME_LOGOFF, '', 'SSL') : tep_href_link(FILENAME_ACCOUNT, '', 'SSL'))?>" class="log-button fl-right relative">
                  <em class="left">&nbsp;</em>
                  <em class="right">&nbsp;</em>
                  <label>&nbsp;</label>
                  <span><?php echo (tep_session_is_registered('customer_id') ? HEADER_TITLE_LOGOFF : HEADER_TITLE_LOGIN)?></span>
              </a>
              <div class="account-control fl-right relative<?php echo (tep_session_is_registered('customer_id') ? ' account-button-on':'')?>">
                  <div class="basket relative fl-right" onclick="return location.href = '<?php if($cart->count_contents() > 0):?> /shopping_cart.php <?php endif;?>'">
                      <div class="q-tip">&nbsp;</div>
                      <label>&nbsp;</label>
                      <p class="relative">
                          <em class="left">&nbsp;</em>
                          <em class="right">&nbsp;</em>
                          <?php if($cart->count_contents() > 0):?>
                              <a class="q-text" href="/shopping_cart.php">
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
                      <a title="Account" class="relative account-button fl-left<?php echo ((stristr($_SERVER['PHP_SELF'], FILENAME_ACCOUNT) != false) ? ' active': '')?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>">
                          <em class="left">&nbsp;</em>
                          <em class="right">&nbsp;</em>
                          <label>&nbsp;</label>
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
                           <input type="submit" value="" title="Search Seacoast" onclick="this.parentNode.submit();">
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
                   <a title="<?php echo (tep_session_is_registered('customer_id') ? HEADER_TITLE_LOGOFF : HEADER_TITLE_LOGIN)?>" href="<?php echo (tep_session_is_registered('customer_id') ? tep_href_link(FILENAME_LOGOFF, '', 'SSL') : tep_href_link(FILENAME_ACCOUNT, '', 'SSL'))?>" class="log-button fl-right relative">
                       <em class="left">&nbsp;</em>
                       <em class="right">&nbsp;</em>
                       <label>&nbsp;</label>
                       <span><?php echo (tep_session_is_registered('customer_id') ? HEADER_TITLE_LOGOFF : HEADER_TITLE_LOGIN)?></span>
                   </a>
                   <div class="account-control fl-right relative<?php echo (tep_session_is_registered('customer_id') ? ' account-button-on':'')?>">
                       <div class="basket relative fl-right" onclick="return location.href = '<?php if($cart->count_contents() > 0):?> /shopping_cart.php <?php endif;?>'">
                           <label>&nbsp;</label>
                           <div class="q-tip">&nbsp;</div>
                           <p class="relative">
                               <em class="left">&nbsp;</em>
                               <em class="right">&nbsp;</em>
                               <?php if($cart->count_contents() > 0):?>
                               <a class="items"><?php echo $cart->count_contents()?> <span>item<?php if($cart->count_contents()>1) echo 's';?>:</span></a>
                               <a class="hover-it" href="/shopping_cart.php">
                                   <strong>$<?php echo number_format($cart->show_total(),2);?></strong>
                                   <span>(Savings of <strong>$<?php echo number_format($cart->show_savings(),2); ?></strong>)</span>
                               </a>
                               <script type="text/javascript">
                                   $(document).ready(function(){
                                       var qTipText = $(".basket p a.q-text").text();
                                       $(".q-tip").text(qTipText);
                                       var textWidthWide;
                                       var textWidthShort = $(".min-width-align .q-tip").width();
                                       var containerWidth;
                                       $(".controllers.big .basket, .hover-it").hover(
                                           function(){
                                               textWidthWide = $(".controllers.big a.q-text").width();
                                               containerWidth = $(".controllers.big .basket > p").width();
                                               console.log(textWidthWide >= containerWidth);
                                               if(textWidthWide >= containerWidth || textWidthShort){
                                                   $(".q-tip").css('visibility', 'visible');
                                               }
                                           },
                                           function(){
                                               if(textWidthWide >= containerWidth || textWidthShort){
                                                   $(".q-tip").css('visibility', 'hidden');
                                               }
                                           }
                                       );
                                   });
                               </script>
                               <?php else:?>
                                   <a class="empty" href="javascript:;" title="">
                                        <span>Shopping cart empty</span>
                                   </a>
                               <?php endif;?>
                           </p>
                       </div>
                       <?php if(tep_session_is_registered('customer_id')):?>
                       <a title="Account" class="relative account-button fl-left<?php echo ((stristr($_SERVER['PHP_SELF'], FILENAME_ACCOUNT) != false) ? ' active': '')?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>">
                           <em class="left">&nbsp;</em>
                           <em class="right">&nbsp;</em>
                           <label>&nbsp;</label>
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
