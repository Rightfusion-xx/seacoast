<?php
include_once("utils.php");

$link = mysql_connect ($sql_host, $sql_username, $sql_passwd) or die ("Could not connect to database. Try later<BR>");
@mysql_select_db($basename, $link);

function disable_sweepstake($id) {
  global $link;

  $r=mysql_query("update sweepstakes set enable='N' where id=$id",$link);
    if (!$r) {
      error_report(10);
      return(10);
    }

  return(0);
}

function new_sweepstake($id,$period,$places) {
  global $link;

  if ($period=="W") {          // every week
    $end_date=date("m/d/y",strtotime("+7 day"));
  }
  else {                      // every month
    $mon=date("m",time());
    $yr=date("y",time());
    $m=(int)$mon;
    if ($m==12) {
      $mon="01";
      $y=(int)$yr;
      $y++;
      if ($y>99) { $y-=100;  }
      if ($y<10) { $yr="0"."$y"; } else { $yr="$y"; }
    }
    else {
      $m++;
      if ($m<10) { $mon="0"."$m"; } else { $mon="$m"; }
    }
    $end_date="$mon/01/$yr";
  }
  
  $begin_date=date("m/d/y",time());
  
  $r=mysql_query("insert into sweepstakes(date_begins,date_ends,period,places) values (
  '".mysql_escape_string($begin_date)."',
  '".mysql_escape_string($end_date)."',
  '".mysql_escape_string($period)."',
  '$places')",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }

  $r=mysql_query("select max(id) from sweepstakes");
  list($new_id)=mysql_fetch_row($r);

  $r1=mysql_query("select prize_name from prizes where sweepstake_id='$id' order by place");
  if (!$r1) {
    error_report(10);
    return(10);
  }

  for ($i=1;$i<$places+1;$i++) {
    list($prize)=mysql_fetch_row($r1);
    $r=mysql_query("insert into prizes(sweepstake_id,place,prize_name) values(
    '$new_id','$i', '".mysql_escape_string($prize)."')");
    if (!$r) {
      error_report(10);
      return(10);
    }
  }

  return(0);
}

function email_admin($email,$winner,$places,$id) {
  global $robot_name;
  global $robot_email;
  global $link;
  global $admin_email;
  
  $r=mysql_query("select prize_name from prizes where sweepstake_id='$id' order by place",$link);
  if (!$r) {
    error_report(10);
    return(10);
  }

  $subject="Sweepstake ends";

  $body="";

  for ($i=1;$i<$places+1;$i++) {
    $winner_index=$winner[$i];
    list($prize)=mysql_fetch_row($r);
    $body.="$i place winner - $email[$winner_index]     Prize - $prize"."\n";        
  }

  $template = new class_mail();
  $template->mailer("$robot_name<".$robot_email.">",$admin_email,$subject,$body);

  return(0);
}

function email_winner($email,$posit,$id) {
  global $link;
  global $INCLUDE_PATH;
  global $robot_name;
  global $robot_email;
  global $winner_email_subject;
  global $script_url;

  $r=mysql_query("select id,prize_name from prizes where place='$posit' and sweepstake_id='$id'",$link);
   if (!$r) {
    error_report(10);
    return(10);
  }
  list($prize_id,$prize)=mysql_fetch_row($r);

  $body="";
  $fp=fopen("$INCLUDE_PATH"."winner_mail.inc","r"); 
  while (!feof ($fp)) {
    $buffer = chop(fgets($fp, 4096));
    if (strchr($buffer,"<<<place>>>")) {
        $buffer=str_replace("<<<place>>>","$posit",$buffer);
    }
    if (strchr($buffer,"<<<prize>>>")) {
        $buffer=str_replace("<<<prize>>>","$prize",$buffer);
    }
    if (strchr($buffer,"<<<url>>>")) {
        $buffer=str_replace("<<<url>>>","$script_url"."winner.php?id="."$prize_id",$buffer);
    }
    $body.="$buffer"."\n";
  }
  fclose ($fp);

//  $body=nl2br($body);  

//  echo "$body";  

  $template = new class_mail();
  $template->mailer("$robot_name<".$robot_email.">",$email,$winner_email_subject,$body);
  return(0);
}

function end_sweepstake($id,$period,$places) {
  global $link;

//  echo "<center><h1>End of sweepstake</h1></center>";


  $r=mysql_query("select email,id from players where sweepstake_id='$id'",$link);
  $players_qty = mysql_numrows($r);

  if ($players_qty == 0) {
    error_report(52);
    return(52);
  }

  if ($players_qty < $places) {  $places = $players_qty;  }


  for($i=0; $i<$players_qty; $i++) {
    $f=mysql_fetch_array($r);
    $email[$i+1]=$f['email'];
    $winner_id[$i+1]=$f['id'];
  }

  srand(time());

  for ($i=1;$i<$places+1;$i++) {
    if ($i<=$players_qty) {
      $exit_key=0;
      while (!$exit_key) {
        $exit_key=1;
        $winner[$i]=rand(1,$players_qty);
        for ($j=1;$j<$i;$j++) {
          if ($winner[$j]==$winner[$i]) { $exit_key = 0; }
        }
      }
    

    $winner_index=$winner[$i];

//    echo "<h3>$email[$winner_index]<br></h3>";

      $r=mysql_query("update prizes set winner_id='$winner_id[$winner_index]' where sweepstake_id='$id' and place='$i'");
      if (!$r) {
        error_report(10);
        return(10);
      }

      email_winner($email[$winner_index],$i,$id);  
    }
  }

  email_admin($email,$winner,$places,$id);
  

  if ($period!="N") {
    new_sweepstake($id,$period,$places);
  }

  disable_sweepstake($id);

  return(0);
}

function check_date() {
  global $link;
  $now_date=date("m/d/y",time());

  $r=mysql_query("select id,period,places from sweepstakes where date_ends='".mysql_escape_string($now_date)."' and enable='Y'",$link);
  for($i=0; $i<mysql_numrows($r); $i++) {
    $f=mysql_fetch_array($r);
    end_sweepstake($f['id'],$f['period'],$f['places']);
  }

  return(0);
}


check_date();

?>