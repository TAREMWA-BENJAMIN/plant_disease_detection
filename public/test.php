<?php

header('Content-Type: application/json');
echo json_encode([
    'message' => 'XAMPP test successful',
    'php_version' => PHP_VERSION,
    'server' => $_SERVER['SERVER_SOFTWARE']
]); 