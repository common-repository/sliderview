<?php

require_once(plugin_dir_path(__FILE__) . 'sv-wp-list-table-base.class.php');

class sliderview_slider_item_list_table extends sliderview_wp_list_table_base
{

	private $_id;

	function __construct($id)
	{

		global $status, $page;
 
		parent::__construct(array(
			'singular' => '',
			'plural' => 'sv-sortable-table',
			'ajax' => false
		));

		$this->_id = $id;
	}

	function get_columns()
	{

		$columns = array(
			'cb' => '<input type="checkbox"/>',
			'sv-itemthumbnail' => __('Thumbnail', 'slider_view'),
			'title' => __('Title', 'slider_view'),
			'sv-itemtype' => __('Type', 'slider_view'),
			'dateupdated' => __('Date Updated', 'slider_view')
		);

		return $columns;
	}

	function prepare_items()
	{

		$this->process_bulk_action();

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->items = $this->setup_items();

		if (!empty($_GET['orderby']) && !empty($_GET['order']))
			usort($this->items, array($this, 'usort_reorder'));
	}

	function column_default($item, $column_name)
	{

		switch ($column_name) {
			case 'sv-itemthumbnail':
			case 'sv-itemtype':
			case 'title':
			case 'dateupdated':
				return $item[$column_name];
			default:
				return 'An unknown error has occured';
		}
	}

	function get_sortable_columns()
	{

		$sortable_columns = array(
			'sv-itemtype'  => array('sv-itemtype', false),
			'title' => array('title', false),
			'dateupdated' => array('dateupdated', false)
		);

		return $sortable_columns;
	}

	function usort_reorder($a, $b)
	{

		// If no sort, default to title
		$orderby = esc_attr((!empty($_GET['orderby'])) ? $_GET['orderby'] : 'name');
		// If no order, default to asc
		$order = esc_attr((!empty($_GET['order'])) ? $_GET['order'] : 'asc');
		// Determine sort order
		$result = strcmp($a[$orderby], $b[$orderby]);
		// Send final sort direction to usort
		return ($order === 'asc') ? $result : -$result;
	}

	//add id to table rows
	function single_row($item)
	{

		static $row_class = '';
		$row_class = ($row_class == '' ? ' class="alternate"' : '');

		echo '<tr id="' . esc_attr($item['ID']) . '" ' . $row_class . '>';
		$this->single_row_columns($item);
		echo '</tr>';
	}

	function get_bulk_actions()
	{

		$actions = array(
			'delete' => __('Delete', 'slider_view')
		);

		return $actions;
	}

	function process_bulk_action()
	{

		$action = $this->current_action();

		if ($action != -1) {

			global $wpdb;
			require_once 'sv-admin-gen.class.php';

			$options = get_option('sliderview_main_opts');
			sliderview_admin_gen::initialize($options);

			if ($action == 'delete')
				sliderview_admin_gen::delete_slider_items(esc_attr($_POST['mediaitem']), $wpdb);
		}
	}

	function column_cb($item)
	{

		return sprintf('<input type="checkbox" name="mediaitem[]" value="%s" />', esc_attr($item['ID']));
	}

	function no_items()
	{

		_e('No media items found', 'slider_view');
	}

	function setup_items()
	{

		global $wpdb;
		$cells = array();

		$data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_item WHERE DATA_ID = ' . $this->_id . ' ORDER BY ITEM_POS', ARRAY_A);
		$dir = wp_upload_dir();
		$dir = $dir['basedir'];

		foreach ($data as $val) {
			array_push($cells, array(
				'ID' => esc_attr($val['ITEM_ID']),
				'sv-itemthumbnail' => '<img src="' . esc_url($val['ITEM_PHOTO']) . '" class="sv-prev-thumb"/><span class="sv-sortable-handle" title="' . __('Click and drag to reorder') . '">::</span>',
				'title' => '<span class="sv-row-title">' . (stripslashes($val['ITEM_TITLE']) == '' ? '-----' : stripslashes($val['ITEM_TITLE'])) . '</span>
					<div class="sv-row-actions">
						<a href="?page=sliderview&view=edititem&id=' . esc_attr($val['ITEM_ID']) . '" title="' . __('Edit this item', 'slider_view') . '">' . __('Edit', 'slider_view') . '</a>
						<span class="sv-row-divider">|</span>
						<a href="" class="sv-delete-item" title="' . __('Delete this item', 'slider_view') . '">' . __('Delete', 'slider_view') . '</a>
					</div>',
				'sv-itemtype' => ucwords(stripslashes($val['ITEM_TYPE'])),
				'dateupdated' => date('Y/m/d', esc_attr($val['ITEM_UPDATEDATE']))
			));
		}

		return $cells;
	}
}
