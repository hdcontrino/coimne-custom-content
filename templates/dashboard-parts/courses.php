<?php if (!defined('ABSPATH')) exit; ?>

<div class="coimne-courses-container">
    <div class="coimne-title">
        <?php _e('Mis Cursos', 'coimne-custom-content'); ?>
    </div>

    <div class="coimne-courses-content">
        <?php foreach ($courses as $course): ?>
            <div class="coimne-course-item">
                <div>
                    <span>MAT_TIP: </span>
                    <span><?php echo $course['MAT_TIP']; ?></span>
                </div>
                <div>
                    <span>FEC_INS: </span>
                    <span><?php echo $course['FEC_INS']; ?></span>
                </div>
                <div>
                    <span>EST: </span>
                    <span><?php echo $course['EST']; ?></span>
                </div>
                <div class="coimne-subtitle">CUR_COI</div>
                <div>
                    <span>NAME: </span>
                    <span><?php echo $course['CUR_COI']['NAME']; ?></span>
                </div>
                <div>
                    <span>DES: </span>
                    <span><?php echo $course['CUR_COI']['DES']; ?></span>
                </div>
                <div>
                    <span>EST: </span>
                    <span><?php echo $course['CUR_COI']['EST']; ?></span>
                </div>
                <div>
                    <span>FEC_INI_CUR: </span>
                    <span><?php echo $course['CUR_COI']['FEC_INI_CUR']; ?></span>
                </div>
                <div>
                    <span>FEC_FIN_CUR: </span>
                    <span><?php echo $course['CUR_COI']['FEC_FIN_CUR']; ?></span>
                </div>
                <div>
                    <span>FEC_INI_MAT: </span>
                    <span><?php echo $course['CUR_COI']['FEC_INI_MAT']; ?></span>
                </div>
                <div>
                    <span>FEC_FIN_MAT: </span>
                    <span><?php echo $course['CUR_COI']['FEC_FIN_MAT']; ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>