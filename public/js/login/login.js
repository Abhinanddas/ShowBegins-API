window.onload = function () {
    document.getElementById('login-button').addEventListener("click", login);
};

function login(e) {
    e.preventDefault();
    let isFormValid;
    isFormValid = validateLoginForm();
    if (!isFormValid) {
        addToast('Please sumbit valid credentials', 'error');
        return;
    }
    document.getElementById('login-form').submit();
}

function validateLoginForm() {
    let email = document.loginForm.email;
    let password = document.loginForm.password;
    let isValid = true;

    if (email === '' || !emailField.checkValidity()) {
        isValid = false;
        return isValid;
    }

    if (password == '') {
        isValid = false;
        return isValid;
    }

    return isValid;
}


