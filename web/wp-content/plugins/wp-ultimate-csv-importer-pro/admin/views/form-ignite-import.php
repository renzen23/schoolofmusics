<?php
/******************************************************************************************
 * Copyright (C) Smackcoders. - All Rights Reserved under Smackcoders Proprietary License
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly
global $uci_admin;
if($_POST) {
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	$records['import_config'] = $_POST;
	$post_values = $uci_admin->GetPostValues(sanitize_key($_REQUEST['eventkey']));
	$result = array_merge($post_values[$_REQUEST['eventkey']], $records);
	$uci_admin->SetPostValues(sanitize_key($_REQUEST['eventkey']), $result);
}
$get_screen_info =  $uci_admin->GetPostValues(sanitize_key($_REQUEST['eventkey']));
$eventkey = sanitize_title($_REQUEST['eventkey']);
if(isset($get_screen_info[$eventkey]['import_config']['handle_duplicate']) && $get_screen_info[$eventkey]['import_config']['handle_duplicate'] == 'Update') {
	$process_of_event = 'Update';
} else {
	$process_of_event = 'Import';
}
$import_type = $get_screen_info[$eventkey]['import_file']['posttype'];
$file = SM_UCI_IMPORT_DIR . '/' . $eventkey . '/' . $eventkey;
$parserObj->parseCSV($file, 0, -1);
//print_r($uci_admin);die;
$total_row_count = $parserObj->total_row_cont - 1;
$get_upload_url = wp_upload_dir();
$uploadLogURL = $get_upload_url['baseurl'] . '/smack_uci_uploads/imports/'. $eventkey . '/' . $eventkey;
$logfilename = $uploadLogURL.".log";
?>
<div class="list-inline pull-right mb10 wp_ultimate_csv_importer_pro">
            <div class="col-md-6"><a href="https://goo.gl/jdPMW8" target="_blank"><?php echo esc_html__('Documentation','wp-ultimate-csv-importer-pro');?></a></div>
            <div class="col-md-6"><a href="https://goo.gl/fKvDxH" target="_blank"><?php echo esc_html__('Sample CSV','wp-ultimate-csv-importer-pro');?></a></div>
         </div>
<div class="template_body whole_body wp_ultimate_csv_importer_pro" style="font-size: 15px; margin-top: 40px;">
<form class="form-inline" method="post">
<div class="col-md-12">
<div class="col-md-12 mt40" style="text-align: center;">
	<input type="button" class="smack-btn smack-btn-primary btn-radius" value="<?php echo esc_attr('Resume','wp-ultimate-csv-importer-pro');?>" style="display:none;" id="continue_import" onclick="continueImport();" >
	<input type="button" class="smack-btn smack-btn-danger btn-radius" value="<?php echo esc_attr('Pause','wp-ultimate-csv-importer-pro');?>" id="terminate_now" onclick="terminateImport()">
	<input type="button" class="smack-btn smack-btn-danger btn-radius" value="<?php echo esc_attr('New Import','wp-ultimate-csv-importer');?>" id="new_import" onclick="reload_to_new_import()" style="display: none;">
</div>
</div>
	<div class="clearfix"></div>
	<div class="event-summary">
		<span class="es-left"> <?php echo esc_html__('File Name:','wp-ultimate-csv-importer-pro');?> <?php echo $get_screen_info[$eventkey]['import_file']['uploaded_name']; ?> </span>
		<span class="es-right"> <?php echo esc_html__('File Size:','wp-ultimate-csv-importer-pro');?> <?php echo $uci_admin->getFileSize($file); ?> </span>
	</div>
	<div class="event-summary">
		<span class="es-left"> <?php echo esc_html__('Process:','wp-ultimate-csv-importer-pro');?> <?php echo $process_of_event; ?> </span>
		<span class="es-right"> <?php echo esc_html__('Total no of records:','wp-ultimate-csv-importer-pro');?> <?php echo $total_row_count; ?> </span>
	</div>
	<div class="event-summary timer">
		<span class="es-left"> <?php echo esc_html__('Time Elapsed:','wp-ultimate-csv-importer-pro');?> </span>
		<span class="es-left" style="padding-left: 10px;">
			<span class="hour">00</span>:<span class="minute">00</span>:<span class="second">00</span>
		</span>
		<span class="es-right" id="remaining" style="padding-right:2px;text-color:red;"> <?php echo esc_html__('Remaining Record:','wp-ultimate-csv-importer-pro');?> </span>

		<span class="es-right" id="current" style = "padding-right:7px;text-color:green;"> <?php echo esc_html__('Current Processing Record:','wp-ultimate-csv-importer-pro');?> </span>

	</div>
	<div class="control" style="display: none;">
		<input type="button" id="smack_uci_timer_start" onClick="timer.start(1000)" value="Start" />
		<input type="button" id="smack_uci_timer_stop" onClick="timer.stop()" value="Stop" />
		<input type="button" id="smack_uci_timer_reset" onClick="timer.reset(60)" value="Reset" />
		<input type="button" id="smack_uci_timer_count_up" onClick="timer.mode(1)" value="Count up"/>
		<input type="button" id="smack_uci_timer_count_down" onClick="timer.mode(0)" value="Count down" />
	</div>
	<div id="logsection" class="seoadv_options">
		<div class="seoadv_options_head"><?php echo esc_html__('Log section','wp-ultimate-csv-importer-pro');?></div>
		<div id="innerlog" class="logcontainer">

		</div>
	</div>
	<span id="dwnld_log_link" style="display:none">
                   <?php if(isset($logfilename))  { ?>
                <a href="<?php echo $logfilename; ?>" download id="dwnldlog" style="margin-left:45px;position:relative;top:-25px;font-size:15px;"> <?php echo esc_html_e("CLICK HERE TO DOWNLOAD LOG","wp-ultimate-csv-importer-pro"); ?></a>
                   <?php } ?>
        </span>
	<input type="hidden" id="eventkey" value="<?php echo sanitize_key($_REQUEST['eventkey']);?>">
	<input type="hidden" id="import_type" value="<?php echo $import_type;?>">
	<input type="hidden" id="importlimit" name="importlimit" value = "1" >
	<input type="hidden" id="currentlimit" name="currentlimit" value = "1" >
	<input type="hidden" id="limit" name="limit" value = "1" >
	<input type="hidden" id="inserted" value="0" >
	<input type="hidden" id="updated" value="0" >
	<input type="hidden" id="skipped" value="0" >
	<input type="hidden" id="totalcount" name="totalcount" value = "<?php echo  $total_row_count;?>">
	<input type="hidden" id="terminate_action" name="terminate_action" value="<?php echo esc_html__('continue','wp-ultimate-csv-importer-pro');?>" />
</form>
</div>
<script>
	jQuery(document).ready(function(e)
	{
		jQuery( "#smack_uci_timer_count_up" ).click();
		jQuery( "#smack_uci_timer_start").click();
	});
	igniteImport();
</script>
