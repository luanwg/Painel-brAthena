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
$ip = $_POST['ip'];
$ssh_login = $_POST['ssh_login'];
$ssh_senha = $_POST['ssh_senha'];
$login = $_POST['login'];
$senha_usuario = $_POST['senha_usuario'];
$loginp = $_POST['loginp'];
$senha_usuariop = $_POST['senha_usuariop'];
if ($ip != "" && $ssh_login != "" && $ssh_senha != "" && $login != "" && $senha_usuario != "" && $loginp != "" && $senha_usuariop != "") {

$sql = new MySQLi("localhost", $login, $senha_usuario);
if ($sql->connect_errno) {
    die('Connect Error: ' . $sql->connect_errno);
}
require_once('Net/SSH2.php');
$ssh = new Net_SSH2("localhost");
if (!$ssh->login($ssh_login, $ssh_senha)) {
	die('Não foi possível conectar ao servidor com o login/senha SSH fornecido!');
}
$confs = file_get_contents("/var/www/html/painel/confs.php");
$txt1c = array('"IP", ""', '"SQLUSER", ""','"SQLPASS", ""', '"SSHUSER", ""', '"SSHPASS", ""');
$txt2c = array('"IP", "'.$ip.'"', '"SQLUSER", "'.$login.'"','"SQLPASS", "'.$senha_usuario.'"', '"SSHUSER", "'.$ssh_login.'"', '"SSHPASS", "'.$ssh_senha.'"');
$escreveconfs = str_replace($txt1c, $txt2c, $confs);
$novoconfs = fopen("/var/www/html/painel/confs.php", "w");
fwrite($novoconfs, $escreveconfs);
fclose($novoconfs);
$ssh->exec("chmod 644 /var/www/html/painel/confs.php");

$usrid = substr(md5($login.time()),0,10);
$passwd  = substr(md5($senha_usuario.time()),0,10);

$sql->select_db("brAthena_Principal");
$sql->query("UPDATE `login` SET `userid`='".$usrid."', `user_pass`='".$passwd."' WHERE `account_id`='1'");

$sql->select_db("brAthena_Confs");
$sql->query("INSERT INTO `import` (config,valor,desc,ref) VALUES ('userid: ','".$usrid."','Configuração das senhas de comunicação do banco de dados.','map-server.conf'),('passwd: ','".$passwd."','Configuração das senhas de comunicação do banco de dados.','map-server.conf'),('char_ip: ','".$ip."','IP do Servidor de Personagens (char-server).','map-server.conf'),('map_ip: ','".$ip."','IP do Servidor de Mapas (map-server).','map-server.conf'),('sql.db_hostname: ','".$ip."','Banco de dados de informações do servidor de login.','inter-server.conf'),('sql.db_username: ','".$login."','Banco de dados de informações do servidor de login.','inter-server.conf'),('sql.db_password: ','".$senha_usuario."','Banco de dados de informações do servidor de login.','inter-server.conf'),('sql.db_database: ','brAthena_Principal','Banco de dados de informações do servidor de login.','inter-server.conf'),('char_server_ip: ','".$ip."','Banco de dados de informações do servidor de personagens.','inter-server.conf'),('char_server_id: ','".$login."','Banco de dados de informações do servidor de personagens.','inter-server.conf'),('char_server_pw: ','".$senha_usuario."','Banco de dados de informações do servidor de personagens.','inter-server.conf'),('char_server_db: ','brAthena_Principal','Banco de dados de informações do servidor de personagens.','inter-server.conf'),('map_server_ip: ','".$ip."','Banco de dados de informações do servidor de mapas.','inter-server.conf'),('map_server_id: ','".$login."','Banco de dados de informações do servidor de mapas.','inter-server.conf'),('map_server_pw: ','".$senha_usuario."','Banco de dados de informações do servidor de mapas.','inter-server.conf'),('map_server_db: ','brAthena_Principal','Banco de dados de informações do servidor de mapas.','inter-server.conf'),('log_db_ip: ','".$ip."','Banco de dados de logs.','inter-server.conf'),('log_db_id: ','".$login."','Banco de dados de logs.','inter-server.conf'),('log_db_pw: ','".$senha_usuario."','Banco de dados de logs.','inter-server.conf'),('log_db_db: ','brAthena_Logs','Banco de dados de logs.','inter-server.conf'),('brAdb_ip: ','".$ip."','Banco de dados de itens, habilidades, monstros e etc.','inter-server.conf'),('brAdb_id: ','".$login."','Banco de dados de itens, habilidades, monstros e etc.','inter-server.conf'),('brAdb_pw: ','".$senha_usuario."','Banco de dados de itens, habilidades, monstros e etc.','inter-server.conf'),('brAdb_name: ','brAthena_DB','Banco de dados de itens, habilidades, monstros e etc.','inter-server.conf'),('userid: ','".$usrid."','Configuração das senhas de comunicação com o banco de dados.','char-server.conf'),('passwd: ','".$passwd."','Configuração das senhas de comunicação com o banco de dados.','char-server.conf'),('login_ip: ','".$ip."','IP de login do servidor.','char-server.conf'),('char_ip: ','".$ip."','IP do servidor de personagens (char-server).','char-server.conf')");


$sql->query("SELECT `config`, `valor` FROM `import` WHERE `ref`='char-server.conf'");
while($char = $sql->fetch_array) {
$escrevechar .= $char[config].$char[valor].PHP_EOL;
}
$sql->query("SELECT `config`, `valor` FROM `import` WHERE `ref`='inter-server.conf'");
while($inter = $sql->fetch_array) {
$escreveinter .= $inter[config].$inter[valor].PHP_EOL;
}
$sql->query("SELECT `config`, `valor` FROM `import` WHERE `ref`='map-server.conf'");
while($map = $sql->fetch_array) {
$escrevemap .= $map[config].$map[valor].PHP_EOL;
}

//chown -R apache /home/emulador/conf/import | chmod -R 744 /home/emulador/conf/import
$novochar = fopen("/home/emulador/conf/import/char_conf.txt", "w");
fwrite($novochar, $escrevechar);
fclose($novochar);
$novointer = fopen("/home/emulador/conf/import/inter_conf.txt", "w");
fwrite($novointer, $escreveinter);
fclose($novointer);
$novomap = fopen("/home/emulador/conf/import/map_conf.txt", "w");
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
$ssh->exec("rm -rf /var/www/html/painel/setup");
}
?>
</body>
</html>