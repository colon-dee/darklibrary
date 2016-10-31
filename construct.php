<?php
if (!defined(INCL_FILE)) die('HTTP/1.0 403 Forbidden');
header ('Content-type: text/html; charset=UTF-8');

require_once __DIR__ . '/helper/debug.php';
require_once __DIR__ . '/autoload.php';

if (!isset($_SESSION)) {
    session_start();
}

# Default definitions.
$def_authentication = isset($def_authentication) ? $def_authentication : true; // Define if this file should make a session authentication.
$def_printHTML = isset($def_printHTML) ? $def_printHTML : true; // Define if this file should print the basic view for the page.
$def_navbar = isset($def_navbar) ? $def_navbar : true; // Define if the navbar should be shown.
$def_currentTab = isset($def_currentTab) ? $def_currentTab : null; // Give the $def_currentTab a default value.

if ($def_printHTML) {
?>

<html>
    <head>
        <title>Dark Library</title>

        <link rel="shortcut icon" type="image/x-icon" href="<?= $def_cred->rootURL ?>assets/dl.ico" />
        <link rel="stylesheet" type="text/css" href="<?= $def_cred->rootURL ?>assets/css/semantic.css" />
        <link rel="stylesheet" type="text/css" href="<?= $def_cred->rootURL ?>assets/css/style.css" />

        <script>
            messagesUrl = "<?= $def_cred->rootURL ?>helper/messages.json"
        </script>
        <script src="<?= $def_cred->rootURL ?>assets/js/jquery.js"></script>
        <script src="<?= $def_cred->rootURL ?>assets/js/semantic.js"></script>
        <script src="<?= $def_cred->rootURL ?>assets/js/helper.js"></script>
    </head>

    <body>
<?php
    if ($def_navbar) {
?>
<div class="ui inverted segment" id="navbar">
	<div class="huge ui violet secondary pointing menu">
		<a class="item<?= strtolower($def_currentTab) == 'home' ? ' active' : '" style="color: white;'?>">
			Home
		</a>
		<a class="item<?= strtolower($def_currentTab) == 'members' ? ' active' : '" style="color: white;'?>">
			Members
		</a>
        <a class="item<?= strtolower($def_currentTab) == 'lastest' ? ' active' : '" style="color: white;'?>">
			Latest uploads
		</a>
		<a class="item<?= strtolower($def_currentTab) == 'donate' ? ' active' : '" style="color: white;'?>">
			Donate
		</a>
        <a class="item<?= strtolower($def_currentTab) == 'invite' ? ' active' : '" style="color: white;'?>">
			Invite a friend
		</a>
        <a class="item<?= strtolower($def_currentTab) == 'vote' ? ' active' : '" style="color: white;'?>">
			Vote
            <div class="floating ui violet label">22</div>
		</a>
        <a class="item<?= strtolower($def_currentTab) == 'vote' ? ' active' : '" style="color: white;'?>">
			Logout
		</a>

        <div class="ui icon transparent right inverted input" style="position:absolute; right: 10px; top: 28px;">
            <input placeholder="Search..." type="text">
            <i class="search link icon"></i>
        </div>
	</div>
</div>

<div class="ui segment inverted" style="height: 100%;">
<?php
    }
}
?>
