<div class="wrap">
    <h2>Upload P12 files Calendar</h2>
    <div id="poststuff" style="direction: ltr;">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
				<form action="" method="post" enctype="multipart/form-data">
					<input type="file" name="maps_booking_system_file" id="maps_booking_system_file">
                    <input type="hidden" name="action" value="upload-calendar-p12" />
                    <input type="hidden" name="cid" value="<?php if($result_response != false) echo $result_response; else if(isset($_POST["cid"]) && @$_POST["cid"] !=NULL) echo $_POST["cid"];  ?>" />
					<input type="submit" value="<?php _e('Upload') ?>" />
                    <p style="color:red"><?php if(isset($upload_response)&&$upload_response == false) echo "Error! the file is invalied ";  ?> </p>
				</form>     
            </div>
        </div>
        <br class="clear">
    </div>
</div>
