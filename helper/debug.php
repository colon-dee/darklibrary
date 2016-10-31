<?php
if (!defined(INCL_FILE)) die('HTTP/1.0 403 Forbidden');

/**
 * Get an error message from its ID
 * Additional parameters will replace any %?% found in the message
 * @see messages.json
 * @param  string $id
 * @return string
 */
function getErrorMessage($id) {
	// Load predefined error messages
	if (!isset($def_errorMessage)) {
		$messagesDir = __DIR__ . '/messages.json';

		if (file_exists($messagesDir)) {
			$def_errorMessage = file_get_contents($messagesDir);
			$def_errorMessage = json_decode($def_errorMessage);
		} else {
			die('There was a problem trying to run Dark Libray: Messages file not found.');
		}
		unset($messagesDir);
	}

	// Verify if codError exists
	$message = isset($def_errorMessage->{$id}) ? $def_errorMessage->{$id} : "Unknown error";

	// Replace %?% with the arguments data
	for ($i=1; $i < func_num_args(); $i++) {
		$pos = strpos($message, "%?%");
		$message = substr_replace($message, func_get_arg($i), $pos, strlen("%?%"));
	}

	return $message;
}

/**
 * Some PHP versions don't have the function json_last_error_msg,
 * the code below creates the function if it does not exist.
 */
if (!function_exists('json_last_error_msg')) {
	function json_last_error_msg() {
		static $ERRORS = array(
		JSON_ERROR_NONE => 'No error',
		JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
		JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
		JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		JSON_ERROR_SYNTAX => 'Syntax error',
		JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
		);

		$error = json_last_error();
		return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Unknown error';
	}
}
?>
