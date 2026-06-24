<?php
session_start();
include "db.php";

$reference = isset($_GET['reference']) ? trim($_GET['reference']) : '';

$order = null;
$order_items = [];

if (!empty($reference)) {
    // Fetch order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE payment_reference = ?");
    $stmt->bind_param("s", $reference);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($order = $res->fetch_assoc()) {
        $order_id = $order['id'];
        
        // Fetch order items joined with product names (and image files if stored in products table)
        // Since mockup items might not be in DB, we fall back gracefully.
        $item_stmt = $conn->prepare("
            SELECT oi.*, p.name AS product_name, p.image AS product_image 
            FROM order_items oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $item_stmt->bind_param("i", $order_id);
        $item_stmt->execute();
        $item_res = $item_stmt->get_result();
        while ($item = $item_res->fetch_assoc()) {
            $order_items[] = $item;
        }
        $item_stmt->close();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .success-card {
            background-color: var(--surface);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        .success-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            font-size: 3rem;
            border-radius: 50%;
            margin-bottom: 24px;
            border: 2px solid rgba(16, 185, 129, 0.2);
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); }
            80% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #A7F3D0, #10B981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 1.05rem;
            margin-bottom: 30px;
        }

        .details-box {
            background-color: var(--background);
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            margin-bottom: 30px;
            border: 1px solid var(--border);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            padding-bottom: 8px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .detail-val {
            color: var(--text);
            font-weight: 600;
        }

        .item-list {
            margin-top: 10px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .btn-home {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-home:hover {
            background-color: var(--primary-hover);
        }

        .btn-home:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

<div class="success-card">
    <div class="success-icon">✓</div>
    <h1>Payment Successful!</h1>
    <p class="subtitle">Thank you for your purchase. Your payment was verified and processed.</p>

    <?php if ($order): ?>
        <div class="details-box">
            <div class="detail-row">
                <span class="detail-label">Payment Reference:</span>
                <span class="detail-val" style="color: var(--primary);"><?= htmlspecialchars($order['payment_reference']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Customer Name:</span>
                <span class="detail-val"><?= htmlspecialchars($order['customer_name']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email Address:</span>
                <span class="detail-val"><?= htmlspecialchars($order['email']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount Paid:</span>
                <span class="detail-val" style="color: var(--success);">₦<?= number_format($order['total_amount'], 2) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order Date:</span>
                <span class="detail-val"><?= date("F j, Y, g:i a", strtotime($order['created_at'])) ?></span>
            </div>

            <div style="margin-top: 16px; font-weight: 600; font-size: 0.95rem; border-top: 1px solid var(--border); padding-top: 12px; color: var(--text);">
                Items Purchased:
            </div>
            <div class="item-list">
                <?php foreach ($order_items as $item): ?>
                    <div class="item-row">
                        <span>
                            <?= htmlspecialchars(!empty($item['product_name']) ? $item['product_name'] : "Product (ID " . $item['product_id'] . ")") ?>
                            x<?= $item['quantity'] ?>
                        </span>
                        <span>₦<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="details-box" style="text-align: center;">
            <p style="color: var(--danger);">Reference number not found in local records. Please check with your payment provider.</p>
        </div>
    <?php endif; ?>

    <a href="index.php" class="btn-home">Continue Shopping</a>
</div>

</body>
</html>
