document.addEventListener("DOMContentLoaded", function () {
    coimneDashboard.init();
});

const coimneDashboard = {
    init: function () {
        this.profile.init();
        this.courses.init();
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
                this.initSelect2("#empresa", false);
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

        initSelect2: function (select, allowClear = true) {
            if (typeof jQuery !== "undefined") {
                const $select = jQuery(select);

                if ($select.length) {
                    $select.select2({
                        placeholder: "Buscar...",
                        allowClear: allowClear,
                        width: '100%',
                        minimumInputLength: 3,
                        ajax: {
                            url: coimneDashboardData.ajaxUrl,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    action: 'coimne_search_companies',
                                    search: params.term
                                };
                            },
                            processResults: function (data) {
                                const result = data.data;
                                if (!data.success) {
                                    console.log("Select2: " + result?.message);
                                    return { results: [] };
                                }
                                
                                return {
                                    results: result[0].map(item => ({
                                        id: item.ID,
                                        text: item.NAME
                                    }))
                                };
                            },
                            cache: true
                        },
                        language: {
                            searching: function () {
                                return "Buscando...";
                            },
                            errorLoading: function () {
                                return "⚠ No se pudo cargar la información";
                            },
                            inputTooShort: function () {
                                return "Introduce al menos 3 caracteres";
                            },
                            noResults: function () {
                                return "No se encontraron resultados";
                            }
                        }
                    });
                }
            } else {
                console.warn("jQuery no está disponible. Select2 no se pudo inicializar.");
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
            coimneDashboard.profile.loc.value = '';
            coimneDashboard.profile.town.value = '';
        },

        provinceChange: function (e) {
            const locTarget = coimneDashboard.profile.loc;
            const townTarget = coimneDashboard.profile.town;
            const countryId = coimneDashboard.profile.country.value;
            const provinceId = e.target.value;
            coimneDashboard.profile.updateTowns(townTarget, countryId, provinceId);
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

    courses: {
        init: function () {
            this.form = document.getElementById("coimne-courses-form");
            this.paginator = document.getElementById("coimne-courses-pagination");
            this.results = document.getElementById("coimne-courses-results");

            if (this.form && this.results) {
                this.form.addEventListener("submit", this.submit.bind(this));
            }

            if (this.paginator) {
                this.paginator.querySelector(".all-pages").addEventListener("click", e => {
                    if (e.target.tagName === "A" && e.target.dataset.page) {
                        e.preventDefault();
                        this.loadResults(parseInt(e.target.dataset.page));
                    }
                });
                this.paginator.querySelector(".prev-page").addEventListener("click", e => {
                    e.preventDefault();
                    const active = this.paginator.querySelector(".all-pages a.active");
                    if (!active) return;

                    const prev = active.previousElementSibling;
                    if (prev && prev.tagName === "A" && prev.dataset.page) {
                        this.loadResults(parseInt(prev.dataset.page));
                    }
                });
                this.paginator.querySelector(".next-page").addEventListener("click", e => {
                    e.preventDefault();
                    const active = this.paginator.querySelector(".all-pages a.active");
                    if (!active) return;

                    const next = active.nextElementSibling;
                    if (next && next.tagName === "A" && next.dataset.page) {
                        this.loadResults(parseInt(next.dataset.page));
                    }
                });
            }

            if (this.results) {
                this.loadResults(1);
            }
        },

        submit: function (e) {
            e.preventDefault();
            this.loadResults(1);
        },

        loadResults: function (page = 1) {
            const formData = new FormData(this.form);
            formData.append("action", "coimne_get_user_courses");
            formData.append("page", page);

            this.results.innerHTML = "<p>Cargando resultados...</p>";

            fetch(coimneDashboardData.ajaxUrl, {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || "Error al cargar resultados");
                }
                if (data.total_pages > 1) {
                    this.paginator.style.opacity = 1;
                    const pageContainer = this.paginator.querySelector(".all-pages");
                    pageContainer.innerHTML = "";

                    for (let i = 1; i <= data.total_pages; i++) {
                        const pageLink = document.createElement("a");
                        pageLink.href = "#";
                        pageLink.dataset.page = i;
                        pageLink.textContent = i;

                        if (i === data.page) {
                            pageLink.classList.add("active");
                        }

                        pageContainer.appendChild(pageLink);
                    }
                }

                const courses = data.data;
                const template = document.getElementById("coimne-course-template");
                this.results.innerHTML = "";
                
                if (!Array.isArray(courses) || !courses.length) {
                    return this.results.innerHTML = "<p style='text-align:center;'>No tienes cursos.</p>";
                }

                courses.forEach(course => {
                    const clone = document.importNode(template.content, true);
                    const wrapper = clone.querySelector(".coimne-course-item");
                    const downloadBtn = "<button class='coimne-course-download' id='coimne-course-download'>Descargar Certificado</button>";
                    const uploadBtn = "<button class='coimne-course-upload' id='coimne-course-upload'>Subir Comprobante PDF</button>";

                    wrapper.dataset.courseId = course.CUR_COI.ID;

                    clone.querySelector(".course-coi-name").textContent = course.CUR_COI.NAME ?? "—";
                    clone.querySelector(".course-ins-est").textContent = `${course.FEC_INS} — ${course.EST}`;
                    clone.querySelector(".course-mat-tip").textContent = course.MAT_TIP ?? "—";
                    clone.querySelector(".course-cuo-ins").textContent = course.CUO_INS ?? "—";
                    clone.querySelector(".course-coi-est").textContent = course.CUR_COI.EST ?? "—";
                    clone.querySelector(".course-coi-des").textContent = course.CUR_COI.DES ?? "—";
                    clone.querySelector(".course-fec-ini-cur").textContent = course.CUR_COI.FEC_INI_CUR ?? "—";
                    clone.querySelector(".course-fec-fin-cur").textContent = course.CUR_COI.FEC_FIN_CUR ?? "—";
                    clone.querySelector(".course-fec-ini-mat").textContent = course.CUR_COI.FEC_INI_MAT ?? "—";
                    clone.querySelector(".course-fec-fin-mat").textContent = course.CUR_COI.FEC_FIN_MAT ?? "—";

                    if (course.CUR_COI.EST === "Finalizado") {
                        this.createCourseButtons(clone, downloadBtn, course.CUR_COI.ID, getCertificate);
                    }

                    if (course.CUR_COI.EST === "Pendiente") {
                        this.createCourseButtons(clone, uploadBtn, course.CUR_COI.ID, uploadDocument);
                    }

                    this.results.appendChild(clone);

                    wrapper.addEventListener("click", e => {
                        wrapper.classList.toggle("expanded");
                    });
                });
            })
            .catch(err => {
                this.results.innerHTML = "<p>⚠ Error al cargar resultados</p>";
                console.error("loadResults error", err);
            });
        },

        createCourseButtons: function (parent, htmlButton, courseId, commAction) {
            const actions = parent.querySelector(".coimne-course-actions");
            const loader = parent.querySelector(".coimne-loader");
            const courseMessage = parent.querySelector(".coimne-course-message");
            actions.innerHTML = htmlButton;

            const actionBtn = actions.querySelector("button");
            actionBtn.addEventListener("click", () => {
                courseMessage.textContent = "";
                actionBtn.disabled = true;
                loader.style.display = 'inline';
                commAction(courseId, (message, type) => {
                    if (type === "error") {
                        courseMessage.textContent = message;
                        courseMessage.style.color = 'red';
                    } else {
                        courseMessage.textContent = message;
                        courseMessage.style.color = 'green';
                    }
                    actionBtn.disabled = false;
                    loader.style.display = 'none';
                });
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
            if (data.success && data.data) {
                return data.data[0];
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
            if (data.success && data.data) {
                return data.data[0];
            } else {
                return [];
            }
        })
        .catch(error => {
            console.error("Error al recibir municipios:", error);
            return [];
        });
}

function getCertificate(inscriptionId, callback) {
    let error = "";
    
    return fetch(`${coimneDashboardData.ajaxUrl}?action=coimne_get_certificates&insId=${inscriptionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                console.log(data);
                error = "Sin implementar aún";
            } else if (data.message) {
                error = data.message;
            }
        })
        .catch(error => {
            error = "Error al recibir certificado";
            console.error(`${error}:`, error);
        })
        .finally(() => {
            if (error) {
                return callback(error, "error");
            }
        });
}

function uploadDocument(inscriptionId, callback) {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = "application/pdf";

    input.addEventListener("change", function () {
        const file = this.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            return callback("Sólo se permite subir archivos PDF.", "error");
        }

        const formData = new FormData();
        formData.append("action", "coimne_upload_course_document");
        formData.append("inscription_id", inscriptionId);
        formData.append("file", file);

        fetch(coimneDashboardData.ajaxUrl, {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    callback("Documento subido correctamente.");
                } else {
                    callback(data.message || "Error al subir el documento.", "error");
                }
            })
            .catch(error => {
                console.error("uploadDocument error:", error);
                callback("Error inesperado al subir el documento.", "error");
            });
    });

    input.click();
}
