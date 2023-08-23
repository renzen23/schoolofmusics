<?php
/**
 */
If (!Class_Exists('WPCSWPOPUP')) {

/**
 *
 */
class WPCSWPOPUP
{
function __construct()
{
  WPCSWPOPUP::add_popup_stylesheet();
  WPCSWPOPUP::add_popup_script();
  call_user_func_array(array('WPCSWPOPUP', 'set_media_upload'), array());
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

public function set_media_upload()
{
  include(WPCSW_PLUGIN_PATH . "media-upload.php");
}

public function add_popup_stylesheet()
{
  echo "<link rel='stylesheet' href='" . WPCSW_PLUGIN_URL . "css/wp-copysafe-web.css' type='text/css' />";
}

public function add_popup_script()
{
  echo "<script type='text/javascript' src='" . WPCSW_PLUGIN_URL . "js/copysafe_media_uploader.js'></script>";
}
}
$popup = new WPCSWPOPUP ();
}
?>