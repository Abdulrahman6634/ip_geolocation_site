<?php
/////////////////////////
///////ZOTEC FRAMEWORK
//////admin@zotecsoft.com
////////////////////////
require 'vendor/autoload.php';
use PHRETS\Configuration;
use PHRETS\Session;
use PHRETS\Models\Search\Results;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Tracy\Debugger;
use Twilio\Rest\Client as TwilioClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

require_once('config/env.php'); // Load environment configuration
Debugger::enable();
session_start();

// Function to set CORS headers for API routes
function setCORSHeaders() {
    header_remove("Access-Control-Allow-Origin");
    header_remove("Access-Control-Allow-Methods");
    header_remove("Access-Control-Allow-Headers");
    header_remove("Access-Control-Allow-Credentials");

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    }

    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit();
    }
}


function get($route, $path_to_include, $page_name = NULL) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        route($route, $path_to_include, $page_name);
    }
}

function post($route, $path_to_include, $page_name = NULL) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        route($route, $path_to_include, $page_name);
    }
}

function put($route, $path_to_include) {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        route($route, $path_to_include);
    }
}

function patch($route, $path_to_include) {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
        route($route, $path_to_include);
    }
}

function delete($route, $path_to_include) {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        route($route, $path_to_include);
    }
}

function any($route, $path_to_include) {
    route($route, $path_to_include);
}

function route($route, $path_to_include, $page_name = NULL) {
    global $PAGE_NAME;
    $PAGE_NAME = $page_name;
    $ROOT = $_SERVER['DOCUMENT_ROOT'];

    // Apply CORS headers for API routes
    if (preg_match('#^/api/#', $_SERVER['REQUEST_URI'])) {
        setCORSHeaders();
    }

    if ($route == "/404") {
        include_once("$ROOT/$path_to_include");
        exit();
    }

    $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    $request_url = rtrim($request_url, '/');
    $request_url = strtok($request_url, '?');
    $route_parts = explode('/', $route);
    $request_url_parts = explode('/', $request_url);
    array_shift($route_parts);
    array_shift($request_url_parts);

    if (@$route_parts[0] == '' && count($request_url_parts) == 0) {
        include_once("$ROOT/$path_to_include");
        exit();
    }

    if (count($route_parts) != count($request_url_parts)) {
        return;
    }

    $parameters = [];
    for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
        $route_part = $route_parts[$__i__];
        if (preg_match("/^[$]/", $route_part)) {
            $route_part = ltrim($route_part, '$');
            array_push($parameters, $request_url_parts[$__i__]);
            $$route_part = $request_url_parts[$__i__];
        } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
            return;
        }
    }

    include_once("$ROOT/$path_to_include");
    exit();
}

function out($text){echo htmlspecialchars($text);}
function set_csrf(){
    $csrf_token = bin2hex(random_bytes(25));
    $_SESSION['csrf'] = $csrf_token;
    return $csrf_token;
}
function is_csrf_GET_script(){
    if( ! isset($_SESSION['csrf']) || ! isset($_GET['csrf'])){ return false; }
    if( $_SESSION['csrf'] != $_GET['csrf']){ return false; }
    return true;
}
function is_csrf_valid(){
    if( ! isset($_SESSION['csrf']) || ! isset($_POST['csrf'])){ return false; }
    if( $_SESSION['csrf'] != $_POST['csrf']){ return false; }
    return true;
}

