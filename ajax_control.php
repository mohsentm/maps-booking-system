<?php

/**
 * This function response to ajax request
 */
function ajax_control()
{
	// show the capacity and price in user side calendar
	if(isset($_POST["price_capacity_info"]) && @$_POST["price_capacity_info"] == true)
	{	

		if(isset($_POST['overall_capacity']) && @$_POST['overall_capacity'] == true)
		{
			if ( !is_super_admin() )
				exit;
						
			$info_arr = _check_price_capacity($_POST['cid'],$_POST['eid'],$_POST['overall_capacity']);			
		}
		else
			$info_arr = _check_price_capacity($_POST['cid'],$_POST['eid']);
			
		if($info_arr == false)
			echo 'error';
		else
			echo json_encode($info_arr);
	}
	// booking user
	else if(isset($_POST["user_booking_email"]) && @$_POST["user_booking_email"] == true)
	{
		$capacity = _check_price_capacity($_POST['cid'],$_POST['eid']);
		if($capacity == false)
			echo 1;
		else
		{
			if( $capacity['capacity'] <= 0)
			{
				$mail_title = 'waiting';
				$booking_status = 0;
			}
			else
			{
				$mail_title = 'booking';
				$booking_status = 1;
			}
			
			$booking_res = _booking_email($_POST['cid'],$_POST['eid'],$_POST['etitle'],$_POST['email'],$_POST['estarttime'],$_POST['eendtime'],$booking_status);	
			
			if($booking_res == 3 )		
				send_email(
					$_POST['email'],
					$mail_title,
					array(
						'title'=> $_POST['etitle'],
						'starttime' => $_POST['estarttime'],
						'endtime' => $_POST['eendtime'],
						'price' => $capacity['price']
						)
					);

			echo $booking_res;
		}		
	}
	else if(isset($_POST["price_capacity_customize"]) && @$_POST["price_capacity_customize"] == true)
	{

		if ( !is_super_admin() )
			exit;
		$reg_res = _price_capacity_customize(
					@$_POST['cid'],
					@$_POST['eid'],
					@$_POST['etitle'],
					@$_POST['new_capacity'],
					@$_POST['new_price'],
					@$_POST['new_discription'],
					@$_POST['estarttime'],
					@$_POST['eendtime'],
					@$_POST['defalut_value_check']
				);
		echo $reg_res;
	}
}

/**
 * This function return the price & capacity | show user side calendar
 * 
 * @param int $cid this is a calendar id`s
 * @param int $eid this is a event id`s
 * @param boolean $overall_capacity if true show the overall capacity.<br>else if false show the remaining capacity
 * 
 * @return boolean|array
 * return false if the input param is null or can not find filed on database
 * if successfull return array of capcity & price & discription
 * 
 */
function _check_price_capacity($cid,$eid,$overall_capacity = false)
{
	if(!isset($cid) || @$cid == NULL ||!isset($eid) || @$eid == NULL)
		return false;
	
	if($overall_capacity == false)
	{
		$count =  db_select('maps_booking_system_booking','COUNT(*) AS Count_booking','b_eid = "'.$eid.'" AND b_cid = "'.$cid.'"');
		foreach($count as $count_row);
	}
	
	$event_list = db_select('maps_booking_system_events','*','e_eid = "'.$eid.'" AND e_cid = "'.$cid.'"');

	if($event_list != false) {
		foreach ($event_list as $event_row )
		{			
			if($overall_capacity == false)
				return array(
						'capacity'    => ($event_row->e_capacity - $count_row->Count_booking ),
						'price'       => $event_row->e_price ,
						'discription' => $event_row->e_discription
					    );				
			else
				return array(
						'capacity'    => ($event_row->e_capacity),
						'price'       => $event_row->e_price ,
						'discription' => $event_row->e_discription,
						'default'     => false
					    );	
		}
	}
	else
	{
		$event_list = db_select('maps_booking_system_calendar','`c_capacity`, `c_price`','`c_id` = "'.$cid.'"');
		
		if($event_list != false) {
			foreach ($event_list as $event_row )
			{
				if($overall_capacity == false)
					return array(
							'capacity'    => ($event_row->c_capacity - $count_row->Count_booking ),
							'price'       => $event_row->c_price
						    );
				else
					return array(
							'capacity'    => ($event_row->c_capacity ),
							'price'       => $event_row->c_price,
							'default'     => true
						    );
									
			}
		}	
	}
	return false;
}

/**
 * This function do the user booking
 * @param int $cid calendar id`s
 * @param int $eid event id`s
 * @param string $etitle title of events
 * @param string $email email of user
 * @param string $estarttime the time of calss start
 * @param string $eendtime  the time of calss finish
 * @param int $booking_status user booking status.if in booked is 1, else if in waiting list is 0
 * 
 * @return int
 * 0 -> Invalid email format <br>
 * 1 -> Database error<br>
 * 2 -> register before<br>
 * 3 -> register successful<br>
 * 
 */
