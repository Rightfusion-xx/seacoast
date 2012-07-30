<!-- configuration //-->
<tr>
    <td>
        <?php
            $heading = array();
            $contents = array();
            $heading[] = array('text'  => BOX_HEADING_CONFIGURATION, 'link'  => tep_href_link(FILENAME_CONFIGURATION, 'gID=1&selected_box=configuration'));

            if ($selected_box == 'configuration')
            {
                $cfg_groups = '';
                $configuration_groups_query = tep_db_query("select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");
                while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
                    $cfg_groups .= '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '" class="menuBoxContentLink">' . $configuration_groups['cgTitle'] . '</a><br>';
                }
                $cfg_groups .= '<a href="' . tep_href_link('top_messages.php') . '" class="menuBoxContentLink">' . BOX_TOOLS_TOP_MESSAGES . '</a><br>';
                $cfg_groups .= '<a href="' . tep_href_link('email_templates.php') . '" class="menuBoxContentLink">' . BOX_EMAIL_TEMPLATES . '</a><br>';
                $cfg_groups .= '<a href="' . tep_href_link('newsletter_subscribers.php') . '" class="menuBoxContentLink">' . BOX_NEWSLETTER_SUBSCRIBERS . '</a><br>';
                $cfg_groups .= '<a href="' . tep_href_link('newsletters.php') . '" class="menuBoxContentLink">' . BOX_NEWSLETTERS . '</a><br>';
                $contents[] = array('text'  => $cfg_groups);
            }

          $box = new box;
          echo $box->menuBox($heading, $contents);
        ?>
    </td>
</tr>
<!-- configuration_eof //-->
