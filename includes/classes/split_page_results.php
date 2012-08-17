<?php

  class splitPageResults {
    var $sql_query, $number_of_rows, $current_page_number, $number_of_pages, $number_of_rows_per_page, $page_name;

/* class constructor */
    function splitPageResults($query, $max_rows, $count_key = '*', $page_holder = 'page') {
      global $HTTP_GET_VARS, $HTTP_POST_VARS;


      if(strpos($_SERVER['REQUEST_URI'],'health_library.php')>0){
        $max_rows=50;
        $offset=0;
      }


      $this->sql_query = $query;
      $this->page_name = $page_holder;

      if (isset($_REQUEST[$page_holder])) {
        $page = $_REQUEST[$page_holder];
      } elseif (isset($_REQUEST[$page_holder])) {
        $page = $_REQUEST[$page_holder];
      } else {
        $page = '';
      }
      

      if (empty($page) || !is_numeric($page)) $page = 1;
      $this->current_page_number = $page;

      $this->number_of_rows_per_page = $max_rows;

      $pos_to = strlen($this->sql_query);
      $pos_from = strpos($this->sql_query, ' from', 0);

      $pos_group_by = strpos($this->sql_query, ' group by', $pos_from);
      if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

      $pos_having = strpos($this->sql_query, ' having', $pos_from);
      if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

      $pos_order_by = strpos($this->sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

      if (strpos($this->sql_query, 'distinct') || strpos($this->sql_query, 'group by')) {
        $count_string = 'distinct ' . tep_db_input($count_key);
      } else {
        $count_string = tep_db_input($count_key);
      }
      
      $limit=parse_section($query.'<eof>',' limit ','<eof>');

      if(strlen($limit)>0){
          $this->number_of_rows = parse_section($limit.'<eof>',',','<eof>');
      }
      else
      {
          $count_query = "select count(" . $count_string . ") as total " . substr($this->sql_query, $pos_from, ($pos_to - $pos_from));
          $count = tep_db_fetch_array(tep_db_query($count_query));

          $this->number_of_rows = $count['total'];
      }

      if(strpos($_SERVER['REQUEST_URI'],'health_library.php')>0){
        $this->number_of_pages=1;
      }
      else{
      $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);
      }

      if ($this->current_page_number > $this->number_of_pages) {
          //Stop checking for page ceiling. Redirect if no products found.
          //$this->current_page_number = $this->number_of_pages;
      }
      

      $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      
      if(strlen($limit)>0){
          //$this->sql_query .= " limit " . $offset . ", " . $this->$limit;
       }
      else{
          $this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;
      }
    
    }

/* class functions */

// display split-page-number-links
    function display_links($max_page_links, $parameters = '') {
      global $request_type;
      $PHP_SELF=$_SERVER['PHP_SELF'];
      
      $parameters=str_replace('%20','+',$parameters);

      $display_links_string = '';

      $class = 'class="pageResults"';

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';
      
      $parameters=str_replace(' ','+',$parameters);
// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" class="pageResults" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' "><u>' . PREVNEXT_BUTTON_PREV . '</u></a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

// previous window of pages
      if ($cur_window_num > 1) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" class="pageResults" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a>';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;';
        } else {
          $display_links_string .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" class="pageResults" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' "><u>' . $jump_to_page . '</u></a>&nbsp;';
        }
      }

// next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" class="pageResults" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a>&nbsp;';

// next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . 'page=' . ($this->current_page_number + 1), $request_type) . '" class="pageResults" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' "><u>' . PREVNEXT_BUTTON_NEXT . '</u></a>&nbsp;';

      return $display_links_string;
    }



// display number of total products found
    function display_count($text_output) {
      $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
      if ($to_num > $this->number_of_rows) $to_num = $this->number_of_rows;

      $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num++;
      }

      return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
    }

  
  
  
  
  
  
  
  // display cheapest stylesplit-page-number-links
    function display_cheapest_links($max_page_links, $parameters = '') {
      global $PHP_SELF, $request_type, $page;

      $display_links_string = '';

      $class = 'class="pageResults"';

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';
$prevpage=$this->current_page_number-1;
// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="' . str_replace('//','/',str_replace('/pg'.$page.'/','',$_SERVER['REQUEST_URI']).'/pg'.$prevpage.'/') . '" class="pageResults" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' "><u>' . PREVNEXT_BUTTON_PREV . '</u></a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;


// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;';
        } else {
          $display_links_string .= '&nbsp;<a href="' .  str_replace('//','/',str_replace('//','/',str_replace('/pg'.$page.'/','',$_SERVER['REQUEST_URI']).'/pg'.$jump_to_page.'/')) . '" class="pageResults" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' "><u>' . $jump_to_page . '</u></a>&nbsp;';
        }
      }

// next button
 $nextpage=$this->current_page_number+1;
     if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="' . str_replace('//','/',str_replace('/pg'.$page.'/','',$_SERVER['REQUEST_URI']).'/pg'.$nextpage.'/') .'" class="pageResults" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' "><u>' . PREVNEXT_BUTTON_NEXT . '</u></a>&nbsp;';

      $display_links_string=str_replace('/pg1/','/',$display_links_string);
      return $display_links_string;
    }
 }
?>