function _booking_email($cid,$eid,$etitle,$email,$estarttime,$eendtime,$booking_status)
{
	if(
 	      !isset($eid) 	  || @$eid 	  == NULL 
 	   || !isset($cid) 	  || @$cid	  == NULL 
	   || !isset($etitle) 	  || @$etitle	  == NULL 
	   || !isset($email) 	  || @$email	  == NULL
	   || !isset($estarttime) || @$estarttime == NULL
	   || !isset($eendtime)   || @$eendtime   == NULL
	 )
	 	return 0;
	
	$email = strtolower($email);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
		return 0;
	}
	
	$calendar_mode = db_select('maps_booking_system_calendar','`c_mode`','`c_id` = "'.$cid.'"'); 
	if($calendar_mode == false) 
		return 1;
	foreach ($calendar_mode as $calendar_mode_row );
	if($calendar_mode_row->c_mode == "task_base")
	{
		$email_check= db_select('maps_booking_system_booking','*','b_email_address = "'.$email.'" AND b_eid = "'.$eid.'" AND b_cid = "'.$cid.'"');
		if($email_check != false)
			return 2;
	}
	else if($calendar_mode_row->c_mode == "title_base")
	{
		$email_check= db_select('maps_booking_system_booking','*','b_email_address = "'.$email.'" AND b_title = "'.$etitle.'" AND b_cid = "'.$cid.'"');
		if($email_check != false)
			return 2;
	}
	else
		return 1;
		
	
	$etitle		=  substr(trim(strip_tags($etitle)), 0, 100);
    	$estarttime	=  trim(strip_tags($estarttime));
    	$eendtime 	=  trim(strip_tags($eendtime));	    	

	$insert_value = array(
				'b_cid' => $cid,
				'b_register_time' => date("Y-m-d H:i:s"),
				'b_email_address' => $email,
				'b_estart' => $estarttime,
				'b_eend' => $eendtime,
				'b_eid' => $eid,
				'b_title' => $etitle,
				'b_booking_status' => $booking_status
			     );

	$insert_res = db_insert('maps_booking_system_booking',$insert_value);
	if($insert_res == false)
		return 1;
	else
		return 3;
	
}

/**
 * This function do the price capacity customize in setting
 * 
 * @param int $cid calendar id`s
 * @param int $eid event id`s
 * @param string $etitle title of events
 * @param int $capacity 
 * @param int $price
 * @param string $discription
 * @param string $estarttime
 * @param string $eendtime
 * @param string $action_check if default delete the customize event feild else if new insert or update the customize values
 * 
 * @return int
 * 0 -> error<br>
 * 1 -> set default value<br>
 * 2 -> successfully update<br> 
 * 3 -> register successful
 */
function _price_capacity_customize($cid,$eid,$etitle,$capacity,$price,$discription,$estarttime,$eendtime,$action_check)
{
	if(!isset($cid)	|| $cid == NULL || !isset($eid) || $eid == NULL)
		return 0;
		
	
	if(isset($action_check) && $action_check == 'default')
	{
		db_delete('maps_booking_system_events',array( 'e_eid' => $eid , 'e_cid' => $cid));
		return 1;
	}
	else if(isset($action_check) && $action_check == 'new')
	{
		
		if(
		    	   !isset($etitle) 	|| $etitle	== NULL
		    	|| !isset($estarttime) 	|| $estarttime	== NULL
		    	|| !isset($eendtime)	|| $eendtime 	== NULL
		    	|| !isset($price) 	|| $price 	== NULL
		    	|| !isset($capacity)    || $capacity  	== NULL
		)
			return 0;
		
	    	$etitle		=  substr(trim(strip_tags($etitle)), 0, 100);
	    	$estarttime	=  trim(strip_tags($estarttime));
	    	$eendtime 	=  trim(strip_tags($eendtime));
	    	$price 		=  trim(strip_tags($price));
	    	$capacity  	=  trim(strip_tags($capacity));
	    	
	    	if(isset($discription))
	    		$discription  =  trim(strip_tags($discription));
	    	
	    	$check_list = db_select('maps_booking_system_events','*','e_eid = "'.$eid.'" AND e_cid = "'.$cid.'"');
	    	if($check_list == false)	 
	    	{	    		    	
	    		$insert_value = array(
						'e_cid' 	=> $cid,
						'e_date' 	=> date("Y-m-d H:i:s"),
						'e_eid' 	=> $eid,
						'e_title' 	=> $etitle,
						'e_estart'	=> $estarttime,
						'e_eend' 	=> $eendtime,
						'e_capacity' 	=> $capacity,
						'e_price'	=> $price,											
						'e_discription' => $discription
					);
   
	    		$insert_res = db_insert('maps_booking_system_events',$insert_value);
			if($insert_res == false)  
				return 0;
			else
				return 1;
	    	}
	    	else
	    	{
	    		$update_res = db_update(
			    			'maps_booking_system_events',
						array( 
							'e_date' 	=> date("Y-m-d H:i:s"),
							'e_capacity' 	=> $capacity,
							'e_price'	=> $price,
							'e_discription' => $discription,
							'e_estart' 	=> $estarttime,
							'e_eend'   	=> $eendtime
						), 
						array( 'e_eid' => $eid , 'e_cid' => $cid)
			    		);
			if($update_res == true)
				return 2;
			else
				return 0;
				
	    	}
	    	   	
	}
	else
		return 0;
}
