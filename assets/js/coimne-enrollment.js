document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('coimne-enrollment-form');
    const submitButton = document.getElementById("coimne-enrollment-submit");
    const loader = document.getElementById("coimne-enrollment-loader");
    const messageContainer = document.getElementById("coimne-enrollment-message");
    const nifInput = document.getElementById("nif");
    const phoneInput = document.getElementById("phone");
    const termsCheckbox = document.getElementById("terms");
    const privacyCheckbox = document.getElementById("privacy");

    if (!form) return;

    function validarNIF(nif) {
        const dniRegex = /^[0-9]{8}[A-Z]$/;
        const nieRegex = /^[XYZ][0-9]{7}[A-Z]$/;

        if (!dniRegex.test(nif) && !nieRegex.test(nif)) {
            return false;
        }

        let num = nif.slice(0, -1);
        let letra = nif.slice(-1);

        // Para NIE, convertir la letra inicial a número
        if (nif.startsWith("X")) num = "0" + num.slice(1);
        if (nif.startsWith("Y")) num = "1" + num.slice(1);
        if (nif.startsWith("Z")) num = "2" + num.slice(1);

        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        return letra === letras[num % 23];
    }

    function validarTelefono(phone) {
        let sanitizedPhone = phone.replace(/[\s-]/g, "");

        const phoneRegex = /^[6789]\d{8}$/;
        return phoneRegex.test(sanitizedPhone);
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        messageContainer.textContent = "";

        if (!termsCheckbox.checked || !privacyCheckbox.checked) {
            messageContainer.textContent = "⚠ Debes aceptar la Cláusula Informativa y las Políticas de Privacidad.";
            messageContainer.style.color = "red";
            return;
        }

        const nifValue = nifInput.value.toUpperCase().trim();
        if (!validarNIF(nifValue)) {
            messageContainer.textContent = "⚠ El NIF ingresado no es válido.";
            messageContainer.style.color = "red";
            return;
        }

        const phoneValue = phoneInput.value.trim();
        if (!validarTelefono(phoneValue)) {
            messageContainer.textContent = "⚠ El teléfono ingresado no es válido.";
            messageContainer.style.color = "red";
            return;
        }

        loader.style.display = "inline-block";
        submitButton.disabled = true;

        const formData = new FormData(form);
        formData.append("action", "coimne_submit_enrollment");

        fetch(coimneEnrollmentData.ajaxUrl, {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageContainer.style.color = data.success ? "green" : "red";
                    messageContainer.textContent = data.message;
                    form.reset();
                } else {
                    messageContainer.textContent = data.message;
                    messageContainer.style.color = "red";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                messageContainer.textContent = "⚠ Hubo un error. Inténtalo de nuevo.";
                messageContainer.style.color = "red";
            })
            .finally(() => {
                loader.style.display = "none";
                submitButton.disabled = false;
            });
    });
});
