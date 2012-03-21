<?php

/*
	(c) Kaavren
*/

include_once("utils.php");

/*
if (!isset($_SERVER['PHP_AUTH_USER'])) {
  header("WWW-Authenticate: Basic realm=\"Sweepstakes Admin\"");
  header('HTTP/1.0 401 Unauthorized');
  echo 'Access Denied';
  exit;
}
else {
  if ($_SERVER['PHP_AUTH_PW']!=$admin_password) {
    header('HTTP/1.0 401 Unauthorized');
    echo "<h1>Access Denied</h1>";
    exit;
  }
}
*/

$link = mysql_connect ($sql_host, $sql_username, $sql_passwd) or die ("Could not connect to database. Try later<BR>");
@mysql_select_db($basename, $link);

function admin_mail_all_step1() {
  echo "<form action=\"admin.php?action=7\" method=\"POST\" enctype=\"multipart/form-data\">";
  echo "<font color=\"#000000\"><h1 align=\"center\">Email to all users<font></h1>";
  echo "<center><textarea name=\"message_body\" rows=\"14\" cols=\"80\"></textarea></center><br>";
  echo "<table width=\"100%\">";
  echo "<tr><td width=\"10%\"></td>";
  echo "<td width=\"30%\" valign=\"top\" align=\"right\">Subject:<br></td>";
  echo "<td>";
  echo "<input type=\"text\" name=\"mail_subject\" size=\"30\" maxlength=\"255\">";
  echo "</td></tr>";
  echo "<tr><td></td>";
  echo "<td valign=\"top\" align=\"right\">HTML-message from file:<br></td>";
  echo "<td><input type=\"file\" name=\"html_file\" size=\"20\" maxlength=\"30\"></td></tr>";
  echo "<tr><td></td>";
  echo "<td valign=\"top\" align=\"right\">Attach file:<br></td>";
  echo "<td><input type=\"file\" name=\"att_file\" size=\"20\" maxlength=\"30\"></td></tr>";
  echo "</table>";
  echo "<br><center><input type=\"submit\" value=\"Send Message\">";
  echo "</form>";

  return(0);
}

function admin_mail_all_step2() {
  global $link;
  global $INCLUDE_PATH;
  global $DATA_PATH;
  global $robot_email;

  $r=mysql_query("select email from mailing_list",$link);
  $recipients_qty = mysql_num_rows($r);

  $message_body=$_POST['message_body'];
  $subject=$_POST['mail_subject'];

  $template=new class_mail();

  if (is_uploaded_file($_FILES['html_file']['tmp_name'])) {
    move_uploaded_file($_FILES['html_file']['tmp_name'],$DATA_PATH.$_FILES['html_file']['name']);
 
    $template->mail_body_html="";

    $fp=fopen($DATA_PATH.$_FILES['html_file']['name'],"r");
    if (!$fp) {
      error_report(31);
      return(31);
    }
    while (!feof ($fp)) {
      $buffer = fgets($fp, 1000000);
      $template->mail_body_html.=$buffer;
    }
    fclose ($fp);

//    echo "<br>$template->mail_body_html";
  
  }
  else {
    $template->mail_body_html=$message_body;
  }

  $attach=0;
  $filename="";

  if (is_uploaded_file($_FILES['att_file']['tmp_name'])) {
    move_uploaded_file($_FILES['att_file']['tmp_name'],$DATA_PATH.$_FILES['att_file']['name']);
    $attach=1;
    $filename=$_FILES['att_file']['name'];

    $template->mail_fileattach($DATA_PATH.$_FILES['att_file']['name'],"application",$filename);

//    echo "<h1>$filename</h1>";
  }
    $template->mail_from=$robot_email;
    $template->mail_subj=$subject;
    $template->mail_body_create($attach,$filename);

    for ($i=0;$i<$recipients_qty;$i++) {
      list($email) = mysql_fetch_row($r);
      echo "<h3>$email -> $i</h3>";  
//      $template->mail_to=$email;
      mail($email,$template->mail_subj,$template->mail_body,$template->mail_header());
    }
 
  return(0);
}


