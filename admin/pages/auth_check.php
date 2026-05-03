<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin/admin_login.php");
    exit;
}

$role = strtolower($_SESSION['role'] ?? 'staff');
$current_page = $current_page ?? '';

$permissions = [
    'admin' => [
        'Dashboard',
        'Products',
        'Categories',
        'Orders',
        'Activity_Logs',
        'Customers',
        'Quotation',
        'Inventory',
        'Admin_Users'
    ],

    'finance' => [
        'Dashboard',
        'Orders',
        'Inventory',
        'Quotation'
    ],

    'staff' => [
        'Orders',
        'Inventory',
        'Customers'
    ]
];

// block access properly
if ($current_page && !in_array($current_page, $permissions[$role] ?? [])) {

    // redirect safe page
    $redirect = match ($role) {
        'admin' => 'Dashboard.php',
        'finance' => 'Dashboard.php',
        'staff' => 'Orders.php',
        default => '/admin/admin_login.php'
    };

    header("Location: $redirect");
    exit;
}
