<?php
require_once 'autoload.php';
require_once 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

function read_tweets ($tweets) {
  if (!isset($tweets)) return [];

  return TwitterAPI::fetch('statuses/lookup.json', [
    'tweet_mode' => 'extended',
    'id' => $tweets
  ]);
}

function read_single_tweet ($id_str) {
  return TwitterAPI::fetch('statuses/lookup.json', [
    'tweet_mode' => 'extended',
    'id' => $id_str
  ]);
}

function extract_in_replay_to_status_ids ($tweets_list = []) {
  $ids = [];
  foreach ($tweets_list as $tweet) {
    if (isset($tweet['in_reply_to_status_id_str'])) {
      $ids[] = $tweet['in_reply_to_status_id_str'];
    }
  }
  return $ids;
}

function search ($q) {
  $response = TwitterAPI::fetch('search/tweets.json', [
    'tweet_mode' => 'extended',
    'q' => urlencode($q)
  ]);

  return array_filter($response['statuses'], function ($item) {
    return !isset($item['retweeted_status']);
  });

}

function fetch_tweet ($id_str) {
  global $database;
  $tweet = $database->fetchRow("SELECT * FROM tweets WHERE tweets.id_str = '{$id_str}'");
  if (isset($tweet['media'])) {
    $tweet['media'] = json_decode($tweet['media']);
  }

  return $tweet;
}

function save_tweet ($status) {
  global $database;

  $hashtags = array_map(function ($item) {
    return '#' . $item['text'];
  }, $status['entities']['hashtags']);

  $urls = array_map(function ($item) {
    return $item['url'];
  }, $status['entities']['urls']);

  $user_mentions = array_map(function ($item) {
    return '@' . $item['screen_name'];
  }, $status['entities']['user_mentions']);


  $database->save('tweets', [
    'id_str' => $status['id_str'],
    'name' => $status['user']['name'],
    'screen_name' => $status['user']['screen_name'],
    'profile_image_url_https' => $status['user']['profile_image_url_https'],
    'full_text' => $status['full_text'],
    'in_reply_to_status_id_str' => $status['in_reply_to_status_id_str'],
    'hashtags' => implode(' ', $hashtags),
    'urls' => implode("\n", $urls),
    'media' => isset($status['extended_entities']) ? json_encode($status['extended_entities']['media']) : NULL,
    'user_mentions' => implode(' ', $user_mentions),
    'status_created_at' => date('Y-m-d H:i:s', strtotime($status['created_at']))
  ]);
}

function populate_weekly_tweets () {
  global $database;
  $fan_weekly_tweets = $database->fetchRow("SELECT * FROM cache WHERE cache.name = 'fan_weekly_tweets'");

  $current_time = time();
  // Make a request every 12 hours
  if ($fan_weekly_tweets && $current_time - strtotime($fan_weekly_tweets['updated_at']) < (12 * 3600)) {
    return;
  }

  $database->save('cache', [
    'id' => $fan_weekly_tweets['id'],
    'name' => 'fan_weekly_tweets',
    'value' => $current_time,
    'updated_at' => NULL
  ]);

  $alita_statuses = search('#Alita');

  $fan_art_statuses = array_filter(search('#Alita #FanArt'), function ($item) {
    return isset($item['extended_entities']);
  });

  $alita_army_statuses = search('#AlitaArmy');

  $all = array_merge($alita_statuses, $fan_art_statuses, $alita_army_statuses);

  $replies = extract_in_replay_to_status_ids($all);
  $replies_tweets = array_filter(read_tweets(implode(',', $replies)), function ($item) {
    return !isset($item['retweeted_status']);
  });

  $all = array_merge($all, $replies_tweets);

  array_walk($all, function ($status) {
    save_tweet($status);
  });
}

if ($method === 'POST') {
  if (isset($_INPUT['tweet_id'])) {
    $tweet_id = $_INPUT['tweet_id'];
    $tweet = fetch_tweet($_INPUT['tweet_id']);

    if ($tweet === false) {
      $twitter_response = read_single_tweet($tweet_id);

      if (isset($twitter_response['errors'])) {
        http_response_code($twitter_response['code']);
        response([
          'message' => 'twitter: ' . $twitter_response['errors'][0]['message']
        ]);
      }

      if ($twitter_response[0]) {
        $tweet = $twitter_response[0];

        if (isset($tweet['in_reply_to_status_id_str'])) {
          $in_reply_to_response = read_single_tweet($tweet['in_reply_to_status_id_str']);

          if ($in_reply_to_response[0]) {
            save_tweet($in_reply_to_response[0]);
          }
        }

        save_tweet($tweet);

        $tweet = fetch_tweet($tweet_id);

        response([
          'message' => 'Thank you for contributing!',
          'data' => $tweet
        ]);
      }
    } else {
      response([
        'message' => 'This tweet has been already added. Thank you for contributing!',
        'data' => $tweet
      ], 202);
    }
  }
}

if ($method === 'GET') {
  populate_weekly_tweets();

  $page = 0;
  if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
    $page--;
  }

  if ($page < 0) {
    $page = 0;
  }

  $item_per_page = 8;
  $start = $page * $item_per_page;

  $total = intval($database->fetchOne('SELECT COUNT(*) FROM tweets WHERE id_str NOT IN (SELECT in_reply_to_status_id_str FROM tweets WHERE in_reply_to_status_id_str IS NOT NULL)'));

  $data = $database->fetchAll("SELECT * FROM tweets WHERE id_str NOT IN (SELECT in_reply_to_status_id_str FROM tweets WHERE in_reply_to_status_id_str IS NOT NULL) ORDER BY status_created_at DESC LIMIT {$item_per_page} OFFSET {$start}");
  array_walk($data, function (&$item) {
    $item['media'] = json_decode($item['media']);

    if (isset($item['in_reply_to_status_id_str'])) {
      $item['in_reply_to_status'] = fetch_tweet($item['in_reply_to_status_id_str']);
    }
  });

  response([
    'page' => $page + 1,
    'item_per_page' => $item_per_page,
    'total_pages' => $total === 0 ? 1 : ceil($total / $item_per_page),
    'data' => $data
  ]);
}

