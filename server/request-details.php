<?php
header('Content-Type: application/json', true);
header("Access-Control-Allow-Origin: *");
echo json_encode($_SERVER);
