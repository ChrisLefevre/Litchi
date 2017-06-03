<?php
// Check for empty fields
if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   empty($_POST['phone'])     ||
   empty($_POST['message'])   ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "No arguments Provided!";
   return false;
   }

$infos = $sw->block("contact");

$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));
   
// Create the email and send the message
$to = $infos['mail_destinate']; 
$email_subject = $sw->_m("Website Contact Form")." : ".$name;
$email_body = $sw->_m("You have received a new message from your website contact form.").' '. $sw->_m("Here are the details:") 
            ."\n\n" .$sw->_m("Name:").' '. $name."\n\n" .$sw->_m("Email:").' '. $email_address. "\n\n" .$sw->_m("Phone:").' '. $phone. "\n\n" .$sw->_m("Message:")."\n" . $message;

$headers = "From: ".$email_address."\n"; 
$headers .= "Reply-To: $email_address";   
mail($to,$email_subject,$email_body,$headers);
return true;         
?>

