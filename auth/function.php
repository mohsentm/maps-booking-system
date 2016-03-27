<?php

/**
 * This function include the wp-config to connect database.
 * This function use in google auth
 * 
 * @return string table prefix
 */
function config_db()
{
    $url_link = explode('/',dirname(__FILE__) );
    $count = count($url_link );
    $link = '';
    $count;

    do{
	    $count--;
	    $link .= '../';
	
    }while($url_link[$count] != 'wp-content' && $count > 0 );
    define('ABSPATH', $link);
    require_once(ABSPATH . 'wp-config.php');
    return $table_prefix;
}

/**
 * This function load the calendar configuration information on the database
 * 
 * @param int $id calendar id`s
 * @return array[] Calendar configuration information
 */
function calender_info($id)
{
    $table_prefix = config_db();    
    $id = trim($id);
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, DB_USER, DB_PASSWORD);	

    $query = "SELECT c_id,c_email_account,c_calendar_id FROM ".$table_prefix."maps_booking_system_calendar WHERE c_id = :id";
    $result = $db->prepare($query);
    $result->execute(array('id'=>$id));
    foreach($result as $result_row)
	{
        $return['id']            = $result_row['c_id'];		
        $return['email_account'] = $result_row['c_email_account'];
        $return['calendar_id']   = $result_row['c_calendar_id'];
    }
    return $return;
}
?>
