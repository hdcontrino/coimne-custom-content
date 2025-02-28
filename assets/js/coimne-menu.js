document.addEventListener("DOMContentLoaded", function () {
    // Manejo del cierre de sesión
    const logoutForm = document.getElementById("coimne-logout-form");
    if (logoutForm) {
        logoutForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append("action", "coimne_logout");

            fetch(coimneMenuData.ajaxUrl, {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
    }

    // Cargar dinámicamente los datos del usuario
    const userContentContainer = document.getElementById("coimne-dynamic-content");
    const menuProfile = document.getElementById("menu-profile");
    if (userContentContainer) {
        menuProfile.querySelectorAll("li a").forEach(button => {
            button.addEventListener("click", function () {
                const contentType = this.getAttribute("data-content");
                userContentContainer.innerHTML = "<p>Cargando...</p>";

                fetch(coimneMenuData.ajaxUrl + "?action=coimne_get_dynamic_content&content=" + contentType)
                    .then((response) => response.json())
                    .then((response) => {
                        userContentContainer.innerHTML = response.data.content;
                        if (typeof coimneDashboard !== "undefined") {
                            coimneDashboard.init();
                        } else {
                            console.error("coimneDashboard no está definido.");
                        }
                    })
                    .catch((error) => {
                        console.error("Error al cargar el contenido:", error);
                        userContentContainer.innerHTML = "<p>Error al cargar el contenido.</p>";
                    });
            });
        });
    }
});
