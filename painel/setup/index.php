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
$csenha_usuario = $_POST['csenha_usuario'];
$loginp = $_POST['loginp'];
$senha_usuariop = $_POST['senha_usuariop'];
$csenha_usuariop = $_POST['csenha_usuariop'];
$email = $_POST['email'];
$versao = $_POST['versao'];
if ($ip != "" && $ssh_login != "" && $ssh_senha != "" && $login != "" && $senha_usuario != "" && $csenha_usuario != "" && $loginp != "" && $senha_usuariop != "" && $csenha_usuariop != "" && $email != ""  && $versao != "") {
if ($senha_usuario == $csenha_usuario && $senha_usuariop == $csenha_usuariop) {

$sql = new MySQLi('localhost','root','');
if ($sql->connect_errno) {
    die('Connect Error: ' . $sql->connect_errno);
}

$sql->query("CREATE USER '$login'@'localhost' IDENTIFIED BY '$senha_usuario'");
$sql->query("CREATE USER '$login'@'$ip' IDENTIFIED BY '$senha_usuario'");
$sql->query("GRANT ALL PRIVILEGES ON * . * TO '$login'@'localhost' IDENTIFIED BY '$senha_usuario' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
$sql->query("GRANT ALL PRIVILEGES ON * . * TO '$login'@'$ip' IDENTIFIED BY '$senha_usuario' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
$sql->query("CREATE DATABASE brAthena_Principal");
$sql->query("CREATE DATABASE brAthena_Logs");
$sql->query("CREATE DATABASE brAthena_DB");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Principal`.* TO '$login'@'localhost' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Logs`.* TO '$login'@'localhost' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_DB`.* TO '$login'@'localhost' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Principal`.* TO '$login'@'$ip' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Logs`.* TO '$login'@'$ip' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_DB`.* TO '$login'@'$ip' WITH GRANT OPTION");

$sql->close();

$confs = file_get_contents("/var/www/html/painel/confs.php");
$txt1c = array('"SQLUSER", ""','"SQLPASS", ""', '"SSHUSER", ""', '"SSHPASS", ""');
$txt2c = array('"SQLUSER", "'.$login.'"','"SQLPASS", "'.$senha_usuario.'"', '"SSHUSER", "'.$ssh_login.'"', '"SSHPASS", "'.$ssh_senha.'"');
$escreveconfs = str_replace($txt1c, $txt2c, $confs);
$novoconfs = fopen("/var/www/html/painel/confs.php", "w");
fwrite($novoconfs, $escreveconfs);
fclose($novoconfs);

include_once("../confs.php");

$ssh->exec("mysql -u ".$ssh_login." -p".$ssh_senha." brAthena_Principal < /home/emulador/sql/principal.sql");
$ssh->exec("mysql -u ".$ssh_login." -p".$ssh_senha." brAthena_Logs < /home/emulador/sql/logs.sql");
if ($versao == "pre") { $vs = "pre-renovacao/pre-renovacao.sql"; } else { $vs = "renovacao/renovacao.sql"; }
$ssh->exec("mysql -u ".$ssh_login." -p".$ssh_senha." brAthena_DB < /home/emulador/sql/".$vs."");

$sql->select_db("brAthena_Principal");
$sql->query("INSERT INTO `login` (userid, userpass, sex, email, group_id) VALUES ('$loginp', '$senha_usuariop', 'M', '$email', '99')");

$sql->query("USE mysql");
$sql->query("DROP DATABASE IF EXISTS test");
$sql->query("DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%'");
$sql->query("DELETE FROM mysql.user WHERE User='' OR (User='root' AND Host!='localhost')");
$sql->query("UPDATE mysql.user SET Password=PASSWORD('$senha_usuario') WHERE User='root' AND Host='localhost'");

$ssh->exec("chmod 644 /var/www/html/painel/confs.php");

$ssh->exec("chmod -R 646 /home/conf");
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

} else {
echo "Senhas não coincidem!";	
}
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
} else {
?>
Por favor, preencha todos os campos:
<form method="post" action="?a=configurar">
<table>
<tr>
<td>IP do servidor:</td>
<td><input type="text" name="ip"></td>
</tr>
<tr>
<td>Login SSH:</td>
<td><input type="text" name="ssh_login"></td>
</tr>
<tr>
<td>Senha SSH:</td>
<td><input type="password" name="ssh_senha"></td>
</tr>
<tr>
<td>Crie um usuario (Mysql):</td>
<td><input type="text" name="login"></td>
</tr>
<tr>
<td>Defina Senha para o usuario:</td>
<td><input type="password" name="senha_usuario"></td>
</tr>
<tr>
<td>Digite novamente:</td>
<td><input type="password" name="csenha_usuario"></td>
</tr>
<tr>
<td>Crie um usuario admin (Painel/Jogo):</td>
<td><input type="text" name="loginp"></td>
</tr>
<tr>
<td>Defina Senha para o admin:</td>
<td><input type="password" name="senha_usuariop"></td>
</tr>
<tr>
<td>Digite novamente:</td>
<td><input type="password" name="csenha_usuariop"></td>
</tr>
<tr>
<td>Digite seu email:</td>
<td><input type="email" name="email"></td>
</tr>
<tr>
<td>Selecione a versão do jogo que deseja importar:</td>
<td><input type="radio" name="versao" value="pre" id="pre"><label for="pre"> Pre-RE</label> | <input type="radio" name="versao" value="re" id="re"><label for="re"> Renew</label></td>
</tr>
</table>
<input type="submit" value="Instalar">
</form>
</body>
</html>
<?php
}
?>