
        window.addEventListener("load", function () {
            const container = document.querySelector(".container");
            container.classList.add("show");
        });


        // Function to show the popup form
function showPopupForm() {
    document.getElementById('popupForm').classList.remove('hidden');
}

// Function to hide the popup form
function hidePopupForm() {
    document.getElementById('popupForm').classList.add('hidden');
}

// Event listener for the "Finish" button to show the popup form
document.getElementById('finishButton').addEventListener('click', function () {
    showPopupForm();
});

// Event listener for the "Submit" button within the popup form
document.getElementById('submitPopupForm').addEventListener('click', function () {
    // Capture sleep quality and wake-up count inputs
    const sleepQuality = document.getElementById('sleepQuality').value;
    const wakeUpCount = document.getElementById('wakeUpCount').value;

    // Perform any validation here if needed

    // Close the popup form
    hidePopupForm();

    // Perform actions to submit the inputs to the database (you'll need to implement this part)
});

    