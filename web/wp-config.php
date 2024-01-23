<?php
/**
 * This is where you should at your configuration customizations. It will work out of the box on Pantheon
 * but you may find there are a lot of neat tricks to be used here.'
 * 
 * For local development, see .env.local-sample.
 *
 * See our documentation for more details:
 *
 * https://pantheon.io/docs
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Pantheon platform settings. Everything you need should already be set.
 */
if (file_exists(dirname(__FILE__) . '/wp-config-pantheon.php') && isset($_ENV['PANTHEON_ENVIRONMENT']) && ('lando' !== $_ENV['PANTHEON_ENVIRONMENT'])) {
	require_once(dirname(__FILE__) . '/wp-config-pantheon.php');
}

require_once dirname(__DIR__) . '/config/application.php';

require_once ABSPATH . 'wp-settings.php';

/** Pantheon: fix for contact-forms-7 */
$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
	if (isset($_SERVER['HTTP_USER_AGENT_HTTPS']) && $_SERVER['HTTP_USER_AGENT_HTTPS'] === 'ON') {
		$_SERVER['SERVER_PORT'] = 443;
	} else {
		$_SERVER['SERVER_PORT'] = 80;
	}
}

/** Pantheon: Autoptimize fix to change location where plugin stores optimized files */
define('AUTOPTIMIZE_CACHE_CHILD_DIR', '/uploads/private/cache/autoptimize/');
