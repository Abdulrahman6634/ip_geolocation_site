<?php
global $h;
global $userInfo;
require("config/env.php");

if ($route == '/profile') {
    // Normal page load
    $seo = array(
        'title' => 'Admin Dashboard | SwiftCart',
        'description' => '',
        'keywords' => '',
    );

    $user = $h->table('users')->select()->where('id' , $loginUserId)->fetchAll();

    echo $twig->render('public/pages/profile.twig', [
        'seo' => $seo,
        'user' => $user[0]
    ]);
}

// update profile
if ($route == '/api/profile/update') {

    try {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        $data = [];

        if (!empty($username)) {
            $data['first_name'] = $username;
        }

        if (!empty($email)) {
            $data['email'] = $email;
        }

        if (empty($data)) {
            echo json_encode([
                'statusCode' => 400,
                'success' => false,
                'message' => 'Please provide at least a username or an email.'
            ]);
            exit;
        }

        // 🔹 Update user profile
        $response = $h->table('users')
            ->update($data)
            ->where('id', $loginUserId)
            ->run();

        if ($response) {
            echo json_encode([
                'statusCode'   => 200,
                'success'      => true,
                'message'      => 'Profile updated successfully!',
                'username'     => $username ?: null,
                'email'        => $email ?: null
            ]);
        } else {
            echo json_encode([
                'statusCode' => 500,
                'success' => false,
                'message' => 'No changes were made.'
            ]);
        }

    } catch (Exception $e) {
        echo json_encode([
            'statusCode' => 500,
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }

    exit;
}

// delete account
if ($route == '/api/profile/delete' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');

    try {
        $response = $h->table('users')
            ->delete()
            ->where('id', $loginUserId)
            ->run();

        if ($response) {
            echo json_encode([
                'success' => true,
                'message' => 'Your account has been deleted successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Account deletion failed.'
            ]);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }

    exit;
}


// change password
if ($route == '/api/profile/change-password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword     = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        // 🔹 Validation checks
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode([
                'success' => false,
                'message' => 'All password fields are required.'
            ]);
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode([
                'success' => false,
                'message' => 'New password and confirmation do not match.'
            ]);
            exit;
        }

        if (strlen($newPassword) < 8) {
            echo json_encode([
                'success' => false,
                'message' => 'New password must be at least 8 characters long.'
            ]);
            exit;
        }

        // 🔹 Strong password regex (at least 1 uppercase, 1 lowercase, 1 digit, 1 special char)
        $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/";
        if (!preg_match($pattern, $newPassword)) {
            echo json_encode([
                'success' => false,
                'message' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
            ]);
            exit;
        }

        // 🔹 Fetch current user
        $user = $h->table('users')
            ->select('password')
            ->where('id', $loginUserId)
            ->fetchAll();

        if (!$user[0]) {
            echo json_encode([
                'success' => false,
                'message' => 'User not found.'
            ]);
            exit;
        }

        // 🔹 Verify current password
        if (!password_verify($currentPassword, $user[0]['password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ]);
            exit;
        }

        // 🔹 Prevent same password reuse
        if (password_verify($newPassword, $user[0]['password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'New password cannot be the same as the current password.'
            ]);
            exit;
        }

        // 🔹 Hash and update
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $response = $h->table('users')
            ->update(['password' => $hashedPassword])
            ->where('id', $loginUserId)
            ->run();

        if ($response) {
            echo json_encode([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Password update failed. Please try again.'
            ]);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }

    exit;
}

