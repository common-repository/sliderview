<?php

class sliderview_admin_ajax
{

    private $_options;

    public function __construct($options)
	{

		require_once(dirname(__FILE__) . '/../class/sv-admin-gen.class.php');

        $this->_options = $options;
		sliderview_admin_gen::initialize($this->_options);

        add_action('wp_ajax_sliderview_update_order', array($this, 'update_order'));
		add_action('wp_ajax_sliderview_delete_item', array($this, 'delete_item'));
		add_action('wp_ajax_sliderview_delete_slider', array($this, 'delete_slider'));

    }
    
    public function update_order()
	{
		global $wpdb;
		$data = explode(',', sanitize_text_field($_POST['order']));

		$cnt = count($data);

		for($i = 0; $i < $cnt; $i++)
		{

			$wpdb->update(
				$wpdb->prefix . 'slider_item',
				array(
					'ITEM_POS' => $i
				),
				array('ITEM_ID' => $data[$i])
			);

		}

	}

	public function delete_item()
	{

		global $wpdb;

		check_ajax_referer('sv-delete-item', 'nonce');

		$keys = array(sanitize_text_field($_POST['key']));

		if(sliderview_admin_gen::delete_slider_items($keys, $wpdb))
			echo 1;

		die();

	}

	public function delete_slider()
	{
		
		global $wpdb;

		check_ajax_referer('sv-delete-slider', 'nonce');

		$keys = array(sanitize_text_field($_POST['key']));

		if(sliderview_admin_gen::delete_sliders($keys, $wpdb))
			echo 1;

		die();

	}

}

?>
