<?php

declare(strict_types=1);

/**
 * Example: Working with the ps_apiresources PrestaShop module.
 *
 * ps_apiresources exposes PrestaShop store data (products, orders, customers,
 * categories) via the PrestaShop API framework. It registers API resources
 * and endpoints for headless/decoupled frontend use cases.
 *
 * This file documents common integration patterns.
 */

// --- REST API endpoint: list products ---
// GET /api/products
// Headers: Authorization: Basic base64(API_KEY:)
//
// curl -u "YOUR_API_KEY:" https://your-shop.com/api/products?output_format=JSON

// --- REST API endpoint: get a single product ---
// GET /api/products/{id}
//
// curl -u "YOUR_API_KEY:" https://your-shop.com/api/products/1?output_format=JSON

// --- Filtering API results ---
// GET /api/products?filter[active]=[1]&display=[id,name,price]&output_format=JSON
//
// Parameters:
//   filter[field]=[value]   — filter by field value
//   display=[field,...]     — select specific fields
//   limit=10                — number of results
//   sort=[name_ASC]         — sort order

// --- Accessing the API from PHP ---
$apiKey  = 'YOUR_API_KEY';
$shopUrl = 'https://your-shop.com';

$context = stream_context_create([
    'http' => [
        'header' => 'Authorization: Basic ' . base64_encode("$apiKey:"),
    ],
]);

$response = file_get_contents("$shopUrl/api/products?output_format=JSON&limit=5", false, $context);
$products = json_decode($response, true);

foreach ($products['products'] as $product) {
    echo "Product ID: {$product['id']}\n";
}

// --- Registering a custom API resource (in a module) ---
// In your module's main PHP file, override ObjectModel resources:
//
// public function hookActionDispatcher(array $params): void
// {
//     // Custom resource registration happens via config/routes.yml
//     // or by extending WebserviceRequest
// }

// --- API schema inspection ---
// GET /api/?output_format=JSON
// Returns all available resources and their supported methods (GET/POST/PUT/DELETE)
