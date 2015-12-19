<?php  
/**
 * @package WJFramework
 */
if (version_compare(PHP_VERSION, '5.3.0') < 0 ) {
	die("You're runing WordPress on outdated PHP version. Please contact your hosting company and updgrade PHP to 5.3 or above. Learn more about new features in PHP 5.3 - http://www.php.net/manual/en/migration53.new-features.php For cPanel users - you may easily switch PHP version using your hosting settings.");
}
require_once( get_template_directory() . '/framework/init.php' );
AT_Module_Init::init();
?>