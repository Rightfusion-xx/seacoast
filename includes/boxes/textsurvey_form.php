<?php
/*
  $Id: textsurvey.php,v 1.0 2003/05/03 22:30:51 Contribution by Chris Chong of AuraDev Web Development chris@auradev.com,
random picker script by cj-design.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<?php /* Randomly pick a question for TextSurvey ---------*/

$random = true;	 // if you want random, keep true, set to false if want sequential
$directory = "http://seacoastvitamins.com/Store/includes/questions/";  //   path to your questions files with / on end!
$questionfile = "randomquestion.inc";  //  The Random QUestion file
$questioncountfile = "displayquestion.inc";  //  The Display Question file (IF SEQUENTIAL!) 

/* End of Random Question Picker Configuration ------*/


$questions = file($directory.$questionfile);
$number = count($questions);

if($random){
	$num = rand(0,$number-1);
}
else{
	$num = file($directory.$questioncountfile);
	$num = $num[0]+1;
	if($num>$number-1){ // If ran out of questions, start again!
		$num=0;	
	}
	if (file_exists($directory.$questioncountfile)) {
		$nu = fopen ($directory.$questioncountfile, "w");
		fputs($nu,$num);
	}
	else {
		die("Cant Find $questioncountfile");
	}
}

?>
<!-- textsurvey //-->
          <tr>
            <td>

<?php  // Survey Starts Here
 	if($textsurvey == $null)

		{ $textsurvey_content = "
		
		<font class='smalltext'>

		<center>

		<form method='post' action='/includes/boxes/textsurvey_submit.php'>


		<br>$questions[$num]<input name='question' type='hidden' size='100' value='$questions[$num]'><br>
		<br><input name='results' type='text'  size='20' maxlength='200' class='input'><br>

		<br><input name='submit' type='submit' ' value='submit' class='input'>

		</form></font>

		
		"; }

	elseif($textsurvey == sent)

		{ $textsurvey_content = "<font class='smalltext'><center>Thank you.<br>Your opinions are very valuable to us.<br>
		Please submit more ideas!</font>"; }


      //  Survey Ends Here
?>


<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $textsurvey_content);

  new infoBox($info_box_contents);?>

            </td>
          </tr>
<!-- textsurvey_eof //-->