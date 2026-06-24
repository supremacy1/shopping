<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : (isset($_POST['qty']) ? intval($_POST['qty']) : 1);
    if ($qty < 1) $qty = 1;

    // Check database first to prevent tamper and fetch secure values
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($product = $res->fetch_assoc()) {
        $name = $product['name'];
        $price = $product['price'];
        $image = $product['image'];
    } else {
        // Fallback for hardcoded sample products not yet in the DB
        $name = isset($_POST['name']) ? $_POST['name'] : "Product " . $id;
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
        $image = "";
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