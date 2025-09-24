
<?php
global $h;
require("config/env.php");

if ($route === '/admin/api/products-by-category') {
    header('Content-Type: application/json'); // Fixed: Added header to ensure client parses as JSON
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $categoryId = $_GET['category_id'] ?? null;
            error_log("Category ID received: " . $categoryId);

            if (!$categoryId) {
                throw new Exception('Category ID is missing');
            }

            // Fetch products
            $products = $h->table('products')
                ->select('id, name, price')
                ->where('category_id', $categoryId)
                ->fetchAll();

            error_log("Products fetched: " . count($products));

            echo json_encode([
                'status' => 200,
                'data' => $products,
            ]);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            echo json_encode([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    } else {
        echo json_encode([
            'status' => 405,
            'message' => 'Method not allowed',
        ]);
    }
}