function commaSeperated($string){
    $prefix=$temp="";
    foreach ($string as $s){
        if(!empty($s || $s != NULL)):
            $temp.=$prefix.$s;
            $prefix=',';
        endif;
    }
    return $temp;
}
function commaSeperatedToArray($string){
    $str_arr = explode (",", trim($string));
    return $str_arr;
}
function checkLanguage($lang_arr, $value){
    $re="selected";
    foreach ($lang_arr as $lang){
        if($lang== $value){
            return $re;
        }
    }
}
//Multi  Image Resize Upload
function upload($f_name, $f_path){

    $target_dir = $f_path;
    $target_file = $target_dir .basename($_FILES[$f_name]['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$f_name]["tmp_name"]);
        if($check !== false) {
            return "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            return "File is not an image.";
            $uploadOk = 0;
        }
    }
// Check if file already exists
//    if (file_exists($target_file)) {
//        return "Sorry, file already exists.";
//        $uploadOk = 0;
//    }
// Check file size
    if ($_FILES[$f_name]["size"] > 5000000000) {
        return "null";
        $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp"
        && $imageFileType != "svg" && $imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType != "gif") {
        return "null";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return "null";
// if everything is ok, try to upload file
    } else {

        $temp = explode(".", $_FILES[$f_name]['name']);
        $orignalName = pathinfo($_FILES[$f_name]['name'], PATHINFO_FILENAME);
        $newfilename = rand().round(microtime(true)) . '.' . end($temp);

        if (move_uploaded_file($_FILES[$f_name]["tmp_name"], $target_dir.$newfilename)) {
            return $newfilename;
        } else {
            return "null";
        }
    }
}
function uploadMultiResizeImage($f_name, $f_path,$f_thumbnail,$width,$height)
{
    $insertValuesSQL[]='';
    $targetDir = $f_path;
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    $fileNames = array_filter($_FILES[$f_name]['name']);
    if (!empty($fileNames)) {
        foreach ($_FILES[$f_name]['name'] as $key => $val) {
            // File upload path
            $fileName = basename($_FILES[$f_name]['name'][$key]);
            $targetFilePath = $targetDir . $fileName;

            // Check whether file type is valid
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                $temp = explode(".", $_FILES[$f_name]['name'][$key]);
                $newfilename = round(microtime(true)).rand(100,10000).'.' . end($temp);
                // Upload file to server
                if (move_uploaded_file($_FILES[$f_name]["tmp_name"][$key], $targetDir . $newfilename)) {
                    $filepath=$targetDir.$newfilename;
                    $thumbnailPath = $f_thumbnail . $newfilename;
                    $img = Image::make($filepath);
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($thumbnailPath);
                    // Image db insert sql
                    $insertValuesSQL[] .= $newfilename;
                }
            }
        }
        $imgz=trim(implode(',',$insertValuesSQL), ",");
        return $imgz;
    }
}
//Favicon Resize Upload



