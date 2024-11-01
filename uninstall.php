<?php

if(defined('WP_UNINSTALL_PLUGIN'))
{

	//multisite call
	if(function_exists('is_multisite') && is_multisite()){

		global $wpdb;
		$old_blog =  $wpdb->blogid;

		//Get all blog ids
		$blogids =  $wpdb->get_col('SELECT blog_id FROM ' .  $wpdb->blogs);

		foreach($blogids as $blog_id){

			switch_to_blog($blog_id);
			sliderview_remove_plugin();

		}

		switch_to_blog($old_blog);

	}

	//regular call
	sliderview_remove_plugin();

	//remove file directories
	$dir = wp_upload_dir();
	sliderview_remove_dir($dir['basedir'] . '/sliderview-cache');

}

function sliderview_remove_plugin() {

	global $wpdb;

	$wpdb->query('DROP TABLE ' . $wpdb->prefix . 'slider_item');
	$wpdb->query('DROP TABLE ' . $wpdb->prefix . 'slider_data');

	delete_option('sliderview_main_opts');

}

function sliderview_remove_dir($dir) {

	foreach(glob($dir . '/*') as $file)
	{

		if(is_dir($file))
			sliderview_remove_dir($file);
		else
			unlink($file);

	}

	rmdir($dir);
}

?>
