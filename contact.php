<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Desamall</title>
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
        .page-content p, .page-content li {
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
        .contact-form {
            margin-top: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
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
            font-size: 1rem;
        }
        .btn-submit {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 12px 28px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-submit:hover {
            background-color: var(--primary-hover);
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-container">
    <h1 class="page-title">Contact Us</h1>
    <div class="page-content">
        <p>We'd love to hear from you! Whether you have a question about our products, your order, or anything else, our team is ready to answer all your questions.</p>

        <h3>Get in Touch</h3>
        <p>
            <strong>Email:</strong> <a href="mailto:support@desamall.com">support@desamall.com</a><br>
            <strong>Phone:</strong> +234 801 234 5678<br>
            <strong>Address:</strong> 123 Wellness Lane, Ikeja, Lagos, Nigeria
        </p>

        <form action="#" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn-submit">Send Message</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>