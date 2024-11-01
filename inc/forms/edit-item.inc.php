<?php

$key = sanitize_text_field($_GET['id']);
$dir = wp_upload_dir();
$dir = $dir['basedir'];

$data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_item WHERE ITEM_ID = ' . $key, ARRAY_A);

if (!isset($data[0])) {

    _e('Invalid slider ID', 'slider_view');
    return;
}

$data = $data[0];
?>

<div class="sv-form-box sv-topform-box card" id="edit-media">
    <h3><?php _e('Edit Slider Item', 'slider_view'); ?></h3>
    <form method="post" id="saveItemFormEdit">
        <div class="sv-left-column">
            <p id="photo-preview">
                <img src="<?php echo esc_url($data['ITEM_PHOTO']); ?>" class="sv-prev-thumb sv-individual-thumb" />
            </p>
            <p>
                <label><?php _e('Photo URL:', 'slider_view'); ?></label>
                <input type="text" value="<?php echo esc_url($data['ITEM_PHOTO']); ?>" tabindex="1" name="itemPhoto" id="itemPhoto" readonly />
            </p>

            <p>
                <label><?php _e('Title:', 'slider_view'); ?></label>
                <input type="text" name="itemTitle" id="itemTitle" value="<?php echo esc_attr($data['ITEM_TITLE']); ?>" />
                <span class="sv-hint"><?php _e('ex: title for media item', 'slider_view'); ?></span>
            </p>
        </div>
        <div class="sv-right-column">
            <p>
                <label><?php _e('Item Type:', 'slider_view'); ?></label>
                <select name="itemType" id="itemType">
                    <option value="video" <?= (esc_attr($data['ITEM_TYPE']) == 'video') ? ' selected="selected"' : ''; ?>>Video</option>
                    <option value="link" <?= (esc_attr($data['ITEM_TYPE']) == 'link') ? ' selected="selected"' : ''; ?>>Link</option>
                </select>
                <span class="sv-hint"><?php _e('ex: type of item', 'slider_view'); ?></span>
            </p>
            <p id="videoBox" class="<?= (esc_attr($data['ITEM_TYPE']) !== 'video') ? 'sv-invis' : '' ?>">
                <label><?php _e('Video Type:', 'slider_view'); ?></label>
                <select name="videoType" id="videoType">
                    <option selected="true" disabled="disabled"></option>
                    <option value="vimeo" <?= ((esc_attr($data['ITEM_TYPE']) === 'video')&&(esc_attr($data['ITEM_VIDEO_TYPE']) == 'vimeo')) ? ' selected="selected"' : ''; ?>>Vimeo</option>
                    <option value="youtube" <?= ((esc_attr($data['ITEM_TYPE']) === 'video')&&(esc_attr($data['ITEM_VIDEO_TYPE']) == 'youtube')) ? ' selected="selected"' : ''; ?>>Youtube</option>
                </select>
                <span class="sv-hint"><?php _e('ex: type of video to add', 'slider_view'); ?></span>
            </p>
            <p id="vimeoURLBox" class="<?= !((esc_attr($data['ITEM_TYPE']) === 'video') && (esc_attr($data['ITEM_VIDEO_TYPE']) === 'vimeo')) ? 'sv-invis' : '' ?>">
                <label><?php _e('Video URL:', 'slider_view'); ?></label>
                <input type="text" name="vimeoID" id="vimeoID" value="<?php echo 'https://vimeo.com/' . esc_attr($data['ITEM_VIDEO_ID']); ?>" />
                <span class="sv-hint"><?php _e('ex: url of video such as - https://vimeo.com/xxxxxxxx', 'slider_view'); ?></span>
            </p>
            <p id="youtubeURLBox" class="<?= !((esc_attr($data['ITEM_TYPE']) === 'video') && (esc_attr($data['ITEM_VIDEO_TYPE']) === 'youtube')) ? 'sv-invis' : '' ?>">
                <label><?php _e('Video URL:', 'slider_view'); ?></label>
                <input type="text" name="youtubeID" id="youtubeID" value="<?php echo 'https://www.youtube.com/watch?v=' . esc_attr($data['ITEM_VIDEO_ID']); ?>" />
                <span class="sv-hint"><?php _e('ex: url of video such as - https://www.youtube.com/watch?v=xxxxxxxxxxxx', 'slider_view'); ?></span>
            </p>
            <p id="linkBox" class="<?= (esc_attr($data['ITEM_TYPE']) !== 'link') ? 'sv-invis' : '' ?>">
                <label><?php _e('Link:', 'slider_view'); ?></label>
                <input type="text" value="<?php echo esc_url($data['ITEM_LINK']); ?>" name="itemLink" id="itemLink" />
            </p>
        </div>
        <p class="submit">
            <input type="hidden" name="key" value="<?php echo $key; ?>" />
            <input type="submit" name="svEditItem" id="svEditItem" value="<?php _e('Save Changes', 'slider_view') ?>" class="button-primary" />
            <?php wp_nonce_field('sliderview_edit_item'); ?>
            <a href="?page=sliderview&view=viewslider&id=<?php echo esc_attr($data['DATA_ID']); ?>" class="sv-cancel"><?php _e('Go Back', 'slider_view'); ?></a>
        </p>
    </form>
</div>