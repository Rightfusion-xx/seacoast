<?php
  /*
  $Id: cvv2.php, v2.0 2003/05/03 14:14:14 waza04_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce / Warren Ashcroft

  Support:
  oscdev@ukcomputersystems.com
  waza04@hotmail.com (MSN Messenger)

  Paypal Donations:
  paypal@ukcomputersystems.com

  Web:
  http://www.ukcomputersystems.com/

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'CVV2 Information');
define('HEADING_TITLE', 'What is CVV2?');
define('VISA_SET_UK', '<b>Visa / MasterCard / JCB / Switch / Solo</b>');
define('VISA_SET_US', '<b>Visa / MasterCard / JCB');
define('AMEX_SET_US', '<b>American Express / Diners Club</b>');

define('TEXT_INFORMATION_VISA', '
<table id="Table2" cellSpacing="2" cellPadding="0" width="550" align="center" border="0">
  <tr>
    <td align="left">
    <table id="Table3" cellSpacing="0" cellPadding="3" width="550" border="0">
      <tr>
        <td><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>
        <img border="0" src="images/visasecurity.gif" width="334" height="78"></b></font></span><p><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>CVV2</b> (Card
        Verification Value), <b>CVC2</b> (Card Validation Code) and <b>CID</b>
        (Card Identification #) codes are a new authentication scheme
        established by credit card companies to further efforts towards reducing
        fraud for internet transactions. It consists of requiring a card holder
        to enter the CVV2, CVC2 or CID codes in at transaction time to verify
        that the card is on hand. You can find these codes as shown in pictures
        below. If your credit card does not have one of the codes, please
        contact your credit card company to get a new credit card to allow us to
        process your credit card in secure way. </font>
        </span>
        </p>
        <p><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>Visa,
        MasterCard, JCB, Switch and Solo</b> cards have CVV2 and CVC2 security codes
        that are 3 digits in length, and they are located on the back of the
        card and are the last 3 digits (see diagram below).</font></span></p>
        <p>
      </tr>
    </table>
    </td>
  </tr>
</table>');

define('TEXT_INFORMATION_AMEX', '
<table id="Table2" cellSpacing="2" cellPadding="0" width="550" align="center" border="0">
  <tr>
    <td align="left">
    <table id="Table3" cellSpacing="0" cellPadding="3" width="550" border="0">
      <tr>
        <td><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>
        <img border="0" src="images/visasecurity.gif" width="334" height="78"></b></font></span><p><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>CVV2</b> (Card
        Verification Value), <b>CVC2</b> (Card Validation Code) and <b>CID</b>
        (Card Identification #) codes are a new authentication scheme
        established by credit card companies to further efforts towards reducing
        fraud for internet transactions. It consists of requiring a card holder
        to enter the CVV2, CVC2 or CID codes in at transaction time to verify
        that the card is on hand. You can find these codes as shown in pictures
        below. If your credit card does not have one of the codes, please
        contact your credit card company to get a new credit card to allow us to
        process your credit card in secure way. </font>
        </span>
        </p>
        <p><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>Visa,
        MasterCard, AmEx, JCB and Diners Club</b> cards have CVV2, CVC2 and CID security codes
        that are 3/4 digits in length, and they are located on the front and back of the
        card. (see diagrams below).</font></span></p>
        <p>
      </tr>
    </table>
    </td>
  </tr>
</table>');

define('TEXT_INFORMATION_UKUS', '
<table id="Table2" cellSpacing="2" cellPadding="0" width="550" align="center" border="0">
  <tr>
    <td align="left">
    <table id="Table3" cellSpacing="0" cellPadding="3" width="550" border="0">
      <tr>
        <td><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>
        <img border="0" src="images/visasecurity.gif" width="334" height="78"></b></font></span><p><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>CVV2</b> (Card
        Verification Value), <b>CVC2</b> (Card Validation Code) and <b>CID</b>
        (Card Identification #) codes are a new authentication scheme
        established by credit card companies to further efforts towards reducing
        fraud for internet transactions. It consists of requiring a card holder
        to enter the CVV2, CVC2 or CID codes in at transaction time to verify
        that the card is on hand. You can find these codes as shown in pictures
        below. If your credit card does not have one of the codes, please
        contact your credit card company to get a new credit card to allow us to
        process your credit card in secure way. </font>
        </span>
        </p>
        <p><span class="smallText"><font face="Arial, Helvetica, sans-serif" size="2"><b>Visa,
        MasterCard, AmEx, JCB, Switch, Solo and Diners Club</b> cards have CVV2, CVC2 and CID security codes
        that are 3/4 digits in length, and they are located on the front and back of the
        card. (see diagrams below).</font></span></p>
        <p>
      </tr>
    </table>
    </td>
  </tr>
</table>');
?>
