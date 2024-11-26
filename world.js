document.addEventListener("DOMContentLoaded", () => {
    const lookupButton = document.getElementById("lookup");
    const lookupCitiesButton = document.getElementById("lookup-cities");
    const resultDiv = document.getElementById("result");

    // Helper function to clear results
    function clearResults() {
        resultDiv.innerHTML = "";
    }

    // Helper function to display an error message
    function displayError(message) {
        clearResults();
        const errorMessage = document.createElement("p");
        errorMessage.textContent = message;
        errorMessage.style.color = "red";
        resultDiv.appendChild(errorMessage);
    }

    // Function to handle lookup for countries or cities
    function fetchData(isCityLookup = false) {
        // Get the country name entered by the user
        const countryName = document.getElementById("country").value.trim();

        if (!countryName) {
            displayError("Please enter a country name.");
            return;
        }

        // Build the query URL
        const queryParam = isCityLookup ? "&lookup=cities" : "";
        const url = `world.php?country=${encodeURIComponent(countryName)}${queryParam}`;

        // Make the AJAX request
        fetch(url)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then((data) => {
                clearResults();

                if (data.trim() === "") {
                    displayError("No results found.");
                } else {
                    resultDiv.innerHTML = data;
                }
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
                displayError("An error occurred while fetching the data.");
            });
    }

    // Event listener for country lookup
    lookupButton.addEventListener("click", () => {
        fetchData(false); // false indicates a country lookup
    });

    // Event listener for city lookup
    lookupCitiesButton.addEventListener("click", () => {
        fetchData(true); // true indicates a city lookup
    });
});
