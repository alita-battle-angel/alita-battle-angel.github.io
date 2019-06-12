<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');

function my_autoloader ($class) {
  include __DIR__ . '/' . $class . '.class.php';
}

spl_autoload_register('my_autoloader');

$input_json_string = file_get_contents('php://input');
$_INPUT = json_decode($input_json_string, TRUE);

function response ($content, $code = 200) {
  http_response_code($code);
  echo json_encode($content);
  die;
}

function response_text ($content, $code = 200) {
  http_response_code($code);
  echo $content;
  die;
}
