<?php
  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  require(DIR_WS_FUNCTIONS . '/render_products.php');
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php


?>
<title>
GoalTribe.com - Goal Achievement Simplicity
</title>
<meta name="keywords" content="goals, planning"/>
<meta name="description" content="Join GoalTribe.com today for the ultimate in goal planning, tracking and execution. Experts available."/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="content">
    <h1>Join GoalTribe.com - Ship Free at Seacoast Vitamins</h1>
    
    
    <div style="border:solid 1px #FF9900;padding:30px;margin-top:30px;">
                        <form action="http://www.goaltribe.com/users/register" method="post" >
          	<input type="hidden" name="data[User][code]"  value="" id="UserCode" />          	<table width="428" border="0" cellpadding="5">
	          	<tr>
	          		<td class="fontBold14px" style="color:black;padding-top:7px" valign="top">Login name:</td>

				 	<td>
					<input name="data[User][username]"  size="45" maxlength="30" value="" type="text" id="UserUsername" />															</td>			 	
				</tr>          		
	          	<tr>
	          		<td class="fontBold14px" style="color:black;padding-top:7px" valign="top">Password:</td>
	          		<td>
					 	<input name="data[User][passwd]"  size="45" maxlength="30" type="password" value="" id="UserPasswd" />												</td>
				</tr>          		
	          	<tr>

	          		<td class="fontBold14px" style="color:black;padding-top:7px" valign="top" nowrap="nowrap">Confirm Password:</td>
	          		<td>
				 	<input name="data[User][confirm_passwd]"  size="45" maxlength="30" type="password" value="" id="UserConfirmPasswd" />					</td>
				</tr>          		
	          	<tr>
	          		<td class="fontBold14px" style="color:black;padding-top:7px" valign="top">Email:</td>
	          		<td>
				 	<input name="data[User][email]"  size="45" maxlength="100" value="" type="text" id="UserEmail" />				
										</td>

				</tr>          		
	          	<tr>
					<td class="fontBold14px" style="color:black;padding-top:7px" valign="top">Confirm email:</td>
	          		<td>
				 	<input name="data[User][confirm_email]"  size="45" maxlength="100" value="" type="text" id="UserConfirmEmail" />				 						</td>
				</tr>
				<tr>
					<td></td>

					<td style="color:black;padding-top:7px;font-size:11px" valign="top" nowrap="nowrap">
						<input type="hidden" name="data[User][conditions]"  value="0" id="UserConditions_" /><input type="checkbox" name="data[User][conditions]" id="UserConditions"  value="1" />&nbsp;I accept the GoalTribe <a class="actionsLink" href="http://www.goaltribe.com/main/terms" target="_new">terms & conditions</a> and <a class="actionsLink" href="http://www.goaltribe.com/main/privacy" target="_new">privacy policy</a>
						</td>
				</tr>          		
				<tr>
					<td nowrap="nowrap">

						</td>
					<td style="padding-top:10px">
						<input align="right" type="image" name="imageField" id="imageField" src="http://www.goaltribe.com/img/button-submit-orange-bg.gif"></td>
				</tr>
			</table>				
          </form>
    </div>
    
    
    
    
	</div>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

