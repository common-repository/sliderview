<?php 

require_once(plugin_dir_path(__FILE__) . 'sv-wp-list-table-base.class.php');

class sliderview_slider_list_table extends sliderview_wp_list_table_base{
				
	function __construct()
	{

		global $status, $page;

        parent::__construct(array(
            'singular' => '',   
            'plural' => '',  
            'ajax' => false      	
		));

		
add_filter('list_table_primary_column', array($this, 'wpdocs_slide_list_table_primary_column'), 10, 2 );
		
	}

	function wpdocs_slide_list_table_primary_column( $column, $screen ) {
    if ( 'edit-slide' === $screen ) {
        $column = 'slide';
    }
 
    return $column;
}

	function get_columns()
	{
	
		$columns = array(
			'cb' => '<input type="checkbox"/>',
			'name' => __('Name', 'slider_view'),
			'slider' => __('Shortcode', 'slider_view'),
			'mediaitems' => __('Items', 'slider_view'),
			'dateupdated' => __('Date Updated', 'slider_view'),
		);
		
		return $columns;
		
	}

	function prepare_items() 
	{
	
		$this->process_bulk_action();
	
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable, 'name');
		
		$this->items = $this->setup_items();
		
		if(!empty($_GET['orderby']) && !empty($_GET['order']))
			usort($this->items, array($this, 'usort_reorder'));
		 
	}

	function column_default($item, $column_name) 
	{
		
		switch($column_name) { 
			case 'name':
			case 'slider':
			case 'mediaitems':
			case 'dateupdated':
				return $item[ $column_name ];
			default:
				return 'An unknown error has occured';
		}
	}

	function get_sortable_columns() 
	{
	 
		$sortable_columns = array(
			'name'  => array('name', false),
			'dateupdated' => array('dateupdated', false),
			'mediaitems'   => array('mediaitems', false),
		);
	  
		return $sortable_columns;
	}

	function usort_reorder($a, $b) 
	{
	
		// If no sort, default to title
		$orderby = esc_attr((!empty($_GET['orderby'])) ? $_GET['orderby'] : 'dateupdated');
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
		
		if($action != -1){
		
			global $wpdb;
			require_once 'sv-admin-gen.class.php';
			
			$options = get_option('sliderview_main_opts');
			
			sliderview_admin_gen::initialize($options);

			if($action == 'delete')
				sliderview_admin_gen::delete_sliders(esc_attr($_POST['slider']), $wpdb);
			
		}

	}
	
	function column_cb($item) 
	{
	
        return sprintf('<input type="checkbox" name="slider[]" value="%s" />', esc_attr($item['ID'])); 
		
    }
	
	function no_items()
	{
	
		_e('No sliders found', 'slider_view');
		
	}
	
	function setup_items()
	{
	
		global $wpdb;
		$cells = array();
	
		$data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_data ORDER BY DATA_ID', ARRAY_A);
				
		foreach($data as $val)
		{
		
			array_push($cells, array(
				'ID' => esc_attr($val['DATA_ID']),
				'name' => '<a href="?page=sliderview&view=viewslider&id=' . esc_attr($val['DATA_ID']) . '" title="' . __('View', 'slider_view') . '" class="sv-row-title">' . esc_attr($val['DATA_NAME']) . '</a>
                    <div class="sv-row-actions">
						<a href="?page=sliderview&view=viewslider&id=' . esc_attr($val['DATA_ID'])  . '" title="' . __('Edit this slider', 'slider_view') . '">' . __('Edit', 'slider_view') . '</a>
						<span class="sv-row-divider">|</span>
						<a href="" class="sv-delete-slider" title="' . __('Delete this slider', 'slider_view') . '">' . __('Delete', 'slider_view') . '</a>
					 </div>',
				'slider' => '[sliderview id="' . esc_attr($val['DATA_ID']) . '"]',
				'mediaitems' => esc_attr($val['DATA_MEDIACOUNT']),
				'dateupdated' => date('Y/m/d', esc_attr($val['DATA_UPDATEDATE'])),
			));

		}
					
		return $cells;
	
	}
	
}
