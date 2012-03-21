<?php

  require('includes/application_top.php');

 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">


<title><?php echo 'CSV Importer'; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<style type=text/css>
	.error{color:red;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
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
        <td>
        	<h1>CSV Importer</h1>
			<p>
				Select csv file for upload.
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
					<input type="file" name="csv_file"/>
					<input type="submit"/>
					
				</form>
			</p>
			<?php 
			//upload file if available
			if($_REQUEST['action']=='insert'){
				echo 'Inserting...<br/><br/>';
				
				//Attempt to insert new products.
				
				//opening file
				$csv_file=opencsvfile();
				$csv_headers=getcsvrow($csv_file);
				
				//create array structure with data to import
				tep_db_query('BEGIN;');
				$sql_errors=false;
				while($csv_contents=getcsvrow($csv_file))
					{
						//parse each row and insert into db
						$dbarray=array();
						
						foreach(array_keys($csv_contents) as $key){
							if(!strpos($csv_headers[$key],'.')){
										echo '<br/><span class="error">No table name found. Skipping ' .$csv_headers[$key].'</span>';
									}
									else{
										//get table and column name.
										$tblcol=split('\.',$csv_headers[$key]);
										
										
										$dbarray[$tblcol[0]][$tblcol[1]]=str_replace('\,',',',$csv_contents[$key]);
										
								}
								
						
					}
					//created array, so attempt upload
					$sql='';
					foreach(array_keys($dbarray) as $curtbl)
					{
						echo $curtbl . '<br/>';
						$usecomma=false;
						$sql='insert into '	. $curtbl .'(';
						foreach(array_keys($dbarray[$curtbl]) as $col)
						{
							if($usecomma)
							{
								$sql.=',';
							}
							$sql.=$col;
							
							$usecomma=true;
						}
						
						$sql.=') values(';
						$usecomma=false;
						
						foreach($dbarray[$curtbl] as $col)
						{
							if($usecomma)
							{
								$sql.=',';
							}
							if(substr($col,0,1)=='$' && substr($col,-1,1)=='$')
							{
								$sql.=tep_db_input(str_replace('$','',$col));
							}
							else
							{
								$sql.='"'.tep_db_input($col).'"';
							}
								
						
							$usecomma=true;
						}
						
						$sql.=');';
					
						
						echo $sql .'<br/><br/><br/>';
						
						if(@tep_db_query($sql))
						{
							echo 'Success!<br/>';
						}
						else
						{
							echo '<span class="error">Failed to insert!<br/>'.$mysql.'<br/><br/><br/>';
							$sql_errors=true;
						}
						
					}
				
				}
			if($sql_errors)
				{
					@tep_db_query('ROLLBACK;');
				}
				else{
					@tep_db_query('COMMIT;');
				}	
			}
			
			
			
			if (strlen($_FILES['csv_file']['name'])>0) {
				
				if (substr($_FILES['csv_file']['name'],-3,3)<>'csv'){
					?><span class="error">Please upload a .csv file.</span><?php
				}
				else
				{
					//looks good, bring in the file.
					move_uploaded_file($_FILES['csv_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].DIR_WS_ADMIN.'/raw_csv.csv');
					
					$csv_file=opencsvfile();
					$csv_headers=getcsvrow($csv_file);
					
					while($csv_contents=getcsvrow($csv_file))
					{
						?><p><table border=1 style="border:collapsed;width:100%;"><?php
						
						
						foreach(array_keys($csv_contents) as $key){
							?>
							<tr>
								<td><?php 
								
									echo $csv_headers[$key];
									if(!strpos($csv_headers[$key],'.')){
										echo '<br/><span class="error">No table name found.</span>';
									}
									
								
								?></td>
								
								
								
								
								<td><?php echo str_replace('\,',',',$csv_contents[$key]);?></td>
							</tr>
							
							
							<?php
						}
						?></table></p>
						
						<?php
					}
					
					?>
						<p>
							<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
								<input type="hidden" name="action" value="insert"/>
								<input type="submit" value="INSERT"/>
								
								
							</form>
						</p>
					<?php
				}
				
				
			}?>
        </td>

              </tr>
            </table></td>
</td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>


<?php
function opencsvfile(){
	
	return(fopen($_SERVER['DOCUMENT_ROOT'].DIR_WS_ADMIN.'/raw_csv.csv','r'));
}

function getcsvrow($csv_file){
	return(fgetcsv($csv_file,filesize($_SERVER['DOCUMENT_ROOT'].DIR_WS_ADMIN.'/raw_csv.csv')));
}
?>