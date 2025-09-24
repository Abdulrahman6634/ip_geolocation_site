<?php
global $h;
require("config/env.php");

if ($route == '/dashboard' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Normal page load
    $seo = [
        'title' => 'Admin Dashboard | SwiftCart',
        'description' => '',
        'keywords' => '',
    ];

    // ✅ Fetch latest 20 activities, newest first
    $activity = $h->table('api_activity_log')
        ->select('timestamp', 'ip_address', 'status_code')
        ->orderBy('timestamp', 'DESC')
        ->limit(20)
        ->fetchAll();

    echo $twig->render('public/pages/dashboard.twig', [
        'seo' => $seo,
        'activity' => $activity,
    ]);
}



if ($route == '/test_lookup' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Example IP (later replace with POST or detected IP)
        $ipAddress = $_POST['ipInput']?? '';
        $geoData   = getGeoLocation($ipAddress);

        if (!$geoData) {
            throw new Exception("Failed to fetch geolocation data.");
        }

        // Fetch country details from DB
        $countryData = [];
        if (!empty($geoData['country_code'])) {
            $countryData = $h->table('countries')
                ->select('*')
                ->where('iso_code', '=', $geoData['country_code'])
                ->fetchAll();

            if ($countryData) {
                $countryData = $countryData[0]; // take first row
            }
        }

        // ✅ Merge geoData + countryData
        // Country table values override geo API ones if both exist
        $merged = array_merge($geoData, $countryData ?: []);

        echo json_encode([
            'statusCode' => 200,
            'message'    => 'Geo data + Country data fetched successfully!',
            'data'       => $merged
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'statusCode' => 500,
            'message'    => 'Failed to fetch Geo data!',
            'error'      => $e->getMessage()
        ]);
    }
    exit;
}





if ($route == '/usage') {
    $seo = [
        'title' => 'Usage Statistics | IPlytic',
        'description' => '',
        'keywords' => '',
    ];

    // Fetch activity logs
    $activity = $h->table('api_activity_log')
        ->select('timestamp', 'api_key', 'endpoint', 'ip_address', 'status_code', 'response_time_ms', 'user_agent')
        ->orderBy('timestamp', 'DESC')
        ->fetchAll();

    // Total Requests
    $totalRequests = count($activity);

    // Successful Requests (200)
    $successfulRequests = count(array_filter($activity, fn($row) => $row['status_code'] == 200));

    // Failed Requests (not 200)
    $failedRequests = $totalRequests - $successfulRequests;

    // Average Response Time
    $avgResponseTime = $totalRequests > 0 
        ? round(array_sum(array_column($activity, 'response_time_ms')) / $totalRequests, 2) 
        : 0;

    echo $twig->render('public/pages/usage.twig', [
        'seo' => $seo,
        'activity' => $activity,
        'stats' => [
            'total' => $totalRequests,
            'success' => $successfulRequests,
            'failed' => $failedRequests,
            'avgTime' => $avgResponseTime,
        ]
    ]);
}





