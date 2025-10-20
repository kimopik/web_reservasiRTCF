<?php include '../../template/header.php'; ?>


<?php include '../template/footer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine Dining Restaurant - Home</title>
    <link rel="stylesheet" href="style.css"> <!-- Link ke CSS yang diperbarui -->
</head>
<body>
    <header>
        <h1>Fine Dining Restaurant</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    
    <!-- Hero Section -->
   <section class="hero">
    <div class="hero-overlay">
        <h2>Experience Elegance and Exquisite Cuisine</h2>
        <p>Indulge in our world-class dishes, crafted with the finest ingredients.</p>
        <a href="reservasi_form.php" class="btn">Make a Reservation</a>
    </div>
</section>
    
    <!-- Featured Menu Section -->
    <section class="featured-menu">
        <h2>Featured Dishes</h2>
        <div class="menu-grid">
            <div class="menu-item">
                <img src="../../asset/img/truffle.jpg" alt="Truffle Mushroom Soup"> <!-- Ganti dengan gambar sebenarnya -->
                <h3>Truffle Mushroom Soup</h3>
                <p>A luxurious starter with earthy flavors. $15</p>
            </div>
            <div class="menu-item">
                <img src="../../asset/img/lobster.jpg" alt="Grilled Lobster">
                <h3>Grilled Lobster</h3>
                <p>Fresh lobster with saffron risotto. $45</p>
            </div>
            <div class="menu-item">
                <img src="../../asset/img/coklat.jpg" alt="Chocolate Fondant">
                <h3>Chocolate Fondant</h3>
                <p>Decadent dessert with berry compote. $12</p>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about">
        <h2>About Us</h2>
        <p>Established in 2010, our restaurant offers an unforgettable fine dining experience with innovative recipes and impeccable service. Join us for a night of culinary excellence.</p>
    </section>
    
    <!-- Reservation Form Section -->
    <section id="reservation" class="reservation">
        <h2>Reserve Your Table</h2>
        <form action="process_reservation.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>
            <input type="submit" value="Book Now">
        </form>
    </section>
    
    <footer>
        <p>&copy; 2023 Fine Dining Restaurant. All rights reserved.</p>
    </footer>
    
    <script src="script.js"></script> <!-- Link ke JavaScript yang diperbarui -->
</body>
</html>