<?php include 'includes/sessionconnection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dream Journal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
    background-image: url('includes/images/dreams.webp');
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
        }





 #dreamForm, #previousEntries, #recommendedReadings {
    background: linear-gradient(to right, rgba(0, 0, 0, 0.7), rgba(35, 37, 38, 0.7));
    background-color: rgba(0, 0, 0, 0.7); /* Black with 70% opacity */
    color: #fff; /* White text for better readability on dark background */
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.3); /* Lighter shadow for contrast */
}

#dreamForm label, #dreamForm button, #searchContainer, #searchBar {
    color: #fff; /* Ensure all text inside the form is white for readability */
}

#dreamForm button {
    background: #333; /* Darker background for buttons */
}

#dreamForm button:hover {
    background: #555; /* Slightly lighter on hover */
}

.star-rating label {
    color: #ddd; /* Adjust star color for better visibility on dark background */
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #ffc107; /* Highlight color remains the same */
}

/* ... (other CSS remains unchanged) ... */



#charCount {
    color: #666;
    font-size: 0.9em;
}

#searchContainer {
    margin-bottom: 20px;
}

#searchBar {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    color: black;
}


</style>
</head>
<body >
<?php  include 'includes/nav.php'; ?>
</br>
</br>
<div class="container">
        <h1 style="color:#ddd;">Dream Journal</h1>
        <form id="dreamForm">
            <label for="dreamDate">Date of Dream:</label>
            <input type="date" id="dreamDate" name="dreamDate" aria-label="Date of Dream">

            <label for="dreamRating">Rate Your Dream:</label>
            <div id="dreamRating" class="star-rating" aria-label="Rate your dream">
                <!-- Star rating elements -->
                <label data-value="1">&#9733;</label>
                <label data-value="2">&#9733;</label>
                <label data-value="3">&#9733;</label>
                <label data-value="4">&#9733;</label>
                <label data-value="5">&#9733;</label>
            </div>

            <label for="dreamText">Describe your dream:</label>
            <textarea id="dreamText" name="dreamText" rows="4" cols="50" maxlength="1000" aria-label="Describe your dream"></textarea>
            <div id="charCount">0/1000</div>

            <button type="submit">Submit Dream</button>
        </form>

        <div id="searchContainer">
            <input type="text" id="searchBar" placeholder="Search dreams..." aria-label="Search dreams">
        </div>

        <section id="previousEntries">
            <h2>Previous Entries</h2>
            <!-- Pagination and entries will be handled here -->
        </section>

        <section id="recommendedReadings">
            <h2>Recommended Readings</h2>
            <!-- Categorized readings will be here -->
        </section>
    <script src="script.js">



document.getElementById('dreamForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const dreamText = document.getElementById('dreamText').value;
    console.log('Dream Submitted:', dreamText);
    document.getElementById('dreamForm').reset();
    // Additional form submission logic here
});

// Star rating functionality
const stars = document.querySelectorAll('.star-rating label');
let currentRating = 0;

stars.forEach((star, index) => {
    star.addEventListener('click', () => {
        currentRating = index + 1;
        updateStarRating(currentRating);
    });
});

function updateStarRating(rating) {
    stars.forEach((star, index) => {
        star.style.color = index < rating ? '#ffc107' : '#ddd';
    });
}

// Character counter
const dreamText = document.getElementById('dreamText');
const charCount = document.getElementById('charCount');
dreamText.addEventListener('input', function() {
    charCount.textContent = `${this.value.length}/1000`;
});

// Search functionality
const searchBar = document.getElementById('searchBar');
searchBar.addEventListener('input', function() {
    let searchText = this.value.toLowerCase();
    filterDreamEntries(searchText);
});

function filterDreamEntries(searchText) {
    const filteredEntries = previousEntries.filter(entry => entry.toLowerCase().includes(searchText));
    displayDreamEntries(filteredEntries);
}

function displayDreamEntries(entries) {
    entriesContainer.innerHTML = ''; // Clear existing entries
    entries.forEach(entry => {
        const p = document.createElement('p');
        p.textContent = entry;
        entriesContainer.appendChild(p);
    });
}

// Mock data for previous entries
const previousEntries = [
    "I was flying over the mountains...",
    "I found a hidden door in my house...",
    // Add more static entries for demonstration
];

const recommendedReadings = [
    { title: "Understanding Dreams", url: "https://example.com/dreams1" },
    { title: "The World of Dreams", url: "https://example.com/dreams2" },
    // Add more links for demonstration
];

// Populate previous entries
const entriesContainer = document.getElementById('previousEntries');
previousEntries.forEach(entry => {
    const p = document.createElement('p');
    p.textContent = entry;
    entriesContainer.appendChild(p);
});

// Populate recommended readings
const readingsContainer = document.getElementById('recommendedReadings');
recommendedReadings.forEach(reading => {
    const a = document.createElement('a');
    a.href = reading.url;
    a.textContent = reading.title;
    a.target = "_blank"; // Open in new tab
    readingsContainer.appendChild(a);
    readingsContainer.appendChild(document.createElement('br'));
});



    </script>
</body>
</html>
