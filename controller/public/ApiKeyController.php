<?php
global $h;
global $userInfo;
require("config/env.php");

if ($route == '/api_key' && $_SERVER['REQUEST_METHOD'] === 'GET') {

    $key = $h->table('api_keys')->select()->where('userId' , $loginUserId)->fetchAll();

    $seo = array(
        'title' => ' SignIn | IPlytic',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('public/pages/api_key.twig', ['seo' => $seo , 'key' => $key[0]]);

}

if ($route == '/api_key' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Generate new token
        $token = generate_jwt_token($loginUserId , $loginUserEmail);

        // Update token in database
        $response = $h->table('api_keys')
            ->update(['token' => $token])
            ->where('userId', $loginUserId)
            ->run();

        echo json_encode([
            'statusCode' => 200,
            'message' => 'Token generated and updated successfully!',
            'token' => $token
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'statusCode' => 500,
            'message' => 'Something went wrong while generating token!',
            'error' => $e->getMessage()
        ]);
    }
}
