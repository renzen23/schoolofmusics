<?php
/**
 */
If (!Class_Exists('WPCSVPOPUP')) {

/**
 *
 */
class WPCSVPOPUP
{
function __construct() {
  WPCSVPOPUP::add_popup_stylesheet();
  WPCSVPOPUP::add_popup_script();
  call_user_func_array(['WPCSVPOPUP', 'set_media_upload'], []);
}

public function header_html()
{?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <title><?php echo __("Step Setting"); ?></title>
</head>
<body>
<div id="wrapper" class="hfeed">
    <ul>
      <?php
      }

      public function footer_html()
      {
      ?>
    </ul>
</div>
</body>
<?php
}

public function set_media_upload() {
  include(WPCSV_PLUGIN_PATH . "media-upload.php");
}

public function add_popup_stylesheet() {
  //echo "<link rel='stylesheet' href='http://code.jquery.com/ui/1.9.2/themes/redmond/jquery-ui.css' type='text/css' />" ;
  //echo "<link rel='stylesheet' href='" . WPCSV_PLUGIN_URL . "lib/uploadify/uploadify.css' type='text/css' />" ;
  echo "<link rel='stylesheet' href='" . WPCSV_PLUGIN_URL . "css/wp-copysafe-video.css' type='text/css' />";
}

public function add_popup_script() {
  //echo "<script type='text/javascript' src='" . WPCSV_PLUGIN_URL . "lib/uploadify/jquery.min.js'></script>" ;
  //echo "<script type='text/javascript' src='" . WPCSV_PLUGIN_URL . "lib/uploadify/jquery.uploadify.min.js'></script>" ;
  //echo "<script type='text/javascript' src='" . WPCSV_PLUGIN_URL . "lib/jquery.json-2.3.js'></script>" ;
  echo "<script type='text/javascript' src='" . WPCSV_PLUGIN_URL . "js/copysafevideo_media_uploader.js'></script>";
}
}
$popup = new WPCSVPOPUP ();

}
?>