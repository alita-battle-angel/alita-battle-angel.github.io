<?php
require_once 'autoload.php';
require_once 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

function get_tweet ($id_str) {
  global $database;
  return $database->fetchRow("SELECT * FROM tweets WHERE tweets.id_str = '{$id_str}'");
}

function save_tweet ($status) {
  global $database;

  $database->insert('tweets', [
    'id_str' => $status['id_str'],
    'name' => $status['name'],
    'screen_name' => $status['screen_name'],
    'profile_image_url_https' => $status['profile_image_url_https'],
    'full_text' => $status['full_text'],
    'in_reply_to_status_id_str' => $status['in_reply_to_status_id_str'],
    'retweet_status' => $status['retweet_status'],
    'hashtags' => $status['hashtags'],
    'urls' => $status['urls'],
    'media' => $status['media'],
    'user_mentions' => $status['user_mentions'],
    'status_created_at' => $status['created_at']
  ]);
}

function update_tweet ($status) {
  global $database;
  $database->update('tweets', [
    'id_str' => $status['id_str'],
    'name' => $status['name'],
    'screen_name' => $status['screen_name'],
    'profile_image_url_https' => $status['profile_image_url_https'],
    'full_text' => $status['full_text'],
    'in_reply_to_status_id_str' => $status['in_reply_to_status_id_str'],
    'retweet_status' => $status['retweet_status'],
    'hashtags' => $status['hashtags'],
    'urls' => $status['urls'],
    'media' => $status['media'],
    'user_mentions' => $status['user_mentions'],
    'status_created_at' => $status['created_at'],
    'updated_at' => NULL
  ], [
    'screen_name = ?' => $status['screen_name']
  ]);
}

if ($method === 'POST') {
  if (isset($_INPUT['tweet_id'])) {
    $tweet_id = $_INPUT['tweet_id'];
    $tweet = get_tweet($_INPUT['tweet_id']);

    if ($tweet === false) {
      $twitter_response = TwitterAPI::fetch('statuses/lookup.json', [
        'tweet_mode' => 'extended',
        'id' => $tweet_id
      ]);

      if (isset($twitter_response['errors'])) {
        http_response_code($twitter_response['code']);
        response([
          'message' => 'twitter: ' . $twitter_response['errors'][0]['message']
        ]);
      }

      if ($twitter_response[0]) {
        $tweet = $twitter_response[0];
        save_tweet($tweet);

        $tweet = get_tweet($tweet_id);

        response([
          'message' => 'Thank you for joining the Alita Army!',
          'data' => $tweet
        ]);
      }
    } else {
      $twitter_response = TwitterAPI::fetch('users/lookup.json', ['screen_name' => $screen_name]);
      if ($twitter_response[0]) {
        update_profile($twitter_response[0]);
      }

      $profile = get_profile($_INPUT['screen_name']);

      response([
        'message' => 'You are already member of Alita Army!',
        'data' => $profile
      ], 202);
    }
  }
}

if ($method === 'GET') {
  $page = 0;
  if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
    $page--;
  }

  if ($page < 0) {
    $page = 0;
  }

  $item_per_page = 18;
  $start = $page * $item_per_page;

  $total = intval($database->fetchOne('SELECT COUNT(*) FROM tweets'));

  response([
    'page' => $page + 1,
    'item_per_page' => $item_per_page,
    'total_pages' => $total === 0 ? 1 : ceil($total / $item_per_page),
    'data' => $database->fetchAll("SELECT * FROM tweets ORDER BY updated_at DESC LIMIT {$item_per_page} OFFSET {$start}")
  ]);
}

