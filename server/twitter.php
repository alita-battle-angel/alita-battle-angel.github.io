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

function extract_in_replay_to_status_ids ($tweets_list = []) {
  $ids = [];
  foreach ($tweets_list as $tweet) {
    if (isset($tweet['in_reply_to_status_id_str'])) {
      $ids[] = $tweet['in_reply_to_status_id_str'];
    }
  }

  return $ids;
}

function read_tweets ($tweets, $context) {
  if (!isset($tweets)) return [];

  return file_get_contents('https://api.twitter.com/1.1/statuses/lookup.json?tweet_mode=extended&id=' . $tweets, false, $context);
}

function enrich_tweets ($original_list = [], $in_replay_to_list = []) {
  $enriched_tweets = [];
  foreach ($original_list as $tweet) {
    if (isset($tweet['in_reply_to_status_id_str'])) {
      $tweet['in_reply_to_status'] = find_by_id($tweet['in_reply_to_status_id_str'], $in_replay_to_list);
    }
    $enriched_tweets[] = $tweet;
  }

  return $enriched_tweets;
}

function find_by_id ($id, $list = []) {
  return $list[array_search($id, array_column($list, 'id_str'))];
}

if (is_cache_fresh($file_name, $config['LIFE_SPAN'])) {
  echo read_from_cache($file_name);
} else {

  $main_tweets = read_tweets($tweets, $context);

  $tweets_list = json_decode($main_tweets, true);

  $in_replay_to_ids = extract_in_replay_to_status_ids($tweets_list);

  $in_replay_to_tweets = read_tweets(implode(',', $in_replay_to_ids), $context);

  $enriched_tweets = enrich_tweets($tweets_list, json_decode($in_replay_to_tweets, true));
  echo update_cache($file_name, json_encode($enriched_tweets));
}



