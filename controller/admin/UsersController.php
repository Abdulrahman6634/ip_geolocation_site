<?php
global $h;
require("config/env.php");

if ($route == '/admin/add-user') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Image upload handling
            $image_name=upload('image','uploads/admin/users');


            // Save to DB
            $response = $h->table('users')->insertOne([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'profile_image' => $image_name
            ]);

            echo json_encode(['status' => 200, 'message' => 'user added successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 202, 'message' => $e->getMessage()]);
        }
        exit;
    }

    $seo = array(
        'title' => 'Add user | GlamourGo Beauty Salon',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/users/add_users.twig', ['seo' => $seo]);
}



if ($route == '/admin/users-list' ) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') :

        endif;

    $seo = array(
        'title' => 'Admin Dashboard | SwiftCart ',
        'description' => '',
        'keywords' => '',
    );
    echo $twig->render('admin/pages/users/users_list.twig', ['seo' => $seo]);

}


if ($route == '/admin/api/users' ) {

    $srNo=0;
    $users = $h->table('users')->select()->fetchAll();
    if(!empty($users)){
        foreach ($users as $user){

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
            $cu_price= array("cu_price"=>'$ '.$user['price']);
            $image=array("image"=>'<div class="d-flex align-items-center">
                                <img src="'.$env['APP_URL'].'uploads/users/'.$user['product_image'].'" alt="product Image" class="product-img-circle" width="40" height="40">
                                <span class="m-2">'.$user['name'].'</span>
                            </div>');
            $createdAT= array("createdAT"=>getRelativeTime($user['created_at'], 'UTC'));
            $check_arr[]=array_merge($ids,$user,$createdAT, $image,$cu_price, $action);
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
            "aaData"=>$users);
        echo json_encode($result);
    }



}