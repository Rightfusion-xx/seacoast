<?php

include_once ("config.php");

$link = mysql_connect ($sql_host, $sql_username, $sql_passwd) or die ("Could not connect");
@mysql_select_db($basename, $link);


$r = mysql_query (
		"create table links (
		id int unsigned not null AUTO_INCREMENT,
		prize_link varchar(255) not null,
		prize_id int not null,
		primary key (id)
		)", $link);
if ($r) echo "Table <em>links</em> created.<BR>\n";
else {
  $r=mysql_error();
  echo "<br><b>Error while links table create:</b>$r<br>";
}


$r = mysql_query (
		"create table sweepstakes (
		id int unsigned not null AUTO_INCREMENT,
		date_begins varchar(8) not null,
		date_ends varchar(8) not null,
		period enum('W','M','N') not null default 'N',
		enable enum('Y','N') not null default 'Y',
		free enum('Y','N') not null default 'N',
		places int not null default 1,
		primary key (id)
		)", $link);
if ($r) echo "Table <em>sweepstakes</em> created.<BR>\n";
else {
  $r=mysql_error();
  echo "<br><b>Error while sweepstakes table create:</b>$r<br>";
}


$r = mysql_query (
		"create table prizes (
		id int unsigned not null AUTO_INCREMENT,
		prize_name varchar(100) not null,
		prize_type enum('C','I','D') not null default 'C',
		winner_id int unsigned,
		sweepstake_id int unsigned not null,
		place int not null,
		primary key (id)
		)", $link);
if ($r) echo "Table <em>prizes</em> created.<BR>\n";
else {
  $r=mysql_error();
  echo "<br><b>Error while prizes table create:</b>$r<br>";
}


$r = mysql_query (
		"create table players (
		id int unsigned not null AUTO_INCREMENT,
		email varchar(100) not null,
		sweepstake_id int unsigned not null,
		primary key (id)
		)", $link);
if ($r) echo "Table <em>players</em> created.<BR>\n";
else {
  $r=mysql_error();
  echo "<br><b>Error while players table create:</b>$r<br>";
}



$r = mysql_query (
		"create table users (
		id int unsigned not null AUTO_INCREMENT,
		user_name varchar(50) not null,
		user_password varchar(50) not null,
		user_country varchar(50) not null,
		user_city varchar(50) not null,
		user_state varchar(50) not null,
		user_address varchar(50) not null,
		user_zip varchar(50) not null,
		user_phone varchar(20) not null,
		user_email varchar(100) not null,
		activation_code varchar(20) null,
		primary key (id)
		)", $link);
if ($r) echo "Table <em>users</em> created.<BR>\n";
else {
  $r=mysql_error();
  echo "<br><b>Error while users table create:</b>$r<br>";
}


$r = mysql_query (
		"create table mailing_list (
		id int unsigned not null AUTO_INCREMENT,
		email varchar(100) not null,
		primary key (id)
		)", $link);
if ($r) echo "Table <em>mailing_list</em> created.<BR>\n";
else {
  $r=mysql_error();
  echo "<br><b>Error while mailing_list table create:</b>$r<br>";
}

?>