document.addEventListener('DOMContentLoaded', function () {
    initAgeFilter('#min_age', '#max_age', authUserGender, savedMinAge, savedMaxAge);
    initHeightFilter('#min_height', '#max_height');
});

// Utility: Generate option tags from start to end
function generateOptions(start, end) {
    const options = [];
    for (let i = start; i <= end; i++) {
        options.push(`<option value="${i}">${i}</option>`);
    }
    return options.join('');
}

// Age Filter Initialization
function initAgeFilter(minSelector, maxSelector, gender, savedMinAge = null, savedMaxAge = null) {
    const minAgeEl = document.querySelector(minSelector);
    const maxAgeEl = document.querySelector(maxSelector);

    const minAgeDefault = gender === 'female' ? 21 : 18;
    const maxAgeLimit = 70;

    // Populate min age options
    minAgeEl.innerHTML = `<option value="">Min Age</option>${generateOptions(minAgeDefault, maxAgeLimit)}`;

    const selectedMinAge = savedMinAge ?? minAgeDefault;
    minAgeEl.value = String(savedMinAge ?? '');

    // Function to update max age
    function updateMaxAge(min) {
        maxAgeEl.innerHTML = `<option value="">Max Age</option>${generateOptions(min, maxAgeLimit)}`;
        if (savedMaxAge) {
            maxAgeEl.value = String(savedMaxAge);
        }
    }

    updateMaxAge(parseInt(selectedMinAge));

    // On change of min age
    minAgeEl.addEventListener('change', () => {
        const newMin = parseInt(minAgeEl.value) || minAgeDefault;
        updateMaxAge(newMin);
    });
}


// Height Filter Initialization
function initHeightFilter(minSelector, maxSelector) {
    const minHeightEl = document.querySelector(minSelector);
    const maxHeightEl = document.querySelector(maxSelector);

    // Safely exit if elements not found
    if (!minHeightEl || !maxHeightEl) return;

    const allMaxHeightOptions = Array.from(maxHeightEl.options);
    minHeightEl.addEventListener('change', () => {
        const selectedIndex = minHeightEl.selectedIndex;
        maxHeightEl.innerHTML = allMaxHeightOptions
            .filter((opt, i) => i >= selectedIndex)
            .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
            .join('');
    });
}
