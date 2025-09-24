<?php
/////////////////////////
///////ZOTEC FRAMEWORK
//////admin@zotecsoft.com
////////////////////////
require_once("./config/main.php");

//PUBLIC Pages

// ✅ Public APIs (accessible without login)
get('/api/ip-lookup', 'api/public_endpoint.php');
get('/api/users', 'api/public_endpoint.php');

// ✅ Public routes
get('/', 'controller/public/HomeController.php');
post('/', 'controller/public/HomeController.php');
get('/public/signin', 'controller/public/AuthController.php');
post('/public/signin', 'controller/public/AuthController.php');
get('/public/signup', 'controller/public/AuthController.php');
post('/public/signup', 'controller/public/AuthController.php');

// ✅ Private routes (only if logged in)
if(!empty($_SESSION['users'])) {
    get('/logout', 'controller/LogoutController.php');

    get('/dashboard', 'controller/public/DashboardController.php');
    post('/test_lookup', 'controller/public/DashboardController.php');
    get('/usage', 'controller/public/DashboardController.php');

    get('/subscription', 'controller/public/SubscriptionController.php');
    get('/payment_processing', 'controller/public/SubscriptionController.php');

    get('/profile', 'controller/public/ProfileController.php');
    post('/api/profile/update', 'controller/public/ProfileController.php');
    post('/api/profile/change-password', 'controller/public/ProfileController.php');
    get('/api/profile/delete', 'controller/public/ProfileController.php');

    get('/api_key', 'controller/public/ApiKeyController.php');
    post('/api_key', 'controller/public/ApiKeyController.php');

    get('/api/users', 'api/public_endpoint.php');
} else {
    // ❌ Don’t redirect all requests blindly
    // Instead, only redirect browser routes
        header('Location: /');

}


//404 PAGE
any('/404','controller/ErrorController.php');
