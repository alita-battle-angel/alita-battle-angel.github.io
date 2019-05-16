<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$context = stream_context_create(array(
  "http" => array(
    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
  )
));

$petition_info = 'petition-data.json';
$file_path = __DIR__ . "/$petition_info";

if (!file_exists($file_path)) {
  $handle = fopen($file_path, 'w') or die('Cannot open file:  ' . $file_path);
  fclose($handle);
}

$handle = fopen($petition_info, 'r') or die('Cannot open file:  ' . $petition_info);
$last_modified_time = filemtime($file_path) or 0;
$current_time = time();
fclose($handle);

if ($current_time - $last_modified_time > 3600 || filesize($file_path) === 0) {
  $content = file_get_contents('https://www.change.org/p/robert-rodriguez-alita-battle-angel-part-2', false, $context);
  preg_match('/window\.changeTargetingData\s=\s([a-zA-z:\'",\s\d\n\t\{\}\-]*);?/m', $content, $output_array);
  $handle = fopen($file_path, 'w') or die('Cannot open file:  ' . $file_path);
  fwrite($handle, $output_array[1]);
  fclose($handle);
}

clearstatcache();
$size = filesize($file_path);
if ($size > 0) {
  $handle = fopen($petition_info, 'r') or die('Cannot open file:  ' . $file_path);
  header('Content-Type: application/json', true);
  header("Access-Control-Allow-Origin: *");
  echo fread($handle, $size);
  fclose($handle);
}




