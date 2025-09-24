<?php
global $h;
require("config/env.php");

if ($route == '/admin/signin' ) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') :
        $email = $_POST['email'] ;
        $password = $_POST['password'];
       echo $response = userLogin($email, $password, 'users');

    exit;
        endif;

    $seo = array(
        'title' => 'Admin SignIn | GlamourGo Beauty Salon',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/auth/signin.twig', ['seo' => $seo]);

}


if ($route == '/admin/forget-password') {
    if($_SERVER['REQUEST_METHOD'] == 'POST') :

    
    exit;
        endif;

    $seo = array(
        'title' => 'Admin SignIn | GlamourGo Beauty Salon',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/auth/forget-password.twig', ['seo' => $seo]);

}