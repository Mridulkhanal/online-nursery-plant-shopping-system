<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo "<p class='success'>" . htmlspecialchars($_SESSION['success_message']) . "</p>";
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo "<p class='error'>" . htmlspecialchars($_SESSION['error_message']) . "</p>";
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/contact.css">
    <script src="js/customer.js"></script>
    <script src="js/validation.js"></script>
    <script src="js/contact.js"></script>
</head>
<body>
    <?php include 'php/header.php'; ?>
    <main>
        <section class="hero bg1">
            <h1>Get in Touch</h1>
            <p>We’d love to hear from you! Whether you have a question, need support, or want to provide feedback, feel free to contact us.</p>
        </section>
        <div class="contact-items">
            <div class="contact-item">
                <div class="icon-label">
                    <div class="contact-icon">
                        <img src="images/phone-icon.png" alt="Phone Icon">
                    </div>
                    <h3>Phone</h3>
                </div>
                <p>+977 - 9863921188</p>
            </div>
            <div class="contact-item">
                <div class="icon-label">
                    <div class="contact-icon">
                        <img src="images/address-icon.png" alt="Address Icon">
                    </div>
                    <h3>Address</h3>
                </div>
                <p>Mulpani,<br>Kathmandu</p>
            </div>
            <div class="contact-item">
                <div class="icon-label">
                    <div class="contact-icon">
                        <img src="images/email-icon.png" alt="Email Icon">
                    </div>
                    <h3>E-mail</h3>
                </div>
                <p>onlinenursery@gmail.com</p>
            </div>
        </div>
        <div class="contact-form">
            <h3>Send a Message</h3>
            <form action="php/send_contact.php" method="POST" onsubmit="return validateContactForm()">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Your name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Your email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" placeholder="Your message" required></textarea>
                </div>
                <button type="submit" class="btn-send">Send</button>
            </form>
        </div>
        <section id="map">
            <h2>Find Us Here</h2>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3533.375695!2d85.32473231501235!3d27.716712482793095!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb193a40c1293b%3A0x8c34f8961d0d2d59!2sKathmandu!5e0!3m2!1sen!2snp!4v1614345156125!5m2!1sen!2snp"
                width="70%"
                height="400"
                style="border: 0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </section>
    </main>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
</body>
</html>