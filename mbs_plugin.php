<?php
/**
 * 
 */
class MBS_Plugin {
	
         /**          
          * @var object $instance class instance 
          */
	static $instance; 
        
        /**         
         * @var object booking WP_List_Table object
         */
	public $maps_booking_system_booking_obj;
        
        /**         
         * @var object events WP_List_Table object 
         */
	public $maps_booking_system_events_obj;
      
        /**
         * @var object calendar WP_List_Table object 
         */
        public $maps_booking_system_calendar_obj;
	
        /**
         * class constructor
         * Below are the callback methods for set-screen-option filter and admin_menu action hook.
         */
	public function __construct() {
		
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}

        /**
         * 
         * @param type $status
         * @param type $option
         * @param type $value
         * @return type
         */
	public static function set_screen( $status, $option, $value ) {
		return $value;
	}
        
        /**
         * This methods that creates the settings page includes a callback screen_option() method to create the screen option for setting the default number of data to display in the table.
         */
	public function plugin_menu() {

		$hook = add_menu_page(
			'MAPS Booking List',
			'Booking List',
			'manage_options',
			'maps_booking_list',
			[ $this, 'plugin_lists_page' ]
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );
		$hook2 = add_options_page('MAPS Booking System Setting', 'Booking System', 'administrator','maps_booking_setting', [ $this, 'maps_booking_setting_page']);	

		add_action( "load-$hook2", [ $this, 'screen_option2' ] );
		
		$hook3 = add_options_page('MAPS Booking System mail', 'Booking System Mail', 'administrator','maps_booking_mail', [ $this, 'maps_booking_mail_page']);	

		//add_action( "load-$hook3", [ $this, 'screen_option2' ] );
	}
	
