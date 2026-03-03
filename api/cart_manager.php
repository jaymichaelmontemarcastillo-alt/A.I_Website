<?php
session_start();
include 'products_list.php';

/*
|--------------------------------------------------------------------------
| ADD TO CART
|--------------------------------------------------------------------------
*/

if (isset($_POST['add_cart'])) {

    $id = $_POST['id'];
    $quantity = intval($_POST['quantity']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($products[$id])) {

        // If product exists but structure is wrong → reset safely
        if (
            isset($_SESSION['cart'][$id]) &&
            is_array($_SESSION['cart'][$id])
        ) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                "product" => $products[$id],
                "quantity" => $quantity
            ];
        }
    }

    header("Location: ../cart.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| ADD TO WISHLIST
|--------------------------------------------------------------------------
*/

if (isset($_GET['wishlist'])) {

    $id = $_GET['wishlist'];

    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }

    if (isset($products[$id])) {
        $_SESSION['wishlist'][$id] = $products[$id];
    }

    header("Location: ../wishlist.php");
    exit;
}
