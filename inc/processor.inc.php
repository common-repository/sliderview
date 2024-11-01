<?php

if(!empty($_POST))
{

    //declare globals
    global $wpdb;

    //save new slider script//
    if(isset($_POST['svAddSlider']))
    {

        if(check_admin_referer('sliderview_add_slider')) 
        {

            $name = sanitize_text_field($_POST['sliderName']);
            $height = sanitize_text_field($_POST['displayHeight']); 
            $dot = sanitize_text_field($_POST['displayDot']) === 'on' ? 1 : 0;
            $arrow = sanitize_text_field($_POST['displayArrow']) === 'on' ? 1 : 0;
            $time = current_time('timestamp');

            if(empty($name) || empty($height) || !is_numeric($height))
            {
                echo '<div class="error error-message"><p>' . __('Oops... all fields must have a value', 'slider_view') . '</p></div>';
                return;
            }

            if($wpdb->insert(
                $wpdb->prefix . 'slider_data',
                array(
                    'DATA_NAME' => $name,
                    'DATA_DISPLAYHEIGHT' => $height,
                    'DATA_DISPLAYDOT' => $dot,
                    'DATA_DISPLAYARROW' => $arrow,
                    'DATA_UPDATEDATE' => $time
                )
            ))
                echo '<div class="updated"><p>' . __('Slider created', 'slider_view') . '</p></div>';
            else
                echo '<div class="error e-message"><p>' . __('Oops... something went wrong', 'slider_view') . '</p></div>';

        }

    }
    //save a slider edit script//
    elseif(isset($_POST['svEditSlider']))
    {

        if(check_admin_referer('sliderview_edit_slider'))
        {

            $name = sanitize_text_field($_POST['sliderName']);
            $height = sanitize_text_field($_POST['displayHeight']);
            $dot = sanitize_text_field($_POST['displayDot']) === 'on' ? 1 : 0;
            $arrow = sanitize_text_field($_POST['displayArrow']) === 'on' ? 1 : 0;
            $key = sanitize_text_field($_POST['key']);
            $time = current_time('timestamp');

            if(empty($name) || empty($height) || !is_numeric($height))
            {
                echo '<div class="error e-message"><p>' . __('Oops... all fields must have a value', 'slider_view') . '</p></div>';
                return;
            }

            if($wpdb->update(
                $wpdb->prefix . 'slider_data',
                array(
                    'DATA_NAME' => $name,
                    'DATA_DISPLAYHEIGHT' => $height,
                    'DATA_DISPLAYDOT' => $dot,
                    'DATA_DISPLAYARROW' => $arrow,
                    'DATA_UPDATEDATE' => $time
                ),
                array('DATA_ID' => $key)
            ) >= 0)
                echo '<div class="updated"><p>' . __('Slider updated', 'slider_view') . '</p></div>';
            else
                echo '<div class="error e-message"><p>' . __('Oops... something went wrong', 'slider_view') . '</p></div>';

        }

    }
    //save a new media item script//
    elseif(isset($_POST['svAddItem']))
    {

        if(check_admin_referer('sliderview_add_item'))
        {

            $itemType = sanitize_text_field($_POST['itemType']);
            $itemTitle = sanitize_text_field($_POST['itemTitle']);
            $itemLink = esc_url($_POST['itemLink']);
            $itemPhoto = esc_url($_POST['itemPhoto']);
            $itemVideoType = sanitize_text_field($_POST['videoType']);
            $time = current_time('timestamp');
            $key = sanitize_text_field($_POST['key']);

            $cnt = $wpdb->get_results('SELECT DATA_MEDIACOUNT FROM ' . $wpdb->prefix . 'slider_data WHERE DATA_ID = ' . $key, ARRAY_A);
            $cnt = $cnt[0]['DATA_MEDIACOUNT'] + 1;

            if($itemVideoType == 'vimeo'){
                $videoUrl = esc_url($_POST['vimeoID']);

                preg_match('/vimeo.com\/([0-9]+)/', $videoUrl, $matches);
                $itemVideoID = $matches[1];
            }elseif($itemVideoType == 'youtube'){
                $videoUrl = esc_url($_POST['youtubeID']);

                parse_str( parse_url( $videoUrl, PHP_URL_QUERY ), $matches );
                $itemVideoID = $matches['v'];
            }

            $media = array(
                'ITEM_TITLE' => $itemTitle, 
                'ITEM_PHOTO' => $itemPhoto,
                'ITEM_TYPE' => $itemType,
                'ITEM_LINK' => $itemLink,
                'ITEM_VIDEO_TYPE' => $itemVideoType,
                'ITEM_VIDEO_ID' => $itemVideoID,
                'ITEM_POS' => $cnt - 1,
                'ITEM_UPDATEDATE' => $time,
                'DATA_ID' => $key
            );

            if($wpdb->insert(
                $wpdb->prefix . 'slider_item',
                $media
            ) && $wpdb->update(
                $wpdb->prefix . 'slider_data',
                array(
                    'DATA_MEDIACOUNT' => $cnt
                ),
                array('DATA_ID' => $key)
            ) >= 0)
                echo '<div class="updated"><p>' . __('Item added', 'slider_view') . '</p></div>';
            else
                echo '<div class="error e-message"><p>' . __('Oops... something went wrong', 'slider_view') . '</p></div>';

        }

    }
    //save a media item edit script//
    elseif(isset($_POST['svEditItem']))
    {

        if(check_admin_referer('sliderview_edit_item'))
        {
            
            $itemType = sanitize_text_field($_POST['itemType']);
            $itemTitle = sanitize_text_field($_POST['itemTitle']);
            $itemLink = esc_url($_POST['itemLink']);
            $itemPhoto = esc_url($_POST['itemPhoto']);
            $itemVideoType = sanitize_text_field($_POST['videoType']);
            $time = current_time('timestamp');
            $key = sanitize_text_field($_POST['key']);

            if($itemVideoType == 'vimeo'){
                $videoUrl = esc_url($_POST['vimeoID']);

                preg_match('/vimeo.com\/([0-9]+)/', $videoUrl, $matches);
                $itemVideoID = $matches[1];
            }elseif($itemVideoType == 'youtube'){
                $videoUrl = esc_url($_POST['youtubeID']);

                parse_str( parse_url( $videoUrl, PHP_URL_QUERY ), $matches );
                $itemVideoID = $matches['v'];
            }

            $media = array(
                'ITEM_TITLE' => $itemTitle, 
                'ITEM_PHOTO' => $itemPhoto,
                'ITEM_TYPE' => $itemType,
                'ITEM_LINK' => $itemLink,
                'ITEM_VIDEO_TYPE' => $itemVideoType,
                'ITEM_VIDEO_ID' => $itemVideoID,
                'ITEM_POS' => $key - 1,
                'ITEM_UPDATEDATE' => $time,
            );

            if($wpdb->update(
                $wpdb->prefix . 'slider_item',
                $media,
                array('ITEM_ID' => $key)
            ) >= 0)
                echo '<div class="updated"><p>' . __('Media item updated', 'slider_view') . '</p></div>';
            else
                echo '<div class="error e-message"><p>' . __('Oops... something went wrong', 'slider_view') . '</p></div>';

        }

    }

}
