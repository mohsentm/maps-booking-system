<?php

$error_style = 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3);border-color: rgb(255, 155, 155);"';
// title
if(isset($result_response) && $result_response == false && (!isset($_POST["maps_booking_system_title"]) || @$_POST["maps_booking_system_title"] == NULL ))
	$title_error = true;
if(isset($result_response) && $result_response == false && $title_error == false )
	$title_value = @$_POST["maps_booking_system_title"];
else
	$title_value = $calendar_info['title'];

// email account	
if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_Email_Account"]) || @$_POST["maps_booking_system_Email_Account"] == NULL ))
	$email_account_error = true;
if(isset($result_response) && $result_response == false && $email_account_error == false )
	$email_account_value = @$_POST["maps_booking_system_Email_Account"];
else
	$email_account_value = $calendar_info['email_account'];	

// calendar id	
if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_Calendar_ID"]) || @$_POST["maps_booking_system_Calendar_ID"] == NULL)) 
	$calendar_id_error = true;
if(isset($result_response) && $result_response == false && $calendar_id_error == false )
	$calendar_id_value = @$_POST["maps_booking_system_Calendar_ID"];
else
	$calendar_id_value = $calendar_info['calendar_id'];

// capacity
if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_default_capacity"]) || @$_POST["maps_booking_system_default_capacity"] == NULL ))
	$capacity_error = true;
if(isset($result_response) && $result_response == false && $capacity_error == false )
	$capacity_value = @$_POST["maps_booking_system_default_capacity"];
else
	$capacity_value = $calendar_info['capacity'];

// price	
if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_default_price"])||@$_POST["maps_booking_system_default_price"] == NULL ))
	$price_error = true;
if(isset($result_response) && $result_response == false && $price_error == false )
	$price_value = @$_POST["maps_booking_system_default_price"];
else
	$price_value = $calendar_info['price'];
// mode	
if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_mode"])|| @$_POST["maps_booking_system_mode"] == NULL )) 	
	$mode_error = true;
if(isset($result_response) && $result_response == false && $mode_error == false )
	$mode_value = @$_POST["maps_booking_system_mode"];
else
	$mode_value = $calendar_info['mode'];

// id
if(isset($result_response) && $result_response == false )
	$id_value = @$_POST["maps_booking_system_id"];
else
	$id_value = $calendar_info['id'];
?>
<style>
	.config_table{
		text-align: left;
		width:510px;
	}
	.config_table th{
		width: 160px;
		padding: 5px;
	}
	.config_table td{
		padding: 5px;
	}
	.config_table td input[type="text"]{
		width: 100%; 
	}
</style>
<div id="poststuff" style="direction: ltr;">
	<div id="post-body" class="metabox-holder columns-2">
	    <div id="post-body-content">
	        <p><span style="color: red;">*</span> Indicates required field</p>
				<form method="post" action="">
					<?php wp_nonce_field('update-options'); ?>
					<table class="config_table">
						<tr valign="top">
							<th scope="row">Title: <span style="color:red">*</span></th>
							<td>
							    <input required <?php if($title_error == true) echo $error_style; ?> class="form-control" 
	                        				name="maps_booking_system_title" type="text" id="maps_booking_system_title" 
	                        				value="<?php echo @$title_value; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">Email Account: <span style="color:red">*</span></th>
							<td>
							    <input required <?php if($email_account_error == true) echo $error_style; ?> class="form-control" 
	                        				name="maps_booking_system_Email_Account" type="text" id="maps_booking_system_Email_Account" 
	                        				value="<?php echo @$email_account_value; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"> Calendar ID: <span style="color:red">*</span></th>
							<td>
							    <input required  <?php if($calendar_id_error == true) echo $error_style; ?> class="form-control"
	                        				name="maps_booking_system_Calendar_ID" type="text" id="maps_booking_system_Calendar_ID" 
	                       			 		value="<?php echo @$calendar_id_value; ?>" />
							</td>
						</tr>
	                <tr valign="top">
							<th scope="row"> Default Capacity: <span style="color:red">*</span></th>
							<td>
							<input required <?php if($capacity_error == true) echo $error_style;  ?> class="form-control" 
	                        				name="maps_booking_system_default_capacity" type="text" id="maps_booking_system_default_capacity" 
	                        				value="<?php echo @$capacity_value; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"> Default Price: <span style="color:red">*</span></th>
							<td>
							<input required <?php if($price_error == true) echo $error_style; ?>  class="form-control" 
	                        				name="maps_booking_system_default_price" type="text" id="maps_booking_system_default_price" 
	                        				value="<?php echo @$price_value;  ?>" />
							</td>
						</tr>  
						<tr valign="top">
							<th scope="row"> Booking Mode: <span style="color:red">*</span></th>
							<td>
							<input required <?php if($mode_error == true) echo $error_style; ?> type="radio" 		name="maps_booking_system_mode" value="title_base" <?php if($mode_value == "title_base") echo 'checked' ?>>
							Title based
							<br>
							<input required <?php if($mode_error == true) echo $error_style; ?> type="radio" name="maps_booking_system_mode" value="task_base" <?php  if($mode_value == "task_base") echo 'checked' ?>>
							Task based
							<br>
							</td>
						</tr>
					</table>
					<input type="hidden" name="action" value="edit-calendar-info" />
					<input type="hidden" name="maps_booking_system_id" value="<?php echo $id_value ?>" />
					<p>
						<input type="submit"  class="btn btn-success" value="<?php _e('Save') ?>" />
					</p>
	
				</form>
	        <p style="color:red"><?php if(isset($result_response) && $result_response == false) echo 'Please fill the required fields';  ?></p>
	    

				<hr>
				<h2>Upload the google account file:</h2>
			<?php if(isset($calendar_info['key'])&& $calendar_info['key'] != NULL):  ?>
				<div class="alert alert-success" role="alert" style="width: 125px;">p12 file is exist</div>
			<?php else: ?>
				<div class="alert alert-danger" role="alert" style="width: 149px;">p12 file is not exist</div>
			<?php endif; ?>
				<p>
					Permitted the extension p12
				</p>
				<form action="" method="post" enctype="multipart/form-data">
					<input type="file" name="maps_booking_system_file" id="maps_booking_system_file"  style="float: left;">
					<input type="hidden" name="maps_booking_system_id" value="<?php echo $id_value ?>" />
					<input type="hidden" name="action" value="upload-calendar-p12" />
					<input type="submit" value="<?php _e('Upload') ?>" class="btn btn-info" />
				</form>
				<p>
			<?php if (isset($result_upload_p12) && $result_upload_p12 == true) : ?>
				<div class="alert alert-success" role="alert" style="width: 182px;">File upload successfully</div>
			<?php elseif (isset($result_upload_p12) && $result_upload_p12 == false): ?>
				<div class="alert alert-danger" role="alert" style="width: 113px;">File is invalid</div>
			<?php endif; ?>
				</p>
		</div>
	</div>				
</div>
