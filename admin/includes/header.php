


    
<div clss="hide-when-print">
<?php


  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
  
  tep_session_is_registered('searchtype');
?>
<script language="javascript">
        function focus_search()
        {
         var so=document.getElementById('msearch');
         so.focus();
         so.select();
        }
</script>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="180"><a href="/"><?php echo tep_image('/images/seacoast_logo.png', 'Seacoast Vitamins Store Manager', '179','60'); ?></a></td>
    <td align="center" style="font-family:arial;font-size:10pt;"><form action="/query_redirect.php" method="get">
    <input type="text" id="msearch" name="msearch" value="<?php echo $_REQUEST['msearch']?>" onclick="this.select();"/><input type="submit" value="Search" />
    <br>
    
    <input type="radio" name="searchtype" value="customer" onclick="focus_search();"
    <?php if($searchtype=='customer'){echo 'checked';}elseif($searchtype=='')echo 'checked';?>/>Customer&nbsp;
    <input type="radio" name="searchtype" value="order" onclick="focus_search();"
    <?php if($searchtype=='order'){echo 'checked';}?>/>Order#&nbsp;
    <input type="radio" name="searchtype" value="product" onclick="focus_search();"
    <?php if($searchtype=='product'){echo 'checked';}?>/>Product
    </form>
    
    </td>
  </tr>
  <tr class="headerBar">
    <td class="headerBarContent">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>'; ?>&nbsp;&nbsp;|&nbsp;&nbsp;
      <b><a href="http://www.seacoastvitamins.com/logoff.php?do_admin=true" target="_blank" class="headerlink">New Customer Order</a></b>
  </td>
  <td align="left" class="headerBarContent">Logging user: <?php echo $_SERVER['PHP_AUTH_USER'];?></td>
    <td class="headerBarContent" align="right"><?php echo '<a href="' . tep_catalog_href_link() . '?do_admin=true" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>

</div>
