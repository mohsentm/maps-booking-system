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
		<form method="post" action="">
			<?php wp_nonce_field('update-options'); ?>
			<table class="config_table">
				<tr valign="top">
					<th scope="row"></th>
					<td>
					    <input class="form-control" 
                				name="active-reminder" type="checkbox"
                				<?php if ( wp_next_scheduled( 'active_reminding_mail' ) ) echo 'checked' ?> value='1' />
                				Active send automatic reminder email
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Reminder:</th>
					<td>
					    <select name="day_reminder" class="form-control" >
					    <option value=""></option>
					    <?php 
					    	for($i=1;$i<4;$i++)
					    		if(get_option('maps_booking_system_email_reminder') == $i )
					    		{
					    			if($i == 1)
					    				echo "<option value='$i' selected>$i day before</option>";
					    			else
					    				echo "<option value='$i' selected>$i days before</option>";
					    		}
					    		else
					    		{
					    			if($i == 1)
					    				echo "<option value='$i'>$i day before</option>";
					    			else
					    				echo "<option value='$i'>$i days before</option>";
					    		}
					    
					    
					    ?>					    	
					    </select>
					</td>
				</tr>				 
			</table>
			<p>
				<input type="hidden" name="change-reminder" value="1" />
				<input type="submit" name="save-change-reminder" class="btn btn-success" value="<?php _e('Save') ?>" />
			</p>

		</form>
	        <?php
	        	if(isset($email_reminder_res) && $email_reminder_res != false):
	        ?>
	        	<div class="alert alert-success" role="alert">Update successfully</div>
	    	<?php
	    		endif;
	    	?>
	    	<!-- End mail config -->	    		    
		</div>
	</div>				
</div>