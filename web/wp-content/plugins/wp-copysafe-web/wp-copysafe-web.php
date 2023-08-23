<?php

/*
Plugin Name: CopySafe Web Protection
Plugin URI: https://artistscope.com/copysafe_web_protection_wordpress_plugin.asp
Description: Add copy protection from PrintScreen and screen capture. Copysafe Web uses encrypted images and domain lock to eapply copy protection for all media displayed on the web page.
Author: ArtistScope
Version: 3.7
Author URI: https://artistscope.com/

	Copyright 2021 ArtistScope Pty Limited


	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// ================================================================================ //
//                                                                                  //
//  WARNING : DONT CHANGE ANYTHING BELOW IF YOU DONT KNOW WHAT YOU ARE DOING        //
//                                                                                  //
// ================================================================================ //
# set script max execution time to 5mins

if (!defined('ABSPATH')) {
  exit;
} 	// Exit if accessed directly

set_time_limit(300);

function wpcsw_enable_extended_upload($mime_types = []) {

  // This function is added to allow the upload of .CLASS file in wordpress. In case this function does not
  // work properly, add the line define('ALLOW_UNFILTERED_UPLOADS', true); in the start of this file

  // You can add as many MIME types as you want.
  $mime_types['class'] = 'application/octet-stream';
  // If you want to forbid specific file types which are otherwise allowed,
  // specify them here.  You can add as many as possible.
  return $mime_types;
}

//This filter is added to add the support for upload of .class file
add_filter('upload_mimes', 'wpcsw_enable_extended_upload');

// ============================================================================================================================
# register WordPress menus
function wpcsw_admin_menus() {
  add_menu_page('CopySafe Web', 'CopySafe Web', 'publish_posts', 'wpcsw_list');
  add_submenu_page('wpcsw_list', 'CopySafe Web List Files', 'List Files', 'publish_posts', 'wpcsw_list', 'wpcsw_admin_page_list');
  add_submenu_page('wpcsw_list', 'CopySafe Web Settings', 'Settings', 'publish_posts', 'wpcsw_settings', 'wpcsw_admin_page_settings');
}

// ============================================================================================================================
# "List" Page
function wpcsw_admin_page_list() {
  $msg = '';
  $table = '';
  $files = _get_wpcsw_uploadfile_list();

  if (!empty($_POST)) {
    $wpcsw_options = get_option('wpcsw_settings');

    $wp_upload_dir = wp_upload_dir();
    $wp_upload_dir_path = str_replace("\\", "/", $wp_upload_dir['basedir']);
    if (!empty($wpcsw_options['settings']['upload_path'])) {
      $target_dir = $wp_upload_dir_path . '/' . $wpcsw_options['settings']['upload_path'];
    } else {
      $target_dir = $wp_upload_dir_path;
    }

    $target_file = $target_dir . basename($_FILES["copysafe-web-class"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (isset($_POST["copysafe-web-class-submit"])) {
      // Allow only .class file formats
      if ($_FILES["copysafe-web-class"]["name"] == "") {
        $msg .= '<div class="error"><p><strong>' . __('Please upload file to continue.') . '</strong></p></div>';
        $uploadOk = 0;
      }
      else if ($imageFileType != "class") {
        $msg .= '<div class="error"><p><strong>' . __('Sorry, only .class files are allowed.') . '</strong></p></div>';
        $uploadOk = 0;
      }
      // Check if $uploadOk is set to 0 by an error
      else if ($uploadOk == 0) {
        $msg .= '<div class="error"><p><strong>' . __('Sorry, your file was not uploaded.') . '</strong></p></div>';
        // if everything is ok, try to upload file
      }
      else {
        if (move_uploaded_file($_FILES["copysafe-web-class"]["tmp_name"], $target_file)) {
          $base_url = get_site_url();
          $msg .= '<div class="updated"><p><strong>' . __('The file ' . basename($_FILES["copysafe-web-class"]["name"]) . ' has been uploaded. Click <a href="' . $base_url . '/wp-admin/admin.php?page=wpcsw_list">here</a> to update below list.') . '</strong></p></div>';
        }
        else {
          $msg .= '<div class="error"><p><strong>' . __('Sorry, there was an error uploading your file.') . '</strong></p></div>';
        }
      }
    }
  }

  if (!empty($files)) {
    foreach ($files as $file) {
      $bare_url = 'admin.php?page=wpcsw_list&cswfilename=' . $file["filename"] . '&action=cswdel';

      $complete_url = wp_nonce_url($bare_url, 'cswdel', 'cswdel_nonce');

      $link = "<div class='row-actions'>
      <span><a href='" . $complete_url . "' title=''>Delete</a></span>
      </div>";
      // prepare table row
      $table .= "<tr><td></td><td>{$file["filename"]} {$link}</td><td>{$file["filesize"]}</td><td>{$file["filedate"]}</td></tr>";
    }
  }

  if (!$table) {
    $table .= '<tr><td colspan="3">' . __('No file uploaded yet.') . '</td></tr>';
  }

  $wpcsw_options = get_option('wpcsw_settings');
  if ($wpcsw_options["settings"]) {
    extract($wpcsw_options["settings"], EXTR_OVERWRITE);
  }

  $wp_upload_dir = wp_upload_dir();
  $wp_upload_dir_path = str_replace("\\", "/", $wp_upload_dir['basedir']);
  $upload_dir = $wp_upload_dir_path . '/' . $upload_path;

  $display_upload_form = !is_dir($upload_dir) ? FALSE : TRUE;

  if (!$display_upload_form) {
    $msg = '<div class="updated"><p><strong>' . __('Upload directory doesn\'t exist. Please configure upload directory to upload class files.') . '</strong></p></div>';
  }

  ?>
    <div class="wrap">
        <div class="icon32" id="icon-file"><br/></div>
      <?php echo $msg; ?>
        <h2>List Class Files</h2>
        <?php if ($display_upload_form): ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="copysafe-web-class" value=""/>
                <input type="submit" name="copysafe-web-class-submit"
                       value="Upload"/>
            </form>
        <?php endif; ?>
        <!--<div><?php // echo wpcsw_media_buttons('');
        ?></div>-->
        <div id="col-container" style="width:700px;">
            <div class="col-wrap">
                <h3>Uploaded Class Files</h3>
                <table class="wp-list-table widefat">
                    <thead>
                    <tr>
                        <th width="5px">&nbsp;</th>
                        <th>File</th>
                        <th>Size</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php echo $table; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>&nbsp;</th>
                        <th>File</th>
                        <th>Size</th>
                        <th>Date</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>
  <?php
}

// ============================================================================================================================
# "Settings" page
function wpcsw_admin_page_settings() {
  $msg = '';
  $wp_upload_dir = wp_upload_dir();
  $wp_upload_dir_path = str_replace("\\", "/", $wp_upload_dir['basedir']);

  if (!empty($_POST)) {
    $wpcsw_options = get_option('wpcsw_settings');
    extract($_POST, EXTR_OVERWRITE);
	
    if (!$upload_path) {
      $upload_path = 'copysafe-web/';
    }
    else {
      $upload_path = sanitize_text_field($upload_path);
    }

    $upload_path = str_replace("\\", "/", stripcslashes($upload_path));
    if (substr($upload_path, -1) != "/") {
      $upload_path .= "/";
    }

    $wpcsw_options['settings'] = [
      'admin_only' => sanitize_text_field($admin_only),
      'upload_path' => $upload_path,
      'mode' => sanitize_text_field($mode),
      'asps' => !empty(sanitize_text_field($asps))  ? 'checked' : '',
      'ff' => !empty(sanitize_text_field($ff)) ? 'checked' : '',
      'ch' => !empty(sanitize_text_field($ch)) ? 'checked' : '',
      'latest_version' => $latest_version,
    ];

    $max_upload_size = wp_max_upload_size();
    if ( ! $max_upload_size ) {
      $max_upload_size = 0;
    }

    $wpcsw_options['settings']['max_size'] = esc_html(size_format($max_upload_size));

    $upload_path = $wp_upload_dir_path . '/' . $upload_path;
    if (!is_dir($upload_path)) {
      mkdir($upload_path, 0, TRUE);
    }

    update_option('wpcsw_settings', $wpcsw_options);
    $msg = '<div class="updated"><p><strong>' . __('Settings Saved') . '</strong></p></div>';
  }

  $wpcsw_options = get_option('wpcsw_settings');
  if ($wpcsw_options["settings"]) {
    extract($wpcsw_options["settings"], EXTR_OVERWRITE);
  }

  $upload_dir = $wp_upload_dir_path . '/' . $upload_path;

  if (!is_dir($upload_dir)) {
    $msg = '<div class="updated"><p><strong>' . __('Upload directory doesn\'t exist.') . '</strong></p></div>';
  }

  $select = '<option value="demo">Demo Mode</option><option value="licensed">Licensed</option><option value="debug">Debugging Mode</option>';
  $select = str_replace('value="' . $mode . '"', 'value="' . $mode . '" selected', $select);
  ?>
    <style type="text/css">#wpcsw_page_setting img {
            cursor: pointer;
        }</style>
    <div class="wrap">
        <div class="icon32" id="icon-settings"><br/></div>
      <?php echo $msg; ?>
        <h2> Default Settings</h2>
        <form action="" method="post">
            <table cellpadding='1' cellspacing='0' border='0'
                   id='wpcsw_page_setting'>
                <p><strong>Default settings applied to all protected
                        pages:</strong></p>
                <tbody>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSW_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow admin only for new uploads.'></td>
                    <td align="left" nowrap>Allow Admin Only:</td>
                    <td align="left"><input name="admin_only" type="checkbox"
                                            value="checked" <?php echo $admin_only; ?>>
                    </td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSW_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Path to the upload folder for Web.'>
                    <td align="left" nowrap>Upload Folder:</td>
                    <td align="left"><input value="<?php echo $upload_path; ?>"
                                            name="upload_path"
                                            class="regular-text code"
                                            type="text"><br />
                        Only specify the folder name. It will be located in site's upload directory, <?php print $wp_upload_dir_path; ?>.
                    </td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSW_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Set the mode to use. Use Licensed if you have licensed images. Otherwise set for Demo or Debug mode.'>
								</td>
                    <td align="left">Mode</td>
                    <td align="left"><select
                                name="mode"><?php echo $select; ?></select></td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSW_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Enter minimum version for ArtisBrowser to allow access.'>
								</td>
                    <td align="left">Minimum Version</td>
                    <td align="left">
					<input type="text" size="8" name="latest_version" value="<?php echo $latest_version ? $latest_version : 27.11; ?>" />
					<br />
                        Enter minimum version for ArtisBrowser to check. 
					</td>
                </tr>
                <tr class="copysafe-video-browsers">
                    <td colspan="5"><h2 class="title">Browser allowed</h2></td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSW_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow visitors using the ArtisBrowser to access this page.'>
                    </td>
                    <td align="left" nowrap>Allow ArtisBrowser:</td>
                    <td align="left"><input name="asps" type="checkbox"
                                            value="checked" <?php echo $asps; ?>>
                    </td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSW_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow visitors using the Firefox web browser to access this page.'>
                    </td>
                    <td align="left">Allow Firefox:</td>
                    <td align="left"><input name="ff" type="checkbox"
                                            <?php echo $ff; ?>> ( for test only )</td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSW_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow visitors using the Chrome web browser to access this page.'>
                    </td>
                    <td align="left">Allow Chrome:</td>
                    <td align="left"><input name="ch" type="checkbox"
                                            <?php echo $ch; ?>> ( for test only )</td>
                </tr>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" value="Save Settings"
                       class="button-primary" id="submit" name="submit">
            </p>
        </form>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <script type='text/javascript'>
      jQuery(document).ready(function () {
        jQuery("#wpcsw_page_setting img").click(function () {
          alert(jQuery(this).attr("alt"));
        });
      });
    </script>
  <?php
}

// ============================================================================================================================
# convert shortcode to html output
function wpcsw_shortcode($atts) {
	wpcsw_check_artis_browser_version();
  global $post;
  $postid = $post->ID;
  $filename = $atts["name"];

  if (!file_exists(WPCSW_UPLOAD_PATH . $filename)) {
    return "<div style='padding:5px 10px;background-color:#fffbcc'><strong>File($filename) don't exist</strong></div>";
  }

  $settings = wpcsw_get_first_class_settings();


  // get plugin options
  $wpcsw_options = get_option('wpcsw_settings');
  if ($wpcsw_options["settings"]) {
    $settings = wp_parse_args($wpcsw_options["settings"], $settings);
  }

  if ($wpcsw_options["classsetting"][$postid][$filename]) {
    $settings = wp_parse_args($wpcsw_options["classsetting"][$postid][$filename], $settings);
  }

  $settings = wp_parse_args($atts, $settings);

  extract($settings);

  if ($asps == "checked") {
    $asps = '1';
  }
  if ($ch == "checked") {
    $chrome = '1';
  }
  if ($ff == "checked") {
    $firefox = '1';
  }

  if ($key_safe == "checked") {
    $key_safe = 1;
  }
  if ($capture_safe == "checked") {
    $capture_safe = 1;
  }
  if ($menu_safe == "checked") {
    $menu_safe = 1;
  }
  if ($remote_safe == "checked") {
    $remote_safe = 1;
  }

  $plugin_url = WPCSW_PLUGIN_URL;
  $plugin_path = WPCSW_PLUGIN_PATH;
  $upload_path = WPCSW_UPLOAD_PATH;
  $upload_url = WPCSW_UPLOAD_URL;

  // display output
  $output = <<<html
	<script type="text/javascript">
		var wpcsw_plugin_url = "$plugin_url" ;
		var wpcsw_upload_url = "$upload_url" ;
	</script>
	 <script type="text/javascript">
		// hide JavaScript from non-JavaScript browsers
		var m_bpDebugging = false;
		var m_szMode = "$mode";
		var m_szClassName = "$name";
		var m_szImageFolder = "$upload_url";		//  path from root with / on both ends
		var m_bpKeySafe = "$key_safe";
		var m_bpCaptureSafe = "$capture_safe";
		var m_bpMenuSafe = "$menu_safe";
		var m_bpRemoteSafe = "$remote_safe";
		var m_bpWindowsOnly = true;	
		var m_bpProtectionLayer = false;		//this page does not use layer control

		var m_bpASPS = "$asps";				// ArtistScope web browsers version 2 and later
		var m_bpChrome = "$chrome";			// all chrome browsers before version 32	
		var m_bpFx = "$firefox";			// all firefox browsers from version 5 and later

		var m_szDefaultStyle = "ImageLink";
		var m_szDefaultTextColor = "$text_color";
		var m_szDefaultBorderColor = "$border_color";
		var m_szDefaultBorder = "$border";
		var m_szDefaultLoading = "$loading_message";
		var m_szDefaultLabel = "";
		var m_szDefaultLink = "$hyperlink";
		var m_szDefaultTargetFrame = "$target";
		var m_szDefaultMessage = "";

		if (m_szMode == "debug") {
			m_bpDebugging = true;
		}
		
		if ((m_bpCaptureSafe == "1") && (m_bpKeySafe == "1")) {
			var cswbody = document.getElementsByTagName("body")[0];
			cswbody.setAttribute("onselectstart", "return false;");
			cswbody.setAttribute("ondragstart", "return false");
			cswbody.setAttribute("onmousedown", "if (event.preventDefault){event.preventDefault();}");
			cswbody.setAttribute("onBeforePrint", "document.body.style.display = '';");
			cswbody.setAttribute("onContextmenu", "return false;");
			cswbody.setAttribute("onClick", "if(event.button==2||event.button==3){event.preventDefault();event.stopPropagation();return false;}");
		}
		else if ((m_bpCaptureSafe == "1") && (m_bpKeySafe != "1")) {
			var cswbody = document.getElementsByTagName("body")[0];
			cswbody.setAttribute("onselectstart", "return false;");
			cswbody.setAttribute("ondragstart", "return false");
			cswbody.setAttribute("onContextmenu", "return false;");
		}
	 </script>
	 <script src="{$plugin_url}js/wp-copysafe-web.js" type="text/javascript"></script>
     <div>
		 <script type="text/javascript">
			//hide JavaScript from non-JavaScript browsers
			if ((m_szMode == "licensed") || (m_szMode == "debug")) {
				insertCopysafeWeb("$name", "$width", "$height");
			}
			else {
				document.writeln("<img src='{$plugin_url}images/image_placeholder.jpg' border='0' alt='Demo mode'>");
			}
		 </script>
     </div>
html;

  return $output;
}

// ============================================================================================================================
# delete short code
function wpcsw_delete_shortcode() {
  // get all posts
  $posts_array = get_posts();
  foreach ($posts_array as $post) {
    // delete short code
    $post->post_content = wpcsw_deactivate_shortcode($post->post_content);
    // update post
    wp_update_post($post);
  }
}

// ============================================================================================================================
# deactivate short code
function wpcsw_deactivate_shortcode($content) {
  // delete short code
  $content = preg_replace('/\[copysafe name="[^"]+"\]\[\/copysafe\]/s', '', $content);
  return $content;
}

// ============================================================================================================================
# search short code in post content and get post ids
function wpcsw_search_shortcode($file_name) {
  // get all posts
  $posts = get_posts();
  $IDs = FALSE;
  foreach ($posts as $post) {
    $file_name = preg_quote($file_name, '\\');
    preg_match('/\[copysafe name="' . $file_name . '"\]\[\/copysafe\]/s', $post->post_content, $matches);
    if (is_array($matches) && isset($matches[1])) {
      $IDs[] = $post->ID;
    }
  }
  return $IDs;
}

// ============================================================================================================================
# delete file options
function wpcsw_delete_file_options($file_name) {
  $file_name = trim($file_name);
  $wpcsw_options = get_option('wpcsw_settings');
  foreach ($wpcsw_options["classsetting"] as $k => $arr) {
    if ($wpcsw_options["classsetting"][$k][$file_name]) {
      unset($wpcsw_options["classsetting"][$k][$file_name]);
      if (!count($wpcsw_options["classsetting"][$k])) {
        unset($wpcsw_options["classsetting"][$k]);
      }
    }
  }
  update_option('wpcsw_settings', $wpcsw_options);
}

// ============================================================================================================================
# install media buttons
function wpcsw_media_buttons($context) {
  global $post_ID;
  // generate token for links
  $token = wp_create_nonce('wpcsw_token');
  $url = admin_url('?wpcsw-popup=copysafe&wpcsw_token=' . $token . '&post_id=' . $post_ID);
  echo "<a href='" . $url . "' class='thickbox' id='wpcsw_link' data-body='no-overflow' title='CopySafe Web'><img src='" . plugin_dir_url(__FILE__) . "/images/copysafebutton.png'></a>";
}

// ============================================================================================================================

// ============================================================================================================================
# admin page scripts
function wpcsw_admin_load_js() {
  // load jquery suggest plugin
  wp_enqueue_script('suggest');
}

// ============================================================================================================================
# admin page styles
function wpcsw_admin_load_styles() {
  // register custom CSS file & load
  wp_register_style('wpcsw-style', plugins_url('css/wp-copysafe-web.css', __FILE__));
  wp_enqueue_style('wpcsw-style');
}

function wpcsw_is_admin_postpage() {
  $chk = FALSE;
  $script_name = explode("/", $_SERVER["SCRIPT_NAME"]);
  $ppage = end($script_name);
  if ($ppage == "post-new.php" || $ppage == "post.php") {
    return TRUE;
  }
}

function wpcsw_includecss_js() {
  if (!wpcsw_is_admin_postpage()) {
    return;
  }
  global $wp_popup_upload_lib;
  if ($wp_popup_upload_lib) {
    return;
  }
  $wp_popup_upload_lib = TRUE;
  echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.9.2/themes/redmond/jquery-ui.css' type='text/css' />";
  echo "<link rel='stylesheet' href='" . WPCSW_PLUGIN_URL . "lib/uploadify/uploadify.css' type='text/css' />";

  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-progressbar');
  wp_enqueue_script('jquery.json', FALSE, ['jquery']);
}

// ============================================================================================================================
# setup plugin
function wpcsw_setup() {
  //----add codding----
  $options = get_option("wpcsw_settings");
  define('WPCSW_PLUGIN_PATH', str_replace("\\", "/", plugin_dir_path(__FILE__))); //use for include files to other files
  define('WPCSW_PLUGIN_URL', plugins_url('/', __FILE__));

  $wp_upload_dir = wp_upload_dir();
  $wp_upload_dir_path = str_replace("\\", "/", $wp_upload_dir['basedir']);
  $upload_path = $wp_upload_dir_path . '/' . $options["settings"]["upload_path"];
  define('WPCSW_UPLOAD_PATH', $upload_path); //use for include files to other files

  $wp_upload_dir = wp_upload_dir();
  $wp_upload_dir_url = str_replace("\\", "/", $wp_upload_dir['baseurl']);
  $upload_url = $wp_upload_dir_url . '/' . $options["settings"]["upload_path"];
  define('WPCSW_UPLOAD_URL', $upload_url);

  include(WPCSW_PLUGIN_PATH . "function.php");
  add_action('admin_head', 'wpcsw_includecss_js');
  add_action('wp_ajax_wpcsw_ajaxprocess', 'wpcsw_ajaxprocess');

  //Sanitize the GET input variables
  $pagename = !empty(@$_GET['page']) ? sanitize_key(@$_GET['page']) : '';

  $cswfilename = !empty(@$_GET['cswfilename']) ? sanitize_file_name(@$_GET['cswfilename']) : '';

  $action = !empty(@$_GET['action']) ? sanitize_key(@$_GET['action']) : '';

  $cswdel_nonce = !empty(@$_GET['cswdel_nonce']) ? sanitize_key(@$_GET['cswdel_nonce']) : '';

  if ($pagename == 'wpcsw_list' && $cswfilename && $action == 'cswdel') {
    //check that nonce is valid and user is administrator
    if (current_user_can('administrator') && wp_verify_nonce($cswdel_nonce, 'cswdel')) {
      wpcsw_delete_file_options($cswfilename);
      if (file_exists(WPCSW_UPLOAD_PATH . $cswfilename)) {
        unlink(WPCSW_UPLOAD_PATH . $cswfilename);
      }
      wp_redirect('admin.php?page=wpcsw_list');
    }
    else {
      wp_nonce_ays();
    }

  }

  if (isset($_GET['wpcsw-popup']) && @$_GET["wpcsw-popup"] == "copysafe") {
    require_once(WPCSW_PLUGIN_PATH . "popup_load.php");
    exit();
  }
  //=============================
  // load js file
  add_action('wp_enqueue_scripts', 'wpcsw_load_js');

  // load admin CSS
  add_action('admin_print_styles', 'wpcsw_admin_load_styles');

  // add short code
  add_shortcode('copysafe', 'wpcsw_shortcode');

  // if user logged in
  if (is_user_logged_in()) {
    // install admin menu
    add_action('admin_menu', 'wpcsw_admin_menus');

    // check user capability
    if (current_user_can('edit_posts')) {
      // load admin JS
      add_action('admin_print_scripts', 'wpcsw_admin_load_js');
      // load media button
      add_action('media_buttons', 'wpcsw_media_buttons');
    }
  }

  wp_register_script('wpcsw-plugin-script', WPCSW_PLUGIN_URL . 'js/copysafe_media_uploader.js');
  wp_register_script('jquery.json', WPCSW_PLUGIN_URL . 'lib/jquery.json-2.3.js');
}

// ============================================================================================================================
# runs when plugin activated
function wpcsw_activate() {
  $wp_upload_dir = wp_upload_dir();
  $wp_upload_dir_path = str_replace("\\", "/", $wp_upload_dir['basedir']);

  // if this is first activation, setup plugin options
  if (!get_option('wpcsw_settings')) {
    // set plugin folder
    $upload_dir = 'copysafe-web/';
    $upload_path = $wp_upload_dir_path . '/' . $upload_dir;

    // set default options
    $wpcsw_options['settings'] = [
      'admin_only' => "checked",
      'upload_path' => $upload_dir,
      'mode' => "demo",
      'asps' => "checked",
      'ff' => "",
      'ch' => "",
    ];

    update_option('wpcsw_settings', $wpcsw_options);

    if (!is_dir($upload_path)) {
      mkdir($upload_path, 0, TRUE);
    }
    // create upload directory if it is not exist
  }
}

// ============================================================================================================================
# runs when plugin deactivated
function wpcsw_deactivate() {
  // remove text editor short code
  remove_shortcode('copysafe');
}

// ============================================================================================================================
# runs when plugin deleted.
function wpcsw_uninstall() {
  // delete all uploaded files
  $wp_upload_dir = wp_upload_dir();
  $wp_upload_dir_path = str_replace("\\", "/", $wp_upload_dir['basedir']);

  $default_upload_dir = $wp_upload_dir_path . '/copysafe-web/';
  if (is_dir($default_upload_dir)) {
    $dir = scandir($default_upload_dir);
    foreach ($dir as $file) {
      if ($file != '.' || $file != '..') {
        unlink($default_upload_dir . $file);
      }
    }
    rmdir($default_upload_dir);
  }

  // delete upload directory
  $options = get_option("wpcsw_settings");

  if ($options["settings"]["upload_path"]) {
    $upload_path = $wp_upload_dir_path . '/' . $options["settings"]["upload_path"];
    if (is_dir($upload_path)) {
      $dir = scandir($upload_path);
      foreach ($dir as $file) {
        if ($file != '.' || $file != '..') {
          unlink($upload_path . '/' . $file);
        }
      }
      // delete upload directory
      rmdir($upload_path);
    }
  }

  // delete plugin options
  delete_option('wpcsw_settings');

  // unregister short code
  remove_shortcode('copysafe');

  // delete short code from post content
  wpcsw_delete_shortcode();
}


function wpcsw_load_js() {
  // load custom JS file
  // wp_enqueue_script( 'wpcsw-browser-detector', plugins_url( 'browser_detection.js', __FILE__), array( 'jquery' ) );
  wp_register_script('wp-copysafeweb-uploader', WPCSW_PLUGIN_URL . 'js/copysafe_media_uploader.js', [
    'jquery',
    'plupload-all',
  ]);
}

function wpcsw_admin_head() {
  $uploader_options = [
    'runtimes' => 'html5,silverlight,flash,html4',
    'browse_button' => 'wpcsw-plugin-uploader-button',
    'container' => 'wpcsw-plugin-uploader',
    'drop_element' => 'wpcsw-plugin-uploader',
    'file_data_name' => 'async-upload',
    'multiple_queues' => TRUE,
    'max_file_size' => wp_max_upload_size() . 'b',
    'url' => admin_url('admin-ajax.php'),
    'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
    'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
    'filters' => [
      [
        'title' => __('Allowed Files'),
        'extensions' => '*',
      ],
    ],
    'multipart' => TRUE,
    'urlstream_upload' => TRUE,
    'multi_selection' => TRUE,
    'multipart_params' => [
      '_ajax_nonce' => '',
      'action' => 'wpcsw-plugin-upload-action',
    ],
  ];
  ?>
    <script type="text/javascript">
      var global_uploader_options =<?php echo json_encode($uploader_options); ?>;
    </script>
  <?php
}

add_action('admin_head', 'wpcsw_admin_head');

function wpcsw_includecss_js_to_footer(){
	if (!wpcsw_is_admin_postpage())
		return;
	
	?>
	<script>
	if( jQuery("#wpcsw_link").length > 0 ){
		if( jQuery("#wpcsw_link").data("body") == "no-overflow" ){
			jQuery("body").addClass("wps-no-overflow");
			
		}
	}
	</script>
	<?php
}

add_action('admin_footer', 'wpcsw_includecss_js_to_footer');

function wpcsw_ajax_action() {
  add_filter('upload_dir', 'wpcsw_upload_dir');
  // check ajax nonce
  //check_ajax_referer( __FILE__ );
  if (current_user_can('upload_files')) {
    $response = [];
    // handle file upload
    $id = media_handle_upload(
      'async-upload',
      0,
      [
        'test_form' => TRUE,
        'action' => 'wpcsw-plugin-upload-action',
      ]
    );

    // send the file' url as response
    if (is_wp_error($id)) {
      $response['status'] = 'error22';
      $response['error'] = $id->get_error_messages();
    }
    else {
      $response['status'] = 'success';

      $src = wp_get_attachment_image_src($id, 'thumbnail');
      $response['attachment'] = [];
      $response['attachment']['id'] = $id;
      $response['attachment']['src'] = $src[0];
    }

  }
  remove_filter('upload_dir', 'wpcsw_upload_dir');
  echo json_encode($response);
  exit;
}

add_action('wp_ajax_wpcsw-plugin-upload-action', 'wpcsw_ajax_action');

$upload = wp_upload_dir();
remove_filter('upload_dir', 'wpcsw_upload_dir');

function wpcsw_upload_dir($upload) {

  $upload['subdir'] = '/copysafe-web';
  $upload['path'] = $upload['basedir'] . $upload['subdir'];
  $upload['url'] = $upload['baseurl'] . $upload['subdir'];
  return $upload;
}

// ============================================================================================================================
# register plugin hooks
register_activation_hook(__FILE__, 'wpcsw_activate'); // run when activated
register_deactivation_hook(__FILE__, 'wpcsw_deactivate'); // run when deactivated
register_uninstall_hook(__FILE__, 'wpcsw_uninstall'); // run when uninstalled

add_action('init', 'wpcsw_setup');
?>