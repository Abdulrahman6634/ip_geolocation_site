<?php
global $h;
require("config/env.php");


if ($route == '/admin/product-category') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        try {
            $name = $_POST['name'];
            $description = base64_encode($_POST['description']);

            $response = $h->table('product_categories')->insertOne([
                'name' => $name,
                'description' => $description
            ]);

            echo json_encode(['status' => 200, 'message' => 'Successfully Saved.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 202, 'message' => $e->getMessage()]);
        }

        exit;
    }

    $seo = array(
        'title' => 'Admin SignIn | GlamourGo Beauty Salon',
        'description' => '',
        'keywords' => '',
    );

    $categories = $h->table('product_categories')->select()->fetchAll();
    echo $twig->render('admin/pages/product/product_category.twig', ['seo' => $seo, 'categories' => $categories]);
}
