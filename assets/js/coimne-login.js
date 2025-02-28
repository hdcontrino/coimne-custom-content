document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('coimne-login-form');
    const forgotPassword = document.getElementById("coimne-forgot-password-form");
    const loginContainer = document.querySelector(".coimne-login-container");
    const showForgotPassword = document.getElementById("show-forgot-password");
    const showLogin = document.getElementById("show-login");

    if (!coimneLoginData.recaptchaEnabled) {
        loginMessage.textContent = coimneLoginData.errorMessage;
        loginMessage.style.color = 'red';
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const submitButton = document.getElementById('coimne-login-submit');
        const loginMessage = document.getElementById('coimne-login-message');
        const loader = document.getElementById('coimne-login-loader');
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

    showForgotPassword.addEventListener("click", function () {
        loginContainer.classList.add("flip");
    });

    showLogin.addEventListener("click", function () {
        loginContainer.classList.remove("flip");
    });

    forgotPassword.addEventListener("submit", function (e) {
        e.preventDefault();
        const submitButton = document.getElementById("coimne-forgot-submit");
        const messageContainer = document.getElementById("coimne-forgot-message");
        const loader = document.getElementById("coimne-forgot-loader");
        const submitButtonOriginalContent = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.textContent = "Enviando...";
        loader.style.display = "inline-block";

        const formData = new FormData();
        formData.append("action", "coimne_forgot_password");
        formData.append("email", document.getElementById("coimne-email").value);

        fetch(coimneLoginData.ajaxUrl, {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                messageContainer.style.color = data.success ? "green" : "red";
                messageContainer.textContent = data.message;
            })
            .catch(error => {
                console.error('Error:', error);
                messageContainer.textContent = "Ocurrió un error inesperado.";
                messageContainer.style.color = "red";
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = submitButtonOriginalContent;
                loader.style.display = "none";
            });
    });
});
