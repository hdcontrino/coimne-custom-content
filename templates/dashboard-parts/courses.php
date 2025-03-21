<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-courses-container">
    <div class="coimne-courses-search">
        <form id="coimne-courses-form">
            <input type="text" name="name" placeholder="Buscar por nombre...">
            <input type="date" name="start_date" placeholder="Fecha inicio curso">
            <input type="date" name="end_date" placeholder="Fecha fin curso">
            <input type="date" name="start_enrollment" placeholder="Inicio inscripción">
            <input type="date" name="end_enrollment" placeholder="Fin inscripción">
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
        </div>
    </template>

    <div class="coimne-courses-pagination" id="coimne-courses-pagination">
        <span class="prev-page">‹</span>
        <div class="all-pages"></div>
        <span class="next-page">›</span>
    </div>

    <div class="coimne-courses-content" id="coimne-courses-results"></div>
</div>