function admin_view_sweepstakes() {
  global $link;

  $r=mysql_query("select * from sweepstakes where enable='Y'",$link);
  if  (mysql_numrows($r)>0) {
    echo "<center><h1>Sweepstakes</h1>";
    echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr><td width=\"5%\">ID</td>";
    echo "<td width=\"20%\">Period</td><td width=\"15%\">Begin</td><td width=\"15%\">End</td><td width=\"15%\">Prizes</td>";
//  echo "<td align=\"center\" width=\"15%\">&nbsp;</td><td align=\"center\" width=\"15%\">&nbsp;</td></tr>";
    echo "<td align=\"center\" colspan=\"2\" width=\"30%\">Actions</td></tr>";
  }
  else {
    echo "<center>No sweepstakes available</center>";
  }
  for($i=0; $i<mysql_numrows($r); $i++) {
    $f=mysql_fetch_array($r);
    echo "<tr>";
    echo "<td width=\"5%\">$f[id]</td>";
    echo "<td width=\"20%\">";
    if ($f['period']=="W") { echo "every week"; }
    if ($f['period']=="M") { echo "every month"; }
    if ($f['period']=="N") { echo "once"; }
    echo "</td>";
    echo "<td width=\"15%\">$f[date_begins]</td>";
    echo "<td width=\"15%\">$f[date_ends]</td>";
    echo "<td width=\"15%\">$f[places]</td>";
    echo "<td width=\"15%\"><a href=\"admin.php?action=4&id=$f[id]\">delete</a></td>";
    echo "<td width=\"15%\"><a href=\"admin.php?action=9&id=$f[id]\">add player</a></td>";

    echo "</td></tr>";
  }

  echo "</table>";
  echo "</center>";

  echo "<br><hr><br><center>";
  echo "<a href=\"admin.php?action=0\">New sweepstake begin</a> &nbsp; &nbsp;";
  echo "<a href=\"admin.php?action=6\">E-Mail to all users</a> &nbsp; &nbsp;";
  echo "<a href=\"admin.php?action=5\">Archive</a>";

  echo "</center>";

  return(0);
}

function admin_sweepstakes_archive() {
  global $link;

  echo "<center><h1>Archive</h1>";
  echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr><td width=\"10%\">ID</td>";
  echo "<td width=\"30%\">Period</td><td width=\"20%\">Begin</td><td width=\"20%\">End</td><td width=\"20%\">Winners</td></tr>";

  $r=mysql_query("select * from sweepstakes where enable='N'",$link);
  for($i=0; $i<mysql_numrows($r); $i++) {
    $f=mysql_fetch_array($r);
    echo "<tr>";
    echo "<td width=\"10%\">$f[id]</td>";
    echo "<td width=\"30%\">";
    if ($f['period']=="W") { echo "every week"; }
    if ($f['period']=="M") { echo "every month"; }
    if ($f['period']=="N") { echo "once"; }
    echo "</td>";
    echo "<td width=\"20%\">$f[date_begins]</td>";
    echo "<td width=\"20%\">$f[date_ends]</td>";
    echo "<td width=\"20%\"><a href=\"admin.php?action=2&id=$f[id]\">click here</a></td>";

    echo "</td></tr>";
  }

  echo "</table>";
  echo "</center>";

  echo "<br><hr><br><center>";
  echo "<a href=\"admin.php?action=0\">New sweepstake begin</a> &nbsp; &nbsp;";
  echo "<a href=\"admin.php?action=6\">E-Mail to all users</a> &nbsp; &nbsp;";
  echo "<a href=\"admin.php\">Enabled sweepstakes</a>";

  echo "</center>";


  return 0;
}


