<?php
global $h;
require("config/env.php");

if ($route == '/api/ip-lookup' ) {

$key = $h->table('api_keys')->select()->where('userId' , $loginUserId)->fetchAll();


$token= validate_jwt_token($key, loginUserId, $h);


if (!$result['valid']) {
    http_response_code(401);
    echo json_encode(['error' => $result['message']]);
    exit;
}

$ip = $_GET['ip']

}

