<?php
session_start();
include "db.php";

$result = $conn->query("SELECT * FROM products");
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
    <title>Nebula Shop - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4F46E5; /* Indigo */
            --primary-hover: #4338CA;
            --background: #F9FAFB; /* Light Gray */
            --surface: #FFFFFF; /* White */
            --surface-alt: #F3F4F6;
            --text: #111827; /* Dark Gray/Black */
            --text-muted: #6B7280; /* Medium Gray */
            --border: #E5E7EB; /* Light Gray Border */
            --success: #16A34A; /* Darker Green */
            --danger: #DC2626;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
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
            background-color: rgba(255, 255, 255, 0.8);
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
            color: var(--text-muted);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .cart-link:hover, .admin-link:hover {
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
            background: linear-gradient(135deg, #1F2937, #4B5563);
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

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .product-card a {
            text-decoration: none;
            color: inherit;
            display: block;
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
            background-color: var(--surface-alt);
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

        .empty-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background-color: var(--surface);
            border-radius: 16px;
            border: 1px dashed var(--border);
        }

        .empty-products p {
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        /* New styles for currency and price display */
        .price-selector {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 16px;
        }

        .currency-select-wrapper {
            position: relative;
            display: inline-block;
        }

        .currency-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 6px 28px 6px 10px;
            font-size: 0.85rem;
            color: var(--text-muted);
            cursor: pointer;
            width: 100%;
        }

        .currency-select-wrapper::after {
            content: '▼';
            font-size: 0.6rem;
            color: var(--text-muted);
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--success);
        }
        
        .product-price-dollar {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .site-footer {
            margin-top: 60px;
            padding: 30px 20px;
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--text-muted);
        }

        .site-footer p {
            margin: 0;
            font-size: 0.9rem;
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
        <a href="admin/products.php" class="cart-link admin-link" style="margin-left: 10px;">
            <span>Admin</span>
        </a>
    </div>
</nav>

<div class="hero">
    <h1>Explore our collection</h1>
    <p>Handpicked, high-quality items designed to fit your standard lifestyle. Pay securely in seconds.</p>
</div>

<div class="container">
    <div class="product-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <a href="product.php?id=<?= $row['id'] ?>">
                        <div class="img-container">
                            <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="product-img" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <div class="product-placeholder">
                                    <?= strtoupper(substr($row['name'], 0, 2)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <div class="card-content">
                        <a href="product.php?id=<?= $row['id'] ?>">
                            <h3 class="product-name"><?= htmlspecialchars($row['name']) ?></h3>
                        </a>
                        <p class="product-desc"><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 100, "...")) ?></p>
                        
                        <form method="POST" action="add_to_cart.php" class="add-form">
                            <div class="price-selector">
                                <h4 class="product-price" data-unit-price="<?= $row['price_naira']; ?>">₦<?= number_format($row['price_naira'], 2); ?></h4>
                                <p class="product-price-dollar" data-unit-price="<?= $row['price_dollar']; ?>">$<?= number_format($row['price_dollar'], 2); ?></p>
                            </div>

                            <div class="price-row">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="price" value="<?= $row['price_naira'] ?>">
                                <input type="number" name="qty" class="qty-input" value="1" min="1" step="1">
                                <button type="submit" class="btn-add">Order</button>
                            </div>
                        </form>

    <!-- <form method="POST" action="cart.php" class="add-form">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <input
            type="number"
            name="qty"
            class="qty-input"
            value="1"
            min="1"
        >

        <button type="submit" class="btn-add">Order</button>
    </form> -->
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-products">
                <p>No products are currently available in the shop database.</p>
                <a href="admin/add_product.php" class="btn-add" style="display: inline-block; text-decoration: none;">Add Your First Product</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> Nebula Shop. All Rights Reserved.</p>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-card').forEach(function(card) {
        const qtyInput = card.querySelector('.qty-input');
        const priceNairaDisplay = card.querySelector('.product-price');
        const priceDollarDisplay = card.querySelector('.product-price-dollar');

        // Get base unit prices from data attributes
        const unitPriceNaira = parseFloat(priceNairaDisplay.dataset.unitPrice);
        const unitPriceDollar = parseFloat(priceDollarDisplay.dataset.unitPrice);

        // Function to format numbers as currency
        const formatCurrency = (amount) => {
            return amount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };

        // Add event listener to the quantity input
        qtyInput.addEventListener('input', function() {
            let quantity = parseInt(this.value);

            // Ensure quantity is a valid number, default to 1 if not
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
            }

            // Calculate new total prices
            const totalNaira = unitPriceNaira * quantity;
            const totalDollar = unitPriceDollar * quantity;

            // Update the displayed prices
            priceNairaDisplay.innerHTML = `₦${formatCurrency(totalNaira)}`;
            priceDollarDisplay.innerHTML = `$${formatCurrency(totalDollar)}`;
        });
    });
});
</script>
</body>
</html>