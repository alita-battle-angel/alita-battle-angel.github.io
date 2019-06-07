<?php
global $config;
$config = parse_ini_file(__DIR__ . '/twitter.config.ini');

class TwitterAPI
{
  public static function fetch($end_point, $params = [])
  {
    global $config;

    $http_headers = [
      "Authorization: Bearer {$config['BEARER_TOKEN']}"
    ];

    $context = stream_context_create(array(
      "http" => array(
        "header" => implode('\n', $http_headers),
      )
    ));

    $query = http_build_query($params);
    return json_decode(file_get_contents("https://api.twitter.com/1.1/$end_point?$query", false, $context), true);
  }
}

