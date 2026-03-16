<?php
session_start();
header('Content-Type: application/json');

// Disable error display
ini_set('display_errors', 0);
error_reporting(0);

// Clear the cart
$_SESSION['cart'] = [];

// Clean output buffer
if (ob_get_length()) ob_clean();

echo json_encode([
    'success' => true,
    'message' => 'Cart cleared successfully'
]);
exit;
?>