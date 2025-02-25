document.addEventListener("DOMContentLoaded", function () {
    coimneDashboard.init();
});

const coimneDashboard = {
    init: function () {
        this.profile.init();
        this.projects.init();
        // Agregar aquí futuras secciones del dashboard
    },

    profile: {
        init: function () {
            this.form = document.getElementById("profile-form");
            if (this.form) {
                this.form.addEventListener("submit", this.submit);
            }
        },

        submit: function (e) {
            e.preventDefault();

            let formData = new FormData(coimneDashboard.profile.form);
            formData.append("action", "coimne_set_user_profile");

            fetch(coimne_ajax.ajax_url, {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Perfil actualizado correctamente.");
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    },

    projects: {
        init: function () {
            console.log("Inicializando sección de proyectos...");
            // Aquí irán las funciones específicas de proyectos cuando se implementen
        }
    }
};