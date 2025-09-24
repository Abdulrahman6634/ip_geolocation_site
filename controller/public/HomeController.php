<?php
global $h;
global $userInfo;
require("config/env.php");

if ($route == '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Normal page load
    $seo = array(
        'title' => 'Admin Dashboard | SwiftCart',
        'description' => '',
        'keywords' => '',
    );

    echo $twig->render('public/home.twig', [
        'seo' => $seo,
        'userInfo' => $userInfo
    ]);
}

if ($route == '/' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle AJAX request
        $ipAddress = "39.61.46.188";
        $geoData = getGeoLocation($ipAddress);

        $country = [];
        if ($geoData && isset($geoData['country_code'])) {
            $country = $h->table('countries')
                ->select()
                ->where('iso_code', $geoData['country_code'])
                ->fetchAll();
        }

        // Send JSON response instead of rendering Twig
        echo json_encode([
            'statusCode' => 200,
            'message' => 'Geo data fetched successfully!',
            'geoData' => $geoData,
            'country' => $country
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'statusCode' => 500,
            'message' => 'Failed to fetch Geo data!',
            'error' => $e->getMessage()
        ]);
    }
    exit;
}



