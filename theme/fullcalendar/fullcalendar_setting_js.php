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
				
				$( "#info_loading" ).show();
				$( "#corser_form" ).hide();
				$( "#error_loading" ).hide();			    
				
				$( "#alert_0" ).hide();
				$( "#alert_1" ).hide();
				$( "#alert_2" ).hide();
				$( "#alert_3" ).hide();
				// document.getElementById("corser_form").setAttribute("style", "display: none;");
				// show the Price and Capacity
				var price_capacity_info = true;
				var eid = $("#eid").val();
				var title = $("#etitle").val();
				var default_capacity = <?php echo @$capacity_value; /* this var load in calendar_edit_config_setting_page.php file */ ?> ;
				var default_price = <?php echo @$price_value; /* this var load in calendar_edit_config_setting_page.php file */  ?>;
				jQuery.ajax({
					type:'POST',
					data:{
						'action' : 'maps_booking_system_do_ajax',
						'eid': eid,
						'etitle':title,
						'cid':<?php echo $id_value; /* this var load in calendar_edit_config_setting_page.php file */ ?>,
						'price_capacity_info':price_capacity_info,
						'overall_capacity': true,
					},
					url: "<?php echo plugin_url("home") ?>/wp-admin/admin-ajax.php",
					success: function(value) {
						if(value == 'error')
						{
							$( "#info_loading" ).hide();
							$( "#error_loading" ).show();
						}
						else{
							obj = JSON.parse(value);
							if(obj.default == true)
							{
								jQuery("#new_capacity").val(default_capacity);
								jQuery("#new_price").val(default_price);
								document.getElementById("defalut_value_check").checked = true;
							}
							else
							{
								jQuery("#new_capacity").val(obj.capacity);
								jQuery("#new_price").val(obj.price);
								jQuery("#new_discription").val(obj.discription);
								document.getElementById("defalut_value_check").checked = false;
								
							}
							changeForm();						
							$( "#info_loading" ).hide();
							$( "#corser_form" ).show();
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
////////////////////////
/*
* Event Customization
*/

function changeForm() {
	var default_capacity = <?php echo @$capacity_value; /* this var load in calendar_edit_config_setting_page.php file */ ?> ;
	var default_price = <?php echo @$price_value; /* this var load in calendar_edit_config_setting_page.php file */  ?>;
 	
        if($('#defalut_value_check').is(":checked")) {
            document.getElementById('new_capacity').disabled = true; 
            document.getElementById('new_price').disabled = true; 
            document.getElementById('new_discription').disabled = true; 
            document.getElementById("new_capacity").setAttribute("style", "background-color: #eee;");
            document.getElementById("new_price").setAttribute("style", "background-color: #eee;");
            document.getElementById("new_discription").setAttribute("style", "background-color: #eee;resize : none;");
            jQuery("#new_capacity").val(default_capacity);
	    jQuery("#new_price").val(default_price);
	    jQuery("#new_discription").val('');
        }
        else{
        	document.getElementById('new_capacity').disabled = false; 
        	document.getElementById('new_price').disabled = false; 
            	document.getElementById('new_discription').disabled = false; 
            	document.getElementById("new_capacity").setAttribute("style", "background-color: #FFF;");
            	document.getElementById("new_price").setAttribute("style", "background-color: #FFF;");
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

	
	if($('#new_price').val() == "") {
		document.getElementById("new_price").setAttribute("style", "border-color: RED;");			 
		exit();
	}

	changeForm();
	
	$( "#alert_0" ).hide();
	$( "#alert_1" ).hide();
	$( "#alert_2" ).hide();
	$( "#alert_3" ).hide();
	
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
	var new_price = $("#new_price").val();
	var new_discription = $("#new_discription").val();
	jQuery.ajax({
		type:'POST',
		data:{
			'action' : 'maps_booking_system_do_ajax',
			'eid': eid,
			'cid':<?php echo $id_value; /* this var load in calendar_edit_config_setting_page.php file */ ?>,
			'etitle':title,
			'estarttime':estarttime,
			'eendtime':eendtime,
			'defalut_value_check':defalut_value_check,
			'new_capacity':new_capacity,
			'new_price':new_price,
			'new_discription':new_discription,
			'price_capacity_customize':'true',
		},
		url: "<?php echo plugin_url('home') ?>/wp-admin/admin-ajax.php",
		success: function(value) {
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

