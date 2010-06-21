<?php

  function CCValidationSolution($Number) {
    global $CardName, $CardNumber, $language;

    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CCVAL_FUNCTION); 


// Get rid of spaces and non-numeric characters.
    $Number = OnlyNumericSolution($Number);

// Do the first four digits fit within proper ranges? If so, who's the card issuer and how long should the number be?
    $NumberLeft = substr($Number, 0, 4);
    $NumberLength = strlen($Number);

    if ( ($NumberLeft >= 3000) && ($NumberLeft <= 3059) ) {
      $CardName = 'Diners Club';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 3600) && ($NumberLeft <= 3699) ) {
      $CardName = 'Diners Club';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 3800) && ($NumberLeft <= 3889) ) {
      $CardName = 'Diners Club';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 3400) && ($NumberLeft <= 3499) ) {
      $CardName = 'American Express';
      $ShouldLength = 15;
    } elseif ( ($NumberLeft >= 3700) && ($NumberLeft <= 3799) ) {
      $CardName = 'American Express';
      $ShouldLength = 15;
    } elseif ( ($NumberLeft >= 3528) && ($NumberLeft <= 3589) ) {
      $CardName = 'JCB';
      $ShouldLength = 16;
    } elseif ( ($NumberLeft >= 3890) && ($NumberLeft <= 3899) ) {
      $CardName = 'Carte Blache';
      $ShouldLength = 14;
    } elseif ( ($NumberLeft >= 4000) && ($NumberLeft <= 4999) ) {
      $CardName = 'Visa';
      if ($NumberLength > 14) {
        $ShouldLength = 16;
      } elseif ($NumberLength < 14) {
        $ShouldLength = 13;
      }
    } elseif ( ($NumberLeft >= 5100) && ($NumberLeft <= 5599) ) {
      $CardName = 'MasterCard';
      $ShouldLength = 16;
    } elseif ($NumberLeft == 5610) {
      $CardName = 'Australian BankCard';
      $ShouldLength = 16;
    } elseif ($NumberLeft == 6011) {
      $CardName = 'Discover/Novus';
      $ShouldLength = 16;
    } else {
      $cc_val = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, $NumberLeft);
      return $cc_val;
    }

// Is the number the right length?
    if ($NumberLength <> $ShouldLength) {
      $Missing = $NumberLength - $ShouldLength;
      if ($Missing < 0) {
        $cc_val = sprintf(TEXT_CCVAL_ERROR_INVALID_NUMBER, $CardName, $Number);
      } else {
        $cc_val = sprintf(TEXT_CCVAL_ERROR_INVALID_NUMBER, $CardName, $Number);
      }

      return $cc_val;
    }

    $card_info = tep_db_query("select c.blacklist_card_number from " . TABLE_BLACKLIST . " c where c.blacklist_card_number = '" . $Number . "'");
      if (tep_db_num_rows($card_info) == 0) { // card not found in database
        return true;
      } else {
        $cc_val = sprintf(TEXT_CCVAL_ERROR_INVALID_NUMBER, $CardName, $Number);
      return $cc_val;
      }

// Does the number pass the Mod 10 Algorithm Checksum?
    if (Mod10Solution($Number)) {
     $CardNumber = $Number;
     return true;
    } else {
      $cc_val = sprintf(TEXT_CCVAL_ERROR_INVALID_NUMBER, $CardName, $Number);
      return $cc_val;
    }
  }

  function OnlyNumericSolution($Number) {
// Remove any non numeric characters.
// Ensure number is no more than 19 characters long.
    return substr(ereg_replace('[^0-9]', '', $Number) , 0, 19);
  }

  function Mod10Solution($Number) {
    $NumberLength = strlen($Number);
    $Checksum = 0;

// Add even digits in even length strings or odd digits in odd length strings.
    for ($Location = 1-($NumberLength%2); $Location<$NumberLength; $Location+=2) {
      $Checksum += substr($Number, $Location, 1);
    }

// Analyze odd digits in even length strings or even digits in odd length strings.
    for ($Location = ($NumberLength%2); $Location<$NumberLength; $Location+=2) {
      $Digit = substr($Number, $Location, 1) * 2;
      if ($Digit < 10) {
        $Checksum += $Digit;
      } else {
        $Checksum += $Digit - 9;
      }
    }

// Is the checksum divisible by ten?
    return ($Checksum % 10 == 0);
  }

  function ValidateExpiry ($month, $year) {
    $cc_val = '';
    $year = '20' . $year;

    if (date('Y') == $year) {
      if (date('m') <= $month) {
        $cc_val = '1';
      } else {
        $cc_val = sprintf(TEXT_CCVAL_ERROR_INVALID_DATE, $month, $year);
      }
    } elseif (date('Y') > $year) {
        $cc_val = sprintf(TEXT_CCVAL_ERROR_INVALID_DATE, $month, $year);
    } else {
      $cc_val = '1';
    }

    return $cc_val;
  }
?>