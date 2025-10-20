<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);
    
    // Kirim email (ganti dengan email Anda)
    $to = "youremail@example.com";
    $subject = "New Reservation Request";
    $message = "Name: $name\nEmail: $email\nDate: $date\nTime: $time";
    $headers = "From: $email";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "<p>Reservation request sent successfully! We will contact you soon.</p>";
    } else {
        echo "<p>Failed to send reservation. Please try again.</p>";
    }
} else {
    echo "<p>Invalid request. Please use the form.</p>";
}
?>