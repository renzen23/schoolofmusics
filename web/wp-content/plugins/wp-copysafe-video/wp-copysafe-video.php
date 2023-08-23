<?php
/*
  Plugin Name: CopySafe Video Protection
  Plugin URI: https://artistscope.com/copysafe_video_protection_wordpress_plugin.asp
  Description: This Wordpress plugin enables sites using CopySafe Video to easily add copy protected videos to web pages for display in the ArtisBrowser.
  Author: ArtistScope
  Version: 2.2
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

if (!defined('ABSPATH')) {
  exit;
} // Exit if accessed directly

# set script max execution time to 5mins
set_time_limit(300);

function wpcsv_enable_extended_upload($mime_types = []) {

  // This function is added to allow the upload of .CLASS file in wordpress. In case this function does not
  // work properly, add the line define('ALLOW_UNFILTERED_UPLOADS', true); in the start of this file

  // You can add as many MIME types as you want.
  $mime_types['class'] = 'application/octet-stream';
  // If you want to forbid specific file types which are otherwise allowed,
  // specify them here.  You can add as many as possible.
  return $mime_types;
}

add_filter('upload_mimes', 'wpcsv_enable_extended_upload');

// ============================================================================================================================
# register WordPress menus
function wpcsv_admin_menus() {
  add_menu_page('CopySafe Video', 'CopySafe Video', 'publish_posts', 'wpcsv_list');
  add_submenu_page('wpcsv_list', 'CopySafe Video List Files', 'List Files', 'publish_posts', 'wpcsv_list', 'wpcsv_admin_page_list');
  add_submenu_page('wpcsv_list', 'CopySafe Video Settings', 'Settings', 'publish_posts', 'wpcsv_settings', 'wpcsv_admin_page_settings');
}

// ============================================================================================================================
# "List" Page
function wpcsv_admin_page_list() {
  $files = _get_wpcsv_uploadfile_list();
    $msg = '';
    $table = '';
  if (!empty($_POST)) {
    $wpcsv_options = get_option('wpcsv_settings');
    

    if (!empty($wpcsv_options['settings']['upload_path'])) {
      $target_dir = $wpcsv_options['settings']['upload_path'];
    }
    else {
      $target_dir = "wp-content/uploads/";
    }

    $target_file = ABSPATH . $target_dir . basename($_FILES["copysafe-video-class"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (isset($_POST["copysafe-video-class-submit"])) {
	  // Allow only .class file formats
	  if ($_FILES["copysafe-video-class"]["name"] == "") {
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
        if (move_uploaded_file($_FILES["copysafe-video-class"]["tmp_name"], $target_file)) {
          $base_url = get_site_url();
          $msg .= '<div class="updated"><p><strong>' . __('The file ' . basename($_FILES["copysafe-video-class"]["name"]) . ' has been uploaded. Click <a href="' . $base_url . '/wp-admin/admin.php?page=wpcsv_list">here</a> to update below list.') . '</strong></p></div>';
        }
        else {
          $msg .= '<div class="error"><p><strong>' . __('Sorry, there was an error uploading your file.') . '</strong></p></div>';
        }
      }
    }
  }

  foreach ($files as $file) {

    $bare_url = 'admin.php?page=wpcsv_list&csvfilename=' . $file["filename"] . '&action=csvdel';

    $complete_url = wp_nonce_url($bare_url, 'csvdel', 'csvdel_nonce');

    $link = "<div class='row-actions'>
					<span><a href='" . $complete_url . "' title=''>Delete</a></span>											
				</div>";
    // prepare table row
    $table .= "<tr><td></td><td>{$file["filename"]} {$link}</td><td>{$file["filesize"]}</td><td>{$file["filedate"]}</td></tr>";
  }

  if (!$table) {
    $table .= '<tr><td colspan="3">' . __('No file uploaded yet.') . '</td></tr>';
  }
  ?>
    <div class="wrap">
        <div class="icon32" id="icon-file"><br/></div>
      <?php echo $msg; ?>
        <h2>List Video Class Files</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="copysafe-video-class" value=""/>
            <input type="submit" name="copysafe-video-class-submit"
                   value="Upload"/>
        </form>
        <div id="col-container" style="width:700px;">
            <div class="col-wrap">
                <h3>Uploaded Video Class Files</h3>
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
function wpcsv_admin_page_settings() {
  $msg = '';

  if (!empty($_POST)) {
    if (wp_verify_nonce($_POST['wpcsv_wpnonce'], 'wpcsv_settings')) {

      $wpcsv_options = get_option('wpcsv_settings');
      extract($_POST, EXTR_OVERWRITE);

      if (!$upload_path) {
        $upload_path = 'wp-content/uploads/copysafe-video/';
      }
      $upload_path = str_replace("\\", "/", stripcslashes($upload_path));
      if (substr($upload_path, -1) != "/") {
        $upload_path .= "/";
      }

      $wpcsv_options['settings'] = [
        'admin_only' => sanitize_text_field($admin_only),
        'upload_path' => $upload_path,
        'mode' => $mode,
        'language' => sanitize_text_field($language),
        'background' => $background,
        'width' => $width,
        'height' => $height,
        'allowremote' => !empty(sanitize_text_field($allowremote)) ? 'checked' : '',
        'asps' => !empty(sanitize_text_field($asps)) ? 'checked' : '',
        'ff' => !empty(sanitize_text_field($ff)) ? 'checked' : '',
        'ch' => !empty(sanitize_text_field($ch)) ? 'checked': '',
        'minimum_version' => $minimum_version,
		'watermarked' => !empty(sanitize_text_field($watermarked)) ? 'checked' : '',
		'wtmtextsize' => $wtmtextsize,
		'wtmtextcolour' => $wtmtextcolour,
		'wtmtextposition' => $wtmtextposition,
		'wtmtextopacity' => $wtmtextopacity,
      ];

      $upload_path = ABSPATH . $upload_path;
      if (!is_dir($upload_path)) {
        mkdir($upload_path, 0, TRUE);
      }

      update_option('wpcsv_settings', $wpcsv_options);
      $msg = '<div class="updated"><p><strong>' . __('Settings Saved') . '</strong></p></div>';
    }

  }
  $wpcsv_options = get_option('wpcsv_settings');
  if ($wpcsv_options["settings"]) {
    extract($wpcsv_options["settings"], EXTR_OVERWRITE);
  }
  $select = '<option value="demo">Demo Mode</option><option value="licensed">Licensed</option><option value="debug">Debugging Mode</option>';
  $select = str_replace('value="' . $mode . '"', 'value="' . $mode . '" selected', $select);

  $lnguageOptions = [
    "0c01" => "Arabic",
    "0004" => "Chinese (simplified)",
    "0404" => "Chinese (traditional)",
    "041a" => "Croatian",
    "0405" => "Czech",
    "0413" => "Dutch",
    "" => "English",
    "0464" => "Filipino",
    "000c" => "French",
    "0007" => "German",
    "0408" => "Greek",
    "040d" => "Hebrew",
    "0439" => "Hindi",
    "000e" => "Hungarian",
    "0421" => "Indonesian",
    "0410" => "Italian",
    "0411" => "Japanese",
    "0412" => "Korean",
    "043e" => "Malay",
    "0415" => "Polish",
    "0416" => "Portuguese (BR)",
    "0816" => "Portuguese (PT)",
    "0419" => "Russian",
    "0c0a" => "Spanish",
    "041e" => "Thai",
    "041f" => "Turkish",
    "002a" => "Vietnamese",
  ];
  foreach ($lnguageOptions as $k => $v) {
    $chk = str_replace("value='$language'", "value='$language' selected", "value='$k'");
    $lnguageOptionStr .= "<option $chk >$v</option>";
  }
  ?>
    <style type="text/css">#wpcsv_page_setting img {
            cursor: pointer;
        }</style>
    <div class="wrap">
        <div class="icon32" id="icon-settings"><br/></div>
      <?php echo $msg; ?>
        <h2>Default Settings</h2>
        <form action="" method="post">
          <?php echo wp_nonce_field('wpcsv_settings', 'wpcsv_wpnonce'); ?>
            <table cellpadding='1' cellspacing='0' border='0'
                   id='wpcsv_page_setting'>
                <p><strong>Default settings applied to all protected Video
                        pages:</strong></p>
                <tbody>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
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
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Path to the upload folder for Video.'>
                    <td align="left" nowrap>Upload Folder:</td>
                    <td align="left"><input value="<?php echo $upload_path; ?>"
                                            name="upload_path"
                                            class="regular-text code"
                                            type="text"></td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Set the mode to use. Use Licensed if you have licensed images. Otherise set for Demo or Debug mode.'>
							</td>
                    <td align="left">Mode</td>
                    <td align="left"><select name="mode">
                        <?php echo $select; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Enter minimum version required for ArtisBrowser access.'>
								</td>
                    <td align="left">Minimum Version</td>
                    <td align="left">
					<input type="text" class="regular-text code" name="minimum_version" value="<?php echo $minimum_version ? $minimum_version : 27.11; ?>" />
					<br />
                        Enter minimum version for ArtisBrowser to check.
					</td>
                </tr>
                <tr class="copysafe-video-attributes">
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Set width of the Video viewer.'>
                    </td>
                    <td align="left">Width - in pixels:</td>
                    <td align="left"><input value="<?php echo $width; ?>"
                                            name="width" type="text"
                                            size="8"></td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Set height of the Video viewer.'>
                    </td>
                    <td align="left">Height - in pixels:</td>
                    <td align="left"><input value="<?php echo $height; ?>"
                                            name="height" type="text"
                                            size="8"></td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow visitors using Windows in a VM partition.'>
                    </td>
                    <td align="left" nowrap>AllowRemote:</td>
                    <td align="left"><input name="allowremote" type="checkbox"
                                            value="checked" <?php echo $allowremote; ?>>
                    </td>
                </tr>
                <tr class="copysafe-video-browsers">
                    <td colspan="5"><h2 class="title">Browser allowed</h2></td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
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
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow visitors using the Firefox web browser to access this page.'>
                    </td>
                    <td align="left">Allow Firefox:</td>
                    <td align="left"><input name="ff"
                                            type="checkbox" <?php echo $ff; ?>>	( for testing only by admin )
                    </td>
                </tr>
                <tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow visitors using the Chrome web browser to access this page.'>
                    </td>
                    <td align="left">Allow Chrome:</td>
                    <td align="left"><input name="ch"
                                            type="checkbox" <?php echo $ch; ?>>	( for testing only by admin )
                    </td>
                </tr>
				 <tr class="copysafe-video-browsers">
                    <td colspan="5"><h2 class="title">Watermark Style Settings</h2></td>
                </tr>
				<tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Allow watermarking?'>
                    </td>
                    <td align="left">Enabled:</td>
                    <td align="left"><input name="watermarked"
                                            type="checkbox" <?php echo $watermarked; ?>>
                    </td>
                </tr>
				
				<tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Text Size (in pixels)'>
                    </td>
                    <td align="left">Watermark Text Size (in pixels):</td>
                    <td align="left">
					<select name="wtmtextsize">
						<option value="10" <?php echo ($wtmtextsize==10)?'selected':''; ?>>10</option>
						<option value="15" <?php echo ($wtmtextsize==15)?'selected':''; ?>>15</option>
						<option value="20" <?php echo ($wtmtextsize==20)?'selected':''; ?>>20</option>
						<option value="25" <?php echo ($wtmtextsize==25)?'selected':''; ?>>25</option>
						<option value="30" <?php echo ($wtmtextsize==30)?'selected':''; ?>>30</option>
						<option value="35" <?php echo ($wtmtextsize==35)?'selected':''; ?>>35</option>
						<option value="40" <?php echo ($wtmtextsize==40)?'selected':''; ?>>40</option>
					</select>
					
                    </td>
                </tr>
				<tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Watermark Text Color'>
                    </td>
                    <td align="left">Text Color:</td>
                    <td align="left">
					<select name="wtmtextcolour">
						<option value="FFFFFF" <?php echo ($wtmtextcolour=='FFFFFF')?'selected':''; ?>>White</option>
						<option value="FF3333" <?php echo ($wtmtextcolour=='FF3333')?'selected':''; ?>>Red</option>
						<option value="FFFF00" <?php echo ($wtmtextcolour=='FFFF00')?'selected':''; ?>>Yellow</option>
						<option value="00FF00" <?php echo ($wtmtextcolour=='00FF00')?'selected':''; ?>>Green</option>
						<option value="00FFFF" <?php echo ($wtmtextcolour=='00FFFF')?'selected':''; ?>>Blue</option>
					</select>
					
                    </td>
                </tr>
				<tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Watermark Text Position'>
                    </td>
                    <td align="left">Text Position:</td>
                    <td align="left">
					<select name="wtmtextposition">
						
						<option value="0" <?php echo ($wtmtextposition=='0')?'selected':''; ?>>Center</option>
						<option value="1" <?php echo ($wtmtextposition=='1')?'selected':''; ?>>Bottom</option>
						<option value="2" <?php echo ($wtmtextposition=='2')?'selected':''; ?>>Top</option>
						<option value="3" <?php echo ($wtmtextposition=='3')?'selected':''; ?>>Top Left</option>
						<option value="4" <?php echo ($wtmtextposition=='4')?'selected':''; ?>>Top Right</option>
						<option value="5" <?php echo ($wtmtextposition=='5')?'selected':''; ?>>Bottom Left</option>
						<option value="6" <?php echo ($wtmtextposition=='6')?'selected':''; ?>>Bottom Right</option>
						<option value="7" <?php echo ($wtmtextposition=='7')?'selected':''; ?>>Rotating</option>
					</select>
					
                    </td>
                </tr>
				<tr>
                    <td align='left' width='50'>&nbsp;</td>
                    <td align='left' width='30'><img
                                src='<?php echo WPCSV_PLUGIN_URL; ?>images/help-24-30.png'
                                border='0'
                                alt='Watermark Text Opacity'>
                    </td>
                    <td align="left">Opacity:</td>
                    <td align="left">
					<select name="wtmtextopacity">
						
						<option value="9" <?php echo ($wtmtextopacity=='9')?'selected':''; ?>>100% (opaque)</option>
						<option value="8" <?php echo ($wtmtextopacity=='8')?'selected':''; ?>>90%</option>
						<option value="7" <?php echo ($wtmtextopacity=='7')?'selected':''; ?>>80%</option>
						<option value="6" <?php echo ($wtmtextopacity=='6')?'selected':''; ?>>70%</option>
						<option value="5" <?php echo ($wtmtextopacity=='5')?'selected':''; ?>>60%</option>
						<option value="4" <?php echo ($wtmtextopacity=='4')?'selected':''; ?>>50%</option>
						<option value="3" <?php echo ($wtmtextopacity=='3')?'selected':''; ?>>40%</option>
						<option value="2" <?php echo ($wtmtextopacity=='2')?'selected':''; ?>>30%</option>
						<option value="1" <?php echo ($wtmtextopacity=='1')?'selected':''; ?>>20%</option>
						<option value="0" <?php echo ($wtmtextopacity=='0')?'selected':''; ?>>10%</option>

						

					</select>
					
                    </td>
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
        jQuery("#wpcsv_page_setting img").click(function () {
          alert(jQuery(this).attr("alt"));
        });
      });
    </script>
  <?php
}
function getIP() 
{
    // populate a local variable to avoid extra function calls.
    // NOTE: use of getenv is not as common as use of $_SERVER.
    //       because of this use of $_SERVER is recommended, but 
    //       for consistency, I'll use getenv below
    $tmp = getenv("HTTP_CLIENT_IP");
    // you DON'T want the HTTP_CLIENT_ID to equal unknown. That said, I don't
    // believe it ever will (same for all below)
    if ( $tmp && !strcasecmp( $tmp, "unknown"))
        return $tmp;
        
    $tmp = getenv("HTTP_X_FORWARDED_FOR");
    if( $tmp && !strcasecmp( $tmp, "unknown"))
        return $tmp;
        
    // no sense in testing SERVER after this. 
    // $_SERVER[ 'REMOTE_ADDR' ] == gentenv( 'REMOTE_ADDR' );
    $tmp = getenv("REMOTE_ADDR");
    if($tmp && !strcasecmp($tmp, "unknown"))
        return $tmp;
	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) { return $_SERVER['REMOTE_ADDR']; }
        
    return("unknown");
}
// ============================================================================================================================
# convert shortcode to html output
function wpcsv_shortcode($atts) {
	wpcsv_check_artis_browser_version();
  global $post;
  $postid = $post->ID;
  $filename = $atts["name"];
  
  
  if (!file_exists(WPCSV_UPLOAD_PATH . $filename)) {
    return "<div style='padding:5px 10px;background-color:#fffbcc'><strong>File($filename) don't exist</strong></div>";
  }

  $settings = wpcsv_get_first_class_settings();

  // get plugin options
  $wpcsv_options = get_option('wpcsv_settings');
  if ($wpcsv_options["settings"]) {
    $settings = wp_parse_args($wpcsv_options["settings"], $settings);
  }

  if ($wpcsv_options["classsetting"][$postid][$filename]) {
    $settings = wp_parse_args($wpcsv_options["classsetting"][$postid][$filename], $settings);
  }

  $settings = wp_parse_args($atts, $settings);

  extract($settings);

  $asps = ($asps) ? '1' : '0';
  $firefox = ($ff) ? '1' : '0';
  $chrome = ($ch) ? '1' : '0';

  $allowremote = ($allowremote) ? '1' : '0';
  
  
  $watermarked = ($watermarked) ? '1' : '0';
  $current_user = wp_get_current_user();
  if((int)$current_user->ID>0){
  $username=$current_user->user_login;
  if($current_user->user_firstname)
	$username=$current_user->user_firstname;

	$userString = $current_user->ID . ' ' . $username . ' ' . date('Y-m-d');
  }
  else 
	  $userString = getIP() . ' ' . date('Y-m-d');
  
  $watermarkstring="".$userString.",".$wtmtextsize.",".$wtmtextcolour.",".$wtmtextposition.",".$wtmtextopacity;
  if($wtmtextposition==7)
  {
	  $wtmtextposition=rand(0,6);
  }
 
  $plugin_url = WPCSV_PLUGIN_URL;
  $plugin_path = WPCSV_PLUGIN_PATH;
  $upload_path = WPCSV_UPLOAD_PATH;
  $upload_url = WPCSV_UPLOAD_URL;
  // display output
  $output = <<<html
     <script type="text/javascript">
		var wpcsv_plugin_url = "$plugin_url" ;
		var wpcsv_upload_url = "$upload_url" ;
	 </script>
	 <script type="text/javascript">
	<!-- hide JavaScript from non-JavaScript browsers
		var m_bpDebugging = false;
		var m_szMode = "$mode";
		var m_szClassName = "$name";
		var m_szImageFolder = "$upload_url";		//  path from root with / on both ends
		var m_bpAllowRemote = "$allowremote";
		//var m_bpLanguage = "$language";
		//var m_bpBackground = "$background";			// background colour without the #
		var m_bpWidth = "$width";				// width of Video display in pixels
		var m_bpHeight = "$height";			// height of Video display in pixels

		var m_bpASPS = "$asps";
		var m_bpChrome = "$chrome";	
		var m_bpFx = "$firefox";			// all firefox browsers from version 5 and later
		var m_min_Version = "$minimum_version";
		//watermarking settings
		
		var m_bpwatermarked = "$watermarked";
		var m_bpwtmtextsize = "$wtmtextsize";
		var m_bpwtmtextcolour = "$wtmtextcolour";
		var m_bpwtmtextposition = "$wtmtextposition";
		var m_bpwtmtextopacity = "$wtmtextopacity";
		var watermarkstring = "$watermarkstring";
		

		if (m_szMode == "debug") {
			m_bpDebugging = true;
		}
		// -->
	 </script>
	 <script src="{$plugin_url}js/wp-copysafe-video.js" type="text/javascript"></script>
html;

  return $output;
}

// ============================================================================================================================
# delete short code
function wpcsv_delete_shortcode() {
  // get all posts
  $posts_array = get_posts();
  foreach ($posts_array as $post) {
    // delete short code
    $post->post_content = wpcsv_deactivate_shortcode($post->post_content);
    // update post
    wp_update_post($post);
  }
}

// ============================================================================================================================
# deactivate short code
function wpcsv_deactivate_shortcode($content) {
  // delete short code
  $content = preg_replace('/\[copysafevideo name="[^"]+"\]\[\/copysafevideo\]/s', '', $content);
  return $content;
}

// ============================================================================================================================
# search short code in post content and get post ids
function wpcsv_search_shortcode($file_name) {
  // get all posts
  $posts = get_posts();
  $IDs = FALSE;
  foreach ($posts as $post) {
    $file_name = preg_quote($file_name, '\\');
    preg_match('/\[copysafevideo name="' . $file_name . '"\]\[\/copysafevideo\]/s', $post->post_content, $matches);
    if (is_array($matches) && isset($matches[1])) {
      $IDs[] = $post->ID;
    }
  }
  return $IDs;
}

// ============================================================================================================================
# delete file options
function wpcsv_delete_file_options($file_name) {
  $file_name = trim($file_name);
  $wpcsv_options = get_option('wpcsv_settings');
  foreach ($wpcsv_options["classsetting"] as $k => $arr) {
    if ($wpcsv_options["classsetting"][$k][$file_name]) {
      unset($wpcsv_options["classsetting"][$k][$file_name]);
      if (!count($wpcsv_options["classsetting"][$k])) {
        unset($wpcsv_options["classsetting"][$k]);
      }
    }
  }
  update_option('wpcsv_settings', $wpcsv_options);
}

// ============================================================================================================================
# install media buttons
function wpcsv_media_buttons($context) {
  global $post_ID;
  // generate token for links
  $token = wp_create_nonce('wpcsv_token');
  $url = admin_url('?wpcsv-popup=file_upload&wpcsv_token=' . $token . '&post_id=' . $post_ID);
  echo "<a href='$url' class='thickbox' id='wpcsv_link' data-body='no-overflow' title='CopySafe Video'><img src='" . plugin_dir_url(__FILE__) . "/images/copysafevideobutton.png'></a>";
}

// ============================================================================================================================
# browser detector js file
function wpcsv_load_js() {
  // load custom JS file
  //wp_enqueue_script( 'wpcsv-browser-detector', plugins_url( '/browser_detection.js', __FILE__), array( 'jquery' ) );
}

// ============================================================================================================================
# admin page scripts
function wpcsv_admin_load_js() {
  // load jquery suggest plugin
  wp_enqueue_script('suggest');
  wp_enqueue_script('plupload-all');
}

// ============================================================================================================================
# admin page styles
function wpcsv_admin_load_styles() {
  // register custom CSS file & load
  wp_register_style('wpcsv-style', plugins_url('/css/wp-copysafe-video.css', __FILE__));
  wp_enqueue_style('wpcsv-style');
}

function wpcsv_is_admin_postpage() {
  $chk = FALSE;

  $tmp = explode("/", $_SERVER["SCRIPT_NAME"]);
  $ppage = end($tmp);


  
  if ($ppage == "post-new.php" || $ppage == "post.php") {
    return TRUE;
  }
}

function wpcsv_includecss_js() {
  if (!wpcsv_is_admin_postpage()) {
    return;
  }
  global $wp_popup_upload_lib;
  if ($wp_popup_upload_lib) {
    return;
  }
  $wp_popup_upload_lib = TRUE;
  echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.9.2/themes/redmond/jquery-ui.css' type='text/css' />";
  // wp_enqueue_script( 'jquery.uploadify');
  wp_enqueue_script('jquery');

  wp_enqueue_script('jquery.json', FALSE, ['jquery']);
  //wp_enqueue_script('plugin-script', false, array('jquery','plupload-all'));
}

function wpcsv_includecss_js_to_footer(){
	if (!wpcsv_is_admin_postpage())
		return;
	
	?>
	<script>
	if( jQuery("#wpcsv_link").length > 0 ){
		if( jQuery("#wpcsv_link").data("body") == "no-overflow" ){
			jQuery("body").addClass("wps-no-overflow");
			
		}
	}
	</script>
	<?php
}

// ============================================================================================================================
# setup plugin
function wpcsv_setup() {
  //----add codding----
  
  $options = get_option("wpcsv_settings");
  define('WPCSV_PLUGIN_PATH', str_replace("\\", "/", plugin_dir_path(__FILE__))); //use for include files to other files
  define('WPCSV_PLUGIN_URL', plugins_url('/', __FILE__));
  define('WPCSV_UPLOAD_PATH', str_replace("\\", "/", ABSPATH . $options["settings"]["upload_path"])); //use for include files to other files
  define('WPCSV_UPLOAD_URL', site_url($options["settings"]["upload_path"]));

  include(WPCSV_PLUGIN_PATH . "login-status.php");
  include(WPCSV_PLUGIN_PATH . "function.php");
  add_action('admin_head', 'wpcsv_includecss_js');
  add_action('admin_footer', 'wpcsv_includecss_js_to_footer');
  add_action('wp_ajax_wpcsv_ajaxprocess', 'wpcsv_ajaxprocess');

  //Sanitize the GET input variables
  $pagename = sanitize_key(@$_GET['page']);
  if (!$pagename) {
    $pagename = '';
  }
  $csvfilename = sanitize_file_name(@$_GET['csvfilename']);
  if (!$csvfilename) {
    $csvfilename = '';
  }
  $action = sanitize_key(@$_GET['action']);
  if (!$action) {
    $action = '';
  }
  $cspdel_nonce = sanitize_key(@$_GET['cspdel_nonce']);

  if ($pagename == 'wpcsv_list' && @$_GET['csvfilename'] && @$_GET['action'] == 'csvdel') {
    //check that nonce is valid and user is administrator
    if (current_user_can('administrator') && wp_verify_nonce(@$_GET['csvdel_nonce'], 'csvdel')) {
      echo "Nonce has been verified";
      wpcsv_delete_file_options(@$_GET['csvfilename']);
      if (file_exists(WPCSV_UPLOAD_PATH . @$_GET['csvfilename'])) {
        unlink(WPCSV_UPLOAD_PATH . @$_GET['csvfilename']);
      }
      wp_redirect('admin.php?page=wpcsv_list');
    }
    else {
      wp_nonce_ays();
    }
  }

  if (isset($_GET['wpcsv-popup']) && @$_GET["wpcsv-popup"] == "file_upload") {
    require_once(WPCSV_PLUGIN_PATH . "popup_load.php");
    exit();
  }
  //=============================
  // load js file
  add_action('wp_enqueue_scripts', 'wpcsv_load_js');

  // load admin CSS
  add_action('admin_print_styles', 'wpcsv_admin_load_styles');

  // add short code
  add_shortcode('copysafevideo', 'wpcsv_shortcode');

  // if user logged in
  if (is_user_logged_in()) {
    // install admin menu
    add_action('admin_menu', 'wpcsv_admin_menus');

    // check user capability
    if (current_user_can('edit_posts')) {
      // load admin JS
      add_action('admin_print_scripts', 'wpcsv_admin_load_js');
      // load media button
      add_action('media_buttons', 'wpcsv_media_buttons');
    }
  }

  wp_register_script('plugin-script', WPCSV_PLUGIN_URL . 'js/copysafevideo_media_uploader.js');
  wp_register_script('jquery.json', WPCSV_PLUGIN_URL . 'lib/jquery.json-2.3.js', ['plupload-all']);
}

// ============================================================================================================================
# runs when plugin activated
function wpcsv_activate() {
  // if this is first activation, setup plugin options
  if (!get_option('wpcsv_settings')) {
    // set plugin folder
    $upload_dir = 'wp-content/uploads/copysafe-video/';

    // set default options
    $wpcsv_options['settings'] = [
      'admin_only' => "checked",
      'upload_path' => $upload_dir,
      'mode' => "demo",
      'language' => "",
      'width' => '620',
      'height' => '400',
      'asps' => "checked",
      'ff' => "",
      'ch' => "",
	  'watermarked' => "checked",
      'wtmtextsize' => "20",
	  'wtmtextcolour' => "FFFF00",
      'wtmtextposition' => "0",
	  'wtmtextopacity' => "9",
	   
				

    ];

    update_option('wpcsv_settings', $wpcsv_options);

    $upload_dir = ABSPATH . $upload_dir;
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0, TRUE);
    }
    // create upload directory if it is not exist
  }
}

// ============================================================================================================================
# runs when plugin deactivated
function wpcsv_deactivate() {
  // remove text editor short code
  remove_shortcode('copysafevideo');
}

// ============================================================================================================================
# runs when plugin deleted.
function wpcsv_uninstall() {
  // delete all uploaded files
  $default_upload_dir = ABSPATH . 'wp-content/uploads/copysafe-video/';
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
  $options = get_option("wpcsv_settings");

  if ($options["settings"]["upload_path"]) {
    $upload_path = ABSPATH . $options["settings"]["upload_path"];
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
  delete_option('wpcsv_settings');

  // unregister short code
  remove_shortcode('copysafevideo');

  // delete short code from post content
  wpcsv_delete_shortcode();
}

// ============================================================================================================================
# register plugin hooks
register_activation_hook(__FILE__, 'wpcsv_activate'); // run when activated
register_deactivation_hook(__FILE__, 'wpcsv_deactivate'); // run when deactivated
register_uninstall_hook(__FILE__, 'wpcsv_uninstall'); // run when uninstalled

add_action('init', 'wpcsv_setup');
//Imaster Coding

function wpcsv_admin_js() {

  wp_register_script('wp-copysafe-video-uploader', WPCSV_PLUGIN_URL . 'js/copysafevideo_media_uploader.js', [
    'jquery',
    'plupload-all',
  ]);
}

add_action('admin_enqueue_scripts', 'wpcsv_admin_load_js');


function wpcsv_admin_head() {
	$get_setting_option = get_option('wpcsv_settings');
  $uploader_options = [
    'runtimes' => 'html5,silverlight,flash,html4',
    'browse_button' => 'mfu-plugin-uploader-button',
    'container' => 'mfu-plugin-uploader',
    'drop_element' => 'mfu-plugin-uploader',
    'file_data_name' => 'async-upload',
    'multiple_queues' => TRUE,
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
      'action' => 'my-plugin-upload-action',
    ],
  ];
  ?>
    <script type="text/javascript">
      var global_uploader_options =<?php echo json_encode($uploader_options); ?>;
    </script>
  <?php
}

add_action('admin_head', 'wpcsv_admin_head');

function wpcsv_ajax_action() {
  add_filter('upload_dir', 'wpcsv_upload_dir');
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
        'action' => 'my-plugin-upload-action',
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
  remove_filter('upload_dir', 'wpcsv_upload_dir');
  echo json_encode($response);
  exit;
}

add_action('wp_ajax_my-plugin-upload-action', 'wpcsv_ajax_action');

$upload = wp_upload_dir();
remove_filter('upload_dir', 'wpcsv_upload_dir');

function wpcsv_upload_dir($upload) {
  $upload['subdir'] = '/copysafe-video';
  $upload['path'] = $upload['basedir'] . $upload['subdir'];
  $upload['url'] = $upload['baseurl'] . $upload['subdir'];
  return $upload;
}

?>