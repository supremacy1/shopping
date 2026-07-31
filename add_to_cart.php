<?php
session_start();
include "db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : (isset($_POST['qty']) ? intval($_POST['qty']) : 1);
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
    if ($qty < 1) $qty = 1;

    // Fetch product details like name and image from the database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($product = $res->fetch_assoc()) {
        $name = $product['name'];
        $image = $product['image'];
        $price_naira = $product['price_naira'];
        $price_dollar = $product['price_dollar'];
    } else {
        header("Location: index.php"); // Product not found
        exit;
    }
    $stmt->close();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        // Just increase the quantity
        $_SESSION['cart'][$id]['qty'] += $qty;
    } else {
        // Add new item with all details
        $_SESSION['cart'][$id] = [
            "id" => $id,
            "name" => $name,
            "image" => $image,
            "qty" => $qty,
            "price_naira" => $price_naira,
            "price_dollar" => $price_dollar,
            "price" => $price_naira // For backward compatibility on cart page
        ];
    }

    header("Location: cart.php");
    exit;
}
?>