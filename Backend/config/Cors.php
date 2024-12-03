<?php
// class Cors {
//     public static function handleCors() {
//         // Remove headers existentes
//         header_remove('Access-Control-Allow-Origin');
//         header_remove('Access-Control-Allow-Methods');
//         header_remove('Access-Control-Allow-Headers');
//         header_remove('Access-Control-Allow-Credentials');
        
//         // Define novos headers
//         header("Access-Control-Allow-Origin: http://localhost:5173");
//         header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
//         header("Access-Control-Allow-Headers: Content-Type");
//         header("Access-Control-Allow-Credentials: true");
        
//         if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//             http_response_code(200);
//             exit();
//         }
//     }
