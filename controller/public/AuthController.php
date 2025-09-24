<?php
global $h;
require("config/env.php");


if ($route == '/public/signin') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') :

        $email = $_POST['email'] ;
        $password = $_POST['password'] ;

        echo $response = userLogin( $email, $password, 'users' );

        exit;
    endif;

    $seo = [
        'title'       => 'SignIn | IPlytic',
        'description' => '',
        'keywords'    => '',
    ];
    echo $twig->render('public/pages/auth/signin.twig', ['seo' => $seo]);
}


if ($route == '/public/signup') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') :

        $username         = $_POST['username'];
        $email            = $_POST['email'];
        $password         = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        echo $response = userRegister($username, $email, $password, $confirm_password, 'users');

        exit;
    endif;

    $seo = [
        'title'       => 'SignUp | IPlytic',
        'description' => '',
        'keywords'    => '',
    ];
    echo $twig->render('public/pages/auth/signup.twig', ['seo' => $seo]);
}



if ($route == '/admin/forget-password') {
    if($_SERVER['REQUEST_METHOD'] == 'POST') :

    
    exit;
        endif;

    $seo = array(
        'title' => ' SignUp | IPlytic',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/auth/forget-password.twig', ['seo' => $seo]);

}