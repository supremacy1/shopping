<?php
session_start();
include "db.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    // Product not found, redirect to homepage
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Nebula Shop</title>
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
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--text);
            padding: 40px 20px;
        }
        .container { max-width: 1100px; margin: 0 auto; }
        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 50px;
            align-items: flex-start;
        }
        @media (max-width: 900px) {
            .product-grid { grid-template-columns: 1fr; }
        }
        .product-image-container {
            background-color: var(--surface);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border);
        }
        .product-image {
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 12px;
        }
        .product-details h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .price {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 24px;
        }
        .add-to-cart-form {
            display: flex;
            gap: 16px;
            margin-top: 24px;
        }
        .qty-input {
            width: 80px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background-color: var(--surface);
            color: var(--text);
            font-size: 1rem;
            text-align: center;
        }
        .btn-add-cart {
            flex-grow: 1;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-add-cart:hover { background-color: var(--primary-hover); }
        .details-section {
            margin-top: 40px;
            background-color: var(--surface);
            padding: 24px;
            border-radius: 16px;
            border: 1px solid var(--border);
        }
        .details-section h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }
        .details-section p, .details-section ul {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 1rem;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 30px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
        }
        .btn-back:hover { color: var(--text); }
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
    <a href="index.php" class="btn-back">&larr; Back to Products</a>
    <div class="product-grid">
        <div class="product-image-container">
            <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
        </div>
        <div class="product-details">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price">
                ₦<?= number_format($product['price_naira'], 2) ?>
                <span style="font-size: 1.2rem; color: var(--text-muted);">$<?= number_format($product['price_dollar'], 2) ?></span>
            </div>
            
            <div class="details-section" style="margin-top: 0; background: none; padding: 0; border: none; box-shadow: none;">
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>

            <form action="add_to_cart.php" method="POST" class="add-to-cart-form">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <input type="hidden" name="price" value="<?= $product['price_naira'] ?>">
                <input type="number" name="quantity" value="1" min="1" class="qty-input">
                <button type="submit" class="btn-add-cart">Add to Cart</button>
            </form>
        </div>
    </div>

    <?php if (!empty($product['health_benefit'])): ?>
    <div class="details-section">
        <h2>Health Benefits</h2>
        <p><?= nl2br(htmlspecialchars($product['health_benefit'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($product['how_to_use'])): ?>
    <div class="details-section">
        <h2>How to Use</h2>
        <p><?= nl2br(htmlspecialchars($product['how_to_use'])) ?></p>
    </div>
    <?php endif; ?>

</div>

<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> Nebula Shop. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>