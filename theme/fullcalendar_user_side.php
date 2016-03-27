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
<link rel="stylesheet" href="<?php echo $url; ?>fullcalendar_user_side_function.css" />

<div id='script-warning'>
	calendar not found
</div>

<div id='loading'>Loading...</div>

<div id='calendar'></div>




<div id="eventContent" title="Event Details" style="display:none;">
    
    <span id='info_loading'>Loading information...</span>
    <span id='error_loading'>Error<br>can not load information</span>
    <span style="display:none;" id="info_data">
	    Start: <span id="startTime"></span><br>
	    End: <span id="endTime"></span><br>	    
	    Capacity: <span id="capacity_data"></span><br>	
	    Price: <span id="price_data"></span><br>	
	    <span id="discription_display">Discription: <span id="discription_data"></span></span><br>	
    </span>

    <div id="alert_0"  class="alert alert-warning" style="display:none;text-align: center;">
    	Invalid email format
    </div>    
    
    <div id="alert_1"  class="alert alert-warning" style="display:none;text-align: center;">
    	Sorry<br>there was a problem in registration
    </div>
        
    <div id="alert_2"  class="alert alert-warning" style="display:none;text-align: center;">
    	You register to this event before!
    </div>
    
    <div id="alert_3"  class="alert alert-success" style="display:none;text-align: center;">
    	Your Booking registered successfully
    </div>
    <div id="alert_email"  class="alert alert-warning" style="display:none;text-align: center;">
	    please Fill your Email Address
    </div>

    <div>
    <table border="0" class="Table">
    <tr>
    <td style="vertical-align: middle; width: 44px;">
    Email:
    </td>
    <td  style="vertical-align: middle; width: 176px;">
    <input type="email" placeholder="jane.doe@example.com" id="email_address" class="form-control" style="margin:0px;" required>
	<input type="hidden" id="eid" value="">
	<input type="hidden" id="etitle" value="">
	<input type="hidden" id="estartTime" value="">
	<input type="hidden" id="eendTime" value="">
	<input type="hidden" id="user_booking_email" value="">
    </td>
    <td  style="vertical-align: middle; width: 100px;">
							    <button id='sendit' class="btn btn-success" >Book it!</button>
    </td>
    </tr>
    </table>

</div>
<script>
<?php
	include("fullcalendar/fullcalendar_user_side_js.php");
?>
</script>
