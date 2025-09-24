<?php
require("config/env.php");
$seo = array(
    'title' => 'Dashboard | Chaisbek Real Estate',
    'description' => 'Learn about Chaisbek Real Estates commitment to integrity, compassion, and excellence. Discover our mission to empower clients and communities through real estate.',
    'keywords' => 'Admin Panel'
);
echo $twig->render('404.twig', ['seo'=>$seo]);

