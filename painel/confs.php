<?php
define("HOST", "localhost");
define("SQLUSER", "");
define("SQLPASS", "");

$sql = new MySQLi(HOST, SQLUSER, SQLPASS);

require_once('Net/SSH2.php');
?>