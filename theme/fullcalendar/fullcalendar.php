<?php
/**
 * fullcalendar javascript functions
 */
$url = plugin_url() . "theme/fullcalendar/";
?>
<link href='<?php echo $url; ?>fullcalendar.css' rel='stylesheet' />
<link href='<?php echo $url; ?>fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo $url; ?>lib/moment.min.js'></script>
<script src='<?php echo $url; ?>lib/jquery.min.js'></script>
<script src='<?php echo $url; ?>fullcalendar.min.js'></script>

<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<script>
	$(document).ready(function() {

		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
    			eventRender: function (event, element) {
			        element.attr('href', 'javascript:void(0);');
			        element.click(function() {
			            $("#estartTime").val(moment(event.start).format('YYYY-MM-DD H:mm:ss'));
			            $("#eendTime").val(moment(event.end).format('YYYY-MM-DD H:mm:ss'));
			            $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));			           
			            $("#endTime").html(moment(event.end).format('MMM Do h:mm A'));
			            $("#eid").val(event.id);
			            $("#etitle").val(event.title);
			            $("#eventContent").dialog({ modal: true, title: event.title, width:350});
			            
			            // hide the booking and email alert
			            jQuery("#alert_booking").html('');			
				    $( "#alert_booking" ).hide();
   			 	    $( "#alert_email" ).hide();
   			 	  
   			 	    $( "#info_loading" ).show();
   			 	    $( "#info_data" ).hide();
   			 	    $( "#error_loading" ).hide();
				   
				    jQuery("#price_capacity_data").html('');
				    $( "#price_capacity_data" ).hide();
   			 	    $( "#discription_display" ).hide();
   			 	    
   			 	    // show the Price and Capacity
   			 	    var price_capacity_info = true;
   			 	    var eid = $("#eid").val();
				    var title = $("#etitle").val();				    
			            jQuery.ajax({
					    type:'POST',
					    data:{
						'action':'maps_booking_system_do_ajax',
						'eid': eid,
						'etitle':title,	
						'cid':<?php echo $calendar_id['id']  ?>,
						'price_capacity_info':price_capacity_info
						},
							url: "<?php echo plugin_url('home') ?>/wp-admin/admin-ajax.php",
							success: function(value) {
								
								$( "#price_capacity_loading" ).hide();
								if(value == 'error')
								{
									$( "#info_loading" ).hide();
									$( "#error_loading" ).show();
								}
								else{
									obj = JSON.parse(value);
									jQuery("#capacity_data").html(obj.capacity);
									jQuery("#price_data").html(obj.price);
									if(obj.discription)
									{	
										jQuery("#discription_data").html(obj.discription);
										$( "#discription_display" ).show();
									}
								
									$( "#info_loading" ).hide();
									$( "#info_data" ).show();
								}
							}
					});
				});
			 },
			//defaultDate: '2015-02-12',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: {
				//url: 'cal.php',
				url: '<?php echo plugin_url(); ?>auth/<?php echo '?cid='.$calendar_id['id']; ?>',
				error: function() {
					$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});

	});

</script>
<style>

	body {
		margin: 0;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#script-warning {
		display: none;
		background: #eee;
		border-bottom: 1px solid #ddd;
		padding: 0 10px;
		line-height: 40px;
		text-align: center;
		font-weight: bold;
		font-size: 12px;
		color: red;
	}

	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}

	#calendar {
		max-width: 900px;
		margin: 40px auto;
		padding: 0 10px;
	}
	.Table,.Table td ,.Table td {
    		border: 0px none !important;
    		margin-bottom: 0px;
	}
	.Table .form-control {
	    display: block;
	    width: 100%;
	    height: 34px;
	    padding: 6px 12px;
	    font-size: 14px;
	    line-height: 1.42857;
	    color: #555;
	    vertical-align: middle;
	    background-color: #FFF;
	    border: 1px solid #CCC;
	    border-radius: 4px;
	    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;
	    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;

	}
	.Table .form-control::-moz-placeholder {
	    color: #999;
	}
	.Table .btn {
		display: inline-block;
		padding: 6px 12px;
		margin-bottom: 0px;
		font-size: 14px;
		font-weight: normal;
		line-height: 1.42857;
		text-align: center;
		vertical-align: middle;
		cursor: pointer;
		border: 1px solid transparent;
		border-radius: 4px;
		white-space: nowrap;
		-moz-user-select: none;
	}
	.Table .btn-success {
	    color: #FFF;
	    background-color: #5CB85C;
	    border-color: #4CAE4C;
	}
	#eventContent .alert {
	    padding: 15px;
	    margin-bottom: 20px;
	    border: 1px solid transparent;
	    border-radius: 4px;
	}
	#eventContent .alert-warning {
	    background-color: #FCF8E3;
	    border-color: #FBEED5;
	    color: #C09853;
	}
	#eventContent .alert-success {
	    background-color: #DFF0D8;
	    border-color: #D6E9C6;
	    color: #468847;
	}
</style>

	<div id='script-warning'>
		<code>php/get-events.php</code> must be running.
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
    
    <div id="alert_booking" style="display:none;">
    </div>

    <div id="alert_email"  class="alert alert-warning" style="display:none;">
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

		$("#sendit").click(function(){
			if($('#email_address').val() == "") {
				 $( "#alert_email" ).show();
				exit();
			}
			else{
			 	$( "#alert_email" ).hide();
			}
			jQuery("#alert_booking").html('');			
			$( "#alert_booking" ).hide();
			
			var eid = $("#eid").val();
			var title = $("#etitle").val();
			var email = $("#email_address").val();
			var estarttime = $("#estartTime").val();
			var eendtime = $("#eendTime").val();			
		         jQuery.ajax({
		          type:'POST',
		          data:{
				'action':'maps_booking_system_do_ajax',
				'user_booking_email' : 'true',
				'eid': eid,
				'cid':<?php echo $calendar_id['id']  ?>,
				'etitle':title,
				'email':email,
				'estarttime':estarttime,
				'eendtime':eendtime
							},
		          url: "<?php echo $base_url ?>/wp-admin/admin-ajax.php",
		          success: function(value) {
		            jQuery("#alert_booking").html(value);
			    $( "#alert_booking" ).show();
                          //  $("#eid").val("");
                           // $("#etitle").val("");
                            $("#email_address").val("");
		          }
		        });
		});

		$(".ui-dialog-titlebar-close").click(function(){			
			jQuery("#alert_booking").html('');			
			$( "#alert_booking" ).hide();
		});
		</script>
