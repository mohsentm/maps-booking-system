		<div class="wrap">
			<h2>Customers Booking List</h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">


<?php if ( ! empty( $_REQUEST['eid'] ) && ! empty( $_REQUEST['cid'] ) ): ?>
<a href="<?php echo $this->maps_booking_system_booking_obj->currentPageUrl(true).'?page='.$_REQUEST['page'].'&cid='.$_REQUEST['cid']  ?>"><input class="button action" value="‌Back" type="button"></a>
<?php
	$Booking_info = $this->maps_booking_system_booking_obj->show_booking_title();
	echo '<h4>Title: '.$Booking_info['title'].' | Start: '.$Booking_info['start'].' | End:'.$Booking_info['end'].'</h4>';
	
 elseif ( ! empty( $_REQUEST['cid'] ) ): ?>
<a href="<?php echo $this->maps_booking_system_booking_obj->currentPageUrl(true).'?page='.$_REQUEST['page']  ?>"><input class="button action" value="‌Back" type="button"></a>
<?php
	$Booking_info = $this->maps_booking_system_booking_obj->show_booking_title();
	echo '<h4>Calendar: '.$Booking_info['calendar'].'</h4>';
 	endif; 
 ?>

							<form method="post">
								<?php
								$this->maps_booking_system_booking_obj->prepare_items();
								$this->maps_booking_system_booking_obj->search_box('Search Table', 'your-element-id');
								$this->maps_booking_system_booking_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
		
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Statistics graph bookings'
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Members'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'Statistics Bookings',
            data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }]
    });
});
		</script>
	</head>
	<body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;"></div>

	</body>
