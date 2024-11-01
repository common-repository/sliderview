<?php

/*
Plugin Name: SliderView
Plugin URI: 
Description: This plugin allows you to create media galleries / slideshows containing videos and photos.
Version: 1.0.0
Author: TeamBlueOtter
Author URI: https://teamblueotter.com/
License: GPL2
*/

if(!class_exists('slider_view'))
{

	class slider_view
	{

		private $sliderview_admin, $sliderview_frontend, $options, $dirpath;
        const CURRENT_VERSION = '1.0.0';

		public function __construct()
		{

			//set dirpath
			$this->dirpath = dirname(__FILE__);

            //load options
            $this->options = get_option('slider_view_main_opts');

            //check for upgrade
			$this->upgrade_check();

			//load external files
			$this->load_dependencies();

			//activation hook
			register_activation_hook(__FILE__, array($this, 'activate'));

		}

		//activate plugin
		public function activate($network)
		{

			//multisite call
			if(function_exists('is_multisite') && is_multisite() && $network){

				global $wpdb;
				$old_blog =  $wpdb->blogid;

				//Get all blog ids
				$blogids =  $wpdb->get_col('SELECT blog_id FROM ' .  $wpdb->blogs);

				foreach($blogids as $blog_id){

					switch_to_blog($blog_id);
					$this->maintenance();

				}

				switch_to_blog($old_blog);

			}

			//regular call
			$this->maintenance();

		}

        private function maintenance()
		{

            //set up globals
			global $wpdb;

			//create database tables for plugin
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			$tbname[0] = $wpdb->prefix . 'slider_data';
			$tbname[1] = $wpdb->prefix . 'slider_item';

			$sql = "CREATE TABLE $tbname[0] (
				DATA_ID int(11) NOT NULL AUTO_INCREMENT,
				DATA_NAME varchar(80) NOT NULL,
				DATA_DISPLAYHEIGHT int(11) NOT NULL,
				DATA_DISPLAYDOT int(4) DEFAULT '0' NOT NULL,
				DATA_DISPLAYARROW int(4) DEFAULT '0' NOT NULL,
				DATA_MEDIACOUNT int(4) DEFAULT '0' NOT NULL,
				DATA_UPDATEDATE int(11) NOT NULL,
				UNIQUE KEY DATA_ID (DATA_ID)
			);
			CREATE TABLE $tbname[1] (
				ITEM_ID int(11) NOT NULL AUTO_INCREMENT,
				ITEM_TITLE longtext NOT NULL,
				ITEM_PHOTO longtext NOT NULL,
				ITEM_TYPE varchar(40) NOT NULL, 
				ITEM_LINK longtext,
				ITEM_VIDEO_TYPE varchar(40), 
				ITEM_VIDEO_ID varchar(80),
				ITEM_POS int(11) DEFAULT '0' NOT NULL,
				ITEM_UPDATEDATE int(11) NOT NULL,
				DATA_ID int(11) NOT NULL,
				UNIQUE KEY ITEM_ID (ITEM_ID)
			);";

			dbDelta($sql);

            if(empty($this->options))
                $this->options = array();

            $dft['version'] =  self::CURRENT_VERSION;

			$this->options = $this->options + $dft;

			update_option('slider_view_main_opts', $this->options);

			//create photo cache directory if needed
			$dir = wp_upload_dir();
			$dir = $dir['basedir'];

			wp_mkdir_p($dir . '/slider_view_cache');

        }

        private function upgrade_check()
		{

			if(!isset($this->options['version']) || $this->options['version'] < self::CURRENT_VERSION){

				$this->options['version'] = self::CURRENT_VERSION;
				$this->maintenance();

			}

		}

		//load dependencies for plugin
		private function load_dependencies()
		{

			load_plugin_textdomain('slider_view', false, false);

			//load backend or frontend dependencies
			if(is_admin())
			{

				require ($this->dirpath . '/admin.php');
				$this->sliderview_admin = new slider_view_admin(self::CURRENT_VERSION);

			}
			else
			{

				require ($this->dirpath . '/frontend.php');
				$this->sliderview_frontend = new slider_view_frontend(self::CURRENT_VERSION);

			}

		}

	}

	$slider_view = new slider_view();

}
?>
