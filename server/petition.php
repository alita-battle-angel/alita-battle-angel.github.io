<?php
require_once 'autoload.php';
require_once 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

$file_name = 'petition-data.json';
function fetch_petition () {
  $http_headers = [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
  ];

  $handle = curl_init();

  $url = "https://www.change.org/p/robert-rodriguez-alita-battle-angel-part-2";
  curl_setopt($handle, CURLOPT_URL, $url);
  curl_setopt($handle, CURLOPT_HTTPHEADER, $http_headers);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

  $content = curl_exec($handle);
  curl_close($handle);

  $output_array = [];
  preg_match('/window\.changeTargetingData\s=\s([a-zA-z:\'",\s\d\n\t\{\}\-]*);?/m', $content, $output_array);
  return json_decode($output_array[1], true);
}

function save_petition ($data) {
  global $database;
  $database->save('cache', [
    'id' => $data['id'],
    'name' => 'petition',
    'value' => $data['value'],
    'updated_at' => NULL
  ]);
}

$petition_data = $database->fetchRow("SELECT * FROM cache WHERE cache.name = 'petition'");
$current_time = time();

if ($petition_data && $current_time - strtotime($petition_data['updated_at']) < 60) {
  response_text($petition_data['value']);
} else {
  $content = fetch_petition();
  save_petition([
    'id' => $petition_data['id'],
    'value' => json_encode($content)
  ]);

  response($content);
}

