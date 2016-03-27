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
			            jQuery("#alert_booking_event").html('');			
				    $( "#alert_booking_event" ).hide();

   			 	    $( "#price_capacity_loading" ).show();
				    jQuery("#price_capacity_data").html('');
				    document.getElementById("corser_form").setAttribute("style", "display: none;");
   			 	    // show the Price and Capacity
   			 	    var price_capacity_info = true;
   			 	    var eid = $("#eid").val();
				    var title = $("#etitle").val();
				    var default_capacity = 30 ;
				    var default_price = 100;
			            jQuery.ajax({
					    type:'POST',
					    data:{
						'action' : 'maps_booking_system_do_ajax',
						'eid': eid,
						'etitle':title,
						'price_capacity_info':price_capacity_info,
						//'maps_booking_'
						},
							url: "<?php echo plugin_url("home") ?>/wp-admin/admin-ajax.php",
							success: function(value) {
							$( "#price_capacity_loading" ).hide();
							if(value == 'default')
							{
								jQuery("#new_capacity").val(default_capacity);
								jQuery("#new_Price").val(default_price);
								document.getElementById("defalut_value_check").checked = true;
								changeForm();
							}
							else{
								obj = JSON.parse(value);
								jQuery("#new_capacity").val(obj.new_capacity);
								jQuery("#new_Price").val(obj.new_price);
								jQuery("#new_discription").val(obj.new_discription);
								document.getElementById("defalut_value_check").checked = false;
								changeForm();
							}
							document.getElementById("corser_form").setAttribute("style", "display: block;");
						}
					});
				});
			 },
			//defaultDate: '2015-02-12',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: {
				//url: 'cal.php',
				url: '<?php echo plugin_url(); ?>auth/<?php echo '?cid='.$_GET['subpage']; ?>',
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
	#new_Price{
		margin-left: 26px;
	}
	#new_discription{
		resize : vertical;
		min-height: 35px;
		max-height: 130px;
	}

</style>

	<div id='script-warning'>
		calendar not found
	</div>

	<div id='loading'>Loading...</div>

	<div id='calendar'></div>




<div id="eventContent" title="Event Details" style="display:none;">	
		<span id='price_capacity_loading'>loading price and capacity...</span>
	<span id="corser_form">
		<span><input id='defalut_value_check' onchange="changeForm()" type="checkbox" name="defalut-value-check" value="defalut" style="margin: 0px;">Set default value</span><hr>
		<div style="position:relative">
		
		<span> Capacity: <input id="new_capacity" type="number" value="" min="0" palceholder="coutom Capacity" name="new-capacity" style="background-color: #eee;"></span><br><br>
		<span>Price: <input id="new_Price" type="number" value="" min="0" palceholder="coutom Price" name="new-Price" style="background-color: #eee;"></span><br><br>
		Discription:<br>
		<textarea id="new_discription" name="new-discription" row="3" style="background-color: #eee;resize : none;"></textarea>
		<div id="alert_booking_event" style="display:none;">
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
		 function changeForm() {
		 	var default_capacity = 30;
		 	var default_price = 100;
		 	
		        if($('#defalut_value_check').is(":checked")) {
		            document.getElementById('new_capacity').disabled = true; 
		            document.getElementById('new_Price').disabled = true; 
		            document.getElementById('new_discription').disabled = true; 
		            document.getElementById("new_capacity").setAttribute("style", "background-color: #eee;");
		            document.getElementById("new_Price").setAttribute("style", "background-color: #eee;");
		            document.getElementById("new_discription").setAttribute("style", "background-color: #eee;resize : none;");
		            jQuery("#new_capacity").val(default_capacity);
			    jQuery("#new_Price").val(default_price);
			    jQuery("#new_discription").val('');
		        }
		        else{
		        	document.getElementById('new_capacity').disabled = false; 
		        	document.getElementById('new_Price').disabled = false; 
		            	document.getElementById('new_discription').disabled = false; 
		            	document.getElementById("new_capacity").setAttribute("style", "background-color: #FFF;");
		            	document.getElementById("new_Price").setAttribute("style", "background-color: #FFF;");
		            	document.getElementById("new_discription").setAttribute("style", "background-color: #FFF;");
		        }

		    }
		 function confirm_alert() {
		    confirm("Are you sure ti set default value?");
		}
		$("#sendit").click(function(){
			if($('#defalut_value_check').is(":checked")){
				if (!window.confirm("Are you sure ti set default value?")) { 
					exit();
				}
			}
			if($('#new_capacity').val() == "") {
				document.getElementById("new_capacity").setAttribute("style", "border-color: RED;");				 
				exit();
			}
			else{
			 	document.getElementById("new_capacity").setAttribute("style", "");	
			}
			if($('#new_Price').val() == "") {
				document.getElementById("new_Price").setAttribute("style", "border-color: RED;");				 
				exit();
			}
			else{
			 	document.getElementById("new_Price").setAttribute("style", "");	
			}
			jQuery("#alert_booking").html('');			
			$( "#alert_booking" ).hide();
			
			var eid = $("#eid").val();
			var title = $("#etitle").val();
			var estarttime = $("#estartTime").val();
			var eendtime = $("#eendTime").val();
			var defalut_value_check = $("#defalut_value_check").val();
			if($('#defalut_value_check').is(":checked"))
				var defalut_value_check = $("#defalut_value_check").val();
			else
				var defalut_value_check = "new";
				
			var new_capacity = $("#new_capacity").val();;			
			var new_Price = $("#new_Price").val();
			var new_discription = $("#new_discription").val();
			var price_capacity_new = true;
		         jQuery.ajax({
		          type:'POST',
		          data:{
				'action' : 'maps_booking_system_do_ajax',
				'eid': eid,
				'etitle':title,
				'estarttime':estarttime,
				'eendtime':eendtime,
				'defalut_value_check':defalut_value_check,
				'new_capacity':new_capacity,
				'new_Price':new_Price,
				'new_discription':new_discription,
				'price_capacity_new':price_capacity_new,
							},
		          url: "<?php echo plugin_url('home') ?>/wp-admin/admin-ajax.php",
		          success: function(value) {
		            jQuery("#alert_booking_event").html(value);
			    $( "#alert_booking_event" ).show();
		          }
		        });
		});

		$(".ui-dialog-titlebar-close").click(function(){			
			jQuery("#alert_booking").html('');			
			$( "#alert_booking" ).hide();
		});
		</script>