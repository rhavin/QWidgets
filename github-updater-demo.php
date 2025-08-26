<?php
namespace Q\Widgets;

/**
 * Plugin Name:        QWidgets
 * Plugin URI:         https://github.com/rhavin/QWidgets
 * Version:            0.0.1
 * Description:        Description
 * Author:             rhavin
 * Author URI:         https://rhavin.de/
 * Text Domain:        QWidgets
 * Tested up to:       6.8.1
 * Requires at least:  6.5
 * Requires PHP:       8.2
 * Update URI:         https://github.com/rhavin/QWidgets
 * License:            none
 * License URI:        none
 */

if (!defined('ABSPATH'))
	exit;

require_once 'autoloader.php';
new Plugin(__FILE__);
?>