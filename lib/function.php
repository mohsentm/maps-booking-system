<?php

/** 
 * This function return plugin url
 *
 * @param string $option chose the which url you want.'home','siteurl' or null
 *  
 * @return string 
 *  link of your page you define in option.<br>
 *  if option is null return the pluing url
 */
function plugin_url($option = NULL)
{
	$url = $_SERVER['SERVER_NAME'] . dirname(__FILE__);
	$array = explode('/',$url);
	$count = count($array);
	$pdn = $array[$count-2];
	
	switch ($option) {
		case 'home':
			return  get_option('home');
			break;
		
		case 'siteurl':
			return  get_option('siteurl');
			break;
		
		default:
			return get_option('home')."/wp-content/plugins/$pdn/";			
			break;
	}
}

/**
 * This function return the current url of page
 *
 * @param boolean $filterGET if set true,filter the get values in return url
 *
 * @return string current url of page
 */
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

/**
 * This function check the remaining cpacity of the events
 *
 * @param int $eid the ids of filed you want to check capacity
 *
 * @return int return the remaining capacity of event
 */
function check_cpacity($eid)
{
	global $wpdb;
	
	$sql = 'SELECT COUNT(*) FROM '.$wpdb->prefix.'maps_booking_system_booking where b_eid = "'.$eid.'"';
	
	$event_list = $wpdb->get_results( 'SELECT * FROM `'.$wpdb->prefix.'maps_booking_system_events` WHERE `e_eid`="'.$eid.'"' );
	//echo $wpdb->last_query;
	$number =  $wpdb->num_rows;
	if($number > 0) {
		//$arr = array('new_capacity' => 1, 'new_price' => 2);
		foreach ($event_list as $event_row )
		{
			if(($event_row->e_capacity - $wpdb->get_var( $sql )) <=0)
				return 0;				
			else
				return $event_row->e_capacity - $wpdb->get_var( $sql );									
		}
	}
	else
	{
		if((get_option('maps_booking_system_default_capacity') - $wpdb->get_var( $sql )) <=0)
			return 0;
		else
			return get_option('maps_booking_system_default_capacity')-$wpdb->get_var( $sql );
	}
}

