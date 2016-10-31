<?php
if (!defined('INCL_FILE')) die('HTTP/1.0 403 Forbidden');

require_once __DIR__ . '/config.php';

// Loads all .class files in 'classes/'
spl_autoload_register(function($class) {
	if (file_exists(__DIR__ . '/classes/' . $class . '.class.php')) {
		require __DIR__ . '/classes/' . $class . '.class.php';
	}
});
?>
