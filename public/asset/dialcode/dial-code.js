document.addEventListener("DOMContentLoaded", function () {
    // Function to initialize intlTelInput
    function initializeIntlTelInput(inputId, countryCodeId) {
        const input = document.querySelector(inputId);
        const countryCodeInput = document.querySelector(countryCodeId);

        if (input && countryCodeInput) {
            const iti = window.intlTelInput(input, {
                initialCountry: "in",
                separateDialCode: true,
                nationalMode: false,
                formatOnDisplay: false,
                utilsScript: "public/assets/dialcode/utils.js",
            });

            const updateCountryCode = () => {
                countryCodeInput.value = iti.getSelectedCountryData().dialCode;
            };

            // Set initial country code
            updateCountryCode();

            // Update country code on change
            input.addEventListener("countrychange", updateCountryCode);

            // Ensure only numeric input
            input.addEventListener("input", () => {
                input.value = input.value.replace(/\D/g, '');
            });
        }
    }

    // Initialize for both inputs
    initializeIntlTelInput("#phone", "#phone_country_code");
    initializeIntlTelInput("#mobile", "#mobile_country_code");
});
