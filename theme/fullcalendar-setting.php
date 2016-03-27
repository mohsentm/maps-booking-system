<?php
$url = plugin_url() . "theme/fullcalendar/";
?>
<link href='<?php echo $url; ?>fullcalendar.css' rel='stylesheet' />
<link href='<?php echo $url; ?>fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo $url; ?>lib/moment.min.js'></script>
<script src='<?php echo $url; ?>lib/jquery.min.js'></script>
<script src='<?php echo $url; ?>fullcalendar.min.js'></script>

<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="<?php echo $url; ?>fullcalendar_setting_function.css" />

<div id='script-warning'>	
	calendar configurator error
</div>

<div id='loading'>Loading...</div>

<div id='calendar'></div>




<div id="eventContent" title="Event Details" style="display:none;">	
	<span id='info_loading'>Loading information...</span>
	<span id='error_loading'>Error<br>can not load information</span>
	<span id="corser_form">
		<span><input id='defalut_value_check' onchange="changeForm()" type="checkbox" name="defalut-value-check" value="default" style="margin: 0px;">Set default value</span><hr>
		<div style="position:relative">
		
		<span> Capacity: <input id="new_capacity" type="number" value="" min="0" palceholder="coutom Capacity" name="new-capacity" style="background-color: #eee;"></span><br><br>
		<span>Price: <input id="new_price" type="number" value="" min="0" palceholder="coutom Price" name="new-price" style="background-color: #eee;"></span><br><br>
		Discription:<br>
		<textarea id="new_discription" name="new-discription" row="3" style="background-color: #eee;resize : none;"></textarea>
		
		<div id="alert_0"  class="alert alert-warning" style="display:none;text-align: center;">
			Sorry<br>there was a problem in registration
		</div>    
		
		<div id="alert_1"  class="alert alert-success" style="display:none;text-align: center;">
			Your Event set default successfully
		</div>
		
		<div id="alert_2"  class="alert alert-success" style="display:none;text-align: center;">
			Your Event update successfully
		</div>
		
		<div id="alert_3"  class="alert alert-success" style="display:none;text-align: center;">
			Your Event registered successfully
		</div>

		<div>
			<input type="hidden" id="eid" value="">
			<input type="hidden" id="etitle" value="">
			<input type="hidden" id="estartTime" value="">
			<input type="hidden" id="eendTime" value="">	
			<button id='sendit' class="btn btn-success" >Save</button>
		</div>
	</div>
	</span>
</div>
<script>
<?php
	include("fullcalendar/fullcalendar_setting_js.php");
?>
</script>