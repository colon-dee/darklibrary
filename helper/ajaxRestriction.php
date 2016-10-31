<?php
if (!defined(INCL_FILE)) die('HTTP/1.0 403 Forbidden');

$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'; // Verify if it's an Ajax request
$host = isset($_SERVER['HTTP_REFERER']) ? strpos($_SERVER['HTTP_REFERER'], getenv('HTTP_HOST')) : ""; // Verify if the request comes from the the server
if(!$ajax || $host === false) {
	exit('Forbidden');
}
?>
