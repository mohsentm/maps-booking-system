<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
/////////////////////////////////
//	   show evet list         //
///////////////////////////////
class maps_booking_system_event_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'event', 'sp' ), //singular name of the listed records
			'plural'   => __( 'events', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve maps_booking_system_events data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_maps_booking_system_events( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}maps_booking_system_events";

		 $cid = trim($_REQUEST['subpage']);
		 $sql .= " WHERE e_cid = '$cid'";
				 
		if ( ! empty( $_REQUEST['s'] ) ) {
				 $search = trim($_REQUEST['s']);
				 $sql .= " AND ( e_title LIKE '%$search%' OR e_date LIKE '%$search%' OR e_capacity LIKE '%$search%' OR e_price LIKE '%$search%' OR e_eend LIKE '%$search%' OR e_estart LIKE '%$search%' )";
		}

	
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		}
		else
		{
			$sql .= ' ORDER BY e_date DESC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_events( $id ) {
		global $wpdb;
		$wpdb->delete(
			"{$wpdb->prefix}maps_booking_system_events",
			array( 'e_id' => $id),
			array('%d' )
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}maps_booking_system_events";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No customize events avaliable.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {		
			case 'e_title':
			case 'e_estart':
			case 'e_eend':
			case 'e_capacity':
			case 'e_price':
			case 'e_discription':
			case 'e_date':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['e_id']
		);
	}
	

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_events' );

		$title = '<strong>' . $item['e_title'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&events=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['e_id'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {	
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'e_date'    => __( 'Registered Time', 'sp' ),
			'e_title' => __( 'Title', 'sp' ),
			'e_estart' => __( 'Start Date & Time', 'sp' ),
			'e_eend' => __( 'End Date & Time', 'sp' ),
			'e_capacity'    => __( 'Capacity', 'sp' ),
			'e_price' => __( 'Price', 'sp' ),
			'e_discription' => __( 'Discription', 'sp' )
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'e_date' => array( 'e_date', true ),
			'e_title' => array( 'e_title', false ),
			'e_estart' => array( 'e_estart', false ),
			'e_eend' => array( 'e_eend', false ),
			'e_capacity' => array( 'e_capacity', false ),
			'e_price' => array( 'e_price', false ),
			'e_discription' => array( 'e_discription', false )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'maps_booking_system_events_per_page', 15 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_maps_booking_system_events( $per_page, $current_page );
	}

	public function process_bulk_action() {

	}

}
/////////////////////////////////
//	show booking list     //
///////////////////////////////
class maps_booking_system_booking_List extends WP_List_Table {

	/** Class constructor */
	
	//private $column_booking_counter = 0;
	//private $column_booking_capacity = 0;
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'booking', 'sp' ), //singular name of the listed records
			'plural'   => __( 'bookings', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve maps_booking_system_booking data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_maps_booking_system_booking( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			$sql = "SELECT * FROM {$wpdb->prefix}maps_booking_system_booking";
			
		else if ( ! empty( $_REQUEST['cid'] ) )
			$sql = "SELECT `b_estart` , `b_eend` , `b_eid` , `b_cid` , `b_title` , count(`b_id`) as `b_capacity` FROM `{$wpdb->prefix}maps_booking_system_booking`";

		else
			$sql = "SELECT c_id,c_title FROM {$wpdb->prefix}maps_booking_system_calendar";

		if ( ! empty( $_REQUEST['s'] ) ) {
			 $search = trim($_REQUEST['s']);		
			 if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			 {
				$eid = trim($_REQUEST['eid']);			 	
				$cid = trim($_REQUEST['cid']);
			 	$sql .= " WHERE b_eid = '$eid' AND b_cid = '$cid' AND (b_email_address LIKE '%$search%' OR b_title LIKE '%$search%' OR b_register_time LIKE '%$search%' )";
			 }
			 else if ( ! empty( $_REQUEST['cid'] ) )
			 	$sql .= " WHERE b_cid = '$cid' AND ( b_email_address LIKE '%$search%' OR b_title LIKE '%$search%' OR b_register_time LIKE '%$search%')";
			 else
				$sql .= " WHERE c_title LIKE '%$search%'";
		}		
		else if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
		{
			$eid = trim($_REQUEST['eid']);
			$cid = trim($_REQUEST['cid']);
			$sql .= " WHERE b_eid = '$eid' AND b_cid = '$cid' ";
		}
		else if ( ! empty( $_REQUEST['cid'] ) )
		{
			$cid = trim($_REQUEST['cid']);
			$sql .= " WHERE b_cid = '$cid'  GROUP BY `b_eid`";
		}
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		}
		else
		{
			if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
				$sql .= ' ORDER BY `b_register_time` ASC'; 
			else if ( ! empty( $_REQUEST['cid'] ) )
				$sql .= ' ORDER BY `b_register_time` DESC';  
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	 function show_booking_title(){
		global $wpdb;
		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
		{
			$title_info = $wpdb->get_results( "SELECT `b_title`,`b_estart`,`b_eend` FROM {$wpdb->prefix}maps_booking_system_booking WHERE b_eid = '".$_REQUEST['eid']."'  AND b_cid = '".$_REQUEST['cid']."'");
			
			foreach ($title_info as $title_info_row )
			{
				return array(
					'title' => $title_info_row->b_title,
					'start' => $title_info_row->b_estart,
					'end' => $title_info_row->b_eend
				);
				
				
			}
		}
		else if( ! empty( $_REQUEST['cid'] ) )
		{
			$title_info = $wpdb->get_results( "SELECT c_title FROM {$wpdb->prefix}maps_booking_system_calendar WHERE c_id = '".$_REQUEST['cid']."'");
			foreach ($title_info as $title_info_row )
			{
				return array(
					'calendar' => $title_info_row->c_title,
				);
				
				
			}
		}
		return;				
	}

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_booking( $id ) {
		global $wpdb;
		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			$wpdb->delete(
				"{$wpdb->prefix}maps_booking_system_booking",
				array( 'b_id' => $id )
			);
		else if ( ! empty( $_REQUEST['cid'] ) )
			$wpdb->delete(
				"{$wpdb->prefix}maps_booking_system_booking",
				array( 'b_eid' => $id )
			);
		else
			$wpdb->delete(
				"{$wpdb->prefix}maps_booking_system_booking",
				array( 'b_cid' => $id )
			);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			$sql = 'SELECT COUNT(*) FROM '.$wpdb->prefix.'maps_booking_system_booking WHERE b_eid = "'.$_REQUEST['eid'].'"  AND b_cid = "'.$_REQUEST['cid'].'"';
		else if ( ! empty( $_REQUEST['cid'] ) )
			$sql = "SELECT count( DISTINCT `b_eid` )  FROM `{$wpdb->prefix}maps_booking_system_booking` WHERE b_cid = '".$_REQUEST['cid'].'"';
		else
			$sql = "SELECT count(*)  FROM `{$wpdb->prefix}maps_booking_system_calendar`";
		

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No item avaliable.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			switch ( $column_name ) {
				case 'b_title':
				case 'b_booking_status':
				case 'b_estart':
				case 'b_eend':
				case 'b_email_address':
				case 'b_register_time':
					return $item[ $column_name ];
				default:
					return print_r( $item, true ); //Show the whole array for troubleshooting purposes
			}
		else if ( ! empty( $_REQUEST['cid'] ) )
			switch ( $column_name ) {
					case 'b_estart':
					case 'b_eend':
					case 'b_eid':
					case 'b_cid':
					case 'b_title':
					case 'b_capacity':
						return $item[ $column_name ];
					default:
						return print_r( $item, true ); //Show the whole array for troubleshooting purposes
				}
		else
			switch ( $column_name ) {
				case 'c_id':
				case 'c_title':
					return $item[ $column_name ];
				default:
					return print_r( $item, true ); //Show the whole array for troubleshooting purposes
			}
	}

	/**
	 * Render the bulk edit checkcolumnbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			return sprintf(
				'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['b_id']
			);
		else if ( ! empty( $_REQUEST['cid'] ) )
			return sprintf(
				'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['b_eid']
			);
		else
			return sprintf(
				'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['c_id']
			);
	}
	
	function currentPageUrl($filterGET = false)
	{
	    $result = 'http';
	    if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {
	        $result .= "s";
	    }
	    $result .= "://";
	    if ($_SERVER["SERVER_PORT"] != "80") {
	        $result .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	    } else {
	        $result .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	    }
	   if($filterGET == true)
	    {
	    	$result = explode('?',$result);
	    	$result = $result[0];
	    }
	    return $result;
	}
	
	function column_setting( $item ) {
		$url = $this->currentPageUrl();
		
		if (! empty( $_REQUEST['cid'] ) )
			return sprintf(
				'<a href="%s&cid=%s&eid=%s"><input class="button action" value="List" name="show-list" type="button"></a>',$url, $item['b_cid'], $item['b_eid']
			);
		else
			return sprintf(
				'<a href="%s&cid=%s"><input class="button action" value="List" name="show-list" type="button"></a>',$url, $item['c_id']
			);
	}
	
	function column_booking_status( $item ) {

            if ($item['b_booking_status'] == 1) {
                return sprintf('Booked');
            } else {
                return sprintf('waiting');
            }
        }
	function column_booked_status( $item ) {
		global $wpdb;
        
		$sql = "SELECT count(b_id) FROM `{$wpdb->prefix}maps_booking_system_booking` WHERE `b_booking_status` = '1' AND `b_eid` = '".$item['b_eid']."'";

		return $wpdb->get_var( $sql );

	}
	function column_waiting_status( $item ) {
		global $wpdb;
        
	        $sql = "SELECT count(b_id) FROM `{$wpdb->prefix}maps_booking_system_booking` WHERE `b_booking_status` = '0' AND `b_eid` = '".$item['b_eid']."'";

		return $wpdb->get_var( $sql );
	}
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_booking' );

		$title = '<strong>' . $item['b_title'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&booking=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['b_id'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			$columns = [
				'cb'      => '<input type="checkbox" />',
				'b_register_time'    => __( 'Register Time', 'sp' ),
				'b_email_address' => __( 'Email Address', 'sp' ),
				'booking_status' => 'Booking Status'
			];
		else if ( ! empty( $_REQUEST['cid'] ) )	
			$columns = [
				'cb'      => '<input type="checkbox" />',
				'b_title'    => __( 'Title', 'sp' ),
				'b_estart' =>  __( 'Start Date & Time', 'sp' ),
				'b_eend' =>  __( 'End Date & Time', 'sp' ),				
				'booked_status' => 'Booked',
				'waiting_status' => 'Waiting',
				'setting' => ''
			];
		else			
			$columns = [
				'cb'      => '<input type="checkbox" />',
				'c_title'    => __( 'Title', 'sp' ),			
				'setting' => '',
			];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {

		if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) )
			{
$sortable_columns = array(
				'b_register_time' => array( 'b_register_time', false ),
				'b_email_address' => array( 'b_email_address', false ),
				'b_booking_status' => array( 'b_booking_status', false ),
			);
}
		else if ( ! empty( $_REQUEST['cid'] ) )	
			$sortable_columns = array(
				'b_estart' => array( 'b_estart', false ),
				'b_eend' => array( 'b_eend', false ),
				'b_capacity' => array( 'b_capacity', false ),
				'b_title' => array( 'b_title', false )
			);
		else
			$sortable_columns = array(
				'c_title' => array( 'c_title', false )
			);
		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'maps_booking_system_booking_per_page', 15 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_maps_booking_system_booking( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_booking' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_booking( absint( $_GET['booking'] ) );

				wp_redirect( esc_url( add_query_arg() ) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_booking( $id );

			}
		/*	if ( ! empty( $_REQUEST['eid'] ) )
			{
				wp_redirect($this->currentPageUrl());
				
			}
			else
			{*/
				//wp_redirect( esc_url( add_query_arg() ) );
			//}	
			//wp_redirect( esc_url( "http://" . $_SERVER['SERVER_NAME'] . add_query_arg() ) );
			//exit;
		}
	}

}

