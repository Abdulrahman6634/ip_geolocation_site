<?php
global $h;
require("config/env.php");

if ($route == '/subscription' ) {

    $seo = array(
        'title' => ' SignIn | IPlytic',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('public/pages/subscription.twig', ['seo' => $seo]);

}


if ($route == '/payment_processing' ) {

    $seo = array(
        'title' => ' SignIn | IPlytic',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('public/pages/payment_processing.twig', ['seo' => $seo]);

}