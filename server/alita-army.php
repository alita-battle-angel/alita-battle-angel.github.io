<?php
require_once 'autoload.php';

header('Access-Control-Allow-Origin: *', true);
header('Content-Type: application/json', true);
header('Access-Control-Allow-Headers: *');

$input_json_string = file_get_contents('php://input');
$input = json_decode($input_json_string, TRUE);

$alita_army_db = new FileDB('alita-army-db.json');

function check_eligibility ($profile) {
  return $profile['statuses_count'] >= 1 && strpos($profile['description'], '#AlitaArmy') !== false;
}

if (isset($input['register']) && isset($input['screen_name'])) {
  $screen_name = $input['screen_name'];
  $user_profile = $alita_army_db->find_by_id($screen_name, 'screen_name');

  if (!$user_profile) {
    $user_profile = TwitterAPI::fetch('users/lookup.json', ['screen_name' => $screen_name]);

    if (isset($user_profile[0]) && check_eligibility($user_profile[0])) {
      $id = $alita_army_db->get_last_index();
      $user_profile[0]['_HUNTER_WARRIOR_ID'] = "HW-0$id";
      $user_profile = $alita_army_db->add($user_profile[0]);
      echo json_encode([
        'message' => 'Thank you for joining the Alita Army!',
        'data' => $user_profile
      ]);
    } else {
      http_response_code(400);
      echo json_encode([
        'message' => 'You need to have #AlitaArmy (case-sensitive) in your profile bio and also you need to have at least one tweet.'
      ]);
    }
  } else {
    http_response_code(202);
    echo json_encode([
      'message' => 'You are already member of Alita Army!',
      'data' => $user_profile
    ]);
  }

  return;
}

$page_no = 0;
if(isset($_REQUEST['page'])) {
  $page_no = $_REQUEST['page'];
}

$list = $alita_army_db->get_all();
usort($list, function ($a, $b) {
  return $b['_SAVED_AT'] - $a['_SAVED_AT'];
});

echo json_encode(FileDB::get_page_of($list, $page_no, 15));
