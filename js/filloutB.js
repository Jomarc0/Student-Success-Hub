
document.addEventListener('DOMContentLoaded', function() {
    const othersCheckbox = document.getElementById('others-checkbox');
    const othersInput = document.getElementById('others-input');

    if (othersCheckbox && othersInput) {
        // Initial setup
        othersInput.style.display = othersCheckbox.checked ? 'inline-block' : 'none';

        // Add event listener
        othersCheckbox.addEventListener('change', function() {
            othersInput.style.display = this.checked ? 'inline-block' : 'none';
            if (!this.checked) {
                othersInput.value = '';
            }
        });

        // Add input event listener
        othersInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                othersCheckbox.checked = true;
            }
        });
    }
});

// Modal handler
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const openModal = urlParams.get('openModal');
    if (openModal === 'FillingOutB') {
        loadFormContent('FillingOutB.php');
    }
});