function uploadContractImage($f_name, $f_path, $f_thumbnail, $width, $height)
{
    // Initialize ImageManager with the Imagick driver
    $manager = new ImageManager(new Driver());

    // Set the allowed file types
    $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];

    try {
        // Check if the file is uploaded
        if (!isset($_FILES[$f_name]) || $_FILES[$f_name]['error'] != UPLOAD_ERR_OK) {
            throw new Exception("No file was uploaded or there was an upload error.");
        }

        // Get the file information
        $fileName = basename($_FILES[$f_name]['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file type
        if (!in_array($fileType, $allowTypes)) {
            throw new Exception("Invalid file type. Allowed types are: " . implode(', ', $allowTypes));
        }

        // Generate a new unique filename
        $newFilename = round(microtime(true)) . rand(100, 10000) . '.' . $fileType;
        $targetFilePath = $f_path . $newFilename;

        // Ensure the upload directory exists
        if (!is_dir($f_path) && !mkdir($f_path, 0755, true)) {
            throw new Exception("Failed to create upload directory.");
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES[$f_name]["tmp_name"], $targetFilePath)) {
            throw new Exception("Failed to move the uploaded file.");
        }

        // Resize the image while maintaining aspect ratio
        $image = $manager->read($targetFilePath);
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Ensure the thumbnail directory exists
        if (!is_dir($f_thumbnail) && !mkdir($f_thumbnail, 0755, true)) {
            throw new Exception("Failed to create thumbnail directory.");
        }

        // Save the resized image as a thumbnail
        $thumbnailPath = $f_thumbnail . $newFilename;
        $image->save($thumbnailPath);

        // Return the new filename
        return $newFilename;

    } catch (Exception $e) {
        // Log the error or handle it as necessary
        error_log("Image upload error: " . $e->getMessage());
        throw $e; // Re-throw the exception for further handling
    }

    // Return null if something went wrong (though this line shouldn't be reached due to exception handling)
    return null;
}
function uploadResizeFavicon($f_name, $f_path,$f_thumbnail,$width,$height){

    $target_dir = $f_path;
    $target_file = $target_dir . basename($_FILES[$f_name]['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$f_name]["tmp_name"]);
        if($check !== false) {
            return "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            return "File is not an image.";
            $uploadOk = 0;
        }
    }
// Check if file already exists
    if (file_exists($target_file)) {
        return "Sorry, file already exists.";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES[$f_name]["size"] > 5000000000) {
        return "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "svg" && $imageFileType != "pdf" && $imageFileType != "docx") {
        return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {

        $temp = explode(".", $_FILES[$f_name]['name']);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if (move_uploaded_file($_FILES[$f_name]["tmp_name"], $target_dir.$newfilename)) {
            $filepath=$target_dir.$newfilename;
            $thumbnailPath = $f_thumbnail.$newfilename;
            $img = Image::make($filepath);
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailPath);
            return $newfilename;
        } else {
            return "Sorry, there was an error uploading your file.";
        }
    }
}


//Single  Image Resize Upload
function uploadResizeImage($f_name, $f_path,$f_thumbnail,$width,$height){

    $target_dir = $f_path;
    $target_file = $target_dir . basename($_FILES[$f_name]['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$f_name]["tmp_name"]);
        if($check !== false) {
            return array('error'=>true, 'message'=>"File is an image - " . $check["mime"] . ".");
            $uploadOk = 1;
        } else {
            return array('error'=>true, 'message'=>"File is not an image.");
            $uploadOk = 0;
        }
    }
// Check if file already exists
    if (file_exists($target_file)) {
        return array('error'=>true, 'message'=>"Sorry, file already exists.");
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES[$f_name]["size"] > 5000000000) {
        return array('error'=>true, 'message'=>"Sorry, your file is too large.");
        $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "svg" && $imageFileType != "pdf" && $imageFileType != "docx") {
        return array('error'=>true, 'message'=>"Sorry, only JPG, JPEG, PNG files are allowed.");

        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return array('error'=>true, 'message'=>"Sorry, your file was not uploaded.");
// if everything is ok, try to upload file
    } else {

        $temp = explode(".", $_FILES[$f_name]['name']);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if (move_uploaded_file($_FILES[$f_name]["tmp_name"], $target_dir.$newfilename)) {
            $filepath=$target_dir.$newfilename;
            $thumbnailPath = $f_thumbnail.$newfilename;
            $img = Image::make($filepath);
            $img->resize($width, $height);//, function ($constraint) {
            //$constraint->aspectRatio();
            //$constraint->upsize();
            //});
            $img->save($thumbnailPath);
            return array('error'=>false, 'message'=>"Upload Successfully",'filename'=>$newfilename );
            //return $newfilename;
        } else {
            return array('error'=>true, 'message'=>"Sorry, there was an error uploading your file.");
        }
    }
}
function uploadDoc($f_name, $f_path){
    $target_dir = $f_path;
    $target_file = $target_dir . basename($_FILES[$f_name]['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$f_name]["tmp_name"]);
        if($check !== false) {
            return "File is an Zip - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            return "File is not an image.";
            $uploadOk = 0;
        }
    }
// Check if file already exists
    if (file_exists($target_file)) {
        return $arr=array("file_name"=>"not found", "status"=>"202", "error"=>"Sorry, file already exists.");
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES[$f_name]["size"] > 5000000000) {
        return $arr=array("file_name"=>"not found", "status"=>"202", "error"=>"Sorry, your file is too large.");
        $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "zip" && $imageFileType != "ZIP" && $imageFileType != "rar") {
        return $arr=array("file_name"=>"not found", "status"=>"202", "error"=>"Sorry, only zip, rar files are allowed.");
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return $arr=array("file_name"=>"not found", "status"=>"202", "error"=>"Sorry, your file was not uploaded.");

// if everything is ok, try to upload file
    } else {
        $temp = explode(".", $_FILES[$f_name]['name']);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if (move_uploaded_file($_FILES[$f_name]["tmp_name"], $target_dir.$newfilename)) {

            return $arr=array("file_name"=>$newfilename, "status"=>"200", "error"=>"File Successfully Submitted.");
        } else {
            return $arr=array("file_name"=>"not found", "status"=>"202", "error"=>"Sorry, there was an error uploading your file.");
        }
    }
}
function datetimeToDate($date){
    $datetime   = strtotime($date);
    return date('Y-m-d', $datetime);
}

function get_words($sentence, $count = 15) {
    preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
    return $matches[0].'....';
}
function getIPAddress() {
    //whether ip is from the share internet
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];

    }

    //whether ip is from the proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
//whether ip is from the remote address
    else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
//GET COUNTRY
function getClientCountry($ip) {
    //whether ip is from the share internet

    $iptolocation = 'https://api.country.is/'.$ip;

    $creatorlocation = trim(file_get_contents($iptolocation));
    return $country =json_decode($creatorlocation, true);
}

//get clients geolocation
function getGeoLocation($ip) {
    $ipv = (strpos($ip, ":") !== false) ? "IPv6" : "IPv4";

    // List of services with their parsing logic
    $services = [
        // Service 1: ipapi.co
        function($ip) use ($ipv) {
            $url = "https://ipapi.co/{$ip}/json/";
            $response = @file_get_contents($url);
            if ($response === FALSE) return null;

            $data = json_decode($response, true);
            if (!$data || isset($data['error'])) return null;

            return [
                'ip'           => $data['ip'] ?? $ip,
                'ipv'          => $ipv,
                'country_code' => $data['country_code'] ?? null,
                'region'       => $data['region'] ?? null,
                'city'         => $data['city'] ?? null,
                'postal'       => $data['postal'] ?? null,
                'latitude'     => $data['latitude'] ?? null,
                'longitude'    => $data['longitude'] ?? null,
                'org'          => $data['org'] ?? null,
                'isp'          => $data['org'] ?? null,
                'asn'          => null,
                'proxy'        => "False",
                'type'         => $ipv,
            ];
        },

        // Service 2: ipinfo.io
        function($ip) use ($ipv) {
            $url = "https://ipinfo.io/{$ip}/json";
            $response = @file_get_contents($url);
            if ($response === FALSE) return null;

            $data = json_decode($response, true);
            if (!$data || isset($data['error'])) return null;

            $loc = isset($data['loc']) ? explode(',', $data['loc']) : [null, null];

            return [
                'ip'           => $data['ip'] ?? $ip,
                'ipv'          => $ipv,
                'country_code' => $data['country'] ?? null,
                'region'       => $data['region'] ?? null,
                'city'         => $data['city'] ?? null,
                'postal'       => $data['postal'] ?? null,
                'latitude'     => $loc[0] ?? null,
                'longitude'    => $loc[1] ?? null,
                'org'          => $data['org'] ?? null,
                'isp'          => $data['org'] ?? null,
                'asn'          => null,
                'proxy'        => "False",
                'type'         => $ipv,
            ];
        },

        // Service 3: ip-api.com
        function($ip) use ($ipv) {
            $url = "http://ip-api.com/json/{$ip}";
            $response = @file_get_contents($url);
            if ($response === FALSE) return null;

            $data = json_decode($response, true);
            if (!$data || $data['status'] !== 'success') return null;

            return [
                'ip'           => $ip,
                'ipv'          => $ipv,
                'country_code' => $data['countryCode'] ?? null,
                'region'       => $data['regionName'] ?? null,
                'city'         => $data['city'] ?? null,
                'postal'       => $data['zip'] ?? null,
                'latitude'     => $data['lat'] ?? null,
                'longitude'    => $data['lon'] ?? null,
                'org'          => $data['org'] ?? null,
                'isp'          => $data['isp'] ?? null,
                'asn'          => $data['as'] ?? null,
                'proxy'        => "False",
                'type'         => $ipv,
            ];
        }
    ];

    // Try each service until one succeeds
    foreach ($services as $service) {
        $result = $service($ip);
        if ($result !== null) {
            return $result;
        }
    }

    // All failed
    return null;
}



function generateMessage($name, $email, $phone, $websiteRequirement, $flexiblePayment, $paymentMethod, $ip, $agent) {
    $countryCode=getClientCountry($ip);
    $msgMM = "
  COSGN LEADS*
- **REQ ID:".random_strings('5')."**
*COSGN INC.*

- Full Name: $name
- Email: $email
- Mobile Number: $phone

- New Website or Upgrade?: 
$websiteRequirement

- How soon do you plan to start your project, and what is your budget range?:
$flexiblePayment

- paymentMethod: 
$paymentMethod

- IP: $ip
- Country: $countryCode
- User Agent: $agent
---------------------

";
    return $msgMM;
}

function SentMessgeToMatterMost($message, $token, $channel_id){
    $api_url = 'https://team.zotecsoft.com';
    $data = array(
        'channel_id' => $channel_id,
        'message' => $message
    );
    $client = new Client([
        'base_uri' => $api_url,
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ]
    ]);
    try {
        $response = $client->post('/api/v4/posts', [
            'json' => $data
        ]);

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }

}
//GET OS
$user_agent = $_SERVER['HTTP_USER_AGENT'];
function getOS() {

    global $user_agent;

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
        '/windows nt 10/i'      =>  'Windows',
        '/windows nt 6.3/i'     =>  'Windows',
        '/windows nt 6.2/i'     =>  'Windows',
        '/windows nt 6.1/i'     =>  'Windows',
        '/windows nt 6.0/i'     =>  'Windows',
        '/windows nt 5.2/i'     =>  'Windows',
        '/windows nt 5.1/i'     =>  'Windows',
        '/windows xp/i'         =>  'Windows',
        '/windows nt 5.0/i'     =>  'Windows',
        '/windows me/i'         =>  'Windows',
        '/win98/i'              =>  'Windows',
        '/win95/i'              =>  'Windows',
        '/win16/i'              =>  'Windows',
        '/macintosh|mac os x/i' =>  'Mac',
        '/mac_powerpc/i'        =>  'Mac',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}
function getBrowser() {

    global $user_agent;

    $browser        = "Unknown Browser";

    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}
function random_strings($length_of_string)
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length_of_string);
}
function userLogin($email, $password, $table_name) {
    global $h;

    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($email) && !empty($email) && isset($password) && !empty($password)) {
        // $stmt = $h->$table_name->select()->where('email', '=', $email)->where('status', '=', 'active')->fetchAll();
        // For debugging
        try {
            $stmt = $h->$table_name->select()->where('email', '=', $email)->where('status', '=', 'active')->fetchAll();
            // $stmt = $CONN->query("SELECT * FROM $table_name WHERE `email`='$email' AND `status`='active'");
            if (!empty($stmt[0])) {
                if (password_verify($password, $stmt[0]['password'])) {
                    $_SESSION[$table_name] = $stmt[0];
                    if ($stmt[0]['type'] == 'admin') {
                        http_response_code(200);
                        return json_encode(array("statusCode" => 200, "message" => "Successfully Login..", "type" => "admin", "path" => "/admin/dashboard"));
                    } else if ($stmt[0]['type'] == 'user') {
                        http_response_code(200);
                        return json_encode(array("statusCode" => 200, "message" => "Successfully Login..","id" => $stmt[0]['id'], "email" => $stmt[0]['email'], "type" => "user", "path" => "/user/dashboard"));
                    } else {
                        http_response_code(202);
                        return json_encode(array("statusCode" => 202, "message" => "Invalid Details!"));
                    }
                } else {
                    http_response_code(202);
                    return json_encode(array("statusCode" => 202, "message" => "Invalid Password"));
                }
            } else {
                http_response_code(202);
                return json_encode(array("statusCode" => 202, "message" => "Invalid Email or Password. Please Try Again."));
            }
        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(array("statusCode" => 500, "message" => "Server error: " . $e->getMessage()));
        }
    }
}


