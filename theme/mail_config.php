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
	    <!-- End mail config -->
	        <p><span style="color: red;">*</span> Indicates required field</p>
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>
			<table class="config_table">
				<tr valign="top">
					<th scope="row">From Email: <span style="color:red">*</span></th>
					<td>
					    <input required class="form-control" 
                				name="maps_booking_system_email" type="text"
                				value="<?php echo get_option('maps_booking_system_email'); ?>" placeholder="booking@website.com" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">From Name: <span style="color:red">*</span></th>
					<td>
					    <input required class="form-control" 
                				name="maps_booking_system_email_name" type="text"
                				value="<?php echo get_option('maps_booking_system_email_name'); ?>" placeholder="email name" />
					</td>
				</tr>
									 
			</table>
			<p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="maps_booking_system_email,maps_booking_system_email_name" />
				<input type="submit" name="save-email-config" class="btn btn-success" value="<?php _e('Save') ?>" />
			</p>

		</form>
	        <?php
	        	if(isset($email_config_res) && $email_config_res == true):
	        ?>
	        	<div class="alert alert-success" role="alert">Update successfully</div>
	         <?php
	        	elseif(isset($email_config_res) && $email_config_res == false):
	        ?>
	    		<div class="alert alert-warning" role="alert">All filed is required</div>
	    	<?php
	    		endif;
	    	?>
	    	<!-- End mail config -->	    		    
		</div>
	</div>				
</div>