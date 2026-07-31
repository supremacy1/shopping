<?php
// If session is not already started, start it.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['qty'];
    }
}
?>
<style>
    /* Basic styles for body and root variables, can be moved to a global stylesheet later */
    :root {
        --primary: #4F46E5;
        --primary-hover: #4338CA;
        --background: #F9FAFB;
        --surface: #FFFFFF;
        --surface-alt: #F3F4F6;
        --text: #111827;
        --text-muted: #6B7280;
        --border: #E5E7EB;
        --success: #16A34A;
        --danger: #DC2626;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--background);
        color: var(--text);
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    * {
        box-sizing: border-box;
    }

    /*================ HEADER ================*/
    .top-bar {
        background: #271b11;
        color: #fff;
        font-size: 14px;
        padding: 10px 25px;
    }

    .top-container {
        max-width: 1300px;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-links {
        display: flex;
        gap: 20px;
    }

    .top-links a {
        color: #fff;
        text-decoration: none;
        opacity: .85;
    }

    .top-links a:hover {
        opacity: 1;
    }

    .navbar {
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
    }

    .nav-container {
        max-width: 1300px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        padding: 18px 20px;
    }

    .logo {
        font-size: 32px;
        font-weight: 800;
        color: #2563eb;
        text-decoration: none;
        white-space: nowrap;
    }

    .nav-icons {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .nav-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #374151;
        border: 1px solid #e5e7eb;
        padding: 10px 16px;
        border-radius: 10px;
        transition: .25s;
    }

    .nav-btn:hover {
        background: #f8fafc;
    }

    .cart-count {
        background: #ef4444;
        color: #fff;
        padding: 2px 8px;
        border-radius: 50px;
        font-size: 12px;
    }
</style>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="top-container">
        <span>🚚 Free Delivery on Orders Above ₦20,000</span>
        <div class="top-links">
            <a href="contact.php">Contact</a>
        </div>
    </div>
</div>

<!-- HEADER -->
<header class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">Desamall</a>
        <div class="nav-icons">
            <a href="cart.php" class="nav-btn">
                🛒 Cart
                <span class="cart-count"><?= $cart_count ?></span>
            </a>
        </div>
    </div>
</header>