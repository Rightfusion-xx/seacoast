<?php
$system_login=true;
  set_time_limit(1200);
  $_SERVER['DOCUMENT_ROOT']='..';
  require('includes/application_top.php');

  $tweet=simplexml_load_file('http://twitter.com/users/show.xml?screen_name=seacoastvits');

  $tweetquery=tep_db_query('select tweet_id from tweets where tweet_id="'.$tweet->status[0]->id[0].'"');

  $result=tep_db_fetch_array($tweetquery);

  if(!$result['tweet_id'] && (int)$tweet->status[0]->id[0]>0 && strpos($tweet->status[0]->text[0], '#scv')>0)
  {
    tep_db_query('insert into tweets(tweet_id, tweet_message) values("'.$tweet->status[0]->id[0].'","'.tep_db_input($tweet->status[0]->text[0]).'")');
  }
  //echo $tweet;

?>