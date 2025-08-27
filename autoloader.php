<?php
namespace Q\Widgets;

spl_autoload_register(function ($class)
{
	$class = substr($class, strlen(__NAMESPACE__ . '\\'));
	$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	$class = dirname(__FILE__).'/'.'plugin/'.$class .'.php';
	if (!file_exists($class))
	{
		error_log('Class ['.$class.'] not found');
		return;
	}
	require_once $class;
});
?>