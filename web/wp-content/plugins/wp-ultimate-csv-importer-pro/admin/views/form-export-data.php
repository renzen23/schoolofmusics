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
require_once  SM_UCI_PRO_DIR . "includes/class-uci-exporter.php";
$exporterObj = new SmackUCIExporter();
$module = isset($_POST['export_type']) ? sanitize_text_field($_POST['export_type']) : 'Posts';
$exportAs = '';
if($module === 'CustomPosts') :
	$exportAs = isset($_POST['export_post_type']) ? sanitize_text_field($_POST['export_post_type']) : '';
elseif($module === 'Taxonomies') :
	$exportAs = isset($_POST['export_taxo_type']) ? sanitize_text_field($_POST['export_taxo_type']) : '';
endif;
$exportAs = $uci_admin->import_post_types($module, $exportAs);
if(is_array( $exportAs )) {
	$exportAs = $exportAs[$module];
}
$exportType = 'csv';
?>
<div id='wp_warning' style = 'display:none;' class = 'error'></div>
<?php if(!isset($_REQUEST['exportType'])) { ?>
	<div class="list-inline pull-right mb10 wp_ultimate_csv_importer_pro">
            <div class="col-md-6"><a href="https://goo.gl/jdPMW8" target="_blank"><?php echo esc_html__('Documentation','wp-ultimate-csv-importer-pro');?></a></div>
            <div class="col-md-6"><a href="https://goo.gl/fKvDxH" target="_blank"><?php echo esc_html__('Sample CSV','wp-ultimate-csv-importer-pro');?></a></div>
         </div>
	<form class="form-horizontal" method="post" name="exportmodule" id="exportmodule" action='<?php echo esc_url(add_query_arg(array('page'=> 'sm-uci-export', 'exportType'=>'export-with-filters'), admin_url() . "admin.php"))?>' onsubmit="return export_module();" >
		<div class="template_body whole_body wp_ultimate_csv_importer_pro" xmlns="http://www.w3.org/1999/html" style="margin-top: 40px;">
			<div id="exportaspecificmodule">
				<div class="table-responsive" id="exporttable">
					<table class='table exportmodule'>
						<th colspan='2'><label class='h-exportmodule csv-importer-heading'><h3 id="innertitle"><?php echo esc_html__('Select your module to export the data','wp-ultimate-csv-importer-pro')?> </h3></label></th>
						<tr>
							<td class='exportdatatype'><label> <input type="radio" name="export_type" value="Posts"><span id="align"><?php echo esc_html__('Post','wp-ultimate-csv-importer-pro'); ?></span> </label></td>
							<td class='exportdatatype'><label> <input type="radio" name="export_type" value="eShop"><span id="align"><?php echo esc_html__('Eshop','wp-ultimate-csv-importer-pro'); ?></span> </label></td>
						</tr>
						<tr>
							<td class='exportdatatype'><label> <input type="radio" name="export_type" value="Pages"><span id="align"> <?php echo esc_html__('Page','wp-ultimate-csv-importer-pro'); ?></span> </label></td>
							<td class='exportdatatype'><label> <input type="radio" name="export_type" value="WPeCommerce"><span id="align"> <?php echo esc_html__('Wp-Commerce','wp-ultimate-csv-importer-pro'); ?></span></label></td>
						</tr>
						<tr>
							<td class='exportdatatype'  style="">
								<label> <input type="radio" name="export_type" value="CustomPosts"><span id="align"> <?php echo esc_html__('Custom Post','wp-ultimate-csv-importer-pro'); ?></span></label>
								<select class="search_dropdown_mapping selectpicker"  name="export_post_type" id="export_post_type" style="margin-left:10px;">
									<option><?php echo esc_html__('--Select--', 'wp-ultimate-csv-importer-pro'); ?></option>
									<?php
									foreach (get_post_types() as $key => $value) {
										if (($value !== 'featured_image') && ($value !== 'attachment') && ($value !== 'wpsc-product') && ($value !== 'wpsc-product-file') && ($value !== 'revision') && ($value !== 'nav_menu_item') && ($value !== 'post') && ($value !== 'page') && ($value !== 'wp-types-group') && ($value !== 'wp-types-user-group') && ($value !== 'product') && ($value !== 'product_variation') && ($value !== 'shop_order') && ($value !== 'shop_coupon') && ($value !== 'acf') && ($value !== 'acf-field') && ($value !== 'acf-field-group') && ($value !== '_pods_pod') && ($value !== '_pods_field') && ($value !== 'shop_order_refund') && ($value !== 'shop_webhook')) {
											?>
											<option id="<?php echo($value); ?>"> <?php echo($value); ?> </option>
											<?php
										}
									}
									?>
								</select>
							</td>
							<td class='exportdatatype'><label><input type="radio" name="export_type" value="WooCommerce"><span id="align"> <?php echo esc_html__('Woo-Commerce','wp-ultimate-csv-importer-pro'); ?></span></label></td>
						</tr>
						<tr>
							<td class='exportdatatype'><label><input type="radio" name="export_type" value="Categories"><span id="align"> <?php echo esc_html__('Category','wp-ultimate-csv-importer-pro'); ?></span></label></td>
							<td class='exportdatatype'><label><input type="radio" name="export_type" value="MarketPress"><span id="align"> <?php echo esc_html__('Marketpress','wp-ultimate-csv-importer-pro'); ?></span></label></td>
						</tr>
						<tr>
							<td class='exportdatatype'><label><input type="radio" id="tags_id" name="export_type" value="Tags"><span id="align"> <?php echo esc_html__('Tags','wp-ultimate-csv-importer-pro'); ?> </label></span></td>
							<td class='exportdatatype'><label> <input type="radio" name="export_type" value="CustomerReviews"><span id="align"> <?php echo esc_html__('Customer Reviews','wp-ultimate-csv-importer-pro'); ?></span></label></td>
						</tr>
						<tr>
							<td class='exportdatatype' style="">
								<label> <input type="radio" name="export_type" value="Taxonomies"><span id="align"> <?php echo esc_html__('Taxonomies','wp-ultimate-csv-importer-pro'); ?></span></label>
								<select class="search_dropdown_mapping selectpicker" name="export_taxo_type" id="export_taxo_type" style="margin-left:10px;">
									<option><?php echo esc_html__('--Select--','wp-ultimate-csv-importer-pro'); ?></option>
									<?php
									foreach (get_taxonomies() as $key => $value) {
										if (($value !== 'category') && ($value !== 'post_tag') && ($value !== 'nav_menu') && ($value !== 'link_category') && ($value !== 'post_format') && ($value !== 'product_tag') && ($value !== 'wpsc_product_category') && ($value !== 'wpsc-variation')) {
											?>
											<option id="<?php echo($value); ?>"> <?php echo($value); ?> </option>
											<?php
										}
									}
									?>
								</select></td>
							<td class='exportdatatype'><label> <input type="radio" name="export_type" value="Comments"><span id="align"> <?php echo esc_html__('Comments','wp-ultimate-csv-importer-pro'); ?></span></label></td>
						</tr>
						<tr>
							<td class='exportdatatype'><label> <input type="radio" name="export_type" value="Users"><span id="align"> <?php echo esc_html__('Users','wp-ultimate-csv-importer-pro'); ?></span></label></td>
							<td class='exportdatatype'></td>
						</tr>
					</table>
					<div class='' style="padding: 15px;float: right;"><input type="submit" name="proceedtoexclusion" value="<?php echo esc_html__('Proceed','wp-ultimate-csv-importer-pro');?>" class='smack-btn smack-btn-primary btn-radius'></div>
				</div>
			</div>
	</form>
	</div>
<?php } ?>

