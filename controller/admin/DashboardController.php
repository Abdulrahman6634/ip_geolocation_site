<?php
global $h;
require("config/env.php");

if ($route == '/admin/dashboard' ) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') :

        endif;

    $seo = array(
        'title' => 'Admin Dashboard | SwiftCart ',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/dashboard.twig', ['seo' => $seo]);

}
