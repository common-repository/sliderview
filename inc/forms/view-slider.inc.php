<?php

    $id = sanitize_text_field($_GET['id']);
    $data = $wpdb->get_results('SELECT DATA_NAME, DATA_MEDIACOUNT	 FROM ' . $wpdb->prefix . 'slider_data WHERE DATA_ID = "' . $id . '"', ARRAY_A);

     if(!isset($data[0])){

        _e('Invalid slider ID', 'slider_view');
        return;

    }

    $data = $data[0];

    require_once(dirname(__FILE__) . '/../../class/sv-slider-item-list-table.class.php');

    $mediaItems = new sliderview_slider_item_list_table($id);
    $mediaItems->prepare_items();

?>

<div class="sv-form-box sv-topform-box" id="view-slider">
    <p class="sv-action-bar">
        <a href="?page=sliderview&view=additem&id=<?php echo $id; ?>" class="sv-link-submit-button"><?php _e('Add Item', 'slider_view'); ?></a>
    </p>
    <h3><?php _e('Items', 'slider_view'); ?><span class="sv-sub-H3"> ( <?php echo $data['DATA_NAME']; ?> ) - <?php echo $data['DATA_MEDIACOUNT'] . ' ' . __('items', 'slider_view'); ?></span></h3>  
    <form method="post">

        <?php echo $mediaItems->display(); ?>

    </form>
</div>
