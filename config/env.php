<?php
/////////////////////////
///////ZOTEC FRAMEWORK
//////admin@zotecsoft.com
////////////////////////
require_once("./config/main.php");
$env=array(
    "ENV_TYPE"=>"local", // local or production
    "SITE_NAME"=>"iplytic",
    "DESCRIPTION"=>"Log in to Zotec File Hub to securely access your files, manage storage, and generate unique URLs. Easy access to your file management dashboard.",
    "KEYWORDS"=>"Zotec File Hub login, file storage login, secure file access, file management login, cloud file platform",
    "APP_URL"=> "http://iplytic.local/",
    "ADMIN_EMAIL"=> "glamourgo@zotecsoft.work",
    "SENDER_EMAIL"=> "glamourgo@zotecsoft.work",
    "TIME_ZONE"=> "America/New_York",

    //PRODUCTION DATABASE CREDENTIALS
    "DATABASE_HOST"=>"localhost",
    "DATABASE_NAME"=>"IPlytic",
    "DATABASE_USERNAME"=>"",
    "DATABASE_PASSWORD"=>"PajM4MNAwi5yrFb4",


    //LOCAL DATABASE CREDENTIALS
    "LC_DATABASE_HOST"=>"localhost",
    "LC_DATABASE_NAME"=>"IPlytic",
    "LC_DATABASE_USERNAME"=>"root",
    "LC_DATABASE_PASSWORD"=>"",

    //SMTP CREDENTIALS
    "SMTP_HOST"=>"128.140.85.24",
    "SMTP_USERNAME"=>"glamourgo@zotecsoft.work",
    "SMTP_PASSWORD"=>"iu6!PKPh61-Hr+KN",
    "SMTP_ENC"=>"tls",
    "SMTP_PORT"=>"587",

    //HANKO
    "HANKO_API_URL"=>"https://3b6e0d12-8d3f-4c24-af12-3e870dfd77e8.hanko.io",
    //PayPal SANDBOX
    "PAYPAL_CLIENT_ID" => "AekHJAZu7yWyEwpVdGDlq-zW6V4QpweqmvYZOXc3jJlnOMau4Jw6101PntxGKDQCM1FLFq3KubojvtNp",
    "PAYPAL_CLIENT_SECRET" => "EKq2JRdQCzrc1fdmZf33b8cDLzvY7TrEz5diV7SEyE-_-uZYYIiUYSotwY2N9c6wS_1oHPG9p1hQfIWi",
    "PAYPAL_SANDBOX" => true,

);

use Cycle\Database;
use Cycle\Database\Config;
use Cycle\ORM\ORM;
use Cycle\ORM\Select;
use Cycle\ORM\Entity\Pivot;
$dbal = new Database\DatabaseManager(
    new Config\DatabaseConfig([
        'default' => 'default',
        'databases' => [
            'default' => ['connection' => 'mysql']
        ],
        'connections' => [
            'mysql' => new Config\MySQLDriverConfig(
                connection: new Config\MySQL\TcpConnectionConfig(
                    database: ($env['ENV_TYPE'] == 'production') ? $env['DATABASE_NAME'] : $env['LC_DATABASE_NAME'] ,
                    host: ($env['ENV_TYPE'] == 'production') ? $env['DATABASE_HOST'] : $env['LC_DATABASE_HOST'] ,
                    port: 3306,
                    user: ($env['ENV_TYPE'] == 'production') ? $env['DATABASE_USERNAME'] : $env['LC_DATABASE_USERNAME'] ,
                    password: ($env['ENV_TYPE'] == 'production') ? $env['DATABASE_PASSWORD'] : $env['LC_DATABASE_PASSWORD'] ,
                ),
                queryCache: true
            ),

        ]
    ])
);
$h=$dbal->database('default');
//SESSION
ob_start();
date_default_timezone_set($env['TIME_ZONE']);
//VIEWS LOADER BY TWIG
$loader = new \Twig\Loader\FilesystemLoader('views/');
if($env['ENV_TYPE'] == 'production'){
    $twig = new \Twig\Environment($loader, [
        'cache' => 'cache',
    ]);
}else{
    $twig = new \Twig\Environment($loader);
}
$twig->addGlobal('env', $env);
$twig->addGlobal('currentYear', date("Y"));
$twig->addGlobal('route', @$route);

//END OF VIEWS LOADER BY TWIG

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$mail = new PHPMailer(true);
//SMTP CREDENTIALS

//$mail_settings = $h->table('smtp_settings')->select()->where('id', '=', 1)->fetchAll();

//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->isSMTP();
$mail->Host       = 'zotecsoft.work'; // SMTP server address
$mail->SMTPAuth   = true;             // Enable SMTP authentication
$mail->Username   = 'glamourgo@zotecsoft.work';  // SMTP username
$mail->Password   = 'iu6!PKPh61-Hr+KN';  // SMTP password
$mail->SMTPSecure = 'tls'; // Encryption method (STARTTLS/SSL)
$mail->Port       = 587;                      // TCP port to connect to


//DEFINE YOUR GLOBAL STUFF HERE
if(isset($_SESSION['users']) && !empty($_SESSION['users'])):
    $loginUserId=$_SESSION['users']['id'];
    $loginUserType=$_SESSION['users']['type'];
    $userInfo = $h->table('users')->select()->where('id', '=', $loginUserId)->fetchAll();
    $loginUserName=$userInfo[0]['first_name'];
    $loginUserLast_name=$userInfo[0]['last_name'];
    $loginUserProfile_image=$userInfo[0]['profile_image'];
    $loginUserEmail=$userInfo[0]['email'];
    $twig->addGlobal('loginId', $loginUserId);
    $twig->addGlobal('loginType', $loginUserType);
    $twig->addGlobal('loginName', $loginUserName);
    $twig->addGlobal('first_name', $userInfo[0]['first_name']);
    $twig->addGlobal('last_name', $userInfo[0]['last_name']);
    $twig->addGlobal('type', $userInfo[0]['type']);
    $twig->addGlobal('profile_image', $userInfo[0]['profile_image']);
    $twig->addGlobal('loginEmail', $loginUserEmail);


endif;