function admin_new_sweepstake_step1() {
  global $link;

  $r=mysql_query("select max(id) from sweepstakes");
  if (mysql_num_rows($r)>0) {
    list($id)=mysql_fetch_row($r);
    $id++;
  }
  else {
    $id=1;
  }

  echo "<form name=\"sweepstake_form1\" action=\"admin.php?action=11&id=$id\" method=\"POST\">";
  echo "<font color=\"#000088\" face=\"Arial\"><h1 align=\"center\">New sweepstake(step1)<font></h1>";
  echo "<table width=\"100%\">";

  echo "<tr><td width=\"20%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "Number of winners:<br>";
  echo "</td><td><input type=\"text\" name=\"places\" size=\"10\" maxlength=\"255\" value=\"1\"></td></tr></table>";

//  echo "<tr><td></td><td valign=\"top\" align=\"right\"> &nbsp; <br></td><td><br>";
  echo "<br><center><input type=\"submit\" value=\"NEXT\"></center>";
//  echo "</td></tr>";

  echo "</form>";

  return(0);
}

function add_prize_to_db() {
  global $link;
  global $DATA_PATH;

  $id=$_GET['id'];
  $num=$_GET['num']; // prize for $num place
  $prize_name=$_POST['prize_name'];    
  $prize_type=$_POST['prize_type'];    

  if (($prize_type=="D") && (!isset($_POST['prize_link']))) {
    error_report(51);
    return(51);
  }

/*  if ($prize_type=="I") { $additional=$_POST['instructions']; }
  if ($prize_type=="C") { $additional=""; }
*/

  $r=mysql_query("insert into prizes(prize_type,prize_name,sweepstake_id,place) values(
  '".mysql_escape_string($prize_type)."',  
  '".mysql_escape_string($prize_name)."',  
  '$id',  
  '$num')",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }

  $r=mysql_query("select max(id) from prizes",$link);

  list($prize_id)=mysql_fetch_row($r);

  if ($prize_type=="D") {
    $prize_link=$_POST['prize_link'];
    $r=mysql_query("insert into links(prize_id,prize_link) values ('$prize_id','".mysql_escape_string($prize_link)."')",$link);
    if (!$r) {
      error_report(10);
      return(10);
    }
  }

  if (($prize_type=="I") && (isset($_POST['instructions']))) {
    $instructions=$_POST['instructions'];
    $filepath = "$DATA_PATH"."$prize_id".".ins";
    $fp=fopen($filepath,"w");
    fwrite($fp,$instructions);
    fclose($fp);    
  }
    

  return(0);
}

function admin_new_prize() {
  $num=0;
  $id=$_GET['id'];

  if (isset($_GET['places'])) {
    $places=$_GET['places'];
    $num=$_GET['num']; // prize for $num place

/*    $prize_name=$_POST['prize_name'];    
    $prize_type=$_POST['prize_type'];    
    if ($prize_type=="D") { $additional=$_POST['prize_link']; }
    if ($prize_type=="I") { $additional=$_POST['instructions']; }
    if ($prize_type=="C") { $additional=""; }
*/

    add_prize_to_db();

  }
  else {
    $places=$_POST['places'];
    $num=0;        
  }

  $num++;

  if ($num==$places) {
    $form_action="admin.php?action=1&num=$num&id=$id&places=$places";
  }
  else {
    $form_action="admin.php?action=11&places=$places&num=$num&id=$id&places=$places"; 
  }
    
  echo "<form name=\"prize_form\" action=\"$form_action\" method=\"POST\">";
  echo "<font color=\"#000088\" face=\"Arial\"><h1 align=\"center\">Prize for $num place<font></h1>";
  echo "<table width=\"100%\">";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "Prize for $num place:<br>";
  echo "</td><td><input type=\"text\" name=\"prize_name\" size=\"30\" maxlength=\"255\"></td></tr>";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "Prize type:<br>";
  echo "</td><td><select name=\"prize_type\">";
  echo "<option value=\"C\">Cash";
  echo "<option value=\"I\">Item";
  echo "<option value=\"D\">Downloadable";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "Link (for downloadable only):<br>";
  echo "</td><td><input type=\"text\" name=\"prize_link\" size=\"30\" maxlength=\"255\"></td></tr>";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "Special instructions (for items only):<br>";
  echo "</td><td><textarea name=\"instructions\" rows=\"10\" cols=\"50\"></textarea></td></tr>";

  echo "</table>";
  echo "<center><br><input type=\"submit\" value=\"  OK  \"></center>";
  echo "</form>";

  

  return(0);
}

