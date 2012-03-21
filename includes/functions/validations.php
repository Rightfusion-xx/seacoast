<?php

  function tep_validate_email($email) {

    $mail_pat = '/^[A-Z0-9]+(\.?[A-Z0-9_-]+)*@(?:[A-Z0-9-]+\.)+[A-Z]{2,6}$/i';
    
    if (preg_match($mail_pat, $email))
    {
      return(true);
    }
    else
    {
      return(false);
    }
  }
?>
