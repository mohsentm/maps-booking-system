<?php

/*
*	booking
*	waiting
*	reminding
*	cancel
*/

/**
 * This function send the email by wp_mail
 * 
 * @param string $to_email Array or comma-separated list of email addresses to send message.
 * @param string $title Email subject to select on email template
 * @param array $info  Email informaiton.
 * 
 * @return boolean 
 * ture  send succeful
 * false can not finde template for title in database  
 */
function send_email($to_email,$title,$info = null)
{
	
	$theme_res = db_select('maps_booking_system_mail','*',"m_title ='$title'");
	
	if($theme_res == false)
		return false;
		
	foreach($theme_res as $theme_row);
	
	$subject = _mail_theme_parse($theme_row->m_subject,$info);
	$body 	 = _mail_theme_parse($theme_row->m_body,$info);
	
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'From: '.get_option('maps_booking_system_email_name').' <'.get_option('maps_booking_system_email').'>';
	
	wp_mail( $to_email, $subject, $body, $headers );
	return true;
}
/*
 * This function send the reminding mail
 */
function send_reminding_mail()
{	

	$date = explode('-', date("Y-m-d"));
	$select_date = date("Y-m-d",mktime(0,0,0,$date[1],$date[2],$date[0]) + (86400* get_option('maps_booking_system_email_reminder')));
		
	$user_info =  db_select('maps_booking_system_booking','*',' `b_estart` >= "'.$select_date.' 00:00:00" AND `b_estart` <= "'.$select_date.' 23:59:59" AND `b_booking_status` = "1"');
	
	if($user_info)
		foreach($user_info as $user_info_row)
		{
			
			$price = _check_price_capacity($user_info_row->b_cid,$user_info_row->b_eid);
			
			$remiding_info = array(
					'title' => $user_info_row->b_title ,
					'starttime' => $user_info_row->b_estart ,
					'endtime' => $user_info_row->b_eend,
					'price' => $price['price']
					);

			send_email($user_info_row->b_email_address,'reminding',$remiding_info);
		}
		
}

/**
 * This function pars the theme and replace the tag with value
 * 
 * @param string $theme template of email 
 * @param array[] $value_array array of data heve been replace with tags in theme
 * 
 * @return string email template was replace data with tags
 */
function _mail_theme_parse($theme,$value_array)
{	
	if($value_array != NULL)
		foreach($value_array as $key => $value)
			$theme = @str_replace("[$key]", $value, $theme);
	return $theme;
}

/**
 * This function insert the default mail theme in the table when the install the plugin
 */
function insert_mail_default_theme()
{
       $theme =  array(
	        	'0' => array(
	        			'm_id' => '1',
	        			'm_title'=>'Booking',
	        			'm_subject'=> 'Booking Successful',
	        			'm_body'=>'You have booked successfully<br> title : [title] <br> time<br> start : [starttime] <br> end : [endtime]<br>price : [price]'
	        			),
	        	'1' => array(
	        			'm_id' => '2',
	        			'm_title'=>'Waiting',
	        			'm_subject'=> 'Waiting Successful',
	        			'm_body'=>'You have been added to the waiting list <br> title : [title] <br> time<br> start : [starttime] <br> end : [endtime]<br>price : [price]'
	        			),
	        	'2' => array(
	        			'm_id' => '3',
	        			'm_title'=>'Reminding',
	        			'm_subject'=> 'Class Reminder',
	        			'm_body'=>'Your booked class is<br> title : [title] <br> time<br> start : [starttime] <br> end : [endtime]<br>price : [price]'
	        			),
	        	'3' => array(
	        			'm_id' => '4',
	        			'm_title'=>'Cancellation',
	        			'm_subject'=> 'Class Cancelled',
	        			'm_body'=>'Your class was cancelled'
	        			),        			        			        
	        );
        
        for($i=0; $i<count($theme) ;$i++)
        	db_insert('maps_booking_system_mail',$theme[$i]);
}
