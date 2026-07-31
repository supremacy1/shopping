<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Desamall</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .page-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 40px;
            background-color: var(--surface);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
        }
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            color: var(--text);
        }
        .page-content p {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        .page-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 15px;
            color: var(--text);
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-container">
    <h1 class="page-title">About Desamall</h1>
    <div class="page-content">
        <p>Welcome to Desamall, your trusted partner in health and wellness. We are dedicated to providing you with the highest quality natural and herbal products, sourced with care and integrity.</p>
        
        <h3>Our Mission</h3>
        <p>Our mission is to empower individuals to take control of their health through the power of nature. We believe that a healthy life is a happy life, and we are committed to offering products that are not only effective but also safe, pure, and ethically produced.</p>

        <h3>Our Values</h3>
        <p><strong>Quality:</strong> We never compromise on quality. From sourcing to packaging, every step is meticulously managed to ensure you receive the best products.<br>
        <strong>Integrity:</strong> We are transparent about our ingredients and processes. We believe in building trust with our customers through honesty and ethical practices.<br>
        <strong>Community:</strong> We are more than just a store; we are a community of wellness enthusiasts. We strive to support our customers on their journey to a healthier lifestyle.</p>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>