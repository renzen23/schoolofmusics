<?php
/******************************************************************************************
 * Copyright (C) Smackcoders. - All Rights Reserved under Smackcoders Proprietary License
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly
global $wp_version, $wpdb;
$ucisettings = get_option('sm_uci_pro_settings');
$ucioptimize = get_option('sm_uci_pro_optimization');
$droptable = isset($ucisettings['drop_table']) ? $ucisettings['drop_table'] : '';
$schedule_mail = isset($ucisettings['send_log_email']) ? $ucisettings['send_log_email'] : '';
$send_password = isset($ucisettings['send_user_password']) ? $ucisettings['send_user_password'] : '';
$woocomattr = isset($ucisettings['woocomattr']) ? $ucisettings['woocomattr'] : '';
$author_editor_access = isset($ucisettings['author_editor_access']) ? $ucisettings['author_editor_access'] : '';
if(!empty($droptable)){
    if($droptable == 'on'){
        $data['drop_on'] = 'enablesetting';
        $data['drop_off'] = 'disablesetting';
        $data['dropon_status'] = 'checked';
        $data['dropoff_status'] = '';
        $droptable = "checked='checked'";
    }else{
        $data['drop_off'] = 'enablesetting';
        $data['drop_on'] = 'disablesetting';
        $data['dropon_status'] = '';
        $data['dropoff_status'] = 'checked';
        $droptable = "";
    }
}
if(!empty($schedule_mail)){
    if($schedule_mail == 'on'){
        $data['mail_on'] = 'enablesetting';
        $data['mail_off'] = 'disablesetting';
        $data['mailon_status'] = 'checked';
        $data['mailoff_status'] = '';
        $schedule_mail = "checked='checked'";
    } else {
        $data['mail_off'] = 'enablesetting';
        $data['mail_on'] = 'disablesetting';
        $data['mailon_status'] = '';
        $data['mailoff_status'] = 'checked';
        $schedule_mail = "";
    }
}
if(!empty($send_password)){
    if($send_password == 'on'){
        $data['mail_on'] = 'enablesetting';
        $data['mail_off'] = 'disablesetting';
        $data['mailon_status'] = 'checked';
        $data['mailoff_status'] = '';
        $send_password = "checked='checked'";
    } else {
        $data['mail_off'] = 'enablesetting';
        $data['mail_on'] = 'disablesetting';
        $data['mailon_status'] = '';
        $data['mailoff_status'] = 'checked';
        $send_password = "";
    }
}
if(!empty($woocomattr)){
    if($woocomattr == 'on'){
        $data['wooattr_on'] = 'enablesetting';
        $data['wooattr_off'] = 'disablesetting';
        $data['wooon_status'] = 'checked';
        $data['woooff_status'] = '';
        $woocomattr = "checked='checked'";
    }else{
        $data['wooattr_off'] = 'enablesetting';
        $data['wooattr_on'] = 'disablesetting';
        $data['wooon_status'] = '';
        $data['woooff_status'] = 'checked';
        $woocomattr = "";
    }
}
if(!empty($author_editor_access)){
    if($author_editor_access == 'on'){
        $data['access_on'] = 'enablesetting';
        $data['access_off'] = 'disablesetting';
        $data['accesson_status'] = 'checked';
        $data['accessoff_status'] = '';
        $author_editor_access = "checked='checked'";
    }else{
        $data['access_off'] = 'enablesetting';
        $data['access_on'] = 'disablesetting';
        $data['accesson_status'] = '';
        $data['accessoff_status'] = 'checked';
        $author_editor_access = "";
    }
}
//database optimization
if(isset($ucioptimize['delete_all_orphaned_post_page_meta'])) {
    $delete_all_post_page = $ucioptimize['delete_all_orphaned_post_page_meta'];
} else {
    $delete_all_post_page = '';
}
if(isset($ucioptimize['delete_all_unassigned_tags'])) {
    $delete_all_unassigned_tag = $ucioptimize['delete_all_unassigned_tags'];
} else {
    $delete_all_unassigned_tag = '';
}
if(isset($ucioptimize['delete_all_post_page_revisions'])) {
    $delete_all_page_revisions = $ucioptimize['delete_all_post_page_revisions'];
} else {
    $delete_all_page_revisions = '';
}
if(isset($ucioptimize['delete_all_auto_draft_post_page'])) {
    $delete_all_auto_draft_page = $ucioptimize['delete_all_auto_draft_post_page'];
} else {
    $delete_all_auto_draft_page = '';
}
if(isset($ucioptimize['delete_all_post_page_in_trash'])) {
    $delete_all_post_page_trash = $ucioptimize['delete_all_post_page_in_trash'];
} else {
    $delete_all_post_page_trash = '';
}
if(isset($ucioptimize['delete_all_spam_comments'])) {
    $delete_all_spam_comments = $ucioptimize['delete_all_spam_comments'];
} else {
    $delete_all_spam_comments = '';
}
if(isset($ucioptimize['delete_all_comments_in_trash'])) {
    $delete_all_comments_trash = $ucioptimize['delete_all_comments_in_trash'];
} else {
    $delete_all_comments_trash = '';
}
if(isset($ucioptimize['delete_all_unapproved_comments'])) {
    $delete_all_unapproved_comments = $ucioptimize['delete_all_unapproved_comments'];
} else {
    $delete_all_unapproved_comments = '';
}
if(isset($ucioptimize['delete_all_pingback_commments'])) {
    $delete_all_pingback_comments = $ucioptimize['delete_all_pingback_commments'];
} else {
    $delete_all_pingback_comments = '';
}
if(isset($ucioptimize['delete_all_trackback_comments'])) {
    $delete_all_trackback_comments = $ucioptimize['delete_all_trackback_comments'];
} else {
    $delete_all_trackback_comments = '';
}
?>
<div class="list-inline pull-right mb10 wp_ultimate_csv_importer_pro">
            <div class="col-md-6"><a href="https://goo.gl/jdPMW8" target="_blank"><?php echo esc_html__('Documentation','wp-ultimate-csv-importer-pro');?></a></div>
            <div class="col-md-6"><a href="https://goo.gl/fKvDxH" target="_blank"><?php echo esc_html__('Sample CSV','wp-ultimate-csv-importer-pro');?></a></div>
         </div>
<div class="whole_body wp_ultimate_csv_importer_pro" style="margin-top: 40px;">
    <form id="form_import_file">
        <div class="import_holder" id="import_holder" >
            <div class="panel " style="width: 99%;">
                <div id="warningsec" style="color:red;width:100%; min-height: 110px;border: 1px solid #d1d1d1;background-color:#fff;display:none;">
                    <div id ="warning" class="display-warning" style="color:red;align:center;display:inline;font-weight:bold;font-size:15px; border: 1px solid red;margin:2% 2%;padding: 20px 0 20px;position: absolute;text-align: center;width:93%;display:none;"> </div>
                </div>
                <div class="panel-body no-padding">
                    <div style="height:300px;" class="col-md-3 setting-manager-list no-padding" id="left_sidebar">
                        <ul id="example">
                            <li id='1' class="bg-leftside selected right-arrow" onclick="settings_div_selection(this.id);">
                                <span class=" icon-settings2"></span>
                                <span><?php echo esc_html__('General Settings','wp-ultimate-csv-importer-pro');?></span>
                            </li>
                            <li id='2' class="bg-leftside" onclick="settings_div_selection(this.id);">
                                <span class="icon-database" style="margin-top: -10px;"></span>
                                <span><?php echo esc_html__('Database optimization','wp-ultimate-csv-importer-pro');?></span>
                            </li>
                            <li id='3' class="bg-leftside" onclick="settings_div_selection(this.id);">
                                <span class="icon-lock4" style="margin-top: -10px;"></span>
                                <span><?php echo esc_html__('Security and Performance','wp-ultimate-csv-importer-pro');?></span>
                            </li>
                            <li id='4'  class="bg-leftside" onclick="settings_div_selection(this.id);">
                                <span class="icon-document-movie2" style="font-size: 1.4em; margin-top: -10px;"></span>
                                <span><?php echo esc_html__('Documentation','wp-ultimate-csv-importer-pro');?></span>
                            </li>
                        </ul>
                    </div>
                    <div  class="col-md-9" id="rightside_content">
                        <div id="division1">

                            <h3 class="csv-importer-heading"><?php echo esc_html_e('General Settings','wp-ultimate-csv-importer-pro'); ?></h3>
                            <div class="col-md-11 col-md-offset-1 mt20 mb40">
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-8 col-md-8  nopadding">
                                        <h4 ><?php echo esc_html_e('Drop Table','wp-ultimate-csv-importer-pro'); ?></h4>
                                        <p><?php echo esc_html_e('If enabled plugin deactivation will remove plugin data, this cannot be restored.','wp-ultimate-csv-importer-pro'); ?></p>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="mt20">
                                            <!-- Drop Table button -->
                                            <input id="drop_table" type='checkbox' class="tgl tgl-skewed noicheck" name='drop_table' id='download_imgon' <?php echo $droptable; ?> style="display:none" onclick="saveoptions(this.id, this.name);" />
                                            <label data-tg-off="NO" data-tg-on="YES" for="drop_table" id="download_on" class="tgl-btn" style="font-size: 16px;" >
                                            </label>
                                            <!-- Drop Table btn End -->
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group mt20">
                                    <div class="col-xs-12 col-sm-8 col-md-8  nopadding">
                                        <h4><?php echo esc_html_e('Scheduled log mails','wp-ultimate-csv-importer-pro'); ?></h4>
                                        <p><?php echo esc_html_e('Enable to get scheduled log mails.','wp-ultimate-csv-importer-pro'); ?></p>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="mt20">
                                            <!-- Scheduled log button -->

                                            <input id="send_log_email" type='checkbox' class="tgl tgl-skewed noicheck" name='send_log_email' <?php echo $schedule_mail; ?>  style="display:none" onclick="saveoptions(this.id, this.name);" />
                                            <label data-tg-off="NO" data-tg-on="YES" for="send_log_email" id="download_on" class="tgl-btn" style="font-size: 16px;" >
                                            </label>
                                            <!-- Scheduled log btn End -->
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group mt20">
                                    <div class="col-xs-12 col-sm-8 col-md-8  nopadding">
                                        <h4><?php echo esc_html_e('Send password to user','wp-ultimate-csv-importer-pro'); ?></h4>
                                        <p><?php echo esc_html_e('Enable to send password information through email.','wp-ultimate-csv-importer-pro'); ?></p>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        <div class="mt20">
                                            <!-- Scheduled log button -->
                                            <input id="send_user_password" type='checkbox' class="tgl tgl-skewed noicheck" name='send_user_password' <?php echo $send_password; ?> style="display:none" onclick="saveoptions(this.id, this.name);" />
                                            <label data-tg-off="NO" data-tg-on="YES" for="send_user_password" id="download_on" class="tgl-btn" style="font-size: 16px;" >
                                            </label>
                                            <!-- Scheduled log btn End -->
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group mt20">
                                    <div class="col-xs-12 col-sm-8 col-md-8  nopadding">
                                        <h4 ><?php echo esc_html_e('Woocommerce Custom attribute','wp-ultimate-csv-importer-pro'); ?></h4>
                                        <p><?php echo esc_html_e('Enables to register woocommrce custom attribute.','wp-ultimate-csv-importer-pro'); ?></p>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 mb20">
                                        <div class="mt20">
                                            <!-- Scheduled log button -->
                                            <input id="woocomattr" type='checkbox' class="tgl tgl-skewed noicheck" name='woocomattr' id='download_imgon' <?php echo $woocomattr; ?> style="display:none" onclick="saveoptions(this.id, this.name);" />
                                            <label data-tg-off="NO" data-tg-on="YES" for="woocomattr" id="download_on" class="tgl-btn" style="font-size: 16px;" >
                                            </label>
                                            <!-- Scheduled log btn End -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="division2" style="display:none;">
                            <h3 class="csv-importer-heading"><?php echo esc_html_e('Database Optimization','wp-ultimate-csv-importer-pro'); ?></h3>
                        </br>
                            <div class="" style="color: red; font-size: 15px;">Please make sure that you take necessary backup before proceeding with database optimization. The data lost can't be reverted.</div>
			                <div class="col-md-12 mt30 ">
                                <div class="col-sm-6 col-md-6">
                                    <ul class="database-optimization">
                                        <li>
                                            <label id="dblabel">
                                                <input type='checkbox' name='delete_all_orphaned_post_page_meta' id='delete_all_orphaned_post_page_meta' value='delete_all_orphaned_post_page_meta' <?php echo $delete_all_page_revisions; ?> onclick='database_optimization_settings(this.id);'  />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all orphaned Post/Page Meta','wp-ultimate-csv-importer-pro'); ?></span></td></label></li>
                                        <li>
                                            <label id="dblabel">
                                                <input type='checkbox' name='delete_all_unassigned_tags' id='delete_all_unassigned_tags' value='delete_all_unassigned_tags' <?php echo $delete_all_auto_draft_page; ?> onclick='database_optimization_settings(this.id);' />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all unassigned tags','wp-ultimate-csv-importer-pro'); ?></span></td></label>
                                        </li>
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_post_page_revisions' id='delete_all_post_page_revisions' value='delete_all_post_page_revisions' <?php echo $delete_all_page_revisions; ?> onclick='database_optimization_settings(this.id);'  />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all Post/Page revisions','wp-ultimate-csv-importer-pro'); ?></span></td></label>
                                        </li>
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_auto_draft_post_page' id='delete_all_auto_draft_post_page' value='delete_all_auto_draft_post_page' <?php echo $delete_all_auto_draft_page; ?> onclick='database_optimization_settings(this.id);' />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all auto drafted Post/Page','wp-ultimate-csv-importer-pro'); ?></span></td></label>
                                        </li>
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_post_page_in_trash' id='delete_all_post_page_in_trash' value='delete_all_post_page_in_trash' <?php echo $delete_all_post_page_trash; ?> onclick='database_optimization_settings(this.id);' />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all Post/Page in trash','wp-ultimate-csv-importer-pro'); ?></span></td></label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <ul class="database-optimization">
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_comments_in_trash' id='delete_all_comments_in_trash' value='delete_all_comments_in_trash'  <?php echo $delete_all_comments_trash; ?> onclick='database_optimization_settings(this.id);'  />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all Comments in trash','wp-ultimate-csv-importer-pro'); ?></span></td></label></li>
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_unapproved_comments' id='delete_all_unapproved_comments' value='delete_all_unapproved_comments'  <?php echo $delete_all_unapproved_comments; ?> onclick='database_optimization_settings(this.id);' />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all Unapproved Comments','wp-ultimate-csv-importer-pro'); ?></span></td></label></li>
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_pingback_commments' id='delete_all_pingback_commments' value='delete_all_pingback_commments'  <?php echo $delete_all_pingback_comments; ?> onclick='database_optimization_settings(this.id);' />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all Pingback Comments','wp-ultimate-csv-importer-pro'); ?></span></td></label></li>
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_trackback_comments' id='delete_all_trackback_comments' value='delete_all_trackback_comments'  <?php echo $delete_all_trackback_comments; ?> onclick='database_optimization_settings(this.id);' />
                                                <td> <span id="align"> <?php echo esc_html_e('Delete all Trackback Comments','wp-ultimate-csv-importer-pro'); ?></span></td></label></li>
                                        <li><label id="dblabel">
                                                <input type='checkbox' name='delete_all_spam_comments' id='delete_all_spam_comments' value='delete_all_spam_comments' <?php echo $delete_all_spam_comments; ?> onclick='database_optimization_settings(this.id);' />
                                                <td><span id="align"> <?php echo esc_html_e('Delete all Spam Comments','wp-ultimate-csv-importer-pro'); ?></span></td></label></li>
                                    </ul>
                                </div>
                            </div>

                            <div id ='divdata'>
                                <div  style="float:right;padding:17px;margin-top:-2px;">
                                    <input id="database_optimization" data-toggle="modal" data-target=".myModals" class="action smack-btn smack-btn-warning btn-radius"  type="button" onclick="databaseoptimization();" value="<?php echo __('Run DB Optimizer','wp-ultimate-csv-importer-pro'); ?>" name="database_optimization">
                                </div>
                                <div class="modal animated zoomIn myModals col-md-6 col-md-offset-1" role="dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Database Optimization Log</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="optimizelog"  style="display:none;">
                                                <!-- <h4><?php echo esc_html_e('Database Optimization Log','wp-ultimate-csv-importer-pro'); ?></h4>-->
                                                <div id="optimizationlog modal" class="optimizerlog">
                                                    <div id="log" class="log">
                                                        <p style="margin:15px;color:red;" id="align"><?php echo esc_html_e('NO LOGS YET NOW.','wp-ultimate-csv-importer-pro'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="division3" style="display:none;">
                            <h3 class="csv-importer-heading">
                                <?php echo esc_html_e('Security and Performance','wp-ultimate-csv-importer-pro'); ?>
                            </h3>
                            <div style="margin-left: 50px; margin-top: 20px;">
                                <!-- Allow/author-editor import start-->
                                <table class="securityfeatures" style="width: 100%">
                                    <tr>
                                        <td>
                                            <h4><?php echo esc_html_e('Allow authors/editors to import','wp-ultimate-csv-importer-pro'); ?></h4>
                                            <p><?php echo esc_html_e('This enables authors/editors to import.','wp-ultimate-csv-importer-pro'); ?></p>
                                        </td>
                                        <td id='divtd'>
                                            <div class="col-xs-12 col-sm-4 col-md-8 mb15">
                                                <div class="mt20">
                                                    <!-- Scheduled log button -->

                                                    <input id="author_editor_access" type='checkbox' class="tgl tgl-skewed noicheck" name='author_editor_access' <?php echo $author_editor_access; ?>  style="display:none" onclick="saveoptions(this.id, this.name);" />
                                                    <label data-tg-off="NO" data-tg-on="YES" for="author_editor_access" id="enableimport" class="tgl-btn" style="font-size: 16px;" >
                                                    </label>
                                                    <!-- Scheduled log btn End -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Allow/author-editor import end-->
                                <!-- Max/Min required start-->
                                <table class="table table-striped">
                                    <tr>
                                        <th colspan="3" >
                                            <h4 class="text-danger" ><?php echo esc_html_e('Minimum required php.ini values (Ini configured values)','wp-ultimate-csv-importer-pro'); ?></h4 >
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label><?php echo esc_html_e('Variables','wp-ultimate-csv-importer-pro'); ?></label>
                                        </th>
                                        <th class='ini-configured-values'>
                                            <label><?php echo esc_html_e('System values','wp-ultimate-csv-importer-pro'); ?></label>
                                        </th>
                                        <th class='min-requirement-values'>
                                            <label><?php echo esc_html_e('Minimum Requirements','wp-ultimate-csv-importer-pro'); ?></label>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('post_max_size','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('post_max_size') ?></td>
                                        <td class='min-requirement-values'>10M</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('auto_append_file','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td class='ini-configured-values'>- <?php echo ini_get('auto_append_file') ?></td>
                                        <td class='min-requirement-values'>-</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('auto_prepend_file','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'>- <?php echo ini_get('auto_prepend_file') ?></td>
                                        <td class='min-requirement-values'>-</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('upload_max_filesize','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('upload_max_filesize') ?></td>
                                        <td class='min-requirement-values'>2M</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('file_uploads','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('file_uploads') ?></td>
                                        <td class='min-requirement-values'>1</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('allow_url_fopen','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('allow_url_fopen') ?></td>
                                        <td class='min-requirement-values'>1</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('max_execution_time','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('max_execution_time') ?></td>
                                        <td class='min-requirement-values'>3000</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('max_input_time','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('max_input_time') ?></td>
                                        <td class='min-requirement-values'>3000</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('max_input_vars','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('max_input_vars') ?></td>
                                        <td class='min-requirement-values'>3000</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('memory_limit','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td class='ini-configured-values'><?php echo ini_get('memory_limit') ?></td>
                                        <td class='min-requirement-values'>99M</td>
                                    </tr>
                                </table>
                                <!-- Max/Min requiredend-->
                                <!-- Extension modules start-->
                                <h3 class="divinnertitle" colspan="2" ><?php echo esc_html_e('Required to enable/disable Loaders, Extentions and modules:','wp-ultimate-csv-importer-pro'); ?></h3>
                                <table class="table table-striped">
                                    <?php $loaders_extensions = get_loaded_extensions();?>
                                    <?php if(function_exists('apache_get_modules')){
                                        $mod_security = apache_get_modules();
                                    }?>
                                        <tr>
                                        <td><?php echo esc_html_e('PDO','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td><?php if(in_array('PDO', $loaders_extensions)) {
                                                echo '<label style="color:green;">';echo __('Yes','wp-ultimate-csv-importer-pro'); echo '</label>';
                                            } else {
                                                echo '<label style="color:red;">';echo __('No','wp-ultimate-csv-importer-pro'); echo '</label>';
                                            } ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html_e('Curl','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td><?php if(in_array('curl', $loaders_extensions)) {
                                                echo '<label style="color:green;">';echo __('Yes','wp-ultimate-csv-importer-pro'); echo '</label>';
                                            } else {
                                                echo '<label style="color:red;">';echo __('No','wp-ultimate-csv-importer-pro'); echo '</label>';
                                            } ?></td>
                                        <td></td>
                                    </tr>
				     <?php if(defined('DISABLE_WP_CRON') && DISABLE_WP_CRON == true) { ?>    
	                             <tr>
                                        <td><?php echo esc_html_e('WP CRON','wp-ultimate-csv-importer-pro'); ?> </td>
                                        <td><?php echo '<label style="color:green;">'; echo __('Disabled','wp-ultimate-csv-importer-pro')
                                           ?></td>
                                     <tr>
                               <?php } ?>
                                </table>
                                <!-- Extension modules end-->
                                <!-- Debug info start-->
                                <h3 class="divinnertitle" colspan="2" ><?php echo esc_html_e('Debug Information:','wp-ultimate-csv-importer-pro'); ?></h3>
                                <table class="table table-striped">
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('WordPress Version','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php echo $wp_version; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('PHP Version','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php echo phpversion(); ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('MySQL Version','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php echo $wpdb->db_version(); ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('Server SoftWare','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('Your User Agent','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php echo $_SERVER['HTTP_USER_AGENT']; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('WPDB Prefix','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php echo $wpdb->prefix; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('WP Multisite Mode','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php if ( is_multisite() ) { echo '<label style="color:green;">'; __('Enabled','wp-ultimate-csv-importer-pro'); echo '</label>'; } else { echo '<label style="color:red;">'; __('Disabled','wp-ultimate-csv-importer-pro');echo '</label>'; } ?> </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class='debug-info-name'><?php echo esc_html_e('WP Memory Limit','wp-ultimate-csv-importer-pro'); ?></td>
                                        <td><?php echo (int) ini_get('memory_limit'); ?></td>
                                        <td></td>
                                    </tr>
                                </table>
                                <!-- Debug info end-->
                                <div class="clearfix"></div>
                                <div class="mb20"></div>
                            </div>
                        </div>
                        <div id="division4" style="display:none;">
                            <div class="divtitle">
                                <h3><?php echo esc_html_e('Documentation','wp-ultimate-csv-importer-pro'); ?></h3>
                            </div>
                            <div id ='divdata'>
                                <div id="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/GbDlQcbnNJY" frameborder="0" allowfullscreen></iframe>
                                </div>
                                <div id="relatedpages">
                                    <h2 id="doctitle"><?php echo esc_html_e('Smackcoders Guidelines','wp-ultimate-csv-importer-pro'); ?> </h2 >
                                    <p> <a href=" https://goo.gl/OAVF9u" target="_blank"> <?php echo __('Development News','wp-ultimate-csv-importer-pro'); ?> </a> </p>
                                    <p> <a href=" https://goo.gl/kKWPui" target="_blank"><?php echo __('Whats New?','wp-ultimate-csv-importer-pro'); ?> </a> </p>
                                    <p> <a href="https://goo.gl/hyU5G1" target="_blank"><?php echo __(' Documentation','wp-ultimate-csv-importer-pro'); ?> </a> </p>
                                    <p> <a href="https://goo.gl/OgW9PJ" target="_blank"> <?php echo __('Youtube Channel','wp-ultimate-csv-importer-pro'); ?> </a> </p>
                                    <p> <a href="https://goo.gl/smw3WV" target="_blank"><?php echo __(' Other WordPress Plugins','wp-ultimate-csv-importer-pro'); ?> </a> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>
</div>
<script>
    jQuery(function () {
        //getting click event to show modal
        jQuery('#database_optimization').click(function () {
            jQuery('.myModals').modal();
        });
    });
</script>
