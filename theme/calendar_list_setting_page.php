<div class="wrap">
    <h2>Calendar List <a href="<?php echo currentPageUrl(); ?>&subpage=new" class="upload page-title-action">Add New</a></h2>
    <div id="poststuff" style="direction: ltr;">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
				<form method="post">
					<?php
					$this->maps_booking_system_calendar_obj->prepare_items();
					$this->maps_booking_system_calendar_obj->search_box('Search Table', 'your-element-id');
					$this->maps_booking_system_calendar_obj->display(); ?>
				</form>       
            </div>
        </div>
        <br class="clear">
    </div>
</div>

