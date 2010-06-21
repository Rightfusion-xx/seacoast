<?php



$my_email = "webmaster@seacoastvitamins.com";
$continue = "/";

//  Initialise variables

$errors = array();

if($_SERVER['REQUEST_METHOD'] == "POST"){$form_input = $_POST;}elseif($_SERVER['REQUEST_METHOD'] == "GET"){$form_input = $_GET;}else{exit;}

// Remove leading whitespace from all values.

function recursive_array_check(&$element_value)
{

if(!is_array($element_value)){$element_value = ltrim($element_value);}
else
{

foreach($element_value as $key => $value){$element_value[$key] = recursive_array_check($value);}

}

return $element_value;

}

recursive_array_check($form_input);

// Check referrer is from same site.

if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}

// Check for a blank form.

function recursive_array_check_blank($element_value)
{

global $set;

if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}

}

}

recursive_array_check_blank($form_input);

if(!$set){$errors[] = "You cannot send a blank form";}

// Strip HTML tags from all fields.

function recursive_array_check2(&$element_value)
{

if(!is_array($element_value)){$element_value = strip_tags($element_value);}
else
{

foreach($element_value as $key => $value){$element_value[$key] = recursive_array_check2($value);}

}

return $element_value;

}

recursive_array_check2($form_input);



// Validate email field.

if(isset($form_input['email']) && !empty($form_input['email']))
{

if(preg_match("/`[\r\n]`/",$form_input['email'])){$errors[] = "You have submitted an invalid new line character";}

if(!preg_match('/^([a-z][a-z0-9_.-\/\%]*@[^\s\"\)\?<>]+\.[a-z]{2,6})$/i',$form_input['email'])){$errors[] = "Email address is invalid";}

}

// Display any errors and exit if errors exist.

if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}

// Build message.

function build_message($request_input){if(!isset($message_output)){$message_output = "";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!is_numeric($key)){$message_output .= "\n\n".$key.": ".build_message($value);}else{$message_output .= "\n\n".build_message($value);}}}return $message_output;}

$message = build_message($form_input);

$message = $message . "\n\n-- \nThank You For Signing up for Seacoast Vitamins Newsletter";

$message = stripslashes($message);

$subject = "FormToEmail Comments";

$headers = "From: " . $form_input['email'] . "\n" . "Return-Path: " . $form_input['email'] . "\n" . "Reply-To: " . $form_input['email'] . "\n";

mail($my_email,$subject,$message,$headers);

?>
<div>
  <center>
    <p>
      <b>Thank you!</b>
      <br>
        You have successfully Signed Up For Seacoast Vitamins Newsletter!
      </p>
    <table width="61%" border="0" height="8">
      <tr>
        <td width="28%">
          <img src="news25.png">
        </td>
        <td width="72%">
          <p>
            <b>
              Newsletter Sign Up Coupon!<br>
                <font color="#FF0000">
                  Coupon Code: &quot;SeaNews25&quot;<br>
                  </font>*This coupon only applies to order over $100.00<br>
                  *This coupon does not apply to any items that end with an *<br>
                    *You may only use this coupon once.
            </b>
          </p>
        </td>
      </tr>
    </table>
    <p>
      <a href="
        <"?php print="" $continue=""; ?>">Click here to continue
      </a>
    </p>
  </center>
</div>

