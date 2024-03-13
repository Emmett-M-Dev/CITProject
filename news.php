<?php include 'includes/sessionconnection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep Tracker News</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

header {
    background-color: #4a6fa5;
    color: white;
    padding: 10px 0;
    text-align: center;
}

nav {
    margin-bottom: 10px;
}

h1 {
    margin: 0;
}

main {
    padding: 20px;
}

section {
    margin-bottom: 20px;
}

h2 {
    color: #333;
}

footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
}
/* Existing styles remain the same, adding new styles below */

#searchBox {
    margin: 10px;
    padding: 5px;
    width: calc(100% - 40px);
}

#articlesList {
    display: flex;
    flex-direction: column;
}

#loadMoreArticles {
    background-color: #4a6fa5;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}


    </style>
</head>
<body>
    <header>
       
        <h1>Sleep Tracker News</h1>
        <input type="text" id="searchBox" placeholder="Search articles..." oninput="searchArticles()">
    </header>
 <?php include 'includes/nav.php'; ?>
    <main>
        <section id="articles">
            <h2>Latest Articles</h2>
            <!-- Articles will be dynamically loaded here -->
            <div id="articlesList">
                <!-- Articles will be dynamically loaded here -->
            </div>
            <button id="loadMoreArticles">Load More Articles</button>
        </section>

        <section id="news">
            <h2>Recent News</h2>
            <!-- News stories will be dynamically loaded here -->
        </section>

        <section id="resources">
            <h2>Sleep Help Resources</h2>
            <!-- Links to sleep help materials will be displayed here -->
        </section>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    loadContent();
    setupPagination();
});


let allArticles = []; // Store all articles
let displayedArticles = []; // Store currently displayed articles
const articlesPerPage = 5; // Number of articles to display per page


function loadContent() {
    // Placeholder data (more articles for pagination)
    allArticles = [
        { title: "Improving Sleep Quality", content: "Article content goes here..." },
        // More articles here...
    ];

    displayedArticles = allArticles.slice(0, articlesPerPage);
    populateSection('articlesList', displayedArticles);
}

function setupPagination() {
    const loadMoreButton = document.getElementById('loadMoreArticles');
    loadMoreButton.addEventListener('click', function() {
        const currentLength = displayedArticles.length;
        const moreArticles = allArticles.slice(currentLength, currentLength + articlesPerPage);
        displayedArticles = displayedArticles.concat(moreArticles);
        populateSection('articlesList', moreArticles);
    });
}

function searchArticles() {
    const searchText = document.getElementById('searchBox').value.toLowerCase();
    const filteredArticles = allArticles.filter(article => 
        article.title.toLowerCase().includes(searchText) || article.content.toLowerCase().includes(searchText));
    
    // Clear and repopulate the section
    document.getElementById('articlesList').innerHTML = '';
    populateSection('articlesList', filteredArticles);
}

function populateSection(sectionId, items) {
    const section = document.getElementById(sectionId);
    items.forEach(item => {
        const article = document.createElement('article');
        article.innerHTML = `<h3>${item.title}</h3><p>${item.content}</p>`;
        section.appendChild(article);
    });
}

function populateResources(sectionId, items) {
    const section = document.getElementById(sectionId);
    items.forEach(item => {
        const link = document.createElement('a');
        link.href = item.url;
        link.textContent = item.title;
        section.appendChild(link);
        section.appendChild(document.createElement('br'));
    });
}

    </script>
</body>
</html>