function admin_new_sweepstake_step2() {
  $places=$_GET['places'];

  add_prize_to_db();

  echo "<form name=\"sweepstake_form2\" action=\"admin.php?action=8&places=$places\" method=\"POST\">";
  echo "<font color=\"#000088\" face=\"Arial\"><h1 align=\"center\">New sweepstake(step2)<font></h1>";
  echo "<table width=\"100%\">";

  $begin = today_date();

  echo "<input type=\"hidden\" name=\"begin_date\" value=\"$begin\">";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "Begin (mm/dd/yy):";
  echo "</td><td><input type=\"text\" name=\"begin2\" size=\"30\" maxlength=\"255\" value=\"$begin\" disabled></td></tr>";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "End (mm/dd/yy):";
  echo "</td><td><input type=\"text\" name=\"end\" size=\"30\" maxlength=\"255\"></td></tr>";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "Period:<br>";
  echo "</td><td><select name=\"period\">";
  echo "<option value=\"N\">Once";
  echo "<option value=\"W\">every week";
  echo "<option value=\"M\">every month</select></td></tr>";

//  echo "<tr><td></td><td valign=\"top\" align=\"right\"> &nbsp; <br></td><td><br>";
//  echo "</td></tr>";

  echo "<tr><td width=\"10%\"></td><td width=\"30%\" valign=\"top\" align=\"right\">";
  echo "&nbsp;";
  echo "</td><td><input type=\"checkbox\" name=\"free\" size=\"30\" maxlength=\"255\">Free sweepstake</td></tr>";

  echo "</table>";

  echo "<br><center><input type=\"submit\" value=\"NEXT\"></center>";


  echo "</form>";

  return(0);
}

function admin_new_sweepstake_step3() {
  global $link;

  $places=$_GET['places'];
  $period=$_POST['period'];
  $begin=$_POST['begin_date'];  
  $end=$_POST['end'];
//  $free_flag=$_POST['free'];

  if (isset($_POST['free'])) { $free="Y"; } else { $free="N"; }
//  $prizes = $_POST['places'];

  if ($period=="W") {
    $weekday=date("w",time());
    $weekday=(int)$weekday;
    $bonus=7-$weekday;
    $unix_time=strtotime("+$bonus day");
    $end=date("m/d/y",$unix_time);
  }

  if ($period=="M") {
    $day=1;
    $mon=date("m",time());
    $year=date("y",time());
    $mon++;
    if ($mon==13) {
      $mon=1;
      $year++;
    } 
    $unix_time=strtotime("$mon/$day/$year");
    $end=date("m/d/y",$unix_time);
  }


  $r=mysql_query("insert into sweepstakes(date_begins,date_ends,period,places,free) values(
  '".mysql_escape_string($begin)."',
  '".mysql_escape_string($end)."',
  '".mysql_escape_string($period)."',
  '".mysql_escape_string($places)."',
  '".mysql_escape_string($free)."'
  )",$link);

  if (!$r) {
    error_report(10);
    return(10);
  }

  $r=mysql_query("select max(id) from sweepstakes");
  list($sweepstake_id)=mysql_fetch_row($r);

/*  for ($i=1;$i<$places+1;$i++) {
    $r=mysql_query("insert into prizes(sweepstake_id,place,prize_name) values(
    '$sweepstake_id',
    '$i',
    '".mysql_escape_string($prizes[$i])."')"
    ,$link);
  }
*/

  admin_view_sweepstakes();

  return(0);
}