<?php $exportType = isset($_REQUEST['exportType']) ? sanitize_text_field($_REQUEST['exportType']) : '' ;
if($exportType) :
	?>
	<div style="width: 98%;" class="template_body whole_body wp_ultimate_csv_importer_pro">
		<form name="export_filters" id="export_filters" method="post" action="<?php #echo admin_url('admin-ajax.php'); ?>" >
			<?php wp_nonce_field('export_file','my-nonce'); ?>
			<input name="action" value="export_file" type="hidden">
			<input type="hidden" name="moduletobeexport" id="moduletobeexport" value="<?php echo $module; ?>" >
			<input type="hidden" name="optional_type" id="optional_type" value="<?php echo $exportAs; ?>" >
			<input type="hidden" name="offset" id="offset" value="0" >
			<input type="hidden" name="limit" id="limit" value="1000" >
			<input type="hidden" name="total_row_count" id="total_row_count" value="" >
			<p>
			<div class="csv-importer-heading"><h4 class="media_styles"><?php echo esc_html__('To export data based on the filters','wp-ultimate-csv-importer-pro'); ?></h4></div>
			<p>
				<label class="media_styles"><?php echo esc_html__('Export File Name:','wp-ultimate-csv-importer-pro');?></label>
				<input class='form-control' type='text' name='export_filename' id='export_filename' value='' placeholder="export_as_<?php echo(date("Y-m-d")); ?>" size="18" style="width:50% !important;">
				<a id="download_file_link" href="" target="_blank" class="col-sm-4" style="margin-top: -35px; float: right; display: none;"> <input type="button" name="download_file" id="download_file" class="smack-btn smack-btn-primary btn-radius" style="display: none;" value="Download"></a>
			</p>
			<label class="media_styles">
			<input type='checkbox'   name='getdatawithdelimiter' id='getdatawithdelimiter' value='getdatawithdelimiter' onclick='addexportfilter(this.id);' /><span id="align"> <?php echo esc_html__('Export data with auto delimiters','wp-ultimate-csv-importer-pro');?></span></label>
			
			<div id='delimiterstatus' class="col-md-12" style='padding:15px;display:none;'>
				<div class="col-md-5 col-md-offset-1">
					<label class="export_label"> <?php echo esc_html__('Delimiters','wp-ultimate-csv-importer-pro');?> </label><br>
					<select class="selectpicker" name='postwithdelimiter' id='postwithdelimiter' class="search_dropdown_mapping selectpicker">
						<option><?php echo esc_html__('Select','wp-ultimate-csv-importer-pro');?></option>
						<option><?php echo esc_html__(',','wp-ultimate-csv-importer-pro');?></option>
						<option><?php echo esc_html__(':','wp-ultimate-csv-importer-pro');?></option>
						<option><?php echo esc_html__(';','wp-ultimate-csv-importer-pro');?></option>
						<option><?php echo esc_html__('{Tab}','wp-ultimate-csv-importer-pro');?></option>
						<option><?php echo esc_html__('{Space}','wp-ultimate-csv-importer-pro');?></option>
					</select></div>
					<div class="col-md-5"><label class="export_label"><?php echo esc_html__('Other Delimiters','wp-ultimate-csv-importer-pro');?></label> <input type = 'text' class="form-control" name='other_delimiter' id ='other_delimiter' style="width:75% !important;" size=6>
				</div>
			</div>

			</p>
			<?php
			$disable_export_option = isset($_POST['export_type']) ? sanitize_text_field($_POST['export_type']) : '';
			if($disable_export_option == 'Tags' || $disable_export_option == 'Categories' || $disable_export_option == 'Taxonomies' || $disable_export_option == 'CustomerReviews' || $disable_export_option == 'Comments' || $disable_export_option == 'Users'){
				$disabled = 'disabled';
			}
			else {
				$disabled = '';
			}
			?>
			<p>
				<label class="media_styles"><input type='checkbox'  name='getdataforspecificperiod' id='getdataforspecificperiod' value='getdataforspecificperiod' onclick='addexportfilter(this.id);' <?php echo $disabled ?> /><span id="align"> <?php echo esc_html__('Export data for the specific period','wp-ultimate-csv-importer-pro');?></span></label>
			<div id='specificperiodexport' class="col-md-12" style='padding:10px;display:none;'>
				<div class="col-md-5 col-md-offset-1"><b><label class="export_label"> <?php echo esc_html__('Start From','wp-ultimate-csv-importer-pro');?></label> </b> <input type='text' class='form-control' name='postdatefrom' readonly="readonly" style='cursor:default;width:75% !important;' id='postdatefrom' value='' onchange='validateDateIntervals();' /></div>
				<div class="col-md-5"><b><label class="export_label"><?php echo esc_html__('End To','wp-ultimate-csv-importer-pro');?> </label></b> <input type='text' class='form-control' name='postdateto' readonly="readonly" style='cursor:default;width:75% !important;' id='postdateto' value='' onchange='validateDateIntervals();'/>
				<input type='hidden' name='nonce' id='nonce' value='<?php if(isset($noncedata)) { echo $noncedata; } ?>'></div>
			</div>
			</p>
			<?php if($exportType !== 'users' && $exportType !== 'categories' && $exportType !== 'tags' && $exportType !== 'customtaxonomy' && $exportType !== 'customerreviews' && $exportType !== 'comments') { ?>
				<p>
					<label class="media_styles">

					<input type='checkbox'  name='getdatawithspecificstatus' id='getdatawithspecificstatus' value='getdatawithspecificstatus' onclick='addexportfilter(this.id);' <?php echo $disabled ?> /><span id="align"> <?php echo esc_html__('Export data with the specific status','wp-ultimate-csv-importer-pro');?></span></label>
				<div id='specificstatusexport' class="col-md-12" style='padding:15px;display:none;'>
					<div class="col-md-2 col-md-offset-1">
						<label class="export_label"> <?php echo esc_html__('Status','wp-ultimate-csv-importer-pro'); ?> </label></div>
						<div class=""><select name='specific_status' id='specific_status' class="search_dropdown_mapping selectpicker">
							<option><?php echo esc_html__('All','wp-ultimate-csv-importer-pro'); ?></option>
							<option><?php echo esc_html__('Publish','wp-ultimate-csv-importer-pro'); ?></option>
							<option><?php echo esc_html__('Sticky','wp-ultimate-csv-importer-pro'); ?></option>
							<option><?php echo esc_html__('Private','wp-ultimate-csv-importer-pro'); ?></option>
							<option><?php echo esc_html__('Protected','wp-ultimate-csv-importer-pro'); ?></option>
							<option><?php echo esc_html__('Draft','wp-ultimate-csv-importer-pro'); ?></option>
							<option><?php echo esc_html__('Pending','wp-ultimate-csv-importer-pro'); ?></option>
						</select>
					</div>
				</div>
				</p>
			<?php } ?>
			<?php if($exportType !== 'users' && $exportType !== 'categories' && $exportType !== 'tags' && $exportType !== 'customtaxonomy' && $exportType !== 'customerreviews') { ?>
				<p>
					<label class="media_styles"><input  type='checkbox' name='getdatabyspecificauthors' id='getdatabyspecificauthors' value='getdatabyspecificauthors' onclick='addexportfilter(this.id);' <?php echo $disabled ?>/><span id="align"> <?php echo esc_html__('Export data by specific authors','wp-ultimate-csv-importer-pro');?></span></label>
				<div id='specificauthorexport' class="col-md-12" style='padding:15px;display:none;'>
					<div class="col-md-2 col-md-offset-1">
						<label class="export_label"> <?php echo esc_html__('Authors','wp-ultimate-csv-importer-pro'); ?> </label></div>
						<div><?php $blogusers = get_users( 'blog_id=1&orderby=nicename' ); #TODO: Need to change the blog id based on the blog. ?>
						<select name='specific_authors' id='specific_authors' class="search_dropdown_mapping selectpicker">
							<option value='0'><?php echo esc_html__('All','wp-ultimate-csv-importer-pro'); ?></option>
							<?php foreach( $blogusers as $user ) { ?>
								<option value='<?php echo esc_html( $user->ID ); ?>'> <?php echo esc_html( $user->display_name ); ?> </option>
							<?php } ?>
						</select>
					</div>
				</div>
				</p>
			<?php } ?>
			<p>
				<label class="media_styles"><input type='checkbox' name='getdatabasedonexclusions' id='getdatabasedonexclusions' value='getdatabasedonexclusions' onclick='addexportfilter(this.id);' /><span id="align"> <?php echo esc_html__('Export data based on specific exclusions','wp-ultimate-csv-importer-pro');?> </span></label>
			<div id="exclusiongrouplist" style="display:none;">
				<?php
				$shortLabel = $shortName = '';
				$node = 1;
				$exportData = $exporterObj->exportData( $module, $exportType, $exportAs, '', array(), '', '' );
				// Get the headers for the export feature
				$integrations = $uci_admin->available_widgets( $module, $exportAs );
				if ( ! empty( $integrations ) ) :
					foreach ( $integrations as $widget_name => $plugin_file ) {
						$widget_slug = strtolower(str_replace(' ', '_', $widget_name));
						$fields = $uci_admin->get_widget_fields( $widget_name, $module, $exportAs ); ?>
						<div class="panel-group" id='accordion' style = "width:98.3%;margin-top:-5px;padding-bottom: 20px;">
							<div class='panel panel-default' data-target="#<?php echo $widget_slug;?>" data-parent="#accordion">
								<div class='panel-heading' style='width:100%'  onclick="toggle_func('<?php echo $widget_slug;?>');">
									<div id= "corehead" class="panel-title"> <b style=""> <?php echo $widget_name ?> </b>
										<span class = 'glyphicon glyphicon-plus' id = '<?php echo $widget_slug ?>' style="float:right;"> </span>
									</div>
								</div>
								<div id='<?php echo $widget_slug;?>toggle' style="height:auto;">
									<div class="grouptitlecontent " id="corefields_content">
										<?php
										foreach ($fields as $groupName => $fieldInfo) {
											if(is_array($fieldInfo) && !empty($fieldInfo)) {
												$fields_count = count($fieldInfo);
												$set_row_count = ceil( $fields_count / 4 );
												$i=1; $j=1;
												echo "<div style='padding: 10px 10px 60px 10px;'>";
												foreach ( $fieldInfo as $fKey => $fVal ) {
													if ( strlen( $fVal['label'] ) > 26 ) {
														$shortLabel = substr( $fVal['label'], 0, 25 ) . '..';
													} else {
														$shortLabel = $fVal['label'];
													}
													if ( strlen( $fVal['name'] ) > 20 ) {
														$shortName = substr( $fVal['name'], 0, 19 ) . '..';
													} else {
														$shortName = $fVal['name'];
													}
													echo "<span class='col-sm-3 exclusion-list'><label title = '{$fVal['label']}'><input type='checkbox' class='TYPES_class' name='".$fVal['name']."' id='column".$node."' onclick='exportexclusion(this.name, this.id);' />" . $shortLabel . "</label><label title = '{$fVal['name']}' class = 'samptxt'>[ " . $shortName . " ]</label></span>";
													if(ceil($i % 4) == 0) {
														if($j <= $set_row_count) {
															echo "</div><div style='padding: 10px 10px 60px 10px;'>";
														} else {
															echo "</div>";
														}
														$j++;
													}
													$i++;
													$node++;
												}
												#die;
												if($j >= $set_row_count)
													echo "</div>";
												if($exportType === 'users'){
													$set_group_height = $set_row_count * 45;
													$set_group_height = "$set_group_height" +"100"."px";
												}
												else {
													$set_group_height = $set_row_count * 35;
													#$set_group_height = "$set_group_height" +"100"."px";
												}
											} else {
												echo "<p style='color:red;text-align:center;padding:20px;'>"; echo esc_html__('No fields Found!','wp-ultimate-csv-importer-pro'); echo "</p>";
												$set_group_height = 'auto';
											}
										}
										?>
									</div>
								</div>
								<script type="text/javascript">
									document.getElementById('<?php echo $widget_slug;?>toggle').style.height = '<?php echo $set_group_height; ?>';
								</script>
							</div>
						</div>
					<?php }
				endif;
				?>
			</div>
			</p>
			<script type = 'text/javascript'>
				jQuery(document).ready(function() {
					jQuery('#postdatefrom').datepicker({
						dateFormat : 'yy-mm-dd'
					});
					jQuery('#postdateto').datepicker({
						dateFormat : 'yy-mm-dd'
					});
				});
			</script>
			<div class="col-md-12 mt15">
			<div class="pull-left"><input name="backtomodulechooser" id="backtomodulechooser" value="<?php echo esc_attr__('Back','wp-ultimate-csv-importer-pro');?>" class="smack-btn btn-default btn-radius" style="" onclick="window.location.href = '<?php echo esc_url(admin_url() . 'admin.php?page=sm-uci-export'); ?>'" type="button"></div>
			<div class="pull-right mb15"><input name="proceed_to_export" id="proceed_to_export" value="<?php echo esc_attr__('Export','wp-ultimate-csv-importer-pro');?>" class="smack-btn smack-btn-primary btn-radius export_continuebtn" onclick="igniteExport();" type="button"></div></div><div class="clearfix"></div>
		</form>
	</div>
