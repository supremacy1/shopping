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
    } else {
        header("Location: index.php"); // Product not found
        exit;
    }
    $stmt->close();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += $qty;
        $_SESSION['cart'][$id]['total'] = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
    } else {
        $_SESSION['cart'][$id] = [
            "id" => $id,
            "name" => $name,
            "price" => $price,
            "qty" => $qty,
            "image" => $image,
            "total" => $price * $qty
        ];
    }

    header("Location: cart.php");
    exit;
}
?>