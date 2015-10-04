<?php
//  ____________________________________________________________
// /                                                            \
// |         _           _   _   _                              |
// |        | |__  _ __ / \ | |_| |__   ___ _ __   __ _         |
// |        | '_ \| '__/ _ \| __| '_ \ / _ \ '_ \ / _` |        |
// |        | |_) | | / ___ \ |_| | | |  __/ | | | (_| |        |
// |        |_.__/|_|/_/   \_\__|_| |_|\___|_| |_|\__,_|        |
// |                                                            |
// |                       brAthena Script                      |
// |------------------------------------------------------------|
// | Nome do arquivo: index.php                                 |
// |------------------------------------------------------------|
// | Criado por: Luan (Chuck)                                   |
// |------------------------------------------------------------|
// | Descrição: Painel de controle.                             |
// |------------------------------------------------------------|
// | Changelog:                                                 |
// | 1.0 Criação [Chuck]                                        |
// |------------------------------------------------------------|
// | - Anotações                                                |
// \____________________________________________________________/

$a = $_GET['a'];
if ($a == "excluir-setup") { unlink("setup"); header('Location: /painel'); }
require_once("confs.php");

if ($a == "start") {
$ssh->exec("../home/emulador/login-server > /var/www/html/painel/logs/login.txt &"); 
$ssh->exec("../home/emulador/char-server > /var/www/html/painel/logs/char.txt &"); 
$ssh->exec("../home/emulador/map-server > /var/www/html/painel/logs/map.txt &"); 
}

if ($a == "stop") {
$ssh->exec("killall login-server | killall char-server | killall map-server");
}

if ($a == "restart") {
$ssh->exec("killall login-server | killall char-server | killall map-server");
sleep(2);
$ssh->exec("../home/emulador/login-server > /var/www/html/painel/logs/login.txt &"); 
$ssh->exec("../home/emulador/char-server > /var/www/html/painel/logs/char.txt &"); 
$ssh->exec("../home/emulador/map-server > /var/www/html/painel/logs/map.txt &"); 
}

if ($a == "compilar") {
$ssh->exec("../home/emulador/configure > /var/www/html/painel/logs/compilacao.txt");
$ssh->exec("cd /home/emulador | make clean >> /var/www/html/painel/logs/compilacao.txt");
$ssh->exec("cd /home/emulador | make sql >> /var/www/html/painel/logs/compilacao.txt");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Painel - brAthena</title>


</head>

<body>
<a href="?a=start">Start</a> - <a href="?a=stop">Stop</a> - <a href="?a=restart">Restart</a> - <a href="?a=compilar">Compilar</a> - <a href="?a=atualizar">Atualizar</a>
<br><br>
<br>
Status:<br>
<?php
$Status = array();
$LoginServer = fsockopen("localhost", 6900, $errno, $errstr, 1);
$CharServer = fsockopen("localhost", 6121, $errno, $errstr, 1);
$MapServer = fsockopen("localhost", 5121, $errno, $errstr, 1);
if(!$LoginServer){ $Status[0] = "OFF";  } else { $Status[0] = "ON"; };
if(!$CharServer){ $Status[1] = "OFF";  } else { $Status[1] = "ON"; };
if(!$MapServer){ $Status[2] = "OFF";  } else { $Status[2] = "ON"; };
?>
Login: <?php echo "$Status[0]"; ?> - <a target="_blank" href="logs/index.php?log=login-server">Logs</a><br>
Char: <?php echo "$Status[1]"; ?> - <a target="_blank" href="logs/index.php?log=char-server">Logs</a><br>
Map: <?php echo "$Status[2]"; ?> - <a target="_blank" href="logs/index.php?log=map-server">Logs</a><br><br>
Compilação:  - <a target="_blank" href="logs/index.php?log=compilacao">Logs</a>
</body>
</html>