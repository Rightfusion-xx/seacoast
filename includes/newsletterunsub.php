<?php 
if (!isset ($_REQUEST['email'])) {
?>
<p>You are about to unsubscribe from Seacoast Vitamins newsletters.</p>
<p>
  <h3>We are sorry to see you go.</h3>
</p>
<p> Our newsletter is designed to inform you with product information,</p> <p>special events and special promotions
that are only available to newsletter subscribers.
</p>
<form action="newsletterunsub.php" method="post">
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

$nlquery="insert into newsletter_removes(email) values('".tep_db_input($_REQUEST['email'])."')"; 
tep_db_query($nlquery);

?>

<p>You have be unsubscribe from Seacoast Vitamins' newsletter. We are sorry to see you go.</p>
<p>
  We hope to see you soon. 
</p>
<p>
  <h2>
    Shop <a href="/">Seacoast Vitamins</a>
  </h2>
</p>
<?php } ?>
