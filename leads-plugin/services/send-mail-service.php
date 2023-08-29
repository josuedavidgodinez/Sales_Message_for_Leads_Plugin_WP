<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";

/**
 * Funcion que obtiene la informacion de un 
 * producto
 */
function wp_SendMail($recipient_email,$subject, $email_message)
{
  
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
    );
    // Send the email
    $wp_mail_result = wp_mail($recipient_email,$subject, $email_message, $headers);

    if ($wp_mail_result) {
       return true;
    } else {
       return false;
    }
}
