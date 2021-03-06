<?php if(tep_count_shipping_modules() > 0):?>
            <tr>
                <td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="main">
                                <h2><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h2>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                        <tr class="infoBoxContents">
                            <td width="100%" >
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                    <?php if(sizeof($quotes) > 1 && sizeof($quotes[0]) > 1):?>
                                    <tr>
                                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                        <td class="main" width="50%" valign="top">Please select your preferred shipping method.</td>
                                        <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>'?></td>
                                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                    </tr>
                                    <?php elseif($free_shipping == false):?>
                                    <tr>
                                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                        <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
                                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                    </tr>
                                    <?php endif;?>
                                    <?php
                                        $radio_buttons = 0;
                                        for($i=0, $n=sizeof($quotes); $i<$n; $i++):?>
                                    <tr>
                                        <td>
                                            <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                        </td>
                                        <td colspan="2">
                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                    <td class="main" colspan="3">
                                                        <b style="margin-top:10px;display: inline-block;">
                                                            <?php echo $quotes[$i]['module'];?>
                                                        </b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?>
                                                    </td>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                </tr>
                                                <?php if(isset($quotes[$i]['error'])):?>
                                                    <tr>
                                                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                        <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                                                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                    </tr>
                                                <?php else:?>
                                                    <?php for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++):?>

                                                        <?php
                                                            // set the radio button to be checked if it is the method chosen
                                                            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);
                                                            if(($checked == true) || ($n == 1 && $n2 == 1))
                                                            {
                                                                $b="'".$quotes[$i]['methods'][$j]['title']."'";
                                                                $c="'".tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))."'";
                                                                echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect2(this, ' . $radio_buttons . ');zhipper='.$b.';zprice='.$c.';">' . "\n";
                                                            }
                                                            else
                                                            {
                                                                $b="'".$quotes[$i]['methods'][$j]['title']."'";
                                                                $c="'".tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))."'";
                                                                $zz=' zhipper='.$b.';zprice='.$c.';" ';
                                                                echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect2(this, ' . $radio_buttons . ');zhipper='.$b.';zprice='.$c.';">' . "\n";
                                                            }
                                                        ?>
                                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                            <td class="main" width="75%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
                                                            <?php if(($n > 1) || ($n2 > 1) ):?>
                                                                <td class="main"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></td>
                                                                <td class="main" align="right">
                                                                    <?php
                                                                        $b="'".$quotes[$i]['methods'][$j]['title']."'";
                                                                        $c="'".tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))."'";
                                                                        $zz=' onchange="zhipper='.$b.';zprice='.$c.';" ';
                                                                        echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked,$zz);
                                                                    ?>
                                                                </td>
                                                            <?php else:?>
                                                                <td class="main" align="right" colspan="2">
                                                                    <?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?>
                                                                </td>
                                                            <?php endif; ?>
                                                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                        </tr>
                                                        <?php $radio_buttons++;?>
                                                    <?php endfor;?>
                                                <?php endif; ?>
                                            </table>
                                        </td>
                                        <td><?php //echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                    </tr>
                                <?php endfor;?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
<?php endif;?>

