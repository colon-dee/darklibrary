<?php
define('INCL_FILE', 'true');
require_once '../helper/ajaxRestriction.php';
$def_printHTML = false;
require_once '../construct.php';

echo User::logIn($_POST['username'], $_POST['password']);

?>
