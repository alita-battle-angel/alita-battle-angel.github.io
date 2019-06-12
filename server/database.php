<?php
$database_config = parse_ini_file(__DIR__ . '/database.config.ini');

global $database;
$database = OneDB::load([
'host' => $database_config['host'],
'database' => $database_config['database'],
'user' => $database_config['username'],
'password' => $database_config['password'],
'charset'   => 'utf8mb4',
]);
