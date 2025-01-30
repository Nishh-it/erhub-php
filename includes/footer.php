<style>
    footer {
        width: 100%;
        background: #162938;
        color: white;
        text-align: center;
        padding: 30px 0;
        display: flex;
        justify-content: center;
    }

    .footer-content {
        max-width: 1200px;
        display: flex;
        justify-content: space-between;
        gap: 50px;
        width: 100%;
        padding: 20px;
    }

    .footer-left, .footer-center, .footer-right {
        flex: 1;
        text-align: left;
    }

    .footer-left h3, .footer-center h3, .footer-right h3 {
        margin-bottom: 15px;
        font-size: 1.5rem;
        color: #fff;
    }

    .footer-left ul {
        list-style: none;
        padding: 0;
    }

    .footer-left ul li {
        margin-bottom: 10px;
    }

    .footer-left ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
    }

    .footer-left ul li a:hover {
        text-decoration: underline;
    }

    .contact-links a {
        display: flex;
        align-items: center;
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .contact-links img {
        width: 24px;
        height: 24px;
        margin-right: 10px;
    }

    .footer-right form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .footer-right input, 
    .footer-right textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    .footer-right button {
        padding: 10px;
        background: #007bff;
        border: none;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }

    .footer-right button:hover {
        background: #0056b3;
    }

    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-left, .footer-center, .footer-right {
            text-align: center;
        }

        .contact-links a {
            justify-content: center;
        }
    }
</style>

<footer>
    <div class="footer-content">
        <div class="footer-left">
            <h3>Information</h3>
            <ul>
                <li><a href="about.php">About Us</a></li>
                <li><a href="terms.php">Terms & Conditions</a></li>
                <li><a href="policies.php">Policies</a></li>
            </ul>
        </div>

        <div class="footer-center">
            <h3>Contact Us</h3>
            <div class="contact-links">
                <a href="mailto:info@erhub.com">
                    <img src="assets/images/email-icon.png" alt="Email"> info@erhub.com
                </a>
                <a href="https://instagram.com/yourpage" target="_blank">
                    <img src="assets/images/instagram-icon.png" alt="Instagram"> @yourpage
                </a>
                <a href="https://wa.me/1234567890" target="_blank">
                    <img src="assets/images/whatsapp-icon.png" alt="WhatsApp"> +1234567890
                </a>
            </div>
        </div>

        <div class="footer-right">
            <h3>Contact Form</h3>
            <form action="contact.php" method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="message" placeholder="Message" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</footer>

<script src="../scripts/script.js"></script>
</body>
</html>