/////////////////////////////////
//	show calendar list     //
///////////////////////////////
class maps_booking_system_calendar_List extends WP_List_Table {

	/** Class constructor */
	
	//private $column_booking_counter = 0;
	//private $column_booking_capacity = 0;
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'calendar', 'sp' ), //singular name of the listed records
			'plural'   => __( 'calendars', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve maps_booking_system_booking data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_maps_booking_system_calendar( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

        $sql = "SELECT c_id,c_title FROM {$wpdb->prefix}maps_booking_system_calendar";

		if ( ! empty( $_REQUEST['s'] ) ) {
			$search = trim($_REQUEST['s']);
		    $sql .= " WHERE c_title LIKE '%$search%'";
		}
		
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
	

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_calendar( $id ) {
		global $wpdb;
		
		$tmp = $wpdb->delete(
			"{$wpdb->prefix}maps_booking_system_calendar",
			array( 'c_id' => $id )
		);

	        $link = rtrim(dirname(__FILE__),"/lib");
	        $link = $link."/auth/p12files/calendar_".$id.".p12";
	        if (file_exists($link))
	        	unlink($link);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		
		$sql = "SELECT count(*)  FROM `{$wpdb->prefix}maps_booking_system_calendar`";
		
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No calendar avaliable.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
				case 'c_id':
				case 'c_title':
					return $item[ $column_name ];
				default:
					return print_r( $item, true ); //Show the whole array for troubleshooting purposes
			}
	}

	/**
	 * Render the bulk edit checkcolumnbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
			return sprintf(
				'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['c_id']
			);
	}	
    
	function currentPageUrl($filterGET = false)
	{
	    $result = 'http';
	    if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {
	        $result .= "s";
	    }
	    $result .= "://";
	    if ($_SERVER["SERVER_PORT"] != "80") {
	        $result .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	    } else {
	        $result .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	    }
	   if($filterGET == true)
	    {
	    	$result = explode('?',$result);
	    	$result = $result[0];
	    }
	    return $result;
	}
	
	function column_setting( $item ) {
		$url = $this->currentPageUrl();
		return sprintf(
			'<a href="%s&subpage=%s"><input class="button action" value="Customize" name="show-list" type="button"></a>',$url, $item['c_id']
		);
	}
    function column_shortcode($item)
    {
        return sprintf('[maps_booking_system id=%s]', $item['c_id']);
    }
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_booking' );

		$title = '<strong>' . $item['c_title'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&calendar=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['c_id'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
			
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'c_title'    => __( 'Title', 'sp' ),			
            'shortcode' => 'Shortcode',
            'setting' => 'Setting',
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {

		$sortable_columns = array(
			'c_title' => array( 'b_estart', false )
		);
		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'maps_booking_system_calendar_per_page', 15 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_maps_booking_system_calendar( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_booking' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_calendar( absint( $_GET['calendar'] ) );

				wp_redirect( esc_url( add_query_arg() ) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_calendar( $id );
			}
			
			if ( ! empty( $_REQUEST['subpage'] ) )
			{
				wp_redirect($this->currentPageUrl());
				exit;
			}

		}
	}
}
