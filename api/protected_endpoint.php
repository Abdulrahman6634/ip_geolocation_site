<?php
require_once 'api/helpers/api_response.php';
require_once 'api/helpers/jwt_handler.php';

require_once 'config/env.php';

// Get JWT token
$token = get_bearer_token();

if (!$token) {
    api_error(401, 'Access denied. No token provided');
}

// Validate token
$decoded = validate_jwt_token($token);

if (!$decoded) {
    api_error(401, 'Invalid or expired token');
}

$user_id = $decoded->user_id ?? null;

if (empty($user_id) || !is_numeric($user_id)) {
    api_error(400, 'Valid user ID is required.');
}

// Token is valid - proceed with protected operation
global $h;

try {
    //ALL USERS
    if($route == '/api/users'){
        // Example: Get user data
        $userData = $h->table('users')
            ->select()
            ->fetchAll();
        $user= $userData[0];
        if (!$user) {
            api_error(404, 'Users not found');
        }

        // Return protected data
        api_response(200, true, 'Access granted', [
            'users' => $userData
        ]);
    }

if ($route == '/api/ip-lookup') {

    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        api_error(405, 'Method Not Allowed');
    }

    try {
        // Step 1: Validate JWT Token
        $key = $h->table('api_keys')
            ->select()
            ->where('userId', $loginUserId)
            ->fetchAll();

        $result = validate_jwt_token($key, $loginUserId, $h);

        if (!$result['valid']) {
            http_response_code(401);
            echo json_encode(['error' => $result['message']]);
            exit;
        }

        // Step 2: Get IP from query OR detect client IP
        $ip = $_GET['ip'] ?? '';
        if (empty($ip)) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }

        // Step 3: Validate IP
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            api_error(400, 'Invalid IP address.');
        }
        $ip_version = (strpos($ip, ':') !== false) ? 'IPv6' : 'IPv4';

        // Step 4: Get geo info from IP
        $geo_url = "http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,lat,lon,city,regionName,timezone";
        $geo_data = json_decode(file_get_contents($geo_url), true);

        if (!$geo_data || $geo_data['status'] !== 'success') {
            api_error(404, 'Unable to fetch location for IP.');
        }

        $countryCode = $geo_data['countryCode'] ?? null;
        if (!$countryCode) {
            api_error(404, 'Country not found for this IP.');
        }

        // Step 5: Fetch country details from DB
        $country = $h->table('countries')
            ->select('*')
            ->where('iso_code', $countryCode)
            ->fetchAll();

        if (!$country) {
            api_error(404, 'Country data not found in database.');
        }

        // Step 6: Build response
        $response = [
            'ip'         => $ip,
            'ip_version' => $ip_version,
            'location'   => [
                'latitude'  => $geo_data['lat'] ?? null,
                'longitude' => $geo_data['lon'] ?? null,
                'city'      => $geo_data['city'] ?? null,
                'region'    => $geo_data['regionName'] ?? null,
                'timezone'  => $geo_data['timezone'] ?? null,
            ],
            'country' => $country
        ];

        api_response(200, true, 'IP info retrieved successfully.', $response);

    } catch (Exception $e) {
        api_error(500, 'Server error: ' . $e->getMessage());
    }
}



    //CREATE NEW PASSWORD
    if ($route == '/api/user/create_new_password') {

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            api_error(405, 'Method Not Allowed');
        }

        if (!$user_id) {
            api_error(401, 'Unauthorized. Please login first.');
        }

        // Validate input
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            api_error(400, 'All fields are required.');
        }

        if ($new_password !== $confirm_password) {
            api_error(400, 'New password and confirm password do not match.');
        }

        // Password strength validation
        $uppercase = preg_match('@[A-Z]@', $new_password);
        $lowercase = preg_match('@[a-z]@', $new_password);
        $number = preg_match('@[0-9]@', $new_password);

        if (!$uppercase || !$lowercase || !$number || strlen($new_password) < 8) {
            api_error(400, 'Password must be at least 8 characters long and contain uppercase, lowercase, and numbers.');
        }

        try {
            $user = $h->table('users')->select('password')->where('id' , $user_id)->fetchAll();
            $user = $user[0] ?? null;

            if (!$user) {
                api_error(404, 'User not found.');
            }

            if (!password_verify($current_password, $user['password'])) {
                api_error(401, 'Current password is incorrect.');
            }

            $h->table('users')->update([
                'password' => password_hash($new_password, PASSWORD_DEFAULT)
            ])->where('id', $user_id)->run();

            echo json_encode(['status' => 200, 'message' => 'Password updated successfully.']);
            exit;

        } catch (Exception $e) {
            api_error(500, 'Server error: ' . $e->getMessage());
        }
    }

} catch (Exception $e) {
    api_error(500, 'Server error: ' . $e->getMessage());
}
?>