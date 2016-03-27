<div class="wrap">
    <h2>Add New Calendar</h2>
    <div id="poststuff" style="direction: ltr;">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <p><span style="color: red;">*</span> Indicates required field</p>
				<form method="post" action="">
					<?php wp_nonce_field('update-options'); ?>
					<table width="510" style="text-align: left;">
						<tr valign="top">
							<th width="130" scope="row">Title: <span style="color:red">*</span></th>
							<td width="406">
							    <input required 
                                    <?php
                                    if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_title"]) || @$_POST["maps_booking_system_title"] == NULL ))
                                        echo 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3); border-color: rgb(255, 155, 155);"'
                                    ?>
                                name="maps_booking_system_title" type="text" id="maps_booking_system_title" 
                                value="<?php if(isset($_POST["maps_booking_system_title"]) && @$_POST["maps_booking_system_title"] != NULL ) echo $_POST["maps_booking_system_title"];?>" />
							</td>
						</tr>
						<tr valign="top">
							<th width="130" scope="row">Email Account: <span style="color:red">*</span></th>
							<td width="406">
							    <input required 
                                <?php if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_Email_Account"]) || @$_POST["maps_booking_system_Email_Account"] == NULL ))
                                     echo 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3); border-color: rgb(255, 155, 155);"' ?> 
                                name="maps_booking_system_Email_Account" type="text" id="maps_booking_system_Email_Account" 
                                value="<?php if(isset($_POST["maps_booking_system_Email_Account"]) && @$_POST["maps_booking_system_Email_Account"] != NULL) echo $_POST["maps_booking_system_Email_Account"]; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th width="130" scope="row"> Calendar ID: <span style="color:red">*</span></th>
							<td width="406">
							    <input required 
                                <?php if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_Calendar_ID"]) || @$_POST["maps_booking_system_Calendar_ID"] == NULL)) 
                                echo 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3); border-color: rgb(255, 155, 155);"' ?> 
                                name="maps_booking_system_Calendar_ID" type="text" id="maps_booking_system_Calendar_ID" 
                                value="<?php if(isset($_POST["maps_booking_system_Calendar_ID"]) && @$_POST["maps_booking_system_Calendar_ID"] != NULL) echo @$_POST["maps_booking_system_Calendar_ID"];  ?>" />
							</td>
						</tr>
                        <tr valign="top">
							<th width="130" scope="row"> Default Capacity: <span style="color:red">*</span></th>
							<td width="406">
    							<input required 
                                <?php if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_default_capacity"]) || @$_POST["maps_booking_system_default_capacity"] == NULL )) 
                                echo 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3); border-color: rgb(255, 155, 155);"' ?> 
                                name="maps_booking_system_default_capacity" type="text" id="maps_booking_system_default_capacity" 
                                value="<?php if(isset($_POST["maps_booking_system_default_capacity"]) && @$_POST["maps_booking_system_default_capacity"] != NULL) echo @$_POST["maps_booking_system_default_capacity"];  ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th width="130" scope="row"> Default Price: <span style="color:red">*</span></th>
							<td width="406">
    							<input required 
                                <?php if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_default_price"])||@$_POST["maps_booking_system_default_price"] == NULL )) 
                                echo 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3); border-color: rgb(255, 155, 155);"' ?> 
                                name="maps_booking_system_default_price" type="text" id="maps_booking_system_default_price" 
                                value="<?php if(isset($_POST["maps_booking_system_default_price"])&&@$_POST["maps_booking_system_default_price"] != NULL ) echo $_POST["maps_booking_system_default_price"]; ?>" />
							</td>
						</tr>  
						<tr valign="top">
							<th width="130" scope="row"> Booking Mode: <span style="color:red">*</span></th>
							<td width="406">
    							<input required 
                                <?php if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_mode"])|| @$_POST["maps_booking_system_mode"] == NULL )) 
                                echo 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3); border-color: rgb(255, 155, 155);"' ?> type="radio" name="maps_booking_system_mode" value="title_base" 
                                <?php if(isset($_POST["maps_booking_system_mode"])&& @$_POST["maps_booking_system_mode"] != NULL && @$_POST["maps_booking_system_mode"] == "title_base") echo 'checked' ?>>
    							Title based
    							<br>
    							<input required 
                                <?php if(isset($result_response) &&$result_response == false && (!isset($_POST["maps_booking_system_mode"]) || @$_POST["maps_booking_system_mode"] == NULL )) 
                                echo 'style="box-shadow: 0px 1px 5px rgba(251, 0, 0, 0.3); border-color: rgb(255, 155, 155);"' ?> type="radio" name="maps_booking_system_mode" value="task_base" 
                                <?php if(isset($_POST["maps_booking_system_mode"])&& @$_POST["maps_booking_system_mode"] != NULL && @$_POST["maps_booking_system_mode"] == "task_base") echo 'checked' ?>>
    							Task based
    							<br>
							</td>
						</tr>
					</table>
					<input type="hidden" name="action" value="new-calendar-info" />
					<p>
						<input type="submit" value="<?php _e('Save') ?>" />
					</p>

				</form>
                <p style="color:red"><?php if(isset($result_response) && $result_response == false) echo 'Please fill the required fields';  ?></p>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
