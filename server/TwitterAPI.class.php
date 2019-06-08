<?php
global $config;
$config = parse_ini_file(__DIR__ . '/twitter.config.ini');

class TwitterAPI {
  public static function fetch ($end_point, $params = []) {
    global $config;

    $http_headers = [
      'Content-Type: application/json',
      "Authorization: Bearer {$config['BEARER_TOKEN']}"
    ];

    $query = http_build_query($params);

    $handle = curl_init();

    $url = "https://api.twitter.com/1.1/$end_point?$query";

    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $http_headers);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($handle);

    $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);

    $response = json_decode($content, true);

    if ($http_code > 250) {
      $response['code'] = $http_code;
    }

    return $response;
  }
}

