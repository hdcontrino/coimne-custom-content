document.addEventListener("DOMContentLoaded", function () {
    coimneDashboard.init();
});

const coimneDashboard = {
    init: function () {
        this.profile.init();
        this.projects.init();
        this.account.init();
    },

    profile: {
        init: function () {
            this.form = document.getElementById("profile-form");
            const tabLinks = document.querySelectorAll(".tab-link");
            const tabPanes = document.querySelectorAll(".tab-pane");

            this.country = document.getElementById("countries");
            this.province = document.getElementById("provinces");
            this.loc = document.getElementById("locs");
            this.town = document.getElementById("towns");

            this.emp_pai = document.getElementById("emp_pai");
            this.emp_pro = document.getElementById("emp_pro");
            this.emp_loc = document.getElementById("emp_loc");
            this.emp_pob = document.getElementById("emp_pob");

            tabLinks.forEach(link => {
                link.addEventListener("click", function () {
                    tabLinks.forEach(link => link.classList.remove("active"));
                    tabPanes.forEach(pane => pane.classList.remove("active"));

                    const tabId = this.getAttribute("data-tab");
                    document.getElementById(tabId).classList.add("active");
                    this.classList.add("active");
                });
            });

            if (this.form) {
                this.form.addEventListener("submit", this.submit);
            }

            if (this.form && this.country) {
                this.country.addEventListener("change", this.countryChange);
                this.province.addEventListener("change", this.provinceChange);
            }

            if (this.form && this.emp_pai) {
                this.emp_pai.addEventListener("change", this.empPaiChange);
                this.emp_pro.addEventListener("change", this.empProChange);
            }
        },

        updateProvinces: function (provinceSelect, countryId) {
            const formGroup = provinceSelect.closest(".coimne-form-group");
            formGroup.classList.add("loading");

            getProvinces(countryId)
                .then(provinces => {
                    if (provinces.length > 0) {
                        provinceSelect.innerHTML = '<option value="">Seleccionar provincia</option>';
                        provinces.forEach(province => {
                            const option = document.createElement("option");
                            option.value = province.ID;
                            option.textContent = province.NAME;
                            provinceSelect.appendChild(option);
                        });
                    } else {
                        provinceSelect.innerHTML = "<option>No hay provincias disponibles</option>";
                    }
                })
                .catch(error => {
                    console.error("Error al cargar provincias: ", error);
                    provinceSelect.innerHTML = "<option>Error al cargar</option>";
                })
                .finally(() => {
                    formGroup.classList.remove("loading");
                });
        },

        updateTowns: function (townSelect, countryId, provinceId) {
            const formGroup = townSelect.closest(".coimne-form-group");
            formGroup.classList.add("loading");

            getTowns(countryId, provinceId)
                .then(towns => {
                    if (towns.length > 0) {
                        townSelect.innerHTML = '<option value="">Seleccionar población</option>';
                        towns.forEach(town => {
                            const option = document.createElement("option");
                            option.value = town.ID;
                            option.textContent = town.NAME;
                            townSelect.appendChild(option);
                        });
                    } else {
                        townSelect.innerHTML = "<option>No hay poblaciones disponibles</option>"
                    }
                })
                .catch(error => {
                    console.log("Error al cargar poblaciones: ", error);
                    townSelect.innerHTML = "<option>Error al cargar</option>";
                })
                .finally(() => {
                    formGroup.classList.remove("loading");
                });
        },

        countryChange: function (e) {
            const target = coimneDashboard.profile.province;
            const countryId = e.target.value;
            coimneDashboard.profile.updateProvinces(target, countryId);
            coimneDashboard.profile.town.value = '';
        },

        provinceChange: function (e) {
            const target = coimneDashboard.profile.town;
            const countryId = coimneDashboard.profile.country.value;
            const provinceId = e.target.value;
            coimneDashboard.profile.updateTowns(target, countryId, provinceId);
        },

        empPaiChange: function (e) {
            const target = coimneDashboard.profile.emp_pro;
            const countryId = e.target.value;
            coimneDashboard.profile.updateProvinces(target, countryId);
            coimneDashboard.profile.emp_pob.value = '';
        },
        
        empProChange: function (e) {
            const target = coimneDashboard.profile.emp_pob;
            const countryId = coimneDashboard.profile.emp_pai.value;
            const provinceId = e.target.value;
            coimneDashboard.profile.updateTowns(target, countryId, provinceId);
        },

        submit: function (e) {
            e.preventDefault();

            const submitButton = document.getElementById('coimne-profile-submit');
            const profileMessage = document.getElementById('coimne-profile-message');
            const loader = document.getElementById('coimne-profile-loader');
            const submitButtonOriginalContent = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.textContent = "Guardando...";
            profileMessage.textContent = "";
            loader.style.display = "inline-block";

            const formData = new FormData(coimneDashboard.profile.form);
            formData.append("action", "coimne_set_user_profile");

            fetch(coimneDashboardData.ajaxUrl, {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        profileMessage.textContent = "Perfil actualizado correctamente.";
                        profileMessage.style.color = 'green';
                    } else {
                        profileMessage.textContent = data.message;
                        profileMessage.style.color = 'red';
                    }
                })
                .catch(error => console.error(error))
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButtonOriginalContent;
                    loader.style.display = "none";
                });
        }
    },

    projects: {
        init: function () {
            console.log("Inicializando sección de proyectos...");
            // Aquí irán las funciones específicas de proyectos cuando se implementen
        }
    },

    account: {
        init: function () {
            this.form = document.getElementById("account-form");

            if (this.form) {
                this.form.addEventListener("submit", this.submit);
            }
        },

        submit: function (e) {
            e.preventDefault();

            const submitButton = document.getElementById('coimne-account-submit');
            const accountMessage = document.getElementById('coimne-account-message');
            const loader = document.getElementById('coimne-account-loader');
            const password = document.getElementById('password');
            const newpassword = document.getElementById('new_password');
            const submitButtonOriginalContent = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.textContent = "Guardando...";
            accountMessage.textContent = "";
            loader.style.display = "inline-block";

            const formData = new FormData(coimneDashboard.account.form);
            formData.append("action", "coimne_set_user_account");

            fetch(coimneDashboardData.ajaxUrl, {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        accountMessage.textContent = "Acceso actualizado correctamente.";
                        accountMessage.style.color = 'green';
                    } else {
                        accountMessage.textContent = data.message;
                        accountMessage.style.color = 'red';
                    }
                })
                .catch(error => console.error(error))
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButtonOriginalContent;
                    loader.style.display = "none";
                    password.value = '';
                    newpassword.value = '';
                });
        }
    }
};

function getProvinces(countryId) {
    return fetch(`${coimneDashboardData.ajaxUrl}?action=coimne_get_provinces&country=${countryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.list) {
                return data.data.list;
            } else {
                return [];
            }
        })
        .catch(error => {
            console.error("Error al recibir provincias:", error);
            return [];
        });
}

function getTowns(countryId, provinceId) {
    return fetch(`${coimneDashboardData.ajaxUrl}?action=coimne_get_towns&country=${countryId}&province=${provinceId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.list) {
                return data.data.list;
            } else {
                return [];
            }
        })
        .catch(error => {
            console.error("Error al recibir pobloados:", error);
            return [];
        });
}
