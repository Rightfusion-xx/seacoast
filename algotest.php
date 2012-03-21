<?php
    include ('/includes/application_top.php');
  include ('/includes/classes/encryption.php') ;
  
  $orig_message='test message !';
  
  $message = Encryption::encrypt_data($orig_message,'twofish',ENCRYPTION_KEY);
  echo $message;
  
  echo '<br/>';
  
  $message= Encryption::decrypt_data($message,'twofish',ENCRYPTION_KEY);
  
  echo $message,'<br/>';
  
  echo 'Original Message=',$orig_message;
  
  echo 'testing massSearchand replace';
  
  echo getHubKeywordsAndRewriteContent("this is a <a >test</a> of the wobenzym wobenzyme testing  test test-emergency broadcast system.");
  
  
?>
