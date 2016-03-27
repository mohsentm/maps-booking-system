<div class="wrap">
	<h2>Customers Booking List</h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php
						$this->maps_booking_system_events_obj->prepare_items();
						$this->maps_booking_system_events_obj->search_box('Search Table', 'your-element-id');
						$this->maps_booking_system_events_obj->display(); ?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>