<?php
require_once 'api/helpers/api_response.php';
require_once 'api/helpers/jwt_handler.php';
require 'config/env.php';

global $h;

if ($route == '/api/user/login') {

// header('Content-Type: application/json');
// echo json_encode([
//     '_POST' => $_POST,
//     '_REQUEST' => $_REQUEST,
//     'raw_input' => file_get_contents('php://input')
// ]);
// exit;
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        api_error(405, 'Method Not Allowed');
    }

    // Get and trim input from $_POST
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');


    // Validate inputs
    if (empty($email) || empty($password)) {
        api_error(400, 'Email and password are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        api_error(400, 'Invalid email format.');
    }

    try {

        // Fetch user with type = user and matching email
        $user = $h->table('users')
            ->select()
            ->where('email' , $email)
            ->where('type' , 'user')
            ->fetchAll();


        if (!$user) {
            api_error(401, 'User not found.');
        }

        $user = $user[0]; // ✅ Extract first user


        // Check if account is active
        if ($user['status'] === 'block') {
            api_error(403, 'Your account is not active.');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            api_error(401, 'Invalid email or password.');
        }

        // Generate JWT token
        $token = $h->table('api_keys')->select()->where('userId' , $user['id']);

        // Prepare success response
        $responseData = [
            'access_token' => $token,
            'type' => $user['type'],
            // 'redirectPath' => $user['type'] === 'admin' ? '/admin/dashboard' : '/user/dashboard',
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'phone_no' => $user['phone_no'],
                'dob' => $user['dob'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ]
        ];

        api_response(200, true, 'Login successful', $responseData);

    } catch (Exception $e) {
        api_error(500, 'Server error: ' . $e->getMessage());
    }
}
?>