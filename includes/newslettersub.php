<?php 
if (!isset ($_REQUEST['email'])) {
?>
<p>You are about to join Seacoast Vitamins!</p>
<p>
  Upon joining, you will have access to thousands of natural health products, reviews
  and <b>Special Site Discounts!</b>
</p>
<p style="color:red;font-size:14pt;">
  Seacoast Vitamins newsletter readers get the latest trends and research on what other people are buying!
</p>
  Our newsletter contains product information, special events, special promotions
  and valuable discounts only available to newsletter subscribers! Your email 
  is 100% safe and secure, <b>never shared or sold.</b>
</p>
<form action="newslettersub.php" method="post">
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

<p>Thank you for joining Seacoast Vitamins.</p>
<p style="color:red;font-size:14pt;">
  You will receive your first newsletter in about a week.
  
</p>
<p>
  <h2>
    Shop <a href="/">Seacoast Vitamins</a>
  </h2>
</p>
<!-- Google Code for Newsletter Signup Conversion Page -->
<script language="JavaScript" type="text/javascript">
<!--
var google_conversion_id = 1064963330;
var google_conversion_language = "en_US";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
if (0.0) {
  var google_conversion_value = 0.0;
}
var google_conversion_label = "zpDrCIKKYRCCmuj7Aw";
//-->
</script>
<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/1064963330/?value=0.0&amp;label=zpDrCIKKYRCCmuj7Aw&amp;script=0"/>
</noscript>

<?php } ?>
