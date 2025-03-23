<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-courses-container">
    <div class="coimne-courses-search">
        <form id="coimne-courses-form">
            <div class="coimne-form-row" style="width: 100%;">
                <div class="coimne-form-group">
                    <label for="name">
                        <div>Nombre del curso (busca por partes del nombre)</div>
                        <input type="text" id="name" name="name">
                    </label>
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="iniD">
                        <div>Fecha desde Inicio curso</div>
                        <input type="date" id="iniD" name="inicioDesde">
                    </label>
                </div>
                <div class="coimne-form-group">
                    <label for="iniH">
                        <div>Fecha hasta Inicio Curso</div>
                        <input type="date" id="iniH" name="inicioHasta">
                    </label>
                </div>
            </div>
            <div class="coimne-form-row">
                <div class="coimne-form-group">
                    <label for="insD">
                        <div>Fecha Inscrito desde</div>
                        <input type="date" id="insD" name="inscriptoDesde">
                    </label>
                </div>
                <div class="coimne-form-group">
                    <label for="insH">
                        <div>Fecha Inscrito hasta</div>
                        <input type="date" id="insH" name="inscriptoHasta">
                    </label>
                </div>
            </div>
            <button type="submit">Buscar</button>
        </form>
    </div>

    <template id="coimne-course-template">
        <div class="coimne-course-item" data-course-id="">
            <div class="coimne-course-summary coimne-justify-between">
                <strong class="course-coi-name"></strong>
                <span class="course-ins-est"></span>
            </div>

            <div class="coimne-course-details">
                <div class="coimne-data-row coimne-justify-between">
                    <div><span>Tipo de matrícula: </span><span class="course-mat-tip"></span></div>
                    <div><span>Coste del curso: </span><span class="course-cuo-ins"></span></div>
                </div>
                <div class="coimne-data-row coimne-justify-right">
                    <p><span>Estado: </span><span class="course-coi-est"></span></p>
                </div>
                <div class="coimne-data-row">
                    <p><span>Descripción: </span><span class="course-coi-des"></span></p>
                </div>
                <div class="coimne-data-row coimne-justify-between">
                    <div>
                        <div><span>Fecha inicio curso: </span><span class="course-fec-ini-cur"></span></div>
                        <div><span>Fecha fin curso: </span><span class="course-fec-fin-cur"></span></div>
                    </div>
                    <div>
                        <div><span>Fecha inicio matrícula: </span><span class="course-fec-ini-mat"></span></div>
                        <div><span>Fecha fin matrícula: </span><span class="course-fec-fin-mat"></span></div>
                    </div>
                </div>
            </div>
            <div class="coimne-justify-right">
                <span class="coimne-loader"></span>
                <div class="coimne-course-actions-group">
                    <div class="coimne-course-actions"></div>
                    <div class="coimne-course-message"></div>
                </div>
            </div>
        </div>
    </template>

    <div class="coimne-courses-pagination" id="coimne-courses-pagination">
        <span class="prev-page">‹</span>
        <div class="all-pages"></div>
        <span class="next-page">›</span>
    </div>

    <div class="coimne-courses-content" id="coimne-courses-results"></div>
</div>