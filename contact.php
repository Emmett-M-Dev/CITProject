<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Sleep.io</title>
   <Style>
    /* Existing styles */

/* Contact Form Styles */
main {
    max-width: 600px;
    margin: auto;
    padding: 20px;
}

h1 {
    color: #3d5a80; /* Using the same color as the header from the dashboard */
    text-align: center;
    margin-bottom: 30px;
}

form {
    display: grid;
    gap: 10px;
}

label {
    font-weight: bold;
}

input, textarea {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    background-color: #3d5a80; /* Matching button color with the header */
    color: white;
    cursor: pointer;
}

footer {
    text-align: center;
    padding: 20px;
    background-color: #f4f4f4;
    color: #3d5a80; /* Same as header */
}

   </Style>
</head>
<body>
    <header>
        <img src="logo.png" alt="Sleep.io Logo" class="logo">
    </header>
    <main>
        <h1>Contact Us</h1>
        <form id="contact-form">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </main>
    <footer>
        <p>Email: contact@sleep.io | Phone: 123-456-7890</p>
        <p>Follow us on social media</p>
        <!-- Social media links here -->
    </footer>
    <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevents the default form submission

    // Form validation logic here
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var subject = document.getElementById('subject').value;
    var message = document.getElementById('message').value;

    if(name && email && subject && message) {
        // Replace with AJAX call if needed
        alert('Thank you for contacting us!');
    } else {
        alert('Please fill out all the fields.');
    }
});

    </script>
</body>
</html>
