<?php

require_once 'api/helpers/api_response.php';
require_once 'config/env.php';


global $h;


    //IP GEOLOCATION INFO
if ($route == '/api/ip-lookup') {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        api_error(405, 'Method Not Allowed');
    }

    // Track response time
    $startTime = microtime(true);

    try {
        // Get IP
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

        // Validate
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            api_error(400, 'Invalid IP address.');
        }
        $ip_version = (strpos($ip, ':') !== false) ? 'IPv6' : 'IPv4';

        // Fetch geo
        $geo_url = "http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,lat,lon,city,regionName,timezone";
        $geo_data = json_decode(file_get_contents($geo_url), true);

        if (!$geo_data || $geo_data['status'] !== 'success') {
            api_error(404, 'Unable to fetch location for IP.');
        }

        $countryCode = $geo_data['countryCode'] ?? null;
        if (!$countryCode) {
            api_error(404, 'Country not found for this IP.');
        }

        // Fetch country from DB
        $country = $h->table('countries')
            ->select('*')
            ->where('iso_code', $countryCode)
            ->fetchAll();

        if (!$country) {
            api_error(404, 'Country data not found in database.');
        }

        // Build response
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

        // Measure response time
        $responseTime = (int) round((microtime(true) - $startTime) * 1000);

        // Insert into api_activity_log
        $h->table('api_activity_log')->insertOne([
            'api_key'         => $_SERVER['HTTP_X_API_KEY'] ?? 'unknown',
            'endpoint'        => '/api/ip-lookup',
            'ip_address'      => $ip,
            'status_code'     => 200,
            'response_time_ms'=> $responseTime,
            'user_agent'      => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);

        api_response(200, true, 'IP info retrieved successfully.', $response);

    } catch (Exception $e) {
        $responseTime = (int) round((microtime(true) - $startTime) * 1000);

        // Log failure
        $h->table('api_activity_log')->insertOne([
            'api_key'         => $_SERVER['HTTP_X_API_KEY'] ?? 'unknown',
            'endpoint'        => '/api/ip-lookup',
            'ip_address'      => $_GET['ip'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
            'status_code'     => 500,
            'response_time_ms'=> $responseTime,
            'user_agent'      => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);

        api_error(500, 'Server error: ' . $e->getMessage());
    }
}




