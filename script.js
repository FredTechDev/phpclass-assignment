/* Student Grade Calculator - client-side validation */

/**
 * Validate a single mark field.
 * Returns an error message string, or an empty string when valid.
 */
function validateMark(fieldName, label, maximumMark) {
    const field = document.getElementById(fieldName);
    const value = field.value.trim();

    // Check that the field has been filled.
    if (value === "") {
        return label + " is required.";
    }

    // Check that the value is numeric.
    if (isNaN(Number(value))) {
        return label + " must be a number.";
    }

    // Check that the value is within the allowed range for this assessment.
    const mark = Number(value);
    if (mark < 0 || mark > maximumMark) {
        return label + " must be between 0 and " + maximumMark + ".";
    }

    return "";
}

/**
 * Validate all form fields before submission.
 * Displays inline error messages and prevents submission when invalid.
 */
function validateForm(event) {
    const fields = [
        { id: "cat1", label: "CAT 1", maximumMark: 20 },
        { id: "cat2", label: "CAT 2", maximumMark: 20 },
        { id: "exam", label: "Final Examination", maximumMark: 60 }
    ];

    let formIsValid = true;

    fields.forEach(function (field) {
        const message = validateMark(field.id, field.label, field.maximumMark);
        document.getElementById(field.id + "-error").textContent = message;

        if (message !== "") {
            formIsValid = false;
        }
    });

    if (!formIsValid) {
        event.preventDefault();
    }

    return formIsValid;
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("gradeForm");

    if (form) {
        form.addEventListener("submit", validateForm);
    }
});
