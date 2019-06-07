<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function my_autoloader ($class) {
  include __DIR__ . '/' . $class . '.class.php';
}

spl_autoload_register('my_autoloader');
