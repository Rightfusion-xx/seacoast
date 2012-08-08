<?php
/*
  $Id: boxes.php,v 1.33 2003/06/09 22:22:50 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';
	var $table_string = '';

// class constructor
    function tableBox($contents, $direct_output = false, $skin_this = false, $header_text = '', $header_link = false, $slice_set = '')
    {
        $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
        if(tep_not_null($this->table_parameters))
            $tableBox_string .= ' ' . $this->table_parameters;
        $tableBox_string .= '>' . "\n";
        for($i = 0, $n = sizeof($contents); $i < $n; $i++)
        {
            if(isset($contents[$i]['form']) && tep_not_null($contents[$i]['form']))
                $tableBox_string .= $contents[$i]['form'] . "\n";
            $tableBox_string .= '  <tr';
            if(tep_not_null($this->table_row_parameters))
                $tableBox_string .= ' ' . $this->table_row_parameters;
            if(isset($contents[$i]['params']) && tep_not_null($contents[$i]['params']))
                $tableBox_string .= ' ' . $contents[$i]['params'];
            $tableBox_string .= '>' . "\n";

            if(isset($contents[$i][0]) && is_array($contents[$i][0]))
            {
                for($x = 0, $n2 = sizeof($contents[$i]); $x < $n2; $x++)
                {
                    if(isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text']))
                    {
                        $tableBox_string .= '    <td';
                        if(isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align']))
                            $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
                        if(isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params']))
                        {
                            $tableBox_string .= ' ' . $contents[$i][$x]['params'];
                        }
                        elseif(tep_not_null($this->table_data_parameters))
                        {
                            $tableBox_string .= ' ' . $this->table_data_parameters;
                        }
                        $tableBox_string .= '>';
                        if(isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form']))
                            $tableBox_string .= $contents[$i][$x]['form'];
                        $tableBox_string .= $contents[$i][$x]['text'];
                        if(isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form']))
                            $tableBox_string .= '</form>';
                        $tableBox_string .= '</td>' . "\n";
                    }
                }
            }
            else
            {
                $tableBox_string .= '    <td';
                if(isset($contents[$i]['align']) && tep_not_null($contents[$i]['align']))
                    $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
                if(isset($contents[$i]['params']) && tep_not_null($contents[$i]['params']))
                {
                    $tableBox_string .= ' ' . $contents[$i]['params'];
                }
                elseif(tep_not_null($this->table_data_parameters))
                {
                    $tableBox_string .= ' ' . $this->table_data_parameters;
                }
                $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
            }

            $tableBox_string .= '  </tr>' . "\n";
            if(isset($contents[$i]['form']) && tep_not_null($contents[$i]['form']))
                $tableBox_string .= '</form>' . "\n";
        }

        $tableBox_string .= '</table>' . "\n";

        if($direct_output == true)
            echo $tableBox_string;
        $this->table_string = $tableBox_string;
        return $tableBox_string;
    }
 }


  class infoBox extends tableBox {
    function infoBox($contents, $direct_output = true)
    {
    global $infobox_header_text, $infobox_header_link;
      $info_box_contents = array();

      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, $direct_output);
      }


    function infoBoxContents($contents) {
      $this->table_cellpadding = '3';
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();
      $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }

			function newinfoboxcontents($contents) {
			global $infobox_header_text, $infobox_header_link, $skin_slice_set;
		  $this->table_cellpadding = '0';
			$this -> align = 'center';
      $info_box_contents = array();
      $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }

      if ($infobox_header_link != false) {
      $infobox_link = '<a href="' . $infobox_header_link . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      }
      return $this->tableBox($info_box_contents, false, true, $infobox_header_text, $infobox_header_link, $skin_slice_set);

    }
  }


  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false, $direct_output = true) {
		global $infobox_header_text, $infobox_header_link;

      $this->table_cellpadding = '0';


      if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == true) {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => $left_corner),
                                   array('params' => 'width="100%" height="14" class="infoBoxHeading"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading" nowrap',
                                         'text' => $right_corner));

      $this->tableBox($info_box_contents, $direct_output);

	 }
  }

  class contentBox extends tableBox {
    function contentBox($contents, $skin_this = false, $slice_set = '') {
     global	$contentbox_header_text;
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents, $skin_this));
      $this->table_cellpadding = '1';

			   $this->table_parameters = 'class="infoBox"';
         $this->tableBox($info_box_contents, true);

    }

    function contentBoxContents($contents, $skin_this = false) {
    $this->table_cellpadding = '4';
 	 	  $this->table_parameters = 'class="infoBoxContents"';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents, $skin_this = false) {
		    global $contentbox_header_text;


        $this->table_width = '100%';
        $this->table_cellpadding = '0';

        $info_box_contents = array();
        $info_box_contents[] = array(
                                   array('params' => 'height="14" width="100%"',
                                         'text' => $contents[0]['text']),
                                   );

        $this->tableBox($info_box_contents, true);

    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

  class productListingBox extends tableBox {
    function productListingBox($contents, $skin_this = false, $slice_set = '') {
     global $contentbox_header_text;
     $this->table_parameters = 'class="productListing"';
      $this->tableBox($contents, true, $skin_this, $contentbox_header_text, false, $slice_set);
    }
  }
?>
