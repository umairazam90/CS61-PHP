// js/script.js

document.addEventListener('DOMContentLoaded', function() {
    // Basic client-side form validation (can be replaced with Bootstrap's built-in validation)
    const forms = document.querySelectorAll('form:not([data-no-validation])'); // Add data-no-validation to forms handled by onchange submit etc.

    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const requiredInputs = form.querySelectorAll('[required]');

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid'); // Bootstrap invalid style
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                event.preventDefault(); // Stop form submission
                // You could display a more prominent error message here
            }
        });

        // Remove invalid state when user starts typing
        form.querySelectorAll('[required]').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    });

    // Confirmation for Delete Lawyer
    const deleteLawyerButtons = document.querySelectorAll('.btn-delete-lawyer');
    deleteLawyerButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to delete this lawyer? This action will also delete their appointments and cannot be undone.')) {
                event.preventDefault();
            }
        });
    });

    // Confirmation for Cancel Appointment
    const cancelAppointmentButtons = document.querySelectorAll('.btn-cancel-appointment');
    cancelAppointmentButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to cancel this appointment?')) {
                event.preventDefault();
            }
        });
    });

});