<?php
namespace Q\WPWidgets;

/**
 * Plugin Name:        QWidgets
 * Plugin URI:         https://github.com/rhavin/QWidgets
 * Version:            0.0.16
 * Description:        Description
 * Author:             rhavin
 * Author URI:         https://rhavin.de/
 * Text Domain:        QWidgets
 * Tested up to:       6.8.1
 * Requires at least:  6.5
 * Requires PHP:       8.1
 * Update URI:         https://github.com/rhavin/QWidgets
 * License:            none
 * License URI:        none
 */

if (!defined('ABSPATH'))
	exit;

spl_autoload_register(function ($class)
{
	if (substr($class, 0, strlen(__NAMESPACE__ . '\\')) !== __NAMESPACE__ . '\\')
		return;
	$class = substr($class, strlen(__NAMESPACE__ . '\\'));
	$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	$class = dirname(__FILE__).'/src/'.$class .'.php';
	if (!file_exists($class))
	{
		error_log('Class ['.$class.'] not found');
		return;
	}
	require_once $class;
});

new Plugin(__FILE__);
?>