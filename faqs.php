<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - Desamall</title>
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
            margin-bottom: 30px;
            text-align: center;
            color: var(--text);
        }
        .faq-item {
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .faq-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .faq-question {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 10px;
            cursor: pointer;
        }
        .faq-answer {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 1rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-container">
    <h1 class="page-title">Frequently Asked Questions</h1>
    
    <div class="faq-item">
        <h3 class="faq-question">How do I track my order?</h3>
        <div class="faq-answer">
            <p>Once your order is shipped, you will receive an email with a tracking number and a link to the courier's website. You can use this to track the status of your delivery.</p>
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">What are the shipping costs?</h3>
        <div class="faq-answer">
            <p>We offer free delivery on all orders above ₦20,000. For orders below this amount, a standard shipping fee will be applied at checkout based on your location.</p>
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">What is your return policy?</h3>
        <div class="faq-answer">
            <p>We accept returns for unopened products within 7 days of delivery. Please contact our customer service team to initiate a return. Note that return shipping costs are the responsibility of the customer.</p>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>