<?php
/*

This script was created by Bryan Helmig at midmodesign.com. It is licensed under http://creativecommons.org/licenses/by-nc-sa/3.0/us/. 
1)   A quick primer: place this on your server. Create a form like below:
--------------------------------------------------------------------------------------------------------
<form action="sendmail.php" method="post" id="contactForm">
	<p>Name:</p> <input type="text" name="name" value="" id="name" />
	<p>Email:</p> <input type="text" name="email" value="" id="email" />
	<p>Telephone:</p> <input type="text" name="tele" value="" id="tele" />
	<span style="display:none;"><p>Honeypot:</p> <input type="text" name="last" value="" id="last" /></span>
	<p>Message:</p> <textarea rows="5" name="message"></textarea>
	<input type="submit" value="Send Message" />
</form
--------------------------------------------------------------------------------------------------------
2)   This will work fine for a standard form. If you want ajax power, add this div above or below and hide it with css.
--------------------------------------------------------------------------------------------------------
<div class="message"><div id="alert"></div></div>
--------------------------------------------------------------------------------------------------------
3)   And add this to the head: Also download $-latest.pack.js and $.form.js and point to those appropriately.
--------------------------------------------------------------------------------------------------------
<script type="text/javascript" src="$-latest.pack.js"></script>
<script type="text/javascript" src="$.form.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
var options = { 
target:        '#alert',
beforeSubmit:  showRequest,
success:       showResponse
}; 
$('#contactForm').ajaxForm(options); 
}); 
function showRequest(formData, jqForm, options) { 
var queryString = $.param(formData); 
return true; 
} 
function showResponse(responseText, statusText)  {  
} 
$.fn.clearForm = function() {
  return this.each(function() {
	var type = this.type, tag = this.tagName.toLowerCase();
	if (tag == 'form')
	  return $(':input',this).clearForm();
	if (type == 'text' || type == 'password' || tag == 'textarea')
	  this.value = '';
	else if (type == 'checkbox' || type == 'radio')
	  this.checked = false;
	else if (tag == 'select')
	  this.selectedIndex = -1;
  });
};
</script>
--------------------------------------------------------------------------------------------------------

Boom. There it is. 
*/

//        Who you want to recieve the emails from the form. (Hint: generally you.)
$sendto = 'you@yourdomain.com';

//        The subject you'll see in your inbox
$subject = 'A message from your website';

//        Message for the user when he/she doesn't fill in the form correctly.
$errormessage = 'Looks like you\'re missing some info. Try again.';

//        Message for the user when he/she fills in the form correctly.
$thanks = "Thanks for the email. We'll get back to you as soon as we can.";

//        Message for the bot when it fills in in at all.
$honeypot = "You filled in the honeypot! If you're human, try again!";

//        Various messages displayed when the fields are empty.
$emptyname =  'Entering your name?';
$emptyemail = 'Entering your email address?';
$emptytele = 'Entering your telephone number?';
$emptymessage = 'Entering a message?';

//       Various messages displayed when the fields are incorrectly formatted.
$alertname =  'Entering your name using only the standard alphabet?';
$alertemail = 'Entering your email in this format: <i>name@example.com</i>?';
$alerttele = 'Entering your telephone number in this format: <i>555-555-5555</i>?';
$alertmessage = "Making sure you aren't using any parenthesis or other escaping characters in the message? Most URLS are fine though!";

// --------------------------- Thats it! don't mess with below unless you are really smart! ---------------------------------

//Setting used variables.
$alert = '';
$pass = 0;

// Sanitizing the data, kind of done via error messages first. Twice is better!
function clean_var($variable) {
    $variable = strip_tags(stripslashes(trim(rtrim($variable))));
  return $variable;
}

//The first if for honeypot.
if ( empty($_REQUEST['last']) ) {

	// A bunch of if's for all the fields and the error messages.
	if ( empty($_REQUEST['name']) ) {
		$pass = 1;
		//$alert .= "<li>" . $emptyname . "</li>";
		$alert .= "<script>jQuery(\"#name\").addClass(\"error\");</script>";
	} elseif ( ereg( "[][{}()*+?.\\^$|]", $_REQUEST['name'] ) ) {
		$pass = 1;
		//$alert .= "<li>" . $alertname . "</li>";
	}
	if ( empty($_REQUEST['email']) ) {
		$pass = 1;
		//$alert .= "<li>" . $emptyemail . "</li>";
		$alert .= "<script>jQuery(\"#email\").addClass(\"error\");</script>";
	} elseif ( !eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $_REQUEST['email']) ) {
		$pass = 1;
		//$alert .= "<li>" . $alertemail . "</li>";
	}
	/*if ( empty($_REQUEST['tele']) ) {
		$pass = 1;
		//$alert .= "<li>" . $emptytele . "</li>";
		$alert .= "<script>jQuery(\"#tele\").addClass(\"error\");</script>";
	} elseif ( !ereg( "\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}", $_REQUEST['tele'] ) ) {
		$pass = 1;
		$alert .= "<li>" . $alerttele . "</li>";
	}*/
	if ( empty($_REQUEST['message']) ) {
		$pass = 1;
		//$alert .= "<li>" . $emptymessage . "</li>";
		$alert .= "<script>jQuery(\"#message\").addClass(\"error\");</script>";
	} elseif ( ereg( "[][{}()*+?\\^$|]", $_REQUEST['message'] ) ) {
		$pass = 1;
		//$alert .= "<li>" . $alertmessage . "</li>";
	}

	//If the user err'd, print the error messages.
	if ( $pass==1 ) {

	//This first line is for ajax/javascript, comment it or delete it if this isn't your cup o' tea.
	//echo "<script>$(\".message\").hide(\"slow\").show(\"slow\"); </script>";
	echo $errormessage;
	echo $alert;

	// If the user didn't err and there is in fact a message, time to email it.
	} elseif (isset($_REQUEST['message'])) {
	    
		//Construct the message.
	    $message = "From: " . clean_var($_REQUEST['name']) . "\n";
		$message .= "Email: " . clean_var($_REQUEST['email']) . "\n";
	    //$message .= "Telephone: " . clean_var($_REQUEST['tele']) . "\n";
	    $message .= "Message: \n" . clean_var($_REQUEST['message']);
	    $header = 'From:'. clean_var($_REQUEST['email']);
	    
	//Mail the message - for production
		mail($sendto, $subject, $message, $header);
	//This is for javascript, 
		echo "<script>jQuery(':input').clearForm();</script>";
		//echo $thanks;
		echo "<script>jQuery(\"#name\").removeClass(\"error\");jQuery(\"#email\").removeClass(\"error\");jQuery(\"#tele\").removeClass(\"error\");jQuery(\"#message\").removeClass(\"error\");</script>";
		die();

//Echo the email message - for development
		//echo "<br/><br/>" . $message;

	}
	
//If honeypot is filled, trigger the message that bot likely won't see.
} else {
	echo "<script>$(\".message\").hide(\"slow\").show(\"slow\"); </script>";
	echo $honeypot;
}
?>
