/* 
* load the event info 
*/
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
		 	    $( "#alert_0" ).hide();
			    $( "#alert_1" ).hide();
			    $( "#alert_2" ).hide();
			    $( "#alert_3" ).hide();
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
								if(obj.capacity > 0)
									jQuery("#capacity_data").html(obj.capacity);
								else
									jQuery("#capacity_data").html('Finishing capacity , <strong>you can be on waiting list</strong><br>');
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

////////////////////////////
/*
* booking function
*/
$("#sendit").click(function(){
	if($('#email_address').val() == "") {
		 $( "#alert_email" ).show();
		exit();
	}
	else{
	 	$( "#alert_email" ).hide();
	}					
	$( "#alert_0" ).hide();
	$( "#alert_1" ).hide();
	$( "#alert_2" ).hide();
	$( "#alert_3" ).hide();
	
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
	    
	        $("#email_address").val("");                            
	    
		if(value == '0')
		{
			$( "#alert_0" ).show();
		}
		else if(value == '1')
		{
			$( "#alert_1" ).show();
		}
		else if(value == '2')
		{
			$( "#alert_2" ).show();
		} 
		else if(value == '3')
		{
			$( "#alert_3" ).show();
		} 								                          
	  }
	});
});