<?php
define("HOST", "localhost");
define("SQLUSER", "");
define("SQLPASS", "");
define("SSHIP", "");
define("SSHUSER", "");
define("SSHPASS", "");

$sql = new MySQLi(HOST, SQLUSER, SQLPASS);

require_once('Net/SSH2.php');
$ssh = new Net_SSH2(SSHIP);
$ssh->login(SSHUSER, SSHPASS);
?>