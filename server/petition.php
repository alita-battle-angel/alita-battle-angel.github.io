<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'cache-manager.php';

$context = stream_context_create(array(
  "http" => array(
    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
  )
));

$file_name = 'petition-data.json';

header('Content-Type: application/json', true);
header("Access-Control-Allow-Origin: *");

if (is_cache_fresh($file_name, 60)) {
  echo read_from_cache($file_name);
} else {
  $content = file_get_contents('https://www.change.org/p/robert-rodriguez-alita-battle-angel-part-2', false, $context);
  preg_match('/window\.changeTargetingData\s=\s([a-zA-z:\'",\s\d\n\t\{\}\-]*);?/m', $content, $output_array);
  echo update_cache($file_name, $output_array[1]);
}

