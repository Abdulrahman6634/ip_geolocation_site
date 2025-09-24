<?php
global $h;
require("config/env.php");

if ($route == '/admin/add-product') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {

            $name = $_POST['name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $description = base64_encode($_POST['description']);
            $category_id = $_POST['category_id'];
            $gender = $_POST['gender'];



            $image_name = upload('image', 'uploads/admin/products/');

            // Save to DB
            $response = $h->table('products')->insertOne([
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'description' => $description,
                'category_id' => $category_id,
                'gender' => $gender,
                'product_image' => $image_name
            ]);

                echo json_encode(['status' => 200, 'message' => 'Product added successfully']);
        } catch (Exception $e) {
            echo json_encode(['status' => 400, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // GET request: Render the add product form
    $seo = [
        'title' => 'Add Product | GlamourGo Beauty Salon',
        'description' => 'Add a new product to the GlamourGo Beauty Salon inventory.',
        'keywords' => 'beauty salon, product management, add product',
    ];

    $categories = $h->table('product_categories')->select()->fetchAll();

    echo $twig->render('admin/pages/product/add_product.twig', [
        'seo' => $seo,
        'categories' => $categories,
        'csrf' => $_SESSION['csrf_token'] // Pass CSRF token to the template
    ]);
}


if ($route == '/admin/products-list' ) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') :

        endif;

    $seo = array(
        'title' => 'Admin Dashboard | SwiftCart ',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/product/products_list.twig', ['seo' => $seo]);

}

if ($route == '/admin/api/products' ) {

    $srNo=0;
    $products = $h->table('products')->select()->fetchAll();
    if(!empty($products)){
        foreach ($products as $product){

            $action= array("action"=>"
         <a href='/api/edit_services/".$product['serv_id']."' class='bs-tooltip'>
<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2 p-1 br-8 mb-1'>
    <path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path>
</svg>
</i></a>
         <a onclick=deleteUser('".$product['serv_id']."') class='bs-tooltip'>
<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash p-1 br-8 mb-1'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 0 0 1 2 2v2'></path></svg>
         </a>
         ");
            $srNo=$srNo+1;
            $ids= array("Ids"=>"$srNo");
            $cu_price= array("cu_price"=>'$ '.$product['price']);
            $image=array("image"=>'<div class="d-flex align-items-center">
                                <img src="'.$env['APP_URL'].'uploads/products/'.$product['product_image'].'" alt="product Image" class="product-img-circle" width="40" height="40">
                                <span class="m-2">'.$product['name'].'</span>
                            </div>');
            $createdAT= array("createdAT"=>getRelativeTime($product['created_at'], 'UTC'));
            $check_arr[]=array_merge($ids,$product,$createdAT, $image,$cu_price, $action);
        }
        $result=array(
            "sEcho" => 1,
            "iTotalRecords" => count($check_arr),
            "iTotalDisplayRecords" => count($check_arr),
            "aaData"=>$check_arr);
        echo json_encode($result);
    }else{
        $result=array(
            "sEcho" => 1,
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData"=>$products);
        echo json_encode($result);
    }



}




if ($route == '/admin/api/product_categories' ) {

    $srNo=0;
    $product_categories = $h->table('product_categories')->select()->fetchAll();
    if(!empty($product_categories)){
        foreach ($product_categories as $product_category){

            $action= array("action"=>"
         <a href='/api/edit_services/".$product['serv_id']."' class='bs-tooltip'>
<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2 p-1 br-8 mb-1'>
    <path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path>
</svg>
</i></a>
         <a onclick=deleteUser('".$product['serv_id']."') class='bs-tooltip'>
<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash p-1 br-8 mb-1'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 0 0 1 2 2v2'></path></svg>
         </a>
         ");
            $srNo=$srNo+1;
            $ids= array("Ids"=>"$srNo");
            $createdAT= array("createdAT"=>getRelativeTime($product_category['created_at'], 'UTC'));
            $check_arr[]=array_merge($ids,$product_category,$createdAT, $action);
        }
        $result=array(
            "sEcho" => 1,
            "iTotalRecords" => count($check_arr),
            "iTotalDisplayRecords" => count($check_arr),
            "aaData"=>$check_arr);
        echo json_encode($result);
    }else{
        $result=array(
            "sEcho" => 1,
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData"=>$product_categories);
        echo json_encode($result);
    }



}