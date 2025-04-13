document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.querySelector('input[name="new_password"]');
    const confirmPassword = document.querySelector('input[name="confirm_password"]');
    const submitBtn = document.getElementById('submit-btn');
    const matchMessage = document.getElementById('password-match-message');
    const form = document.querySelector('form');

    function checkPasswords() {
        if (confirmPassword.value === '') {
            matchMessage.style.display = 'none';
            submitBtn.disabled = false;
            return;
        }

        if (newPassword.value !== confirmPassword.value) {
            matchMessage.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            matchMessage.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    newPassword.addEventListener('input', checkPasswords);
    confirmPassword.addEventListener('input', checkPasswords);

    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to reset your password?')) {
            this.submit();
        }
    });

    
    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('newPassword');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.textContent = '👁️';
        } else {
            passwordField.type = 'password';
            this.textContent = '👁️‍🗨️';
        }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('confirmPassword');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.textContent = '👁️';
        } else {
            passwordField.type = 'password';
            this.textContent = '👁️‍🗨️';
        }
    });
});