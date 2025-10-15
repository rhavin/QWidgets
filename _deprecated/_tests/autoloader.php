<?php
namespace Q;

spl_autoload_register(function ($class)
{
	if (!str_starts_with($class, __NAMESPACE__. '\\'))
		return; // aint me babe
	$class = substr($class, strlen(__NAMESPACE__ . '\\'));
	$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	$class = __DIR__.'/../../src/'.$class .'.php';
	if (!file_exists($class))
	{
		error_log('Class -['.$class.']- not found.');
		print 'Class -['.$class.']- not found.'."\n";
		return;
	}
	require_once $class;
});

?>