function admin_new_player_step1() {
  $id=$_GET['id'];

  echo "<center><h1>Add Player</h1>";
  echo "<form name=\"new_player\" action=\"admin.php?action=10&id=$id\" method=\"POST\">";
  echo "<b>User E-Mail:</b> &nbsp; <input type=\"text\" name=\"email\"><br><br>";
  echo "<input type=\"submit\" value=\"Add Player\">";
  echo "</form>";  
  echo "</center>";

  return(0);
}

function admin_new_player_step2() {
  global $link;
  $id=$_GET['id'];
  $email=$_POST['email'];
  
  $r=mysql_query("insert into players(sweepstake_id,email)
  values('$id','".mysql_escape_string($email)."')",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }

  admin_view_sweepstakes();

  return(0);
}


function admin_delete_sweepstake_step1() {
  $id=$_GET['id'];

  echo "<center><h2>Are you really want to delete whis sweepstake?";
  echo "<br><a href=\"admin.php?action=3&id=$id\">Yes</a>";
  echo "&nbsp; &nbsp;<a href=\"admin.php\">No</a>";
  echo "</center></h2>";

  return(0);
}

function admin_delete_sweepstake_step2() {
  global $link;

  $id=$_GET['id'];

  $r=mysql_query("delete from sweepstakes where id='$id'",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }

  $r=mysql_query("delete from players where sweepstake_id='$id'",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }

  $r=mysql_query("delete from prizes where sweepstake_id='$id'",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }

  admin_view_sweepstakes();

  return(0);
}

function admin_view_winners() {
  global $link;

  $id=$_GET['id'];

  echo "<center><h1>Winners List</h1>";
  echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\"><tr>";
  echo "<td width=\"20%\">Place</td><td width=\"30%\">Winner</td><td width=\"50%\">Prize</td></tr>";

  $r=mysql_query("select pl.email, pr.place, pr.prize_name from players pl, prizes pr where pr.sweepstake_id='$id' and pl.sweepstake_id='$id' and pr.winner_id=pl.id",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }
  for($i=0; $i<mysql_numrows($r); $i++) {
    $f=mysql_fetch_array($r);
    echo "<tr>";
    echo "<td width=\"20%\">$f[place]</td>";
    echo "<td width=\"20%\">$f[email]</td>";
    echo "<td width=\"20%\">$f[prize_name]</td>";

    echo "</tr>";
  }

  echo "</table>";
  echo "</center>";

  echo "<br><hr><br><center>";
  echo "<a href=\"admin.php?action=0\">New sweepstake begin</a> &nbsp; &nbsp;";
  echo "<a href=\"admin.php?action=6\">E-Mail to all users</a> &nbsp; &nbsp;";
  echo "<a href=\"admin.php?action=5\">Archive</a>";
	
  echo "</center>";  

  return(0);
}


if (!isset($_GET['action'])) {
  admin_view_sweepstakes();
}
else {
  $action=$_GET['action'];

  switch ($action) {
  case 0:
    admin_new_sweepstake_step1();
  break;
  case 1:
    admin_new_sweepstake_step2();
  break;
  case 2:
    admin_view_winners();
  break;
  case 8:
    admin_new_sweepstake_step3();
  break;
  case 9:
    admin_new_player_step1();
  break;
  case 10:
    admin_new_player_step2();
  break;
  case 3:
    admin_delete_sweepstake_step2();
  break;
  case 4:
    admin_delete_sweepstake_step1();
  break;
  case 5:
    admin_sweepstakes_archive();
  break;
  case 6:
    admin_mail_all_step1();
  break;
  case 7:
    admin_mail_all_step2();
  break;
  case 11:
    admin_new_prize();
  break;

  }
}

?>