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
// | Descrição: Setup - Painel de Controle                      |
// |------------------------------------------------------------|
// | Changelog:                                                 |
// | 1.0 Criação [Chuck]                                        |
// |------------------------------------------------------------|
// | - Anotações                                                |
// \____________________________________________________________/

$a = $_GET['a'];
if ($a == "configurar") {
$ssh_login = $_POST['ssh_login'];
$ssh_senha = $_POST['ssh_senha'];
$login = $_POST['login'];
$senha_usuario = $_POST['senha_usuario'];
$loginp = $_POST['loginp'];
$senha_usuariop = $_POST['senha_usuariop'];
if ($ssh_login != "" && $ssh_senha != "" && $login != "" && $senha_usuario != "" && $loginp != "" && $senha_usuariop != "") {

$sql = new MySQLi("localhost", $login, $senha_usuario);
if ($sql->connect_errno) {
    die('Connect Error: ' . $sql->connect_errno);
}
require_once('Net/SSH2.php');
$ssh = new Net_SSH2("localhost");
if (!$ssh->login($ssh_login, $ssh_senha)) {
	die('Não foi possível conectar ao servidor com o login/senha SSH fornecido!');
}
$sql->select_db("brAthena_Principal");
$sql->query("SELECT userid, userpass FROM `login` WHERE userid='$loginp', userpass='$senha_usuariop'");
if (!$sql->num_rows) {
	die('Login/Senha fornecidos estão incorretos!');	
}

$confs = file_get_contents("/var/www/html/painel/confs.php");
$txt1c = array('"SQLUSER", ""','"SQLPASS", ""', '"SSHUSER", ""', '"SSHPASS", ""');
$txt2c = array('"SQLUSER", "'.$login.'"','"SQLPASS", "'.$senha_usuario.'"', '"SSHUSER", "'.$ssh_login.'"', '"SSHPASS", "'.$ssh_senha.'"');
$escreveconfs = str_replace($txt1c, $txt2c, $confs);
$novoconfs = fopen("/var/www/html/painel/confs.php", "w");
fwrite($novoconfs, $escreveconfs);
fclose($novoconfs);
$ssh->exec("chmod 644 /var/www/html/painel/confs.php");

$usrid = substr(md5($login.time()),0,10);
$passwd  = substr(md5($senha_usuario.time()),0,10);
$conf1 = array('userid: s1', 'passwd: p1', '//login_ip: 127.0.0.1', '//char_ip: 127.0.0.1', '//map_ip: 127.0.0.1', 'sql.db_hostname: 127.0.0.1', 'sql.db_username: ragnarok', 'sql.db_password: ragnarok', 'sql.db_database: ragnarok', 'char_server_ip: 127.0.0.1', 'char_server_id: ragnarok', 'char_server_pw: ragnarok', 'char_server_db: ragnarok', 'map_server_ip: 127.0.0.1', 'map_server_id: ragnarok', 'map_server_pw: ragnarok', 'map_server_db: ragnarok', 'log_db_ip: 127.0.0.1', 'log_db_id: ragnarok', 'log_db_pw: ragnarok', 'log_db_db: log', 'log_login_db: loginlog', 'brAdb_ip: 127.0.0.1', 'brAdb_id: ragnarok', 'brAdb_pw: ragnarok', 'brAdb_name: bra_db');
$conf2 = array('userid: $usrid', 'passwd: $passwd', '//login_ip: $ip', '//char_ip: $ip', '//map_ip: $ip', 'sql.db_hostname: $ip', 'sql.db_username: $login', 'sql.db_password: $senha_usuario', 'sql.db_database: brAthena_Principal', 'char_server_ip: $ip', 'char_server_id: $login', 'char_server_pw: $senha_usuario', 'char_server_db: brAthena_Principal', 'map_server_ip: $ip', 'map_server_id: $login', 'map_server_pw: $senha_usuario', 'map_server_db: brAthena_Principal', 'log_db_ip: $ip', 'log_db_id: $login', 'log_db_pw: $senha_usuario', 'log_db_db: brAthena_Logs', 'log_login_db: brAthena_Logs', 'brAdb_ip: $ip', 'brAdb_id: $login', 'brAdb_pw: $senha_usuario', 'brAdb_name: brAthena_DB');
$char = file_get_contents("/home/emulador/conf/char-server.conf");
$escrevechar = str_replace($conf1, $conf2, $char);
$inter = file_get_contents("/home/emulador/conf/inter-server.conf");
$escreveinter = str_replace($conf1, $conf2, $inter);
$map = file_get_contents("/home/emulador/conf/map-server.conf");
$escrevemap = str_replace($conf1, $conf2, $map);
$novochar = fopen("/home/emulador/conf/char-server.conf", "w");
fwrite($novochar, $escrevechar);
fclose($novochar);
$novointer = fopen("/home/emulador/conf/inter-server.conf", "w");
fwrite($novointer, $escreveinter);
fclose($novointer);
$novomap = fopen("/home/emulador/conf/map-server.conf", "w");
fwrite($novomap, $escrevemap);
fclose($novomap);
$configurado = "sim";

} else {
echo "Dados em branco!";	
}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Setup - brAthena</title>
</head>

<body>
<?php
if ($configurado == "sim") {
?>
Configuração terminada com sucesso!<br>
Volte para o painel de controle: <a href="/painel">Clique aqui</a>
<?php
}
?>
</body>
</html>