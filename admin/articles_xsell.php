<?php
/*
  $Id: articles_xsell.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  cross.sale.php created By Isaac Mualem im@imwebdesigning.com

  Modified by Andrew Edmond (osc@aravia.com)
  Sept 16th, 2002

  Further Modified by Rob Anderson 12 Dec 03

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><? echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? include(DIR_WS_INCLUDES . 'header.php');  ?>
<!-- header_eof //-->


<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
     </tr>
     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<!-- body_text //-->
    <td width="100%" valign="top"> 
      <!-- Start of cross sale //-->

      <table width="100%" border="0" cellpadding="0"  cellspacing="0">
        <tr><td align=left>
        <?php
	/* general_db_conct($query) function */
	/* calling the function:  list ($test_a, $test_b) = general_db_conct($query); */
	function general_db_conct($query_1)
	{
	  $result_1 = tep_db_query($query_1);
  	  $num_of_rows = mysql_num_rows($result_1);
	  for ($i=0;$i<$num_of_rows;$i++)
	  {
	    $fields = mysql_fetch_row($result_1);
	    $a_to_pass[$i]= $fields[$y=0];
	    $b_to_pass[$i]= $fields[++$y];
    	    $c_to_pass[$i]= $fields[++$y];
	    $d_to_pass[$i]= $fields[++$y];
	    $e_to_pass[$i]= $fields[++$y];
	    $f_to_pass[$i]= $fields[++$y];
	    $g_to_pass[$i]= $fields[++$y];
	    $h_to_pass[$i]= $fields[++$y];
	    $i_to_pass[$i]= $fields[++$y];
	    $j_to_pass[$i]= $fields[++$y];
	    $k_to_pass[$i]= $fields[++$y];
	    $l_to_pass[$i]= $fields[++$y];
	    $m_to_pass[$i]= $fields[++$y];
	    $n_to_pass[$i]= $fields[++$y];
	    $o_to_pass[$i]= $fields[++$y];
	  }
	return array($a_to_pass,$b_to_pass,$c_to_pass,$d_to_pass,$e_to_pass,$f_to_pass,$g_to_pass,$h_to_pass,$i_to_pass,$j_to_pass,$k_to_pass,$l_to_pass,$m_to_pass,$n_to_pass,$o_to_pass);
	}//end of function	

        // first major piece of the program
        // we have no instructions, so just dump a full list of products and their status for cross selling	

	if (!$add_related_article_ID )
	{
        $query = "select a.articles_id, ad.articles_name, ad.articles_description, ad.articles_url from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where ad.articles_id = a.articles_id and ad.language_id = '" . (int)$languages_id . "' order by ad.articles_name";
	list ($articles_id, $articles_name, $articles_description, $articles_url) = general_db_conct($query);
	?>
				
            <table border="0" cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
              <tr class="dataTableHeadingRow"> 
                <td class="dataTableHeadingContent" align="center" nowrap>ID</td>
                <td class="dataTableHeadingContent"><?php echo HEADING_ARTICLE_NAME; ?></td>
                <td class="dataTableHeadingContent" nowrap><?php echo HEADING_CROSS_ASSOCIATION; ?></td>
                <td class="dataTableHeadingContent" colspan="3" align="center" nowrap><?php echo HEADING_CROSS_SELL_ACTIONS; ?></td>
              </tr>
               <?php 
			   $num_of_articles = sizeof($articles_id);
				for ($i=0; $i < $num_of_articles; $i++)
					{
					/* now we will query the DB for existing related items */
                    $query = "select pd.products_name, ax.xsell_id from " . TABLE_ARTICLES_XSELL . " ax, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = ax.xsell_id and ax.articles_id ='".$articles_id[$i]."' and pd.language_id = '" . (int)$languages_id . "' order by ax.sort_order";
					list ($Related_items, $xsell_ids) = general_db_conct($query);

					echo "<tr bgcolor='#FFFFFF'>";
					echo "<td class=\"dataTableContent\" valign=\"top\">&nbsp;".$articles_id[$i]."&nbsp;</td>\n";
					echo "<td class=\"dataTableContent\" valign=\"top\">&nbsp;".$articles_name[$i]."&nbsp;</td>\n";
					if ($Related_items)
					{
  					  echo "<td  class=\"dataTableContent\"><ol>";
					  foreach ($Related_items as $display)
 						echo '<li>'. $display .'&nbsp;';
						echo"</ol></td>\n";
						}
					else
						echo "<td class=\"dataTableContent\">--</td>\n";
					echo '<td class="dataTableContent"  valign="top">&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, 'add_related_article_ID=' . $articles_id[$i], 'NONSSL') . '">Add/Remove</a></td>';
									
					if (count($Related_items)>1)
					{
					  echo '<td class="dataTableContent" valign="top">&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, 'sort=1&add_related_article_ID=' . $articles_id[$i], 'NONSSL') . '">Sort</a>&nbsp;</td>';
					} else {
						echo "<td class=\"dataTableContent\" valign=top align=center>--</td>";
						}
					echo "</tr>\n";
					unset($Related_items);
					}
				?>

            </table>
            <?
			}	// the end of -> if (!$add_related_article_ID)

	if ($_POST && !$sort)
	{
  	  if ($_POST[run_update]==true)
	  {
	    $query ="DELETE FROM " . TABLE_ARTICLES_XSELL . " WHERE articles_id = '".$_POST[add_related_article_ID]."'";
	    if (!tep_db_query($query))
		exit(TEXT_NO_DELETE);
	  }
	  if ($_POST[xsell_id])
		foreach ($_POST[xsell_id] as $temp)
  	  {
	    $query = "INSERT INTO " . TABLE_ARTICLES_XSELL . " VALUES ('',$_POST[add_related_article_ID],$temp,1)";
	    if (!tep_db_query($query))
		exit(TEXT_NO_INSERT);
	  } ?>
	            <tr>
                  <td class="main"><?php echo TEXT_DATABASE_UPDATED; ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo sprintf(TEXT_LINK_SORT_PRODUCTS, tep_href_link(FILENAME_ARTICLES_XSELL, '&sort=1&add_related_article_ID=' . $add_related_article_ID, 'NONSSL')); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo sprintf(TEXT_LINK_MAIN_PAGE, tep_href_link(FILENAME_ARTICLES_XSELL, '', 'NONSSL')); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
    <?php

//	  if ($_POST[xsell_id])
	//	echo '<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, 'sort=1&add_related_article_ID=' . $_POST[add_related_article_ID], 'NONSSL') . '">Click here to sort (top to bottom) the added cross sale</a>' . "\n";
	}
		
        if ($add_related_article_ID && ! $_POST && !$sort)
	{	?>
 	  <table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
               <form action="<?php tep_href_link(FILENAME_ARTICLES_XSELL, '', 'NONSSL'); ?>" method="post">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent">&nbsp;</td>
                  <td class="dataTableHeadingContent" nowrap>ID</td>
                  <td class="dataTableHeadingContent"><?php echo HEADING_PRODUCT_NAME; ?></td>
                </tr>
	
                <? 

        $query = "select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name";

			list ($products_id, $products_name, $products_description, $products_url  ) = general_db_conct($query);
			 $num_of_products = sizeof($products_id);
				$query = "select * from " . TABLE_ARTICLES_XSELL . " where articles_id = '".$add_related_article_ID."'";
						list ($ID_PR, $products_id_pr, $xsell_id_pr) = general_db_conct($query);
					for ($i=0; $i < $num_of_products; $i++)
					{
					?><tr bgcolor="#FFFFFF">
						<td class="dataTableContent">
					
					<input <?php /* this is to see it it is in the DB */
						$run_update=false; // set to false to insert new entry in the DB
						if ($xsell_id_pr) foreach ($xsell_id_pr as $compare_checked)if ($products_id[$i]===$compare_checked) {echo "checked"; $run_update=true;} ?> size="20"  size="20"  name="xsell_id[]" type="checkbox" value="<?php echo $products_id[$i]; ?>"></td>
					
					<? echo "<td  class=\"dataTableContent\" align=center>".$products_id[$i]."</td>\n"
						."<td class=\"dataTableContent\">".$products_name[$i]."</td>\n";
					}?>
					<tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td bgcolor="#CCCCCC">
                        <input type="hidden" name="run_update" value="<?php if ($run_update==true) echo "true"; else echo "false" ?>">
				        <input type="hidden" name="add_related_article_ID" value="<?php echo $add_related_article_ID; ?>">
                        <?PHP echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
                      </td>
                </tr>
              </form>
            </table>
		<? }

        // sort routines
	if ($sort==1)
	{
	//	first lets take care of the DB update.
  	  $run_once=0;
	  if ($_POST)
		foreach ($_POST as $key_a => $value_a)
	  {
		tep_db_connect();
		$query = "UPDATE " . TABLE_ARTICLES_XSELL . " SET sort_order = '".$value_a."' WHERE xsell_id= '$key_a' ";
		if ($value_a != 'Update')
			if (!tep_db_query($query))
				exit(TEXT_NO_UPDATE);
			else
				if ($run_once==0)
				{ ?>
                <tr>
                  <td class="main"><?php echo TEXT_DATABASE_UPDATED; ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo sprintf(TEXT_LINK_MAIN_PAGE, tep_href_link(FILENAME_ARTICLES_XSELL, '', 'NONSSL')); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
			<?php
            $run_once++;
            }

	}// end of foreach.
	?>
	<form method="post" action="<?php tep_href_link(FILENAME_ARTICLES_XSELL, 'sort=1&add_related_article_ID=' . $add_related_article_ID, 'NONSSL'); ?>">
              <table cellpadding="3" cellspacing="1" bgcolor="#CCCCCC" border="0">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent">ID</td>
                  <td class="dataTableHeadingContent"><?php echo HEADING_PRODUCT_NAME; ?></td>
                  <td class="dataTableHeadingContent"><?php echo HEADING_PRODUCT_ORDER; ?></td>
                </tr>
				<? 
				$query = "select * from " . TABLE_ARTICLES_XSELL . " where articles_id = '".$add_related_article_ID."'";
				list ($ID_PR, $products_id_pr, $xsell_id_pr, $order_PR) = general_db_conct($query);
				$ordering_size =sizeof($ID_PR);
				for ($i=0;$i<$ordering_size;$i++)
					{

        $query = "select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = ".$xsell_id_pr[$i]."";

					list ($products_id, $products_name, $products_description, $products_url) = general_db_conct($query);

					?>
					<tr class="dataTableContentRow" bgcolor="#FFFFFF">
					  <td class="dataTableContent"><?php echo $products_id[0]; ?></td>
					  <td class="dataTableContent"><?php echo $products_name[0]; ?></td>
					  <td class="dataTableContent" align="center"><select name="<?php echo $products_id[0]; ?>">
						  <? for ($y=1;$y<=$ordering_size;$y++)
						  		{
								echo "<option value=\"$y\"";
									if (!(strcmp($y, "$order_PR[$i]"))) {echo "SELECTED";}
									echo ">$y</option>";
								}
								?>
						</select></td>
					</tr>
					<? } // the end of foreach
                    ?>
                <tr>
                  <td>&nbsp;</td>
                  <td bgcolor="#CCCCCC"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </form>
			
			<?php }?>
		
		
          </td>
        </tr>	
	</table>
	<!-- End of cross sale //-->
	</td>
</tr></table>
<!-- body_text_eof //-->
<!-- footer //-->
<? include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? include(DIR_WS_INCLUDES . 'application_bottom.php');?>