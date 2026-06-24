<?php
session_start();
include "db.php";

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart_items)) {
    header("Location: index.php");
    exit;
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['total'];
}

$order_created = false;
$payment_reference = "";
$customer_name = "";
$customer_email = "";

// Process checkout form and insert order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customer_name = trim($_POST['name']);
    $customer_email = trim($_POST['email']);

    if (!empty($customer_name) && !empty($customer_email)) {
        // Generate unique reference
        $payment_reference = 'REF-' . strtoupper(bin2hex(random_bytes(6)));

        $conn->begin_transaction();
        try {
            // 1. Insert into orders table
            $stmt = $conn->prepare("INSERT INTO orders (customer_name, email, total_amount, payment_status, payment_reference) VALUES (?, ?, ?, 'pending', ?)");
            $stmt->bind_param("ssds", $customer_name, $customer_email, $total, $payment_reference);
            $stmt->execute();
            $order_id = $conn->insert_id;
            $stmt->close();

            // 2. Insert order items
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart_items as $item) {
                // Ensure product_id is an integer (handling static mockup product IDs as well)
                $product_id = intval($item['id']);
                $qty = intval($item['qty']);
                $price = floatval($item['price']);
                $stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);
                $stmt->execute();
            }
            $stmt->close();

            $conn->commit();
            $order_created = true;
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Order creation failed: " . $e->getMessage();
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #A5B4FC, #818CF8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-back {
            background-color: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background-color: var(--surface);
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background-color: var(--surface);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background-color: var(--background);
            color: var(--text);
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn-pay {
            display: block;
            width: 100%;
            background-color: var(--primary);
            color: white;
            padding: 14px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            text-align: center;
        }

        .btn-pay:hover {
            background-color: var(--primary-hover);
        }

        .btn-pay:active {
            transform: scale(0.98);
        }

        .order-summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px dashed var(--border);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid var(--danger);
            color: #FCA5A5;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid var(--success);
            color: #A7F3D0;
        }

        .pending-payment-box {
            text-align: center;
            padding: 30px 20px;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: var(--primary);
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Checkout</h1>
        <a href="cart.php" class="btn-back">Back to Cart</a>
    </header>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <div class="checkout-grid">
        <!-- Left Side: Forms / Payment status -->
        <div>
            <?php if (!$order_created): ?>
                <div class="card">
                    <h2 class="card-title">Customer Information</h2>
                    <form method="POST" action="checkout.php">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="e.g. john@example.com" required>
                        </div>
                        <button type="submit" name="place_order" class="btn-pay">Proceed to Payment</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="card pending-payment-box">
                    <h2 class="card-title" style="border: none; margin-bottom: 10px;">Order Placed Successfully!</h2>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">Your order has been recorded. Click below to complete your payment.</p>
                    
                    <div id="payment-status-message" style="display:none;" class="alert alert-success">
                        Verifying payment, please wait...
                        <div class="spinner"></div>
                    </div>

                    <button onclick="payWithPaystack()" id="pay-btn" class="btn-pay">Pay ₦<?= number_format($total, 2) ?> Now</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Side: Order Summary -->
        <div class="card">
            <h2 class="card-title">Order Summary</h2>
            <?php foreach ($cart_items as $item): ?>
                <div class="order-summary-item">
                    <span><?= htmlspecialchars($item['name']) ?> <span style="color: var(--text-muted);">x<?= $item['qty'] ?></span></span>
                    <span>₦<?= number_format($item['total'], 2) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="total-row">
                <span>Total Amount</span>
                <span>₦<?= number_format($total, 2) ?></span>
            </div>
        </div>
    </div>
</div>

<?php if ($order_created): ?>
<!-- Paystack Inline JS -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function payWithPaystack() {
    const payBtn = document.getElementById('pay-btn');
    const statusMsg = document.getElementById('payment-status-message');
    
    let handler = PaystackPop.setup({
        key: '<?= isset($_ENV['PAYSTACK_PUBLIC_KEY']) ? htmlspecialchars($_ENV['PAYSTACK_PUBLIC_KEY']) : "pk_test_demo_public_key_buy_shop" ?>',
        email: '<?= htmlspecialchars($customer_email) ?>',
        amount: <?= $total * 100 ?>,
        currency: "NGN",
        ref: '<?= htmlspecialchars($payment_reference) ?>',
        callback: function(response) {
            // Payment success! Disable button and show loader
            payBtn.style.display = 'none';
            statusMsg.style.display = 'block';

            // Send reference to our server for verification and status update
            fetch("verify_payment.php", {
                method: "POST",
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    reference: response.reference
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location = "success.php?reference=" + encodeURIComponent(response.reference);
                } else {
                    alert("Verification Failed: " + (data.message || "Unknown error"));
                    payBtn.style.display = 'block';
                    statusMsg.style.display = 'none';
                }
            })
            .catch(err => {
                console.error(err);
                alert("An error occurred during verification.");
                payBtn.style.display = 'block';
                statusMsg.style.display = 'none';
            });
        },
        onClose: function() {
            alert('Payment checkout window closed.');
        }
    });

    handler.openIframe();
}
</script>
<?php endif; ?>

</body>
</html>