function userRegister($first_name, $email, $password, $confirm_password, $table_name) {
    global $h;

    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($email) && !empty($email) && isset($password) && !empty($password) && isset($confirm_password) && !empty($confirm_password)) {
        // CSRF token validation (commented out as in original)
        /*
        if (!is_csrf_valid()) {
            http_response_code(202);
            return json_encode(array("statusCode" => 202, "message" => "Invalid CSRF Token. Please <a href='javascript:refresh_page()' onclick='refresh_page();return false;'>Refresh Page.</a>"));
            exit();
        }
        */

        // Password and confirm password match validation
        if ($password !== $confirm_password) {
            http_response_code(202);
            return json_encode(array("statusCode" => 202, "message" => "Password and Confirm Password do not match."));
            exit();
        }

        // Password strength validation
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
            http_response_code(202);
            return json_encode(array("statusCode" => 202, "message" => "A minimum 8 characters password contains a combination of uppercase and lowercase letters and numbers."));
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $userAvailable = $h->table($table_name)->select()->where('email', '=', $email);
        if ($userAvailable->count() < 1) {
            try {
                // Insert new user into the database
                $h->table($table_name)->insertOne([
                    'email' => $email,
                    'password' => $hashed_password,
                    'first_name' => $first_name,
                ]);

                // Fetch the newly created user
                $user = $h->table($table_name)
                    ->select()
                    ->where('email', '=', $email)
                    ->fetchAll();

                if (!$user) {
                    http_response_code(500);
                    return json_encode(array("statusCode" => 500, "message" => "Failed to retrieve user data after registration."));
                    exit();
                }

                $user = $user[0]; // Extract first user

                // Generate JWT token
                $token = generate_jwt_token($user['id'], $user['email']);

                 // Store token in api_keys
                $h->table('api_keys')->insertOne([
                    'userId' => $user['id'],
                    'token'  => $token
                ]);

                $_SESSION['users'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'type' => $user['type'] ?? 'user', // default type if not set
                ];

                http_response_code(200);
                return json_encode(array(
                    "statusCode" => 200,
                    "message" => "Successfully Registered.",
                    "path" => "/dashboard",
                    "user" => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'first_name' => $user['first_name'],
                    ]
                ));
            } catch (PDOException $e) {
                http_response_code(500);
                return json_encode(array("statusCode" => 500, "message" => "Server Side error, try again!"));
                exit();
            }
        } else {
            http_response_code(202);
            return json_encode(array("statusCode" => 202, "message" => "Email Already Exists. Login to continue."));
        }
    } else {
        http_response_code(202);
        return json_encode(array("statusCode" => 202, "message" => "Please fill all the required fields."));
    }
}

