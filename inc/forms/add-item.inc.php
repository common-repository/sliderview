<div id="sv-add-item-form"> 
    <div class="sv-form-box sv-topform-box card">
        <form method="post" id="saveItemForm">
            <h3><?php _e('Add Item', 'slider_view'); ?></h3>
            <p>
                <label><?php _e('Title:', 'slider_view'); ?></label>
                <input type="text" name="itemTitle" id="itemTitle"/>
                <span class="sv-hint"><?php _e('ex: an optional title', 'slider_view'); ?></span>
            </p>
            <p id="photoURLBox">
                <label><?php _e('Photo URL:', 'slider_view'); ?></label>
                <input type="text" name="itemPhoto" id="itemPhoto" readonly/>
                <span class="sv-hint"><?php _e('ex: photo that you want to add', 'slider_view'); ?></span>
                <div id="photo-preview" class="sv-invis">
                    <img src="" alt="photo preview" class="sv-prev-thumb sv-individual-thumb"/>
                </div>
            </p>
            <p>
                <label><?php _e('Item Type:', 'slider_view'); ?></label>
                <select name="itemType" id="itemType">
                    <option selected="true" disabled="disabled"></option>
                    <option value="video">Video</option>
                    <option value="link">Link</option>
                </select>
                <span class="sv-hint"><?php _e('ex: type of item', 'slider_view'); ?></span>
            </p>
            <p id="videoBox" class="sv-invis">
                <label><?php _e('Video Type:', 'slider_view'); ?></label>
                <select name="videoType" id="videoType">
                    <option selected="true" disabled="disabled"></option>
                    <option value="vimeo">Vimeo</option>
                    <option value="youtube">Youtube</option>
                </select>
                <span class="sv-hint"><?php _e('ex: type of video to add', 'slider_view'); ?></span>
            </p>
            <p id="vimeoURLBox" class="sv-invis"> 
                <label><?php _e('Video URL:', 'slider_view'); ?></label> 
                <input type="text" name="vimeoID" id="vimeoID"/>
                <span class="sv-hint"><?php _e('ex: url of video such as - https://vimeo.com/xxxxxxxx', 'slider_view'); ?></span>
            </p>
            <p id="youtubeURLBox" class="sv-invis">
                <label><?php _e('Video URL:', 'slider_view'); ?></label>
                <input type="text" name="youtubeID" id="youtubeID"/>
                <span class="sv-hint"><?php _e('ex: url of video such as - https://www.youtube.com/watch?v=xxxxxxxxxxxx', 'slider_view'); ?></span>
            </p>
            <p id="linkBox" class="sv-invis">
                <label><?php _e('Link:', 'slider_view'); ?></label>
                <input type="text" name="itemLink" id="itemLink"/> 
                <span class="sv-hint"><?php _e('ex: link that you want to add', 'slider_view'); ?></span>
            </p>
            <p class="submit">
                <input type="submit" id="svAddItem" name="svAddItem" value="<?php _e('Save Item','slider_view') ?>" class="button-primary"/>
                <input type="hidden" name="key" value="<?php echo esc_attr($_GET['id']); ?>"/>
                <?php wp_nonce_field('sliderview_add_item'); ?>
                <a href="?page=sliderview&view=viewslider&id=<?php echo esc_attr($_GET['id']); ?>" class="sv-cancel"><?php _e('Go Back', 'slider_view'); ?></a>
            </p>
        </form>
    </div>
</div>
 