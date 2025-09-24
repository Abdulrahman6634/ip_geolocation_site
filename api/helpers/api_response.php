<?php
function api_response($status_code, $success, $message, $data = null) {
    header('Content-Type: application/json');
    http_response_code($status_code);

    $response = [
        'success' => $success,
        'message' => $message,
        'data' => $data
    ];

    echo json_encode($response);
    exit();
}

function api_error($status_code, $message, $errors = null) {
    api_response($status_code, false, $message, ['errors' => $errors]);
}
?>