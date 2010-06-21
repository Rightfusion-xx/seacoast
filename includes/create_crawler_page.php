
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $product_info['products_head_title_tag'] ?></title>
<meta name="description" content="<?php echo $product_info['products_head_desc_tag']; ?>" />
<meta name="keywords" content="<?php echo $product_info['products_head_keywords_tag']; ?>" />
</head>
<body>

<?php

      // Create a page specifically for the GSA crawler
      echo '<h1>',$product_info['products_name'],' ', $products_info['manufacturers_name'],'</h1>';
      echo  '<p>',$product_info['products_description'],'</p>';
      echo '<p>',$product_info['products_ailments'],'</p>';
      echo '<p>',$product_info['products_keywords'],'</p>';
      echo '<p>',$product_info['products_uses'],'</p>';
      echo '<p>',$product_info['products_departments'],'</p>';


 ?>
 
 </body>
 </html>