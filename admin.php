<?php

if(!class_exists('slider_view_admin'))
{

	class slider_view_admin
	{

		private $options, $version, $dirpath;

		public function __construct($version)
		{

			//get plugin options
			$this->options = get_option('slider_view_main_opts');

			//set version
			$this->version = $version;

			//set dirpath
			$this->dirpath = dirname(__FILE__);

			//add hooks
			add_action('admin_init', array($this, 'processor'));
			add_action('admin_menu', array($this, 'add_menu'));
			add_action('admin_enqueue_scripts', array($this, 'add_scripts'));

			//ajax hooks
			require($this->dirpath . '/inc/ajax.php');
			$sliderview_admin_ajax = new sliderview_admin_ajax($this->options);

		}

		public function add_menu()
		{

			add_menu_page('SliderView', 'SliderView', 'manage_options', 'sliderview', array($this, 'sliders_panel'), 'dashicons-admin-page');

		}

		public function add_scripts()
		{

			wp_enqueue_style('sliderview_admin_style', plugins_url('css/admin_style.css', __FILE__), false, $this->version);
			wp_enqueue_media();
			wp_enqueue_script('jquery');

			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_style('jquery-ui-sortable', plugins_url('css/jquery-ui-1.10.3.custom.min.css', __FILE__), false, $this->version);
			wp_enqueue_script('sliderview_admin_script', plugins_url('js/admin.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), $this->version, true);

			$jsdata = array(

				'translations' => array(
					'confirmSliderDelete' =>  __('Are you sure you want to delete this slider?', 'slider_view'),
					'confirmItemDelete' => __('Are you sure you want to delete this item?', 'slider_view')
				),
				'nonces' => array(
					'delete_slider' => wp_create_nonce('sv-delete-slider'),
					'delete_item' => wp_create_nonce('sv-delete-item')
				)

			);

			wp_localize_script('sliderview_admin_script', 'svJSData', $jsdata);

		}

		public function sliders_panel()
		{

			//declare globals
			global $wpdb;

			?>

			<div class="wrap sv-admin">

			<?php

			if(isset($_GET['view']))
			{ 

				//display form adding slider 
				if($_GET['view'] == 'addslider')
					require($this->dirpath . '/inc/forms/add-slider.inc.php');
				//view a slider
				elseif($_GET['view'] == 'viewslider'){
					require($this->dirpath . '/inc/forms/edit-slider.inc.php');
					require($this->dirpath . '/inc/forms/view-slider.inc.php');
				}
				//display form adding slider item 
				elseif($_GET['view'] == 'additem')
					require($this->dirpath . '/inc/forms/add-item.inc.php');
				//display form editing slider item 
				elseif($_GET['view'] == 'edititem')
					require($this->dirpath . '/inc/forms/edit-item.inc.php');

			}
			//display all sliders
			else
				require($this->dirpath . '/inc/forms/overview-sliders.inc.php');

			?>

		</div>

		<?php

		}

		public function processor()
		{

			require($this->dirpath . '/inc/processor.inc.php');

		}

	}

}
?>
