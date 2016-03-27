<?php
$url = plugin_url() . "theme/";

$url_redirect = str_replace("subpage=".@$_GET['subpage'], "",currentPageUrl());  

?>
<link href="<?php echo $url?>bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo $url?>bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>

<div class="wrap">
	<h2>Plugin Setting <?php  echo '<a href="'.$url_redirect.'" class="upload page-title-action">Back to List</a>'; ?></h2>
	<br>
	<div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
		
		<ul id="myTabs" class="nav nav-tabs" role="tablist">
			<li  class="active" role="presentation">
				<a aria-expanded="true" href="#Customized-courses" role="tab" id="Customized-courses-tab" data-toggle="tab" aria-controls="Customized-courses">Customized Events</a>
			</li>
			<li role="presentation">
				<a aria-expanded="true" href="#Customized-List" role="tab" id="Customized-List-tab" data-toggle="tab" aria-controls="Customized-List">Event List Customization</a>
			</li>
			<li role="presentation">
				<a aria-expanded="true" href="#Google_Calendar" role="tab" id="Google_Calendar-tab" data-toggle="tab" aria-controls="Google_Calendar">Calendar Configuration</a>
			</li>
		</ul>
		
		<div id="myTabContent" class="tab-content">			
			<!-- Event List Customization -->
			<div role="tabpanel" class="tab-pane fad" id="Google_Calendar" aria-labelledby="Google_Calendar-tab">				
				<?php include_once('calendar_edit_config_setting_page.php'); ?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="Customized-List" aria-labelledby="Customized-List-tab">
				<?php include_once('calendar_edit_event_list_setting_page.php'); ?>
			</div>			
			<div role="tabpanel" class="tab-pane fade active in" id="Customized-courses" aria-labelledby="Customized-courses-tab">
				<?php include_once ('fullcalendar-setting.php'); ?>
			</div>			
		</div>
	</div>
</div>