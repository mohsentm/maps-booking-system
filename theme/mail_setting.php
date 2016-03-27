<?php
$url = plugin_url() . "theme/";

//$url_redirect = str_replace("subpage=".@$_GET['subpage'], "",currentPageUrl());  

?>
<link href="<?php echo $url?>bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo $url?>bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>

<div class="wrap">
	<h2>Mail Setting</h2>
	<br>
	<div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
		
		<ul id="myTabs" class="nav nav-tabs" role="tablist">
			<li  class="active" role="presentation">
				<a aria-expanded="true" href="#mail-config" role="tab" id="mail-config-tab" data-toggle="tab" aria-controls="mail-config">Configuration</a>
			</li>
			<li role="presentation">
				<a aria-expanded="true" href="#mail-template" role="tab" id="mail-template-tab" data-toggle="tab" aria-controls="mail-template">Template</a>
			</li>
			<li role="presentation">
				<a aria-expanded="true" href="#mail-reminding" role="tab" id="mail-reminding-tab" data-toggle="tab" aria-controls="mail-reminding">Reminder</a>
			</li>
		</ul>
		
		<div id="myTabContent" class="tab-content">			
			<!-- Event List Customization -->
			<div role="tabpanel" class="tab-pane fad  active in" id="mail-config" aria-labelledby="Google_Calendar-tab">				
				<?php include_once('mail_config.php'); ?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="mail-template" aria-labelledby="mail-template-tab">
				<?php include_once('mail_template.php'); ?>
			</div>	
			<div role="tabpanel" class="tab-pane fade" id="mail-reminding" aria-labelledby="mail-reminding-tab">				
				<?php include_once('mail_reminding.php'); ?>
			</div>				
		</div>
	</div>
</div>