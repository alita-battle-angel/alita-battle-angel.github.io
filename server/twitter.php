<?php

require_once 'autoload.php';

function read_tweets($tweets)
{
  if (!isset($tweets)) return [];

  return TwitterAPI::fetch('statuses/lookup.json', [
    'tweet_mode' => 'extended',
    'id' => $tweets
  ]);
}

function extract_in_replay_to_status_ids($tweets_list = [])
{
  $ids = [];
  foreach ($tweets_list as $tweet) {
    if (isset($tweet['in_reply_to_status_id_str'])) {
      $ids[] = $tweet['in_reply_to_status_id_str'];
    }
  }
  return $ids;
}

function enrich_tweets($original_list = [], $in_replay_to_list = [])
{
  $enriched_tweets = [];
  foreach ($original_list as $tweet) {
    if (isset($tweet['in_reply_to_status_id_str'])) {
      $tweet['in_reply_to_status'] = find_by_id($tweet['in_reply_to_status_id_str'], $in_replay_to_list);
    }
    $enriched_tweets[] = $tweet;
  }
  return $enriched_tweets;
}

function find_by_id($id, $list = [])
{
  return $list[array_search($id, array_column($list, 'id_str'))];
}

if (!isset($_REQUEST['id'])) {
  http_response_code(400);
  die('id parameter is required');
}

$tweets = $_REQUEST['id'];

$db = new FileDB('tweets-db.json');

header('Content-Type: application/json', true);
header("Access-Control-Allow-Origin: *");

$requested_tweets = explode(',', $tweets);

// We only need to fetch tweets that are not already in the database
$absents = $db->find_absent_by_id($requested_tweets, 'id_str');
if (sizeof($absents) > 0) {
  $data = read_tweets(implode(',', $absents));
  $db->batch_update($data, 'id_str');
}

$tweets_list = $db->get_all_by_id($requested_tweets, 'id_str');

$in_replay_to_ids = extract_in_replay_to_status_ids($tweets_list);

// We only need to fetch replies that are not already in the database
$absent_replies = $db->find_absent_by_id($in_replay_to_ids,'id_str');
if (sizeof($absent_replies) > 0) {
  $data = read_tweets(implode(',', $absent_replies));
  $db->batch_update($data, 'id_str');
}

$in_replay_to_tweets_list = $db->get_all_by_id($in_replay_to_ids, 'id_str');
$enriched_tweets = enrich_tweets($tweets_list, $in_replay_to_tweets_list);
usort($enriched_tweets, function ($a, $b) {
  return strtotime($b['created_at']) - strtotime($a['created_at']);
});

echo json_encode($enriched_tweets);
$db->destroy();