<?php endif; ?>
<script>
	jQuery(function(){
		var current_effect = jQuery('#waitMe_ex_effect').val();
		jQuery('#proceed_to_export').click(function(){
			var fileName = jQuery('#export_filename').val();
			if( fileName == '')
			return false;
			run_waitMe(current_effect);
		});
		jQuery('#waitMe_ex_close').click(function(){
			jQuery('#wpwrap').waitMe('hide');
		});
		jQuery('#waitMe_ex_effect').change(function(){
			current_effect = jQuery(this).val();
			run_waitMe(current_effect);
		});
		jQuery('#waitMe_ex_effect').click(function(){
			current_effect = jQuery(this).val();
		});
		function run_waitMe(effect){
			jQuery('#wpwrap').waitMe({
				effect: effect,
				text: 'Please wait, Your export is in progress...',
				bg: 'rgba(255,255,255,0.7)',
				color: '#47A447',
				maxSize: '',
				source: 'img.svg',
				onClose: function() {}
			});
		}
		var current_body_effect = jQuery('#waitMe_ex_body_effect').val();
		jQuery('#waitMe_ex_body').click(function(){
			run_waitMe_body(current_body_effect);
		});
		jQuery('#waitMe_ex_body_effect').change(function(){
			current_body_effect = jQuery(this).val();
			run_waitMe_body(current_body_effect);
		});
		function run_waitMe_body(effect){
			jQuery('body').addClass('waitMe_body');
			var img = '';
			var text = '';
			if(effect == 'img'){
				img = 'background:url(\'img.svg\')';
			} else if(effect == 'text'){
				text = 'Loading...';
			}
			var elem = jQuery('<div class="waitMe_container ' + effect + '"><div style="' + img + '">' + text + '</div></div>');
			jQuery('body').prepend(elem);
			setTimeout(function(){
				jQuery('body.waitMe_body').addClass('hideMe');
				setTimeout(function(){
					jQuery('body.waitMe_body').find('.waitMe_container:not([data-waitme_id])').remove();
					jQuery('body.waitMe_body').removeClass('waitMe_body hideMe');
				},200);
			},4000);
		}
	});
	jQuery('#postdatefrom').datepicker({
		format: 'yyyy-mm-dd',
	});
	jQuery('#postdateto').datepicker({
		format: 'yyyy-mm-dd',
	});
</script>
