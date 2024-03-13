<style>   /* Custom CSS for fixed navigation bar */
       .nav {
      position: fixed; /* Changed from absolute to fixed for full-width nav bar */
      top: 0;
      left: 0; /* Added for full-width nav bar */
      right: 0;
      padding: 1rem;
      width: 100%; /* Ensure the nav is full width */
      background-color: transparent; /* Set background to transparent */
      z-index: 10; /* Ensure nav is above other content */
  }

  .nav a {
      color: white;
      text-decoration: none;
      margin-left: 2rem;
      font-weight: 700;
  }
</style>
<nav class="nav bg-black p-4 text-white fixed w-full flex justify-between items-center">
        <span class="text-xl font-bold ml-4">Sleepio</span>
        <div class="mr-8">
            <a href="dashboard.php" class="text-white px-4 hover:text-gray-300">Dashboard</a>
            <a href="history.php" class="text-white px-4 hover:text-gray-300">History</a>
            <a href="profile.php" class="text-white px-4 hover:text-gray-300">Profile</a>
            <a href="#" class="text-white px-4 hover:text-gray-300">Settings</a>
        </div>
    </nav>