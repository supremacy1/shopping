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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $id = intval($_POST['id']);
    $qty = intval($_POST['qty']);

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] = $qty;
        // The 'total' key is no longer stored in the session, it's calculated on the fly.
    }
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_naira = 0;
$total_dollar = 0;
foreach ($cart_items as $item) {
    // Use price_naira if available, otherwise fall back to the old 'price' key.
    $price_naira = isset($item['price_naira']) ? $item['price_naira'] : ($item['price'] ?? 0);
    $price_dollar = isset($item['price_dollar']) ? $item['price_dollar'] : 0;

    $total_naira += $price_naira * $item['qty'];
    $total_dollar += $price_dollar * $item['qty'];
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
            background: linear-gradient(135deg, #1F2937, #4B5563);
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
            border: 1px solid var(--border);
        }
        
        .cart-table-wrapper {
            overflow-x: auto;
            /* Add a subtle hint for scrolling on touch devices */
            -webkit-overflow-scrolling: touch;
            /* On some systems, this can make the scrollbar more visible */
            scrollbar-width: thin;
            scrollbar-color: var(--border) var(--surface);
        }

        /* Custom Scrollbar for Webkit browsers (Chrome, Safari, Edge) */
        .cart-table-wrapper::-webkit-scrollbar {
            height: 12px; /* Height of the horizontal scrollbar */
        }

        .cart-table-wrapper::-webkit-scrollbar-track {
            background: var(--border); /* Use a slightly darker track color */
            border-radius: 10px;
        }

        .cart-table-wrapper::-webkit-scrollbar-thumb {
            background-color: var(--primary); /* The color of the scrollbar handle */
            border-radius: 10px;
            border: 2px solid var(--border); /* Creates padding around thumb */
        }

        .cart-table-wrapper::-webkit-scrollbar-thumb:hover {
            background-color: var(--primary-hover); /* Color on hover */
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
            border-bottom: 1px solid var(--border);
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

        .price-display-naira { font-weight: 500; }
        .price-display-dollar {
            font-size: 0.85rem;
            color: var(--text-muted);
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
            border-top: 1px solid var(--border);
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
            <div class="cart-card cart-table-wrapper">
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
                        <tr class="cart-item-row" data-id="<?= $id ?>" data-price-naira="<?= isset($item['price_naira']) ? $item['price_naira'] : ($item['price'] ?? 0) ?>" data-price-dollar="<?= $item['price_dollar'] ?? 0 ?>">
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
                            <td>
                                <div class="price-display-naira">₦<?= number_format(isset($item['price_naira']) ? $item['price_naira'] : ($item['price'] ?? 0), 2) ?></div>
                                <div class="price-display-dollar">$<?= number_format($item['price_dollar'] ?? 0, 2) ?></div>
                            </td>
                            <td>
                                <input type="number" name="qty" class="qty-input" value="<?= $item['qty'] ?>" min="1" required>
                            </td>
                            <td class="item-total">
                                <div class="price-display-naira">
                                    <?php
                                        $item_price_naira = isset($item['price_naira']) ? $item['price_naira'] : ($item['price'] ?? 0);
                                        echo '₦' . number_format($item_price_naira * $item['qty'], 2);
                                    ?>
                                </div>
                                <div class="price-display-dollar">$<?= number_format(($item['price_dollar'] ?? 0) * $item['qty'], 2) ?></div>
                            </td>
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
                    <span style="color: var(--text-muted);" id="summary-subtotal-label">Subtotal</span>
                    <span id="summary-subtotal-value">
                        <div class="price-display-naira">₦<?= number_format($total_naira, 2) ?></div>
                        <div class="price-display-dollar">$<?= number_format($total_dollar, 2) ?></div>
                    </span>
                </div>
                <div class="summary-row">
                    <span style="color: var(--text-muted);">Shipping</span>
                    <span style="color: var(--success); font-weight: 500;">Free</span>
                </div>
                <div class="summary-row total">
                    <span id="summary-total-label">Total</span>
                    <span id="summary-total-value">
                        <div class="price-display-naira">₦<?= number_format($total_naira, 2) ?></div>
                        <div class="price-display-dollar">$<?= number_format($total_dollar, 2) ?></div>
                    </span>
                </div>

                <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?><script>
document.addEventListener('DOMContentLoaded', function() {
    const formatCurrency = (amount, currency) => {
        const symbol = currency === 'USD' ? '$' : '₦';
        return symbol + amount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    const updateCartTotals = () => {
        let grandTotalNaira = 0;
        let grandTotalDollar = 0;

        document.querySelectorAll('.cart-item-row').forEach(row => {
            const unitPriceNaira = parseFloat(row.dataset.priceNaira);
            const unitPriceDollar = parseFloat(row.dataset.priceDollar);
            const qtyInput = row.querySelector('.qty-input');
            const quantity = parseInt(qtyInput.value);
            
            const itemTotalNaira = unitPriceNaira * quantity;
            const itemTotalDollar = unitPriceDollar * quantity;
            grandTotalNaira += itemTotalNaira;
            grandTotalDollar += itemTotalDollar;

            // Update individual item total display
            const itemTotalElement = row.querySelector('.item-total');
            itemTotalElement.querySelector('.price-display-naira').textContent = formatCurrency(itemTotalNaira, 'NGN');
            itemTotalElement.querySelector('.price-display-dollar').textContent = formatCurrency(itemTotalDollar, 'USD');
        });

        // Update summary totals
        document.querySelector('#summary-subtotal-value .price-display-naira').textContent = formatCurrency(grandTotalNaira, 'NGN');
        document.querySelector('#summary-subtotal-value .price-display-dollar').textContent = formatCurrency(grandTotalDollar, 'USD');
        document.querySelector('#summary-total-value .price-display-naira').textContent = formatCurrency(grandTotalNaira, 'NGN');
        document.querySelector('#summary-total-value .price-display-dollar').textContent = formatCurrency(grandTotalDollar, 'USD');
    };

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            let quantity = parseInt(this.value);
            if (isNaN(quantity) || quantity < 1) {
                this.value = 1;
            }
            updateCartTotals();

            // AJAX call to update session
            const row = this.closest('.cart-item-row');
            const id = row.dataset.id;
            
            const formData = new FormData();
            formData.append('update_cart', '1');
            formData.append('id', id);
            formData.append('qty', this.value);

            fetch('cart.php', {
                method: 'POST',
                body: formData
            }).catch(err => console.error('Failed to update cart session:', err));
        });
    });
});
</script>

</body>
</html>