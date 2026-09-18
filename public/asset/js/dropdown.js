$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    const dropdownMappings = [
        { trigger: '#country', target: '#state', url: 'get-states', param: 'country', reset: ['#city'], placeholder: 'Select State' },
        { trigger: '#state', target: '#city', url: 'get-cities', param: 'state', reset: [], placeholder: 'Select City' },
        { trigger: '#occupation_type', target: '#occupation', url: 'get-occupations', param: 'occupation_type', reset: [], placeholder: 'Select Occupation' },
        { trigger: '#education_level', target: '#education', url: 'get-education', param: 'education_level', reset: [], placeholder: 'Select Education' },
        { trigger: '#caste', target: '#sub_caste', url: 'get-subCastes', param: 'caste', reset: [], placeholder: 'Select Sub Caste' },

        { trigger: '#birth_country', target: '#birth_state', url: 'get-states', param: 'country', reset: ['#birth_city'], placeholder: 'Select State' },
        { trigger: '#birth_state', target: '#birth_city', url: 'get-cities', param: 'state', reset: [], placeholder: 'Select City' },
    ];

    dropdownMappings.forEach(({ trigger, target, url, param, reset, placeholder }) => {
        $(document).on('change', trigger, function () {
            let value = $(this).val();
            resetDropdowns([{ selector: target, placeholder }, ...reset.map(sel => ({ selector: sel, placeholder: 'Select' }))]);
            if (value) fetchData(url, { [param]: value }, target);
        });
    });

    function resetDropdowns(dropdowns) {
        dropdowns.forEach(({ selector, placeholder }) => {
            $(selector).html(`<option value="">${placeholder}</option>`);
        });
    }

    function fetchData(url, data, targetDropdown) {
        $.post(url, { _token: csrfToken, ...data }, function (response) {
            $(targetDropdown).append(response.map(name => `<option value="${name}">${name}</option>`));
        });
    }
});
