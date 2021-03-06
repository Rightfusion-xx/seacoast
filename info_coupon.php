<?php
  require("includes/application_top.php");
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_COUPON);
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo HEADING_TITLE; ?></title>
<?php include('includes/meta_tags.php');?>
<link rel="stylesheet" type="text/css" href="<?php echo STYLESHEET; ?>">
</head>
<body>
<table cellpadding="3">
<tr><td>
<p><b><?php echo HEADING_TITLE; ?></b><br><?php echo tep_draw_separator(); ?></p>
<p><b><i><?php echo SUB_HEADING_TITLE_1; ?></i></b><br><?php echo SUB_HEADING_TEXT_1; ?></p>
<p><b><i><?php echo SUB_HEADING_TITLE_2; ?></i></b><br><?php echo SUB_HEADING_TEXT_2; ?></p>
<p><b><i><?php echo SUB_HEADING_TITLE_3; ?></i></b><br><?php echo SUB_HEADING_TEXT_3; ?></p>
<p align="center"><a href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
</td>
</tr>
</table>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