	/**
         * This method displays the content of the calendar settings page.
         */
	public function maps_booking_setting_page() {		
		
	        // Add New Calendar Page        
	        if ( ! empty( $_REQUEST['subpage'] ) && $_REQUEST['subpage'] == 'new' )
	        {
	            $this->maps_booking_setting_new();
	        }
	        //END  Add New Calendar Page 
	        else if ( ! empty( $_REQUEST['subpage'] ) && $_REQUEST['subpage'] != NULL )
	        {
	           
	           
	            $this->maps_booking_setting_edit();
	            
	        }
		
	        // Calendar List
	        else
	           include("theme/calendar_list_setting_page.php");
	        // END calendar List
	}
        /**
         * This method displays the content of the edit calendar page. 
         */
	private function maps_booking_setting_edit()
	{
		if(isset($_POST["action"]) && @$_POST["action"] == 'edit-calendar-info' )
		{
 			$result_response = true;
			if(
			      (!isset($_POST["maps_booking_system_id"])               || @$_POST["maps_booking_system_id"] == NULL )
			   || (!isset($_POST["maps_booking_system_title"])            || @$_POST["maps_booking_system_title"] == NULL )
			   || (!isset($_POST["maps_booking_system_Email_Account"])    || @$_POST["maps_booking_system_Email_Account"] == NULL )
			   || (!isset($_POST["maps_booking_system_Calendar_ID"])      || @$_POST["maps_booking_system_Calendar_ID"] == NULL )
			   || (!isset($_POST["maps_booking_system_default_capacity"]) || @$_POST["maps_booking_system_default_capacity"] == NULL )
			   || (!isset($_POST["maps_booking_system_default_price"])    || @$_POST["maps_booking_system_default_price"] == NULL )
			   || (!isset($_POST["maps_booking_system_mode"])             || @$_POST["maps_booking_system_mode"] == NULL )
			  )
			     $result_response = false;
			
			if($result_response == true)
			{
			    $db_value = array(
			    'c_title'         => trim($_POST["maps_booking_system_title"]),
			    'c_email_account' => trim($_POST["maps_booking_system_Email_Account"]),
			    'c_calendar_id'   => trim($_POST["maps_booking_system_Calendar_ID"]),
			    'c_capacity'      => trim($_POST["maps_booking_system_default_capacity"]),
			    'c_price'         => trim($_POST["maps_booking_system_default_price"]),
			    'c_mode'          => trim($_POST["maps_booking_system_mode"])        
			    );	
			
			    $db_where = array('c_id' => $_POST["maps_booking_system_id"]);
			    $result_response = db_update('maps_booking_system_calendar',$db_value,$db_where);
			
			}
			if($result_response == true)
			{
				$result_response = db_select('maps_booking_system_calendar','*','c_id ='.'"'.$_POST["maps_booking_system_id"].'"');
			    
			    	if($result_response != false)
				    foreach($result_response as $calendar_info_row)
				    {
				    	$calendar_info['id'] 		=  $calendar_info_row->c_id ;
				    	$calendar_info['title'] 	=  $calendar_info_row->c_title ;
				    	$calendar_info['email_account'] =  $calendar_info_row->c_email_account ;
				    	$calendar_info['calendar_id'] 	=  $calendar_info_row->c_calendar_id ;
				    	$calendar_info['capacity'] 	=  $calendar_info_row->c_capacity ;
				    	$calendar_info['price']		=  $calendar_info_row->c_price ;
				    	$calendar_info['mode'] 		=  $calendar_info_row->c_mode;
				    }
			}
			$file_name = "calendar_".$_REQUEST['subpage'].".p12";
	                if (file_exists(dirname(__FILE__)."/auth/p12files/" . $file_name))
	                	$p12_check = true;
			
			include("theme/maps_booking_setting_page.php");
		}
		else if(isset($_POST["action"]) && @$_POST["action"] == 'upload-calendar-p12' && isset($_FILES["maps_booking_system_file"]) )
		{
			$result_upload_p12 = $this->upload_p12($_FILES["maps_booking_system_file"],$_POST['maps_booking_system_id']);

			$result_response = db_select('maps_booking_system_calendar','*','c_id ='.'"'.$_POST["maps_booking_system_id"].'"');
		    
		    	if($result_response != false)
			    foreach($result_response as $calendar_info_row)
			    {
			    	$calendar_info['id'] 		=  $calendar_info_row->c_id ;
			    	$calendar_info['title'] 	=  $calendar_info_row->c_title ;
			    	$calendar_info['email_account'] =  $calendar_info_row->c_email_account ;
			    	$calendar_info['calendar_id'] 	=  $calendar_info_row->c_calendar_id ;
			    	$calendar_info['capacity'] 	=  $calendar_info_row->c_capacity ;
			    	$calendar_info['price']		=  $calendar_info_row->c_price ;
			    	$calendar_info['mode'] 		=  $calendar_info_row->c_mode;
			    }

			$file_name = "calendar_".$_REQUEST['subpage'].".p12";
	                if (file_exists(dirname(__FILE__)."/auth/p12files/" . $file_name))
	                	$p12_check = true;
	                else
	                	$p12_check = false;

			include("theme/maps_booking_setting_page.php");
		}
		elseif ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )) 
		{
		
			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) 		
				db_delete('maps_booking_system_events',array( 'e_id' => $id));
				
			$result_response = db_select('maps_booking_system_calendar','*','c_id ='.'"'.$_REQUEST['subpage'].'"');
		    
			if($result_response != false)
			{
			    foreach($result_response as $calendar_info_row)
			    {
			    	$calendar_info['id'] 		=  $calendar_info_row->c_id ;
			    	$calendar_info['title'] 	=  $calendar_info_row->c_title ;
			    	$calendar_info['email_account'] =  $calendar_info_row->c_email_account ;
			    	$calendar_info['calendar_id'] 	=  $calendar_info_row->c_calendar_id ;
			    	$calendar_info['capacity'] 	=  $calendar_info_row->c_capacity ;
			    	$calendar_info['price']		=  $calendar_info_row->c_price ;
			    	$calendar_info['mode'] 		=  $calendar_info_row->c_mode;
			    }
			    
			$file_name = "calendar_".$_REQUEST['subpage'].".p12";
			if (file_exists(dirname(__FILE__)."/auth/p12files/" . $file_name))
				$p12_check = true;
			        	
			include("theme/maps_booking_setting_page.php");
			}
			else
			{
			$url_redirect = str_replace("subpage=".$_REQUEST['subpage'], "",currentPageUrl()); 
			include("theme/404.php");		    	
			}		
			
		}
		else
		{
		    $result_response = db_select('maps_booking_system_calendar','*','c_id ='.'"'.$_REQUEST['subpage'].'"');
		    
		    if($result_response != false)
		    {
			    foreach($result_response as $calendar_info_row)
			    {
			    	$calendar_info['id'] 		=  $calendar_info_row->c_id ;
			    	$calendar_info['title'] 	=  $calendar_info_row->c_title ;
			    	$calendar_info['email_account'] =  $calendar_info_row->c_email_account ;
			    	$calendar_info['calendar_id'] 	=  $calendar_info_row->c_calendar_id ;
			    	$calendar_info['capacity'] 	=  $calendar_info_row->c_capacity ;
			    	$calendar_info['price']		=  $calendar_info_row->c_price ;
			    	$calendar_info['mode'] 		=  $calendar_info_row->c_mode;
			    }
			    
			$file_name = "calendar_".$_REQUEST['subpage'].".p12";
	                if (file_exists(dirname(__FILE__)."/auth/p12files/" . $file_name))
	                	$p12_check = true;
		                	
		    	include("theme/maps_booking_setting_page.php");
		    }
		    else
		    {
		    	$url_redirect = str_replace("subpage=".$_REQUEST['subpage'], "",currentPageUrl()); 
		    	include("theme/404.php");		    	
		    }
		    
		}
	}
        
        /**
         * This method displays the content of the new calendar page.
         */
	private function maps_booking_setting_new()
	{
		// Insert Calendar info page
		if($_REQUEST['subpage'] == 'new')
		{
		
			if(isset($_POST["action"]) && @$_POST["action"] == 'new-calendar-info' )
			{ 
			    $result_response = true;
			    if(
			          (!isset($_POST["maps_booking_system_title"])            || @$_POST["maps_booking_system_title"] == NULL )
			       || (!isset($_POST["maps_booking_system_Email_Account"])    || @$_POST["maps_booking_system_Email_Account"] == NULL )
			       || (!isset($_POST["maps_booking_system_Calendar_ID"])      || @$_POST["maps_booking_system_Calendar_ID"] == NULL )
			       || (!isset($_POST["maps_booking_system_default_capacity"]) || @$_POST["maps_booking_system_default_capacity"] == NULL )
			       || (!isset($_POST["maps_booking_system_default_price"])    || @$_POST["maps_booking_system_default_price"] == NULL )
			       || (!isset($_POST["maps_booking_system_mode"])             || @$_POST["maps_booking_system_mode"] == NULL )
			      )
			        $result_response = false;
			
			    if($result_response == true)
			    {
			        $db_value = array(
			            'c_title'         => trim($_POST["maps_booking_system_title"]),
			            'c_email_account' => trim($_POST["maps_booking_system_Email_Account"]),
			            'c_calendar_id'   => trim($_POST["maps_booking_system_Calendar_ID"]),
			            'c_capacity'      => trim($_POST["maps_booking_system_default_capacity"]),
			            'c_price'         => trim($_POST["maps_booking_system_default_price"]),
			            'c_mode'          => trim($_POST["maps_booking_system_mode"])        
			        );
			        
			        $result_response = db_insert('maps_booking_system_calendar',$db_value);
			
			    }
			    
			    if($result_response == false)
			        include("theme/calendar_new_setting_page.php"); 
			    else
			        include("theme/calendar_new_upload_setting_page.php");         
			}
			else if(isset($_POST["action"]) && @$_POST["action"] == 'upload-calendar-p12' && isset($_FILES["maps_booking_system_file"]) )
			{
				
			   $upload_response = $this->upload_p12($_FILES["maps_booking_system_file"],$_POST["cid"]);
			
			    if($upload_response == false)
			        include("theme/calendar_new_upload_setting_page.php"); 
			     else
			        {			                    
			           $url_redirect = str_replace("subpage=new", "",currentPageUrl());          
			           include("theme/calendar_new_status_setting_page.php");
			        }				    
			
			}
			else
			    include("theme/calendar_new_setting_page.php"); 
		
		}
		//END Insert Calendar info page
		else		
		   include("theme/maps_booking_setting_page.php");
	}
	
        /**
         *  This method upload the p12 file
         * 
         * @param array[] $File information of files uploaded by user
         * @param int $id calendar id`s
         * 
         * @return boolean 
         *  true if uploaded succeful<br>
         *  false if have error in uploading
         */
	private function upload_p12($File,$id)
	{
	    $temp = explode(".", $File["name"]);
	    $extension = end($temp);
	
	    $upload_response = true;
		
	    if ($File["size"] <= 0 || $extension != "p12")
	   	 $upload_response = false;
	
	    if ($File["error"] > 0)
	    	$upload_response = false; 
	
	     if($upload_response == true)
	     {			
	        $file_name = "calendar_".$id.".p12";
                if (file_exists(dirname(__FILE__)."/auth/p12files/" . $file_name))
                	unlink(dirname(__FILE__)."/auth/p12files/" . $file_name);

                move_uploaded_file($File["tmp_name"], dirname(__FILE__)."/auth/p12files/" .$file_name);
	    }
	    return $upload_response;
	}	
	
        /**
         * This method displays the content of the mail settings page.
         */
	public function maps_booking_mail_page()
	{		
						
		if(isset($_POST['save-change-template']))
		{

			$mail_template =  db_select('maps_booking_system_mail','*');	

			foreach($mail_template as $mail_template_check)
			{
				if(
				       isset($_POST["$mail_template_check->m_title-subject"]) 
				    && $_POST["$mail_template_check->m_title-subject"] != NULL 
				    && $_POST["$mail_template_check->m_title-subject"] != $mail_template_check->m_subject
				  )
					$insert_value['m_subject'] = $_POST["$mail_template_check->m_title-subject"];
				
				if(
				       isset($_POST["$mail_template_check->m_title-body"])
				    && $_POST["$mail_template_check->m_title-body"] != NULL
				    && $_POST["$mail_template_check->m_title-body"] != $mail_template_check->m_body
				  )
					$insert_value['m_body'] = $_POST["$mail_template_check->m_title-body"];
				
				if(isset($insert_value))
					$insert_res = db_update(
								'maps_booking_system_mail',
								$insert_value,
								array('m_title'=>$mail_template_check->m_title)
								);	
				unset($insert_value);		
			}
		
		}
		
		if(isset($_POST['change-reminder']))
		{
			
			$email_reminder_res = true;
			
			if(isset($_POST['active-reminder']))
			{
				if ( ! wp_next_scheduled( 'active_reminding_mail' ) )
					wp_schedule_event( time(), 'daily', 'active_reminding_mail' );
				
			}
			else
				if (  wp_next_scheduled( 'active_reminding_mail' ) )
					wp_clear_scheduled_hook('active_reminding_mail');
			
			if(isset($_POST['day_reminder']))	
				$email_reminder_res = update_option( 'maps_booking_system_email_reminder', $_POST['day_reminder'] );
			
			
			
		}
		//send_reminding_mail();
		
		$mail_template =  db_select('maps_booking_system_mail','*','','m_id ASC');	

		include("theme/mail_setting.php");
	}
	/**
	 * Plugin settings page
	 */
	public function plugin_lists_page() {
		include("theme/plugin_lists_page.php");
	}

	/**
	 * Screen options
         * we instantiated the maps_booking_system_booking_List child class and saved the object to the maps_booking_system_booking_obj property defined earlier at the class declaration.
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'maps_booking_system_booking',
			'default' => 5,
			'option'  => 'maps_booking_system_booking_per_page'
		];

		add_screen_option( $option, $args );

		$this->maps_booking_system_booking_obj = new maps_booking_system_booking_List();
	}
        
        /**
	 * Screen options
         * If subpage is set we instantiated the maps_booking_system_event_List child class and saved the object to the maps_booking_system_events_obj property defined earlier at the class declaration.
         * esle we instantiated the maps_booking_system_event_List child class and saved the object to the maps_booking_system_calendar_obj property defined earlier at the class declaration.
	 */	
	public function screen_option2() {

            if ( ! empty( $_REQUEST['subpage'] ) && $_REQUEST['subpage'] != 'new' )
            {
                        $option = 'per_page';
                        $args   = [
                                'label'   => 'maps_booking_system_events',
                                'default' => 5,
                                'option'  => 'maps_booking_system_events_per_page'
                        ];

                        add_screen_option( $option, $args );

                        $this->maps_booking_system_events_obj = new maps_booking_system_event_List();
            }
            else
            {    
                        $option = 'per_page';
                        $args   = [
                                'label'   => 'maps_booking_system_calendar',
                                'default' => 5,
                                'option'  => 'maps_booking_system_calenar_per_page'
                        ];

                        add_screen_option( $option, $args );

                        $this->maps_booking_system_calendar_obj = new maps_booking_system_calendar_List();
            }

	}

        /**
         * Singleton instance 
         * @return object
         */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}



}
