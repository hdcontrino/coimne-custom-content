document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('coimne-login-form');
    const submitButton = document.getElementById('coimne-login-submit');
    const loginMessage = document.getElementById('coimne-login-message');
    const loader = document.getElementById('coimne-login-loader');

    if (!submitButton || !form) {
        console.error("Error: No se encontró el formulario de login.");
        return;
    }

    if (!coimneLoginData.recaptchaEnabled) {
        loginMessage.textContent = coimneLoginData.errorMessage;
        loginMessage.style.color = 'red';
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const submitButtonOriginalContent = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.textContent = "Ingresando...";
        loader.style.display = "inline-block";

        const formData = new FormData();
        formData.append('action', 'coimne_login');
        formData.append('username', document.getElementById('coimne-username').value);
        formData.append('password', document.getElementById('coimne-password').value);
        formData.append('g-recaptcha-response', grecaptcha.getResponse());

        fetch(coimneLoginData.ajaxUrl, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect ? data.redirect : coimneLoginData.redirectUrl;
                } else {
                    loginMessage.textContent = data.message;
                    loginMessage.style.color = 'red';
                    grecaptcha.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loginMessage.textContent = "Ocurrió un error inesperado.";
                loginMessage.style.color = 'red';
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = submitButtonOriginalContent;
                loader.style.display = "none";
            });
    });
});
