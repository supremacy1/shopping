<?php
session_start();
include "db.php";

// Handle remove item
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Handle update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['id']);
    $qty = intval($_POST['qty']);
    if ($qty < 1) $qty = 1;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] = $qty;
        $_SESSION['cart'][$id]['total'] = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
    }
    header("Location: cart.php");
    exit;
}

// Handle adding to cart via POST (submitting from index.php directly)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $id = intval($_POST['id']);
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
    if ($qty < 1) $qty = 1;

    // Secure database fetch
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($product = $res->fetch_assoc()) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
            $_SESSION['cart'][$id]['total'] = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
        } else {
            $_SESSION['cart'][$id] = [
                "id" => $id,
                "name" => $product['name'],
                "price" => $product['price'],
                "qty" => $qty,
                "image" => $product['image'],
                "total" => $product['price'] * $qty
            ];
        }
    }
    $stmt->close();
    header("Location: cart.php");
    exit;
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --background: #0B0F19;
            --surface: #1E293B;
            --surface-alt: #334155;
            --text: #F8FAFC;
            --text-muted: #94A3B8;
            --border: #475569;
            --success: #10B981;
            --danger: #EF4444;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px rgba(0, 0, 0, 0.3);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--text);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #A5B4FC, #818CF8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-shop {
            background-color: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-shop:hover {
            background-color: var(--surface);
            border-color: var(--text-muted);
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        @media (max-width: 900px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }
        }

        .cart-card {
            background-color: var(--surface);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .empty-cart {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            display: block;
        }

        .empty-cart-text {
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .cart-table td {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }

        .cart-product {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            background-color: var(--surface-alt);
            border: 1px solid var(--border);
        }

        .product-info-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text);
            margin-bottom: 4px;
        }

        .qty-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-input {
            width: 60px;
            padding: 6px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background-color: var(--background);
            color: var(--text);
            font-size: 0.95rem;
            text-align: center;
        }

        .btn-update {
            background-color: var(--surface-alt);
            color: var(--text);
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-update:hover {
            background-color: var(--border);
        }

        .btn-remove {
            color: var(--danger);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .btn-remove:hover {
            opacity: 0.8;
        }

        .summary-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 1rem;
        }

        .summary-row.total {
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px dashed var(--border);
            color: var(--text);
        }

        .btn-checkout {
            display: block;
            width: 100%;
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 14px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            margin-top: 24px;
        }

        .btn-checkout:hover {
            background-color: var(--primary-hover);
        }

        .btn-checkout:active {
            transform: scale(0.98);
        }

        .badge-total {
            background-color: var(--primary);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-left: 8px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Shopping Cart <span class="badge-total"><?= count($cart_items) ?> items</span></h1>
        <a href="index.php" class="btn-shop">Continue Shopping</a>
    </header>

    <?php if (empty($cart_items)): ?>
        <div class="cart-card empty-cart">
            <span class="empty-cart-icon">🛒</span>
            <p class="empty-cart-text">Your shopping cart is currently empty.</p>
            <a href="index.php" class="btn-checkout" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 0;">Shop Products</a>
        </div>
    <?php else: ?>
        <div class="cart-grid">
            <div class="cart-card" style="overflow-x: auto;">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $id => $item): ?>
                        <tr>
                            <td>
                                <div class="cart-product">
                                    <?php if (!empty($item['image']) && file_exists("uploads/" . $item['image'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="product-img" alt="<?= htmlspecialchars($item['name']) ?>">
                                    <?php else: ?>
                                        <div class="product-img" style="display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--text-muted);">
                                            <?= strtoupper(substr($item['name'], 0, 2)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="product-info-name"><?= htmlspecialchars($item['name']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>₦<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <form method="POST" action="cart.php" class="qty-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="number" name="qty" class="qty-input" value="<?= $item['qty'] ?>" min="1" required>
                                    <button type="submit" class="btn-update">Update</button>
                                </form>
                            </td>
                            <td>₦<?= number_format($item['total'], 2) ?></td>
                            <td>
                                <a href="cart.php?action=remove&id=<?= $id ?>" class="btn-remove">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-card">
                <h2 class="summary-title">Order Summary</h2>
                <div class="summary-row">
                    <span style="color: var(--text-muted);">Subtotal</span>
                    <span>₦<?= number_format($total, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span style="color: var(--text-muted);">Shipping</span>
                    <span style="color: var(--success); font-weight: 500;">Free</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₦<?= number_format($total, 2) ?></span>
                </div>

                <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>