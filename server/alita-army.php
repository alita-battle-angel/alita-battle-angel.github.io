<?php
require_once 'autoload.php';

header('Content-Type: application/json', true);
header("Access-Control-Allow-Origin: *");

$alita_army_db = new FileDB('alita-army-db.json');

function check_eligibility($profile)
{
  return $profile['statuses_count'] >= 1 && strpos($profile['description'], '#AlitaArmy') !== false;
}

if (isset($_POST['register']) && isset($_POST['screen_name'])) {
  $screen_name = $_POST['screen_name'];
  $user_profile = $alita_army_db->find_by_id($screen_name, 'screen_name');

  if (!$user_profile) {
    $user_profile = TwitterAPI::fetch('users/lookup.json', ['screen_name' => $screen_name]);
    if (isset($user_profile[0]) && check_eligibility($user_profile[0])) {
      $id = $alita_army_db->get_last_index();
      $user_profile[0]['_HUNTER_WARRIOR_ID'] = "HW-0$id";
      $user_profile = $alita_army_db->add($user_profile[0]);
    } else {
      http_response_code(400);
      echo json_encode([
        'message' => 'You need to have #AlitaArmy in your profile bio and also you need to have at least one tweet'
      ]);

      return;
    }
  }

  echo json_encode($user_profile);
  return;
}

echo json_encode($alita_army_db->get_all());
