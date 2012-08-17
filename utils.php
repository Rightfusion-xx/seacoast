<?php

/*
	(c) Kaavren
*/

include_once("swconfig.php");

function error_report($errcode) {
  global $err;
  if ($errcode==10) { 		// mysql error
    $r=mysql_error();
  }
  else {
/*    $err[11] = "Password fields must be identical";
    $err[12] = "You must fill all fields";
    $err[13] = "Undefined code";
    $err[15] = "No such email or password mismatch";

    $err[21] = "Name field is too long";
    $err[22] = "Password field is too long";
    $err[23] = "Address field is too long";
    $err[24] = "Country field is too long";
    $err[25] = "State field is too long";
    $err[26] = "City field is too long";
    $err[27] = "E-Mail field is too long";
    $err[28] = "ZIP field is too long";
    $err[29] = "Phone field is too long";

    $err[31] = "Can't upload html-file";
    $err[32] = "Can't upload attached file";

    $err[40] = "Such user is already enroll in this sweepstake";
*/

    $r=$err[$errcode];
  }

    echo "<b>Error:</b>"."$r";

  return 0;
}

  function today_date() {
    $ret=date("m/d/y");
//    $ret=date("d/m/y",time());

    return($ret);
  }

  function today_time() {
    $ret=date("G:i:s",time());
    return($ret);
  }

  function remote_ip()
  {
    global $REMOTE_ADDR;
    return($REMOTE_ADDR);
  }


class class_mail {


var $mail_boundary = "----_=_NextPart_000_01C1.94F.653432C1";
var $mail_boundary2="----_=_NextPart_001_01C1.94F.653432C1"; 
var $mail_priority=0;   

var $mail_from;       
var $mail_to;          
var $mail_subj;       

var $mail_body_plain;  
var $mail_body_html;   
var $mail_body;      
var $mail_attach;        
var $mail_attach_type; 

  function mailer($from, $to, $subj, $body) {
    $from="From: $from\nReply-To: $from\nContent-Type: text/plain;    charset=\"koi8-r\"\nContent-Transfer-Encoding: 8bit";
//    $from=convert_cyr_string($from,"w","k");
//    $to=convert_cyr_string($to,"w","k");
//    $subj=convert_cyr_string($subj,"w","k");
//    $body=convert_cyr_string($body,"w","k");
    mail($to,$subj,$body,$from);
  }

  function mail_to_root($message) {
    global $REQUEST_URI;
    for($i=0;$i<count($this->EMAIL_ADMIN);$i++) {
      $this->mailer("Robot<admin@floridakeyshosting.com>", $this->EMAIL_ADMIN[$i], "Fatal error!", "Error:    $message\nDateTime:".$this->today_date()." ".$this->today_time()."\n"."Remote IP:".$this->remote_ip()."\n\nURI: $REQUEST_URI\n\nSQL_QUERY:".$this->sql_query."\nSQL_ERROR:".$this->sql_err);
      usleep(100000);
    }
  }

function mail_header()
{
 $header="Reply-To: ".$this->mail_from. "\n";
 $header.="MIME-Version: 1.0\n";
 $header.="Content-Type: multipart/mixed; \n boundary=\"".$this->mail_boundary. "\" \n";
// $header.="X-Priority: 0\n";

 return($header);
}

function mail_body($html, $plain)
{
 $this->mail_body_html=$html;
 $this->mail_body_plain=$plain;
}

// Attaching Data

function mail_attach($name, $type, $data)
{
 $this->mail_attach_type[$name]=$type;
 $this->mail_attach[$name]=$data;

// echo "$data";
}

// Attaching file from disk

function mail_fileattach($path, $type,$filename)
{
 $name=ereg_replace("/(.+/)/","",$path);
 if(!$r=fopen($path,'r')) return(1);

// echo "<h1>$name</h1>";
 
 $this->mail_attach($filename, $type, fread($r,filesize($path)));
 fclose($r);

 return(0);
}

function mail_body_create($attach,$filename)
{
 $this->mail_body="\n\n";
// $this->mail_body.=$this->mail_body_plain;

 if(strlen($this->mail_body_html)>0) // html-version
 {
//   $this->mail_body.="--".$this->mail_boundary."\n ";
//   $this->mail_body.="Content-Type: multipart/alternative boundary=".$this->mail_boundary2."\n\n";
//   $this->mail_body.=$this->mail_body_plain."\n";
//   $this->mail_body.="--".$this->mail_boundary."\n";
//   $this->mail_body.="Content-Type: text/plain\n";
//   $this->mail_body.="Content-Transfer-Encoding: quoted-printable\n\n";
//   $this->mail_body.=$this->mail_body_plain."\n\n";
   $this->mail_body.="--".$this->mail_boundary."\n";
   $this->mail_body.="Content-Type: text/html\n";
   $this->mail_body.="Content-Transfer-Encoding: quoted-printable\n\n";
   $this->mail_body.=$this->mail_body_html."\n\n";
//   $this->mail_body.="--".$this->mail_boundary."--\n";

 } else // no html-version
 {
   $this->mail_body.="--".$this->mail_boundary."\n"; 
   $this->mail_body.="Content-Type: text/plain\n";
   $this->mail_body.="Content-Transfer-Encoding: quoted-printable\n\n";
   $this->mail_body.=$this->mail_body_plain."\n\n--";
   $this->mail_body.=$this->mail_boundary. "\n";
 }

 if ($attach) {
   reset($this->mail_attach_type);
   while(list($name, $content_type)=each($this->mail_attach_type) ) 
   {
     $this->mail_body.="\n--".$this->mail_boundary."\n";
     $this->mail_body.="Content-Type: $content_type\n";
     $this->mail_body.="Content-Transfer-Encoding: base64\n";
     $this->mail_body.="Content-Disposition: attachment;";
     $this->mail_body.="filename=\"$filename\"\n\n";
     $this->mail_body.=chunk_split(base64_encode($this->mail_attach[$filename]))."\n";
   }
//   $this->mail_body.= "--".$this->mail_boundary. "--\n";
 }

 $this->mail_body.= "--".$this->mail_boundary. "--\n";

 return(0);
}

function html_header()
{
 header( "Cache-Control: max-age=". $this->CACHE_TIME_ALL.", must-revalidate" );
 header( "Last-Modified: " . gmdate("D, d M Y H:i:s", time()-3600) . " GMT");
 header( "Expires: " . gmdate("D, d M Y H:i:s", time()+$this->CACHE_TIME_ALL) . " GMT");
 header( "Content-type:text/html");
}

}


?>