<?php

    require_once(dirname(__FILE__) . '/../../class/sv-slider-list-table.class.php');

    $sliders = new sliderview_slider_list_table();
    $sliders->prepare_items();

?>

<div class="sv-form-box">
    <p class="sv-actionbar">
        <a href="?page=sliderview&view=addslider" class="sv-link-submit-button"><?php _e('Add Slider', 'slider_view'); ?></a>
    </p>
    <h3>Sliders</h3>
    <form method="post">

        <?php $sliders->display(); ?>

    </form>
</div>
