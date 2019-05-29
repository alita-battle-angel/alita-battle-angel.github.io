<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'cache-manager.php';

$config = parse_ini_file(__DIR__ . '/twitter.config.ini');

$http_headers = [
  "Authorization: Bearer {$config['BEARER_TOKEN']}"
];

$context = stream_context_create(array(
  "http" => array(
    "header" => implode('\n', $http_headers),
  )
));

if (!isset($_REQUEST['id'])) {
  http_response_code(400);
  die('id parameter is required');
}

$tweets = $_REQUEST['id'];

$hash = hash('md5', $tweets);
$file_name = "tweets-{$hash}.json";

header('Content-Type: application/json', true);
header("Access-Control-Allow-Origin: *");

if (is_cache_fresh($file_name, $config['LIFE_SPAN'])) {
  echo read_from_cache($file_name);
} else {

  $content = file_get_contents('https://api.twitter.com/1.1/statuses/lookup.json?tweet_mode=extended&id=' . $tweets, false, $context);
  echo update_cache($file_name, $content);
}


