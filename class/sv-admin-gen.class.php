<?php

class sliderview_admin_gen
{

    private static $_options, $_basePath;

    public static function initialize(&$options)
    {

        self::$_options = $options;
        self::$_basePath = wp_upload_dir();
        self::$_basePath = self::$_basePath['basedir'] . '/sliderview-cache/';

    }
    
    public static function delete_sliders($sliders, &$wpdb)
    {

        //create querystring
        $queryString = implode(', ', array_map('intval', $sliders));

        //get media items included in selected sliders
        $items = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_item WHERE DATA_ID IN (' . $queryString . ')', ARRAY_A);

		//delete media items and update count
        if($wpdb->query('DELETE FROM ' . $wpdb->prefix . 'slider_item WHERE DATA_ID IN (' . $queryString . ')') === false 
        || $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'slider_data WHERE DATA_ID IN (' . $queryString . ')') === false)
            return false;

        //remove cached thumbnails
        foreach($items as $item)
		{

            if(!isset($item['ITEM_VALUE']))
                continue;
            
            $temp = unserialize($item['ITEM_VALUE']);

            if(!isset($temp['thumb']))
                continue;

            unlink(self::$_basePath . $temp['thumb']);

		}

        return true;

    }

    public static function delete_slider_items($items, &$wpdb)
    {

        //sanitize key array and break apart
        $queryString = implode(', ', array_map('intval', $items));

        $items = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_item WHERE ITEM_ID IN (' . $queryString . ')', ARRAY_A);

        //check for empty items
        if(!isset($items[0]))
            return false;

        $slider = $wpdb->get_results('SELECT DATA_MEDIACOUNT FROM ' . $wpdb->prefix . 'slider_data WHERE DATA_ID = ' . $items[0]['DATA_ID'], ARRAY_A);
       
        //check for empty slider
        if(!isset($slider[0]))
            return false;

        $itemcnt = $slider[0]['DATA_MEDIACOUNT'] - count($items);

        //delete media items and update count
        if($wpdb->query('DELETE FROM ' . $wpdb->prefix . 'slider_item WHERE ITEM_ID IN (' . $queryString . ')'
        ) === false || $wpdb->update(
			$wpdb->prefix . 'slider_data',
			array(
				'DATA_MEDIACOUNT' => $itemcnt
			),
			array('DATA_ID' => $items[0]['DATA_ID'])
		) === false)
            return false;

        //delete media item thumbnails from cache	
        foreach($items as $item)
        {

            if(!isset($item['ITEM_VALUE']))
                continue;
            
            $temp = unserialize($item['ITEM_VALUE']);

            if(!isset($temp['thumb']))
                continue;

            unlink(self::$_basePath . $temp['thumb']);

        }

        return true;

    }

}

?>
