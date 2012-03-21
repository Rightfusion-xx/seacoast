<?php
/*
  $Id: admin_notes_help.php,v 1.0 2004/11/10 12:30:35 popthetop Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
_________________________________________________________________
Admin Notes MODULE for osC Admin Side
By PopTheTop of www.popthetop.com
Original Code By: Robert Hellemans of www.RuddlesMills.com 
These are LIVE SHOPS - So please, no TEST accounts etc...
We will report you to your ISP if you abuse our websites!

*/

define('TEXT_GRAY_TITLE','Admin Notes Help');
define('ADMIN_NOTES_TITLE','Admin Notes v1.0');
define('AUTHOR','<br>Created by: PopTheTop');
define('TEXT_NOTE','<STRONG><FONT COLOR="RED">PLEASE NOTE:</FONT></STRONG><BR>Please ask any additional questions at the osC contributions forum.<br>Forum Thread Topic: <A HREF="http://forums.oscommerce.com/index.php?showtopic=119993" TARGET="_blank"><FONT COLOR="#0000FF">Admin Notes</FONT></A>');
define('TEXT_IC_ONE','<BR><br><STRONG>Note Title:</STRONG><BR>&nbsp;&nbsp;&nbsp;This is the name or title of the note you entered.<BR>&nbsp;&nbsp;&nbsp;Click on it to highlight it in Overview.<BR>&nbsp;&nbsp;&nbsp;Float your mouse over it to view the Category your note is in.<BR><BR><b>Buttons:</b><br>&nbsp;&nbsp;&nbsp;This is only a marker for your reference and does nothing<BR>&nbsp;&nbsp;&nbsp;but highlights a button to show the importance of your note.<BR><BR>&nbsp;&nbsp;&nbsp;Samples are:<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Important', 10, 10) . ' = Important<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'No so important', 10, 10) . ' = No so important<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_yellow.gif', 'Caution', 10, 10) . ' = Caution (Keep an eye on this one)<BR><BR><STRONG>Insert</STRONG>:<BR>&nbsp;&nbsp;&nbsp;Click this to add a new note.<BR><BR>The rest of it should be self explanatory...');
define('TEXT_CLOSE_WINDOW','<br><font color=red><b>Close Window</b></font>');
?>