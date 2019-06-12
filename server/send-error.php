<?php
require_once 'autoload.php';

$to = 'eeliya.rasta@gmail.com';
$subject = 'I Do Not Stand - Alita Army Form';
$message = $_INPUT['message'];
$headers = 'From: noreplay@ewcms.org' . "\r\n" . 'Reply-To: noreplay@ewcms.org' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
