<?php


/**
 * This function do the create plugin tables in the wordpress database
 *
 * @return void
 */
function create_plugin_table()
{
	
    global $wpdb; // @global object wordpress global object to connect database
    $table_name_booking = $wpdb->prefix . 'maps_booking_system_booking';
    $table_name_events = $wpdb->prefix . 'maps_booking_system_events';
    $table_name_calendar = $wpdb->prefix . 'maps_booking_system_calendar';
    $table_name_mail = $wpdb->prefix . 'maps_booking_system_mail';
    $charset_collate = $wpdb->get_charset_collate();

    add_option("maps_booking_system_email", 'example@site.com', '', 'yes');
    add_option("maps_booking_system_email_name", get_option('blogname'), '', 'yes');

    $sql = "CREATE TABLE $table_name_booking (
	  b_id mediumint(9) NOT NULL AUTO_INCREMENT,
	  b_cid int(11) DEFAULT '0' NOT NULL,
	  b_register_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  b_estart datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  b_eend datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,	
	  b_email_address varchar(55) DEFAULT '' NOT NULL,
	  b_eid varchar(55) DEFAULT '' NOT NULL,
	  b_title varchar(100) DEFAULT '' NOT NULL,
	  b_booking_status int DEFAULT '0' NOT NULL,
	  UNIQUE KEY id (b_id)
	) $charset_collate;";

    $sql .= "CREATE TABLE $table_name_events (
		e_id mediumint(9) NOT NULL AUTO_INCREMENT,
		e_cid int(11) DEFAULT '0' NOT NULL,
		e_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		e_eid varchar(55) DEFAULT '' NOT NULL,
		e_title varchar(100) DEFAULT '' NOT NULL,
		e_estart datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		e_eend datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,						
		e_capacity varchar(55) DEFAULT '' NOT NULL,
		e_price varchar(55) DEFAULT '' NOT NULL,
		e_discription TEXT NOT NULL,
		UNIQUE KEY id (e_id)
	) $charset_collate;";

    $sql .= "CREATE TABLE $table_name_calendar(
		c_id mediumint(9) NOT NULL AUTO_INCREMENT,
		c_title varchar(100) DEFAULT '' NOT NULL,
		c_email_account varchar(100) DEFAULT '' NOT NULL,
		c_calendar_id varchar(100) DEFAULT '' NOT NULL,				
		c_capacity varchar(55) DEFAULT '' NOT NULL,
		c_price varchar(55) DEFAULT '' NOT NULL,
        c_mode varchar(12) DEFAULT 'task_base' NOT NULL,
        c_key MEDIUMBLOB NOT NULL,
		UNIQUE KEY id (c_id)
	) $charset_collate;";

    $sql .= "CREATE TABLE $table_name_mail(
		m_id mediumint(9) NOT NULL AUTO_INCREMENT,
		m_title varchar(55) DEFAULT '' NOT NULL,		
		m_subject varchar(100) DEFAULT '' NOT NULL,
        	m_body text DEFAULT '' NOT NULL,
		UNIQUE KEY id (m_id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    return;
}

/**
 * This function do the drop plugin tables in the wordpress database
 *
 * @return void
 */
function drop_plugin_table() {
    global $wpdb;
    $table_name_booking = $wpdb->prefix . 'maps_booking_system_booking';
    $table_name_events = $wpdb->prefix . 'maps_booking_system_events';
    $table_name_calendar = $wpdb->prefix . 'maps_booking_system_calendar';
    $table_name_mail = $wpdb->prefix . 'maps_booking_system_mail';
    $wpdb->query("DROP TABLE IF EXISTS $table_name_booking,$table_name_events,$table_name_calendar,$table_name_mail");
    return;
}

/**
 * This function insert the information in database
 *
 * @param string $table_name name of the table will insert the data.
 * @param array[] $value  array of the value will insert in database.
 *
 * @return int|boolean 
 * insert_id => insert successful return the id of field.<br>
 * false     => have error when insert.
 */
function db_insert($table_name, $value) {
    global $wpdb;
    $tmp = $wpdb->insert($wpdb->prefix . $table_name, $value);

    if ($tmp == 1)
        return $wpdb->insert_id;
    else
        return false;
}

/**
 * This function update the field of table
 *
 * @param string $table_name name of the table will insert the data.
 * @param array[] $value array of the value will insert in database.
 * @param string $where field of table you want select to update.
 * 
 * @return boolean 
 * true  => updated successful .<br>
 * false => have error when update.
 */
function db_update($table_name,$value,$where)
{
    global $wpdb;
    $tmp = $wpdb->update($wpdb->prefix.$table_name,$value,$where);	
    if($tmp == 1)
        return true;
    else
        return false;
}

/**
 * This function do the select in the table.
 *
 * @param string $table_name name of the table will insert the data.
 *
 * @param string $filed name of filed you want select. user "*" to select all.
 * @param string|NULL $where add the filter to your select query.
 * @param string|NULL  $order_by   sort the the data order by every field you want.
 *
 * @return array|boolean
 * list  => return the array of information list.<br>
 * false =>  have error when select.
 * 
 */
function db_select($table_name, $filed, $where = NULL, $order_by = NULL) {
    global $wpdb;
    if ($where != NULL)
        $where = ' WHERE ' . $where;

    if ($order_by != NULL)
        $order_by = ' ORDER BY ' . $order_by;

    //echo 'SELECT '.$filed.' FROM '.$wpdb->prefix.$table_name.$where.$order_by;
    $list = $wpdb->get_results('SELECT ' . $filed . ' FROM ' . $wpdb->prefix . $table_name . $where . $order_by);
    $number = $wpdb->num_rows;
    if ($number > 0)
        return $list;
    else
        return false;
}

/**
 * This function do the delete action
 *   
 * @param string $table_name name of the table will insert the data
 * @param string $where add the filter to your select query
 * 
 * @return boolean 
 * true  => delete successful .<br>
 * false => have error when update.
 */
function db_delete($table_name, $where) {
    global $wpdb;
    $tmp = $wpdb->delete($wpdb->prefix . $table_name, $where);

    if ($tmp == 1)
        return true;
    else
        return false;
}
