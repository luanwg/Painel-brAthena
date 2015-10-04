<?php
define("HOST", "localhost");
define("SQLUSER", "");
define("SQLPASS", "");
define("SSHUSER", "");
define("SSHPASS", "");

$sql = new MySQLi(HOST, SQLUSER, SQLPASS);

require_once('Net/SSH2.php');
$ssh = new Net_SSH2(HOST);
$ssh->login(SSHUSER, SSHPASS);
?>