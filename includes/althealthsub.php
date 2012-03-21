<h1>Alternative Medicine Magazine & Seacoast Vitamins Natural Health Newsletter</h1>
<?php 
if (!isset ($_REQUEST['email']) || strlen($_REQUEST['email'])<5) {
?> 
<table width="100%" border="0">
  <tr>
    <td width="4%"><img src="images/98_June_cover.jpg"></td>
    <td width="96%">
      <p>Alternative Medicine Magazine Readers - Subscribe <b>FREE</b> to the Seacoast Vitamins Natural Health Newsletter
      and receive these great offers:
      
      </p>
      <ul>
        <li>25% off your first order to Seacoast Vitamins</li>
        <li>Weekly Natural Health Newsletter</li>
        <li>Chance to win great prizes in our Allergy Relief Sweepstakes</li>
        <li>Complete privacy - we never share your email</li>
        <li>Much more...</li>
        
      </ul>
      <p>The Seacoast Natural Health Newsletter contains latest trends, natural health details, product information, special events,  
        promotions and valuable discounts only available to newsletter subscribers! 
      </p>
</td>
  </tr>
</table>
<form action="/alternativemedicine/" method="post">
  <table border="0" bgcolor="#CCCCCC" cellspacing="5" width="315">
    <tr>
      <td>
        <font face="Arial, Helvetica, sans-serif" size="2">
          <b>
            <font color="#FFFFFF">
              <font color="#000000">Email Address </font>
            </font>
          </b>
        </font>
      </td>
      <td>
        <input type="text" size="30" name="email">
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <input type="submit" value="Submit">
        </td>
    </tr>
  </table>
</form>
<?php 
}
else { 

$nlquery="insert into newsletter_emails(email) values('".tep_db_input($_REQUEST['email'])."')"; 
tep_db_query($nlquery);

?>

<p>Thanks! You are now subscribed to the Seacoast Vitamins Natural Health Newsletter! Use the following coupon code for great discounts.</p>
<p>Coupon Code: <b>ALTMED25</b> <br>
  Save 25% OFF Store Wide!
    </p><p>
  *Excludes Prostasol, Nordic Naturals and other products marked *. Limit one 
  coupon per customer. While supplies last. </p>
<p>
  <h2>
    Shop <a href="/">Seacoast Vitamins Now</a>!
  </h2>
</p>
<?php } ?>
