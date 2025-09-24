<?php
global $h;
require("config/env.php");


if ($route == '/admin/add-order' ) {

    if($_SERVER['REQUEST_METHOD'] == 'POST') :
        header('Content-Type: application/json');
        try{
            // Get the arrays from POST data
            $productIds = commaSeperatedToArray($_POST['product_id']);
            $quantities = commaSeperatedToArray($_POST['quantities']);
            $prices = commaSeperatedToArray($_POST['prices']);
            
            // Validate that all arrays have the same length
            if (count($productIds) !== count($quantities) || count($productIds) !== count($prices)) {
                throw new Exception("Mismatched product data arrays");
            }
            
            // Insert the order
            $response = $h->table('orders')->insertOne([
                'user_id' => $_POST['user_id'],
                'shipping_address' => $_POST['address'],
                'total' => $_POST['total_amount'] // Use the calculated total from frontend
            ]);

            // Insert order items
            foreach ($productIds as $index => $productId) {
                $h->table('order_items')->insertOne([
                    'order_id' => $response, 
                    'user_id' => $_POST['user_id'],
                    'product_id' => $productId,
                    'quantity' => $quantities[$index],
                    'price' => $prices[$index] // Unit price
                ]);
            }

            echo json_encode(array('status'=>200, 'message'=>'Successfully Saved.'));

        }catch (Exception $e){
            echo json_encode(array('status'=>202, 'message'=>$e->getMessage()));
        }

        exit;
    endif;
    $products = $h ->table('products') ->select() ->fetchAll();
    $product_categories = $h ->table('product_categories') ->select() ->fetchAll();
    $users = $h ->table('users') ->select()->where('type' , 'user')->fetchAll();
    $seo = array(
        'title' => 'Add order | GlamourGo Beauty Salon',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/orders/add_order.twig', ['seo' => $seo, 'products' => $products, 'product_categories' => $product_categories, 'users' => $users]);

}


if ($route == '/admin/orders-list' ) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') :

        endif;

    $seo = array(
        'title' => 'Admin Dashboard | SwiftCart ',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/orders/orders_list.twig', ['seo' => $seo]);

}


if ($route == '/admin/api/orders') {
    $srNo = 0;
    
    // First get all orders
    $orders = $h->table('orders')
        ->select('orders.*', 'users.first_name', 'users.last_name')
        ->innerJoin('users')->on('orders.user_id', '=', 'users.id')
        ->fetchAll();
    
    $check_arr = array();
    
    if (!empty($orders)) {
        foreach ($orders as $order) {
            // Get all products for this order
            $orderItems = $h->table('order_items')
                ->select('order_items.*', 'products.name as product_name', 'products.product_image')
                ->innerJoin('products')->on('order_items.product_id', '=', 'products.id')
                ->where('order_items.order_id', $order['id'])
                ->fetchAll();
            
            // Build product list with quantities
            $productNames = [];
            $productQuantities = [];
            foreach ($orderItems as $item) {
                $productNames[] = $item['product_name'];
                $productQuantities[] = $item['quantity'] . ' x ' . $item['product_name'];
            }
            
            $productList = implode('<br>', $productQuantities);
            
            $action = array("action" => "
                <a href='/admin/edit-order/" . $order['id'] . "' class='bs-tooltip'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2 p-1 br-8 mb-1'>
                        <path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path>
                    </svg>
                </a>
                <a onclick=\"deleteOrder('" . $order['id'] . "')\" class='bs-tooltip'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash p-1 br-8 mb-1'>
                        <polyline points='3 6 5 6 21 6'></polyline>
                        <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 0 0 1 2 2v2'></path>
                    </svg>
                </a>
            ");
            
            $srNo = $srNo + 1;
            $ids = array("id" => $srNo);
            $total = array("total" => '$ ' . number_format($order['total'], 2));
            $customerName = array("customer_name" => $order['first_name'] . ' ' . $order['last_name']);
            $createdAT = array("created_at" => getRelativeTime($order['created_at'], 'UTC'));
            
            $check_arr[] = array_merge(
                $ids,
                $customerName,
                array("product_list" => $productList),
                $total,
                array("payment_method" => $order['payment_method'] ?? 'N/A'),
                array("shipping_address" => $order['shipping_address']),
                $action
            );
        }
        
        $result = array(
            "sEcho" => 1,
            "iTotalRecords" => count($check_arr),
            "iTotalDisplayRecords" => count($check_arr),
            "aaData" => $check_arr
        );
        echo json_encode($result);
    } else {
        $result = array(
            "sEcho" => 1,
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData" => array()
        );
        echo json_encode($result);
    }
}