<?php

$key = sanitize_text_field($_GET['id']);
$data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_data WHERE DATA_ID = ' . $key, ARRAY_A);

if (!isset($data[0])) {

    _e('Invalid slider ID', 'slider_view');
    return;
}

$data = $data[0];
$shortcode = '[sliderview id="' . esc_attr($data['DATA_ID']) . '"]';
?>

<div id="sv-edit-slider-form" class="sv-topform-box">
    <div class="sv-form-box sv-topform-box">
        <form method="post" id="svEditSliderFrm">
            <h3><?php _e('Edit Slider', 'slider_view'); ?></h3> 
            <p>
                <label><?php _e('Shortcode :', 'slider_view'); ?></label>
                <input type="text" value='<?php echo $shortcode; ?>' readonly/>
            </p>
            <p>
                <label><?php _e('Slider Name:', 'slider_view'); ?></label>
                <input type="text" name="sliderName" id="sliderName" value="<?php echo esc_attr($data['DATA_NAME']); ?>" />
                <span class="sv-hint"><?php _e('ex: name of slider', 'slider_view'); ?></span>
            </p>
            <p>
                <label><?php _e('Display Height:', 'slider_view'); ?></label>
                <input type="number" id="displayHeight" name="displayHeight" value="<?php echo esc_attr($data['DATA_DISPLAYHEIGHT']); ?>" />
                <button id="resetHeight" class="button-secondary"><?php _e('Reset', 'slider_view'); ?></button>
                <span class="sv-hint"><?php _e('ex: max height of media viewer (min height 400px, does not include title)', 'slider_view'); ?></span>
            </p>
            <p>
                <label><?php _e('Display dot:', 'slider_view'); ?></label>
                <label class="switch">
                    <input type="checkbox" name="displayDot" <?= (esc_attr($data['DATA_DISPLAYDOT']) == 1) ? 'checked' : ''; ?>>
                    <span class="slider round"></span>
                </label>
                <span class="sv-hint"><?php _e('ex: default is show', 'slider_view'); ?></span>
            </p>
            <p>
                <label><?php _e('Display arrow:', 'slider_view'); ?></label>
                <label class="switch">
                    <input type="checkbox" name="displayArrow" <?= (esc_attr($data['DATA_DISPLAYARROW']) == 1) ? 'checked' : ''; ?>> 
                    <span class="slider round"></span>
                </label>
                <span class="sv-hint"><?php _e('ex: default is show', 'slider_view'); ?></span>
            </p>
            <p class="submit">
                <input type="hidden" name="key" value="<?php echo $key; ?>" />
                <input type="submit" name="svEditSlider" id="svEditSlider" value="<?php _e('Save Changes', 'slider_view') ?>" class="button-primary" />
                <?php wp_nonce_field('sliderview_edit_slider'); ?>
                <a href="?page=sliderview" class="sv-cancel"><?php _e('Go Back', 'slider_view'); ?></a>
            </p>
        </form>
    </div>
</div>
