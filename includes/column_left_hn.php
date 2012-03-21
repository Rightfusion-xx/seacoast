

<table border="0" width="100%" cellspacing="2" cellpadding="0">
  <tr>
  <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<?php

  if ((USE_CACHE == 'true') && empty($SID)) {

    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'catahn.php');
  }

?>

</TABLE></TD>