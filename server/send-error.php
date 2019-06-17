<?php
require_once 'autoload.php';

$date = new DateTime();

$to = 'eeliya.rasta@gmail.com';
$subject = 'I Do Not Stand ' . $date->format('d-m-Y H:i:s');
$message = $_INPUT['message'] . ' - The End';
$header = "From: Alita <noreply@ewcms.org>\n";
$header .= "MIME-Version: 1.0\n";
$header .= "Content-Type: text/plain; charset=utf-8\n";


$success = mail($to, $subject, $message, $header);
if (!$success) {
  echo response(['messsage' => error_get_last()['message']]);
} else {
  echo response(['message' => 'success ' . $_INPUT['message']]);
}
