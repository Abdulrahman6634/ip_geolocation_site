<?php
global $h;
require("config/env.php");

if ($route == '/admin/profile') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];

            // Update data array
            $updateData = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email
            ];

            // Only update password if provided
            if (!empty($_POST['password'])) {
                $updateData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            // Image upload handling if new image is provided
            if (!empty($_FILES['image']['name'])) {
                $image_name = upload('image', 'uploads/admin/users/');
                $updateData['profile_image'] = $image_name;

                // Optional: Delete old image if exists
                $old_user = $h->table('users')->select()->where('id', $loginUserId)->fetchAll();
                if ($old_user && !empty($old_user['profile_image']) && file_exists('uploads/users/' . $old_user['profile_image'])) {
                    unlink('uploads/users/' . $old_user['profile_image']);
                }
            }

            // Update the current user's profile
            $response = $h->table('users')
                ->update($updateData)
                ->where('id', $loginUserId)
                ->run();

            echo json_encode(['status' => 200, 'message' => 'Profile updated successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 202, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // Get the current logged-in user's data
    $user = $h->table('users')->select()->where('id', $loginUserId)->fetchAll();

    $seo = array(
        'title' => 'Profile | GlamourGo Beauty Salon',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/profile/profile.twig', ['seo' => $seo, 'user' => $user[0]]);
}