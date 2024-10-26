let currentIndex = 0;
let industries = [];



// Function to fetch all industries at once
function fetchIndustries() {
    fetch('fetchIndustries.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            industries = data;
            displayIndustries();
        })
        .catch(error => {
            console.error('Fetch error: ', error);
        });
}

// Function to display three industries at a time
function displayIndustries() {
    const container = document.getElementById('industries-container');

    // Clear previous industries
    container.innerHTML = '';

    // Display the next set of three industries
    for (let i = currentIndex; i < currentIndex + 3 && i < industries.length; i++) {
        const industry = industries[i];
        const industryDiv = document.createElement('div');
        industryDiv.className = 'industry';
        industryDiv.innerHTML = `
            <h3>${industry.name}</h3>
            <p>Location: ${industry.location}</p>
            <p>Domain: ${industry.domain}</p>
            <p>Work Type: ${industry.work_type}</p>
            <p>Skills: ${industry.skills}</p>
        `;
        container.appendChild(industryDiv);
    }

    // Update the current index
    currentIndex += 3;

    // Hide the 'Load More' button if there are no more industries to display
    if (currentIndex >= industries.length) {
        document.getElementById('load-more').style.display = 'none';
    }
}

// Load more industries when the button is clicked
document.getElementById('load-more').addEventListener('click', displayIndustries);

// Fetch all records on page load
fetchIndustries();





// Detect when the feature boxes come into view and add animation class
window.addEventListener('scroll', function() {
const elements = document.querySelectorAll('.animated-box');
const scrollTop = window.scrollY + window.innerHeight;

elements.forEach(function(element) {
    if (element.offsetTop < scrollTop) {
        element.classList.add('visible');
    }
});
});



// register as gig worker
window.registerAsGigWorker = function() {
    console.log("clicked");
    
    document.getElementById("gig").click()
};

