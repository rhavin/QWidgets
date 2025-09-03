<?php
namespace Q;

/**
 * Plugin Name:        QWidgets
 * Plugin URI:         https://github.com/rhavin/QWidgets
 * Version:            0.1.23
 * Description:        RDF/a aware Widgets for Classicpress and Wordpress 
 * Author:             rhavin
 * Author URI:         https://rhavin.de/
 * Text Domain:        QWidgets
 * Tested up to:       6.8.2
 * Requires at least:  6.2.5
 * Requires PHP:       8.1
 * Update URI:         https://github.com/rhavin/QWidgets
 * License:            Still in dev, use at own risk.
 * License URI:        none
 */

if (!defined('ABSPATH'))
	exit;

spl_autoload_register(function ($class)
{
	if (!str_starts_with($class, __NAMESPACE__. '\\'))
		return; // aint me babe
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

new WPWidgets\Plugin(__FILE__);
?>