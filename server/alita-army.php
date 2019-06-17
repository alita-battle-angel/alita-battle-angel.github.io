<?php
require_once 'autoload.php';
require_once 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

function check_eligibility ($profile) {
  return $profile['statuses_count'] >= 1 && strpos($profile['description'], '#AlitaArmy') !== false;
}

function get_profile ($screen_name) {
  global $database;
  return $database->fetchRow("SELECT * FROM alita_army WHERE alita_army.screen_name = '{$screen_name}'");
}

function save_profile ($profile) {
  global $database;
  $hunter_warrior_id = $database->fetchOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'alita_army'");
  $database->insert('alita_army', [
    'name' => $profile['name'],
    'screen_name' => $profile['screen_name'],
    'description' => $profile['description'],
    'location' => $profile['location'],
    'profile_banner_url' => isset($profile['profile_banner_url']) ? $profile['profile_banner_url'] : null,
    'profile_image_url_https' => $profile['profile_image_url_https'],
    'hunter_warrior_id' => "HW-00{$hunter_warrior_id}",
    'created_at' => date('Y-m-d H:i:s', time()),
    'updated_at' => date('Y-m-d H:i:s', time())
  ]);
}

function update_profile ($profile) {
  global $database;
  $database->update('alita_army', [
    'name' => $profile['name'],
    'description' => $profile['description'],
    'location' => $profile['location'],
    'profile_banner_url' => isset($profile['profile_banner_url']) ? $profile['profile_banner_url'] : null,
    'profile_image_url_https' => $profile['profile_image_url_https'],
    'updated_at' => date('Y-m-d H:i:s', time())
  ], [
    'screen_name = ?' => $profile['screen_name']
  ]);
}

if ($method === 'POST') {
  if (isset($_INPUT['screen_name'])) {
    $screen_name = $_INPUT['screen_name'];
    $profile = get_profile($_INPUT['screen_name']);

    if ($profile === false) {
      $twitter_response = TwitterAPI::fetch('users/lookup.json', ['screen_name' => $screen_name]);

      if (isset($twitter_response['errors'])) {
        http_response_code($twitter_response['code']);
        response([
          'message' => 'twitter: ' . $twitter_response['errors'][0]['message']
        ]);
      }

      if ($twitter_response[0] && !check_eligibility($twitter_response[0])) {
        response([
          'message' => 'You need to have #AlitaArmy (case-sensitive) in your profile bio and also you need to have at least one tweet.',
          'profile' => $twitter_response[0]
        ], 400);
      }

      if ($twitter_response[0]) {
        $profile = $twitter_response[0];
        save_profile($profile);

        $profile = get_profile($_INPUT['screen_name']);

        response([
          'message' => 'Thank you for joining the Alita Army!',
          'data' => $profile
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

  $total = intval($database->fetchOne('SELECT COUNT(*) FROM alita_army'));

  response([
    'page' => $page + 1,
    'item_per_page' => $item_per_page,
    'total_pages' => $total === 0 ? 1 : ceil($total / $item_per_page),
    'data' => $database->fetchAll("SELECT * FROM alita_army ORDER BY updated_at DESC LIMIT {$item_per_page} OFFSET {$start}")
  ]);
}

