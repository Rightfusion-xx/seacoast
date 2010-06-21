Sweepstakes script (c) Kaavren, 2004

Distrubuted exclusivelty by http://majorscripts.com
and htttp://cashcowscripts.com 
and http://sellingscripts.com


Files description:

ADMIN.PHP - contains all administration functions. (Protected by password - set in config.php)
CHECK.PHP - very important script. It must be run every day to determine winner, notice admin and close Sweeps
CONFIG.PHP - all options can be changed here by admin. Some options (such as MySQL options must be set before run script)
REGISTER.PHP - new user registration
SETUP.PHP - Must be run after set options in config file (before script runs)
SWEEPSTAKES.PHP - main file for user. Here he can view list of available sweepstakes, winners list and enroll in sweepstakes (after registration)
TERMS.PHP - terms & conditions page
UNSUBSCRIBE.PHP - Unsubscribe user

Forms description:

All files in inc/ directory can be changed by admin before or after script installation.
Please, don't change 'name' atributes in the forms because they will be used by scripts.
Also don't change expressions such as "<<<confirmation email>>>" because they will automaticallly changes by script than it will must be done.

CONFIRM_MAIL.INC - Plain/text message that will be sent to user to confirm registration. (No html here)
ENROLL_FORM.INC - Form that user must fill to enroll in sweepstakes
REGISTRATION FORM - Form that user must fill to register in the system.
HEADER.PHP and FOOTER.PHP - header and footer for main sweepstakes file -  sweepstakes.php
TERMS.INC.PHP - Terms and Conditions form
UNSUBSCRIBE_FORM.INC - Form that user must fill to unsubscribe
WINNER_MAIL.INC -  Plain/text message that will be sent to sweepstake winner. (No html here)
WINNER_DOWNLOADABLE.INC - form that user will see if he won downloadable prize
WINNER_CASH.INC - form that user will see if he won cash prize
WINNER_ITEMS.INC - form that user will see if he won items prize
AUTH_FORM.INC.PHP - user authorisation form

data/ directory - for uploaded files