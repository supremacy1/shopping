<?php
session_start();

$products = [
    ["id" => 1, "name" => "Rice", "description" => "5kg bag of rice", "price" => 8000, "image" => ""],
    ["id" => 2, "name" => "Beans", "description" => "1kg brown beans", "price" => 2500, "image" => ""],
    ["id" => 3, "name" => "Cooking Oil", "description" => "2L vegetable oil", "price" => 4500, "image" => ""],
];

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['qty'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nebula Shop - Demo Products</title>
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
        }

        .navbar {
            background-color: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            background: linear-gradient(135deg, #A5B4FC, #818CF8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cart-link {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .cart-link:hover {
            border-color: var(--text-muted);
            background-color: var(--surface-alt);
        }

        .cart-count {
            background-color: var(--primary);
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .hero {
            max-width: 1200px;
            margin: 40px auto 20px;
            padding: 0 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #F8FAFC, #94A3B8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background-color: var(--surface);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, border-color 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .img-container {
            height: 200px;
            background-color: var(--surface-alt);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-placeholder {
            font-size: 3rem;
            color: var(--text-muted);
            font-weight: 700;
            opacity: 0.3;
        }

        .card-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
        }

        .product-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 16px;
            flex-grow: 1;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 16px;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--success);
        }

        .add-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-input {
            width: 50px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background-color: var(--background);
            color: var(--text);
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-add {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .btn-add:hover {
            background-color: var(--primary-hover);
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">Nebula Shop</a>
        <a href="cart.php" class="cart-link">
            <span>🛒 Cart</span>
            <span class="cart-count"><?= $cart_count ?></span>
        </a>
    </div>
</nav>

<div class="hero">
    <h1>Demo Mockup Products</h1>
    <p>Check out our catalog mockup items. You can also view the live database collection on the homepage.</p>
</div>

<div class="container">
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="img-container">
                    <div class="product-placeholder">
                        <?= strtoupper(substr($product['name'], 0, 2)) ?>
                    </div>
                </div>
                
                <div class="card-content">
                    <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-desc"><?= htmlspecialchars($product['description']) ?></p>
                    
                    <div class="price-row">
                        <span class="product-price">₦<?= number_format($product['price'], 2) ?></span>
                        
                        <form method="POST" action="add_to_cart.php" class="add-form">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="name" value="<?= $product['name'] ?>">
                            <input type="hidden" name="price" value="<?= $product['price'] ?>">
                            <input type="number" name="quantity" class="qty-input" value="1" min="1">
                            <button type="submit" class="btn-add">Order</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
