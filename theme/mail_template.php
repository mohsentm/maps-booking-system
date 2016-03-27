<div class="wrap">
    <h2>Email Template</h2>
    <div id="poststuff" style="direction: ltr;">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
		<form action="" method="post" class="form-horizontal">
			<div class="container-fluid">
			<?php 
				foreach($mail_template as $mail_template_row):
			?>
				<!-- Start -->
				<div class="row">
					<div class="col-md-12">
						<hr>
						<h4><?php echo $mail_template_row->m_title ?> Email</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Subject</label>
							<div class="col-sm-10">
								<input type="text" value="<?php echo $mail_template_row->m_subject ?>" class="form-control" name="<?php echo $mail_template_row->m_title ?>-subject" placeholder="Subject">
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Content</label>
							<div class="col-sm-10">
								<textarea class="form-control" rows="5" name="<?php echo $mail_template_row->m_title ?>-body" placeholder="Email Content"><?php echo $mail_template_row->m_body ?></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						Available tags: <br>
						[title]    : Event title <br>
						[starttime] : Start time of event <br>
						[endtime]   : End time of event <br>
						[price]     : Event price
					</div>						
				</div>
				<!-- End -->
				<?php
				endforeach;
				?>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-5">
					<?php
						if(isset($insert_res) && $insert_res != false):
						?>
							<div class="alert alert-success" role="alert">Update successfully</div>
						<?php
						elseif(isset($insert_res) && $insert_res == false):
						?>
							<div class="alert alert-warning" role="alert">Sorry! there was a problem in registration</div>
						<?php
						endif;
						?>
					</div>
					<div class="col-md-2">
						<input class="btn btn-success" name="save-change-template" type="submit" style="float: right;" value="<?php _e('Save') ?>">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-offset-2">
						
					</div>
				</div>			
			</div>						
		</form>     
            </div>
        </div>
    </div>
</div>
