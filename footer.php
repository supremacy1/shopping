<style>
    /*================ FOOTER ================*/
    .footer {
        background: #0f172a;
        color: #cbd5e1;
        padding: 70px 0 30px;
        margin-top: 60px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 20px;
    }

    .footer h3,
    .footer h4 {
        color: #fff;
        margin-bottom: 20px;
    }

    .footer p {
        line-height: 1.6;
    }

    .footer a {
        display: block;
        color: #cbd5e1;
        text-decoration: none;
        margin-bottom: 10px;
        transition: color 0.2s;
    }

    .footer a:hover {
        color: #fff;
    }

    .footer hr {
        border: none;
        height: 1px;
        background: #334155;
        margin: 30px auto;
        max-width: 1200px;
    }

    .copyright {
        text-align: center;
        font-size: 0.9rem;
    }

    @media(max-width:768px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<footer class="footer">
    <div class="footer-grid">
        <div>
            <h3>Desamall</h3>
            <p>
                Your trusted online marketplace for quality products at affordable prices.
            </p>
        </div>
        <div>
            <h4>Customer Service</h4>
            <a href="contact.php">Contact Us</a>
            <a href="about.php">About Us</a>
            <a href="faqs.php">FAQs</a>
        </div>
        <div>
            <h4>Follow Us</h4>
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">TikTok</a>
        </div>
    </div>
    <hr>
    <p class="copyright">
        &copy; <?= date('Y') ?> Desamall. All Rights Reserved.
    </p>
</footer>