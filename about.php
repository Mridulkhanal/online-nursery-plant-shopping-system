<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
</head>
<body>
    <?php include 'php/header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero bg3">
            <h1>Our Story</h1>
            <p>Bringing green serenity to homes since 2025.</p>
        </section>

        <!-- Mission and Vision -->
        <section id="mission-vision" class="about">
            <div class="container">
                <div class="mission">
                    <h3>Our Mission</h3>
                    <p>To provide high-quality plants and gardening tools that transform homes into green sanctuaries.</p>
                </div>
                <div class="vision">
                    <h3>Our Vision</h3>
                    <p>To make the world greener, one plant at a time, by inspiring sustainable gardening practices.</p>
                </div>
            </div>
        </section>

        <!-- Meet the Team -->
        <section id="team" class="about">
            <div class="container">
                <h2>Meet Our Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="images/team1.jpg" alt="Mridul Khanal">
                        <h4>Mridul Khanal</h4>
                        <p>Founder</p>
                    </div>
                    <div class="team-member">
                        <img src="images/team2.jpg" alt="Nabin Neupane">
                        <h4>Nabin Neupane</h4>
                        <p>Co-Founder</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="about">
            <div class="container">
                <h2>What Our Customers Say</h2>
                <div class="testimonial-grid">
                    <div class="testimonial">
                        <p>"Their plants are vibrant and healthy. I’ve never had such a good experience buying plants online!"</p>
                        <h4>— Chitra</h4>
                    </div>
                    <div class="testimonial">
                        <p>"I love the detailed care instructions they provide. It’s like they truly care about their customers."</p>
                        <h4>— Mahesh</h4>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>
</body>
</html>