function setPassword($password, $verify_code, $table_name){
    global $h;
//CHECK PASSWORD
    if (isset($password) && !empty($password)) {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
            http_response_code(202);
            return json_encode(array("statusCode" => 202, "message"=>"A minimum 8 characters password contains a combination of uppercase and lowercase letter and number."));
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }
    } else {
        http_response_code(202);
        return json_encode(array("statusCode" => 202, "message"=>"Password is Required."));
        exit();
    }
    $noRows = $h->$table_name->select()->where('verify_code', '=', $verify_code);

    if($noRows->count() < 1){
        http_response_code(202);
        return json_encode(array("statusCode" => 202, "message"=>"Wrong Email or Verification Code."));
    }else{
        try{
            $updatePassSQL= $h->$table_name->update(['password' => $hashed_password])->where('verify_code', '=', $verify_code)->run();

//            $updatePassSQL = "UPDATE $table_name SET password=? WHERE email=? AND verify_code=?";
//            $CONN->prepare($updatePassSQL)->execute([$hashed_password, $email, $verify_code]);

            //change Verify Code
            $verify_codenew=round(microtime(true));
            $h->$table_name->update(['verify_code' => $verify_codenew])->where('verify_code', '=', $verify_code)->run();

//            $changeVerifyCodeSQL = "UPDATE $table_name SET verify_code=? WHERE email=?";
//            $CONN->prepare($changeVerifyCodeSQL)->execute([$verify_code, $email]);

            http_response_code(200);
            return json_encode(array("statusCode" => 200, "message"=>"Password Successfully Changed"));
        }catch (PDOException $e){
            http_response_code(202);
            return json_encode(array("statusCode" => 202, "message"=>$e));
        }
    }
}
function forgetPassword($email, $table_name){
    global $h,$settings;
    $userAvailable = $h->$table_name->select()
        ->where('email', '=', $email);
    // $userAvailable = $CONN->query("SELECT COUNT(*) FROM $table_name WHERE email='$email'")->fetchColumn();
    if($userAvailable->count() < 1){
        http_response_code(202);
        return json_encode(array("statusCode" => 202, "message"=>"Email Not Found. Please SignUp to Continue."));
        exit();
    }else{
        try{
            forgetPasswordEmail($email, $table_name);

            http_response_code(200);
            return json_encode(array("statusCode" => 200, "message"=>"Forget password email has been send to your inbox."));
        }catch(PDOException $e){
            http_response_code(202);
            return json_encode(array("statusCode" => 202, "message"=>$e));
        }

    }
}
///MAIL SENDER
function mailSender($admin_email,$email,$subject,$message,$mail){
    global $env;
    //Recipients
    $mail->setFrom($admin_email, $env['SITE_NAME']);
    $mail->addAddress($email);               //Name is optional
    $mail->addReplyTo($admin_email);
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    try {
        $mail->send();
        return "Email has been sent successfully! Please check your inbox.";

    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}
function forgetPasswordEmail($email, $table_name){
    global $h;
    global $env;
    global $mail,$settings;
    $verify_code = rand(1000, 9999); // e.g., 4723
//    $verify_code=round(microtime(true));
    //SAVING VERIFICATION CODE
//    $sql = "UPDATE $table_name SET verify_code=? WHERE email=?";
//    $CONN->prepare($sql)->execute([$verify_code, $email]);
    $_SESSION['reset']= $email;
    $sql= $h->$table_name->update([
        'verify_code' => $verify_code,
    ])->where('email', '=', $email)->run();

    //FORGET EMAIL
    include "views/admin/email-template/email-template/forget-password.php";
    mailSender($env['SENDER_EMAIL'],$email,'Forget Password - '.$env['SITE_NAME'],$message,$mail);


}

function define_once($name, $value){
    if (!defined($name)) define($name, $value);
}
function userDetails($loginUserId){
    global $CONN;
    $userDetails = $CONN->query("SELECT * FROM users WHERE id='$loginUserId'")->fetch();
    return $userDetails;
}
function slugify($str, $delimiter = '-')
{
    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;
}


function getRelativeTime($timestamp, $timezone = 'UTC') {
    // Create a DateTime object for the provided timestamp
    $dateTime = new DateTime($timestamp, new DateTimeZone($timezone));

    // Create a DateTime object for the current time in the specified timezone
    $currentTime = new DateTime('now', new DateTimeZone($timezone));

    // Calculate the difference between the current time and the provided timestamp
    $interval = $currentTime->diff($dateTime);

    // Determine the relative time string based on the interval
    if ($interval->y > 0) {
        // Years
        return $interval->y === 1 ? '1 year ago' : $interval->y . ' years ago';
    } elseif ($interval->m > 0) {
        // Months
        return $interval->m === 1 ? '1 month ago' : $interval->m . ' months ago';
    } elseif ($interval->d > 0) {
        // Days
        return $interval->d === 1 ? '1 day ago' : $interval->d . ' days ago';
    } elseif ($interval->h > 0) {
        // Hours
        return $interval->h === 1 ? '1 hour ago' : $interval->h . ' hours ago';
    } elseif ($interval->i > 0) {
        // Minutes
        return $interval->i === 1 ? '1 minute ago' : $interval->i . ' minutes ago';
    } else {
        // Seconds
        return $interval->s <= 1 ? '1 second ago' : $interval->s . ' seconds ago';
    }
}
function sendSMS($sender_no, $to, $msg){
    $sid    = "AC511d16ef7b25af7397fbbff705fe1414";
    $token  = "6145787f199e0e7bc1533e2a53b27c5f";
    $twilio = new TwilioClient($sid, $token);
    $message = $twilio->messages
        ->create($to, // to
            array(
                "from" => $sender_no,
                "body" => $msg
            )
        );
    return $message;
}

function PaypalProductCreate(){
    $client = new Client([
        'base_uri' => 'https://api-m.sandbox.paypal.com']);

    try {
        // Make the POST request
        $response = $client->request('POST', '/v1/catalogs/products', [
            'headers' => [
                'Authorization' => 'Basic <client_id>:<secret>',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'PayPal-Request-Id' => 'PRODUCT-18062019-001',
                'Prefer' => 'return=representation',
            ],
            'json' => [
                'name' => 'Video Streaming Service',
                'description' => 'Video streaming service',
                'type' => 'SERVICE',
                'category' => 'SOFTWARE',
                'image_url' => 'https://example.com/streaming.jpg',
                'home_url' => 'https://example.com/home',
            ]
        ]);

        // Get the response body and decode it
        $responseBody = json_decode($response->getBody(), true);

        // Print the response
        print_r($responseBody);

    } catch (RequestException $e) {
        // Handle errors or exceptions
        if ($e->hasResponse()) {
            echo $e->getResponse()->getBody();
        } else {
            echo $e->getMessage();
        }
    }


}

function SendOTP($to){
    $sid    = "AC511d16ef7b25af7397fbbff705fe1414";
    $token  = "6145787f199e0e7bc1533e2a53b27c5f";
    $twilio = new TwilioClient($sid, $token);
    $verification = $twilio->verify->v2->services("VA5ebd1373d7debfbe3704cdcb334c73af")
        ->verifications
        ->create($to, "sms");
    return $verification;
}

function VerifyOTP($to,$code){
    $sid    = "AC511d16ef7b25af7397fbbff705fe1414";
    $token  = "6145787f199e0e7bc1533e2a53b27c5f";
    $twilio = new  TwilioClient($sid, $token);

    $verification_check = $twilio->verify->v2->services("VA5ebd1373d7debfbe3704cdcb334c73af")
        ->verificationChecks
        ->create([
                "to" =>$to,
                "code" => $code
            ]
        );
    return $verification_check;
}
function addNotification($message, $title, $type, $user_id) {
    global $h;
    try {
        // Validate user_id is an integer
        if (!is_int($user_id) || $user_id <= 0) {
            throw new Exception('Invalid user_id: must be a positive integer');
        }
        // Validate type is a non-empty string
        if (empty($type) || !is_string($type)) {
            throw new Exception('Invalid type: must be a non-empty string');
        }
        // Validate message and title
        if (empty($message) || empty($title)) {
            throw new Exception('Message and title cannot be empty');
        }

        $h->table('notifications')->insertOne([
            'message' => $message,
            'title' => $title,
            'type' => $type,
            'user_id' => (int)$user_id // Ensure user_id is cast to int
        ]);
        return ['status' => 200, 'message' => 'Notification added successfully'];
    } catch (Exception $e) {
        return ['status' => 202, 'message' => 'Error Occurred: ' . $e->getMessage()];
    }
}

function readNotification($id){
    global $h;
    try{
        $h->table('notifications')->update(['status' => 'read'])->where('id', $id)->run();
    } catch (RequestException $e) {
        echo json_encode(array('status'=>202, 'message'=>'Error Occured.'));
    }
}

//generate JWT token
function generate_jwt_token($userId, $email) {
    // Secret key (keep this safe, e.g., in .env)
    $secret_key = "6096ec172f9f94ae6a44a64ece2a7878";

    // Header
    $header = json_encode([
        'alg' => 'HS256',
        'typ' => 'JWT'
    ]);

    // Payload
    $payload = json_encode([
        'id'    => $userId,
        'email' => $email,
        'iat'   => time(),           // Issued at
        'exp'   => time() + 3600     // Expiry (1 hour)
    ]);

    // Encode to Base64Url
    $base64UrlHeader  = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
    $base64UrlPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

    // Signature
    $signature = hash_hmac(
        'sha256',
        $base64UrlHeader . "." . $base64UrlPayload,
        $secret_key,
        true
    );
    $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    // Final JWT
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

//validate jwt token
function validate_jwt_token($token, $userId, $h) {
    $secret_key = "6096ec172f9f94ae6a44a64ece2a7878";

    // Split JWT into parts
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return ['valid' => false, 'message' => 'Invalid token format'];
    }

    list($headerB64, $payloadB64, $signatureB64) = $parts;

    // Decode payload
    $payloadJson = base64_decode(strtr($payloadB64, '-_', '+/'));
    $payload = json_decode($payloadJson, true);

    if (!$payload || !isset($payload['id'], $payload['exp'])) {
        return ['valid' => false, 'message' => 'Invalid payload'];
    }

    // Verify expiration
    if ($payload['exp'] < time()) {
        return ['valid' => false, 'message' => 'Token expired'];
    }

    // Verify userId matches payload
    if ((int)$payload['id'] !== (int)$userId) {
        return ['valid' => false, 'message' => 'User mismatch'];
    }

    // Verify signature
    $expectedSignature = rtrim(strtr(base64_encode(
        hash_hmac('sha256', $headerB64 . "." . $payloadB64, $secret_key, true)
    ), '+/', '-_'), '=');

    if (!hash_equals($expectedSignature, $signatureB64)) {
        return ['valid' => false, 'message' => 'Invalid signature'];
    }

    // ✅ Verify token exists in api_keys table
    $key = $h->table('api_keys')
             ->select()
             ->where('userId', $userId)
             ->fetchAll();

    if (empty($key) || $key[0]['token'] !== $token) {
        return ['valid' => false, 'message' => 'Token not found in database'];
    }

    return ['valid' => true, 'message' => 'Token is valid', 'payload' => $payload];
}

?>