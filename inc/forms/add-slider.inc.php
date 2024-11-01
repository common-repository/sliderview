<div id="sv-add-slider-form">
    <div class="sv-form-box sv-topform-box card">
        <form method="post" id="svAddSliderFrm">
            <h3><?php _e('Add Slider', 'slider_view'); ?></h3>
            <p>
                <label><?php _e('Slider Name:', 'slider_view'); ?></label>
                <input type="text" name="sliderName" id="sliderName"/>
                <span class="sv-hint"><?php _e('ex: name of slider for your reference', 'slider_view'); ?></span>
            </p>
            <p>
                <label><?php _e('Display Height:', 'slider_view'); ?></label>
                <input type="number" id="displayHeight" name="displayHeight" value="400"/>
                <button id="resetHeight" class="button-secondary"><?php _e('Reset', 'slider_view'); ?></button>
                <span class="sv-hint"><?php _e('ex: max height of media viewer (min height 400px, does not include title)', 'slider_view'); ?></span>
            </p>
            <p>
                <label><?php _e('Display dot:', 'slider_view'); ?></label>
                <label class="switch">
                    <input type="checkbox" name="displayDot" checked>
                    <span class="slider round"></span>
                </label>
                <span class="sv-hint"><?php _e('ex: default is show', 'slider_view'); ?></span>
            </p>
            <p>
                <label><?php _e('Display arrow:', 'slider_view'); ?></label>
                <label class="switch">
                    <input type="checkbox" name="displayArrow" checked> 
                    <span class="slider round"></span>
                </label>
                <span class="sv-hint"><?php _e('ex: default is show', 'slider_view'); ?></span>
            </p>
            <p class="submit">
                <input type="submit" name="svAddSlider" id="svAddSlider" value="<?php _e('Save Slider', 'slider_view') ?>" class="button-primary"/>
                <?php wp_nonce_field('sliderview_add_slider'); ?>
                <a href="?page=sliderview" class="sv-cancel"><?php _e('Go Back', 'slider_view'); ?></a>
            </p>
        </form>
    </div>
</div>
