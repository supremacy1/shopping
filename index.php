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
            --primary: #4F46E5;
            /* Indigo */
            --primary-hover: #4338CA;
            --background: #F9FAFB;
            /* Light Gray */
            --surface: #FFFFFF;
            /* White */
            --surface-alt: #F3F4F6;
            --text: #111827;
            /* Dark Gray/Black */
            --text-muted: #6B7280;
            /* Medium Gray */
            --border: #E5E7EB;
            /* Light Gray Border */
            --success: #16A34A;
            /* Darker Green */
            --danger: #DC2626;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated {
            animation-duration: 0.7s;
            animation-fill-mode: both;
            animation-timing-function: cubic-bezier(0.25, 0.46, 0.45, 0.94);
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
            scroll-behavior: smooth;
        }

        /* ================= HEADER ================= */

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

        /* SEARCH */

        .search-box {
            flex: 1;
            display: flex;
            height: 48px;
        }

        .category-box {
            width: 180px;
            border: 1px solid var(--border);
            border-right: none;
            border-radius: 10px 0 0 10px;
            padding: 0 15px;
            background: #fff;
            outline: none;
        }

        .search-input {
            flex: 1;
            border: 1px solid var(--border);
            border-left: none;
            border-right: none;
            padding: 0 18px;
            font-size: 15px;
            outline: none;
        }

        .search-btn {
            width: 65px;
            border: none;
            background: #2563eb;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            border-radius: 0 10px 10px 0;
        }

        .search-btn:hover {
            background: #1d4ed8;
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

        .rating {

            color: #f59e0b;

            font-size: 14px;

            margin-bottom: 12px;

        }

        /* SECOND MENU */

        .main-menu {
            background: #2563eb;
        }

        .menu-container {
            max-width: 1300px;
            margin: auto;
            display: flex;
            gap: 35px;
            padding: 14px 20px;
        }

        .menu-container a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        .menu-container a:hover {
            color: #dbeafe;
        }

        .hero {
            max-width: 1200px;
            margin: 40px auto 20px;
            padding: 0 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #111827, #4B5563);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* New Slider Styles */
        .slider-container {
            max-width: 1300px;
            margin: 25px auto;
            position: relative;
            height: 200px;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .08);
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            z-index: 1;
        }

        .slide.active {
            opacity: 1;
            z-index: 2;
        }

        .slide img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .slide-content {

            position: absolute;

            left: 80px;

            top: 50%;

            transform: translateY(-50%);

            max-width: 520px;

            padding: 40px;

            background: rgba(0, 0, 0, .45);

            backdrop-filter: blur(10px);

            border-radius: 15px;

            text-align: left;

        }

        .slide-content h1 {

            font-size: 52px;

            font-weight: 800;

            margin-bottom: 20px;

        }

        .slide-content p {

            font-size: 18px;

            line-height: 1.8;

            margin-bottom: 25px;

        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.3);
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            z-index: 4;
            font-size: 1.5rem;
            transition: background-color 0.2s;
        }

        .slider-nav:hover {
            background-color: rgba(0, 0, 0, 0.6);
        }

        .slider-nav.prev {
            left: 10px;
            border-radius: 8px 0 0 8px;
        }

        .slider-nav.next {
            right: 10px;
            border-radius: 0 8px 8px 0;
        }

        .slider-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 4;
        }

        .dot {
            width: 12px;
            height: 12px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dot.active {
            background-color: white;
        }

        .btn-hero {
            display: inline-block;
            margin-top: 24px;
            background-color: var(--primary);
            color: white;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.2s, transform 0.2s;
        }

        .btn-hero:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            transition: .3s;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .05);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .12);
        }

        .img-container {

            height: 260px;

            background: #fff;

            display: flex;

            justify-content: center;

            align-items: center;

            overflow: hidden;

            position: relative;

        }

        .product-img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            transition: .4s;

        }

        .product-card:hover .product-img {

            transform: scale(1.08);

        }

        .sale-badge {

            position: absolute;

            top: 12px;

            left: 12px;

            background: #ef4444;

            color: #fff;

            padding: 5px 10px;

            font-size: 12px;

            font-weight: bold;

            border-radius: 20px;

            z-index: 5;

        }

        .wishlist {

            position: absolute;

            top: 12px;

            right: 12px;

            width: 38px;

            height: 38px;

            background: #fff;

            border-radius: 50%;

            display: flex;

            justify-content: center;

            align-items: center;

            box-shadow: 0 5px 15px rgba(0, 0, 0, .1);

            font-size: 18px;

            cursor: pointer;

        }

        .product-placeholder {
            font-size: 3rem;
            color: var(--text-muted);
            font-weight: 700;
            opacity: 0.3;
        }

        .card-content {

            padding: 18px;

            display: flex;

            flex-direction: column;

            flex: 1;

        }

        .product-name {

            font-size: 18px;

            font-weight: 700;

            line-height: 1.5;

            margin-bottom: 8px;

            height: 55px;

            overflow: hidden;

        }

        .product-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .product-desc {

            font-size: 14px;

            color: #6b7280;

            line-height: 1.6;

            margin-bottom: 15px;

            height: 45px;

            overflow: hidden;

        }

        .price-row {

            display: flex;

            align-items: center;

            gap: 10px;

            margin-top: auto;

        }

        .add-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .price-row { margin-top: auto; }

        .qty-input {

            width: 65px;

            height: 42px;

            text-align: center;

            border: 1px solid #d1d5db;

            border-radius: 8px;

        }

        .btn-add {

            flex: 1;

            height: 42px;

            border: none;

            border-radius: 8px;

            background: #2563eb;

            color: #fff;

            font-weight: 700;

            cursor: pointer;

            transition: .3s;

        }

        .btn-add:hover {

            background: #1d4ed8;

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

            font-size: 26px;

            font-weight: 800;

            color: #16a34a;

            margin-bottom: 3px;

        }

        .product-price-dollar {

            font-size: 14px;

            color: #6b7280;

            margin-bottom: 15px;

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

        /* New Section Styles */
        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 40px;
            color: var(--text);
        }

        .feature-section {
            padding: 60px 0;
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            margin-top: 60px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .feature-item h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .feature-item p {
            color: var(--text-muted);
            line-height: 1.6;
        }

        .health-tips-section {
            padding: 60px 0;
        }

        .health-tip-card {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }

        .health-tip-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .health-tip-card p {
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }

        .health-tip-card .icon {
            font-size: 2.5rem;
            margin-bottom: 16px;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /*================ SERVICES ================*/

        .services {

            padding: 70px 0;

            background: #fff;

        }

        .services-grid {

            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));

            gap: 25px;

        }

        .service-card {

            background: #f8fafc;

            padding: 35px;

            border-radius: 15px;

            text-align: center;

            transition: .3s;

        }

        .service-card:hover {

            transform: translateY(-6px);

            box-shadow: 0 15px 30px rgba(0, 0, 0, .08);

        }

        .service-icon {

            font-size: 45px;

            margin-bottom: 15px;

        }

        /*================ PROMO ================*/

        .promo-banner {

            margin: 70px 0;

        }

        .promo-content {

            background: linear-gradient(135deg, #2563eb, #1d4ed8);

            padding: 70px;

            border-radius: 20px;

            color: #fff;

            text-align: center;

        }

        .promo-content h2 {

            font-size: 42px;

            margin-bottom: 15px;

        }

        .promo-content p {

            font-size: 20px;

            margin-bottom: 25px;

        }

        .promo-btn {

            display: inline-block;

            padding: 15px 35px;

            background: #fff;

            color: #2563eb;

            font-weight: bold;

            border-radius: 10px;

            text-decoration: none;

        }

        /*================ BRANDS ================*/

        .brands {

            padding: 70px 0;

        }

        .brand-grid {

            display: grid;

            grid-template-columns: repeat(6, 1fr);

            gap: 20px;

        }

        .brand-card {

            background: #fff;

            border: 1px solid #e5e7eb;

            padding: 30px;

            font-size: 22px;

            font-weight: bold;

            text-align: center;

            border-radius: 12px;

            transition: .3s;

        }

        .brand-card:hover {

            border-color: #2563eb;

        }

        /*================ NEWSLETTER ================*/

        .newsletter {

            padding: 80px 0;

            background: #111827;

            color: #fff;

            text-align: center;

        }

        .newsletter h2 {

            font-size: 36px;

            margin-bottom: 15px;

        }

        .newsletter p {

            margin-bottom: 30px;

        }

        .newsletter-box {

            display: flex;

            max-width: 700px;

            margin: auto;

        }

        .newsletter input {

            flex: 1;

            height: 55px;

            padding: 0 20px;

            border: none;

            outline: none;

            border-radius: 8px 0 0 8px;

        }

        .newsletter button {

            width: 170px;

            background: #2563eb;

            border: none;

            color: #fff;

            font-weight: bold;

            border-radius: 0 8px 8px 0;

            cursor: pointer;

        }

        /*================ FOOTER ================*/

        .footer {

            background: #0f172a;

            color: #cbd5e1;

            padding: 70px 0 30px;

        }

        .footer-grid {

            display: grid;

            grid-template-columns: 2fr 1fr 1fr 1fr;

            gap: 40px;

            margin-bottom: 40px;

        }

        .footer h3,

        .footer h4 {

            color: #fff;

            margin-bottom: 20px;

        }

        .footer a {

            display: block;

            color: #cbd5e1;

            text-decoration: none;

            margin-bottom: 10px;

        }

        .footer a:hover {

            color: #fff;

        }

        .footer hr {

            border: none;

            height: 1px;

            background: #334155;

            margin: 30px 0;

        }

        .copyright {

            text-align: center;

        }

        /*================ MOBILE ================*/

        @media(max-width:768px) {

            .brand-grid {

                grid-template-columns: repeat(2, 1fr);

            }

            .footer-grid {

                grid-template-columns: 1fr;

            }

            .newsletter-box {

                flex-direction: column;

                gap: 15px;

            }

            .newsletter input,

            .newsletter button {

                width: 100%;

                border-radius: 8px;

            }

            .promo-content {

                padding: 40px 25px;

            }

            .promo-content h2 {

                font-size: 28px;

            }

        }
    </style>
</head>

<body>

    <!-- TOP BAR -->

    <div class="top-bar">

        <div class="top-container">

            <span>🚚 Free Delivery on Orders Above ₦20,000</span>

            <div class="top-links">
                <!-- <a href="#">Track Order</a>
                <a href="#">Help</a> -->
                <a href="contact.php">Contact</a>
            </div>

        </div>

    </div>


    <!-- HEADER -->

    <nav class="navbar">

        <div class="nav-container">

            <a href="index.php" class="logo">
                Desamall
            </a>

            <!-- <div class="search-box">

                <select class="category-box">

                    <option>All Categories</option>

                    <option>Electronics</option>

                    <option>Fashion</option>

                    <option>Phones</option>

                    <option>Laptops</option>

                    <option>Home & Living</option>

                    <option>Beauty</option>

                    <option>Gaming</option>

                </select>

                <input
                    type="text"
                    class="search-input"
                    placeholder="Search for products...">

                <button class="search-btn">

                    🔍

                </button>

            </div> -->

            <div class="nav-icons">

                <!-- <a href="#" class="nav-btn">

                    👤

                    Account

                </a> -->

                <a href="cart.php" class="nav-btn">

                    🛒 Cart

                    <span class="cart-count">

                        <?= $cart_count ?>

                    </span>

                </a>

                <!-- <a href="admin/products.php" class="nav-btn">

                    ⚙️ Admin

                </a> -->

            </div>

        </div>

    </nav>

    <!-- MENU -->

    <!-- <div class="main-menu">

        <div class="menu-container">

            <a href="#">Home</a>

            <a href="#">Categories</a>

            <a href="#">New Arrivals</a>

            <a href="#">Best Sellers</a>

            <a href="#">Flash Sale</a>

            <a href="#">Brands</a>

            <a href="#">Contact</a>

        </div> -->

    </div>

    <div class="slider-container">
        <div class="slides">
                <div class="slide active">
                    <img src="image/slide112.jfif" alt="Banner 1">
                </div>
                <div class="slide">
                    <img src="image/slider23.jfif" alt="Banner 2">
                </div>
        </div>
        <button class="slider-nav prev">&lt;</button>
        <button class="slider-nav next">&gt;</button>
        <div class="slider-dots"></div>
    </div>
    
    <div class="container">
        <div id="products" class="product-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $animation_delay = 0; ?>
                <?php while ($row = $result->fetch_assoc()): $animation_delay += 0.05; ?>
                    <div class="product-card animated" style="animation-name: fadeInUp; animation-delay: <?= $animation_delay ?>s;">
                        <a href="product.php?id=<?= $row['id'] ?>">
                            <div class="sale-badge">

                                SALE

                            </div>

                            <div class="wishlist">

                                ♡

                            </div>
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
                            <div class="rating">

                                ★★★★★
                                <span style="color:#6b7280;">
                                    (4.8)
                                </span>

                            </div>
                            <p class="product-desc"><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 100, "...")) ?></p>

                            <form method="POST" action="add_to_cart.php" class="add-form">
                                <div class="price-selector">
                            <div class="price-row">
                                <form method="POST" action="add_to_cart.php" class="add-form">
                                    <h4 class="product-price" data-unit-price="<?= $row['price_naira']; ?>">₦<?= number_format($row['price_naira'], 2); ?></h4>
                                    <p class="product-price-dollar" data-unit-price="<?= $row['price_dollar']; ?>">$<?= number_format($row['price_dollar'], 2); ?></p>
                                </div>

                                <div class="price-row">
                                    <!-- The dollar price can be a tooltip or shown on the product page to simplify the card -->
                                    <!-- <p class="product-price-dollar" data-unit-price="<?= $row['price_dollar']; ?>">$<?= number_format($row['price_dollar'], 2); ?></p> -->
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="price" value="<?= $row['price_naira'] ?>">
                                    <input type="number" name="qty" class="qty-input" value="1" min="1" step="1">
                                    <button type="submit" class="btn-add">

                                        🛒 Add to Cart

                                    </button>
                                </div>
                            </form>
                                </form>
                            </div>

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

    <div class="feature-section">
        <div class="container">
            <h2 class="section-title animated" style="animation-name: fadeInUp;">Why Choose Us?</h2>
            <div class="feature-grid">
                <div class="feature-item animated" style="animation-name: fadeInUp; animation-delay: 0.1s;">
                    <h3>🌿 100% Natural</h3>
                    <p>All our products are sourced from nature, free from artificial additives and harmful chemicals, ensuring you get only the best.</p>
                </div>
                <div class="feature-item animated" style="animation-name: fadeInUp; animation-delay: 0.2s;">
                    <h3>💖 Ethically Sourced</h3>
                    <p>We partner with local farmers and suppliers who share our commitment to sustainability, quality, and fair trade practices.</p>
                </div>
                <div class="feature-item animated" style="animation-name: fadeInUp; animation-delay: 0.3s;">
                    <h3>🚀 Fast Delivery</h3>
                    <p>Your wellness journey shouldn't have to wait. We offer fast, reliable, and secure shipping on all your orders.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="health-tips-section">
        <div class="container">
            <h2 class="section-title animated" style="animation-name: fadeInUp;">Health & Wellness Tip</h2>
            <div class="health-tip-card animated" style="animation-name: fadeInUp; animation-delay: 0.1s;">
                <div class="icon">💧</div>
                <h4>The Power of Hydration</h4>
                <p>
                    Drinking enough water daily is crucial for energy levels and brain function. Start your day with a glass of water to kickstart your metabolism and stay refreshed and focused throughout the day.
                </p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.product-card').forEach(function(card) {
                    // ... existing product card script
                });

                // New Slider Script
                const slides = document.querySelectorAll('.slide');
                const nextBtn = document.querySelector('.slider-nav.next');
                const prevBtn = document.querySelector('.slider-nav.prev');
                const dotsContainer = document.querySelector('.slider-dots');
                let currentSlide = 0;
                let slideInterval;

                function showSlide(n) {
                    slides.forEach(slide => slide.classList.remove('active'));
                    const dots = document.querySelectorAll('.dot');
                    dots.forEach(dot => dot.classList.remove('active'));

                    currentSlide = (n + slides.length) % slides.length;

                    slides[currentSlide].classList.add('active');
                    dots[currentSlide].classList.add('active');
                }

                function nextSlide() {
                    showSlide(currentSlide + 1);
                }

                function prevSlide() {
                    showSlide(currentSlide - 1);
                }

                function startSlideShow() {
                    slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
                }

                function stopSlideShow() {
                    clearInterval(slideInterval);
                }

                // Create dots
                slides.forEach((_, i) => {
                    const dot = document.createElement('div');
                    dot.classList.add('dot');
                    dot.addEventListener('click', () => {
                        stopSlideShow();
                        showSlide(i);
                        startSlideShow();
                    });
                    dotsContainer.appendChild(dot);
                });

                nextBtn.addEventListener('click', () => {
                    stopSlideShow();
                    nextSlide();
                    startSlideShow();
                });
                prevBtn.addEventListener('click', () => {
                    stopSlideShow();
                    prevSlide();
                    startSlideShow();
                });

                // Initialize
                showSlide(0);
                startSlideShow();

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