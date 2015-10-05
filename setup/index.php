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
// | Descrição: Instalação completa.                            |
// |------------------------------------------------------------|
// | Changelog:                                                 |
// | 1.0 Criação [Chuck]                                        |
// | Criação e instalação do servidor.(Última revisão do GitHub)|
// | Instalação dos compiladores necessários.                   |
// | Instalação dos plugins necessários.                        |
// | Instalação do apache, php, mysql e phpMyAdmin.             |
// | Instalação do painel de controle brAthena.                 |
// |------------------------------------------------------------|
// | - Anotações                                                |
// \____________________________________________________________/
$a = $_GET['a'];
if ($a == "instalacao") {
$ip = htmlspecialchars($_POST['ip']);
$sshlogin = $_POST['sshlogin'];
$sshsenha = $_POST['sshsenha'];
$so = $_POST['so'];
$login = $_POST['login'];
$senha_usuario = $_POST['senha_usuario'];
$csenha_usuario = $_POST['csenha_usuario'];
$loginp = $_POST['loginp'];
$senha_usuariop = $_POST['senha_usuariop'];
$csenha_usuariop = $_POST['csenha_usuariop'];
$email = $_POST['email'];
$versao = $_POST['versao'];
if ($ip != "" && $sshlogin != "" && $sshsenha != "" && $so != "" && $login != "" && $senha_usuario != "" && $csenha_usuario != "" && $loginp != "" && $senha_usuariop != "" && $csenha_usuariop != "" && $email != ""  && $versao != "") {
if ($senha_usuario == $csenha_usuario && $senha_usuariop == $csenha_usuariop) {
require_once('Net/SSH2.php');
$ssh = new Net_SSH2($ip);
if ($ssh->login($sshlogin, $sshsenha)) {
$comando[0] = "rpm -Uvh http://mirror.webtatic.com/yum/el6/latest.rpm";
$comando[1] = "rpm -ivh http://ftp.jaist.ac.jp/pub/Linux/Fedora/epel/6/i386/epel-release-6-8.noarch.rpm";
$comando[2] = "yum -y update";
$comando[3] = "yum install -y subversion httpd php56w php56w-mysql php56w-cli php56w-gd php56w-mbstring php56w-mhash php56w-pdo php56w-xmlrpc php56w-pear gcc gcc-c++ make pcre pcre-devel zlib zlib-devel git phpMyAdmin";
if ($so == "centos6") {
$comando[4] = "yum localinstall -y http://dev.mysql.com/get/mysql-community-release-el6-5.noarch.rpm";
$comando[5] = "yum install -y mysql-community-server mysql-devel";
} elseif ($so == "centos7") {
$comando[4] = "yum install -y mariadb mariadb-server";
$comando[5] = "yum install -y mysql mysql-server mysql-devel";
}
$comando[6] = "pear channel-discover phpseclib.sourceforge.net";
$comando[7] = "pear install phpseclib/Net_SSH2";
$comando[8] = "svn co https://github.com/brAthena/brAthena/trunk /home/emulador";
$comando[9] = "rm -rf /etc/httpd/conf.d/phpMyAdmin.conf";
$comando[10] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/confs/phpMyAdmin /etc/httpd/conf.d";
$comando[11] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/confs/sv /usr/bin";
$comando[12] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/painel /var/www/html/painel";
$comando[13] = "yum -y update";
$comando[14] = "chmod +x /home/emulador/sysinfogen.sh | chmod 777 /home/emulador/configure | chmod 777 /usr/bin/sv | chmod 646 /var/www/html/painel/confs.php";
if ($so == "centos6") {
$comando[15] = "chkconfig httpd on | chkconfig mysqld on";
$comando[16] = "service httpd start | service mysqld start";
} elseif ($so == "centos7") {
$comando[15] = "systemctl enable httpd.service | systemctl enable mariadb.service";
$comando[16] = "systemctl start mariadb.service | systemctl start httpd.service";
}


if (ob_get_level() == 0) ob_start(); 
set_time_limit(1800);
ob_implicit_flush(true);
echo '<div style="width: 750px; height: 350px; border: 2px solid; border-color: #666; background: #F5F5F5; overflow: auto; padding: 10px;">';
for ($i = 0; $i < count($comando); $i++) {
echo '<pre>'.$ssh->exec($comando[$i]).'</pre>';
ob_flush();
flush();
usleep(50000);
}
echo '</div>';




$sql = new MySQLi('$ip','root','');
if ($sql->connect_errno) {
    die('Connect Error: ' . $sql->connect_errno);
}
/*
Acrescentar os mysqli_real_escape_string aqui em tudo que for usar!
*/
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

/*
$confs = file_get_contents("/var/www/html/painel/confs.php");
$txt1c = array('"SQLUSER", ""','"SQLPASS", ""', '"SSHUSER", ""', '"SSHPASS", ""');
$txt2c = array('"SQLUSER", "'.$login.'"','"SQLPASS", "'.$senha_usuario.'"', '"SSHUSER", "'.$ssh_login.'"', '"SSHPASS", "'.$ssh_senha.'"');
$escreveconfs = str_replace($txt1c, $txt2c, $confs);
$novoconfs = fopen("/var/www/html/painel/confs.php", "w");
fwrite($novoconfs, $escreveconfs);
fclose($novoconfs);

include_once("../confs.php");
*/
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

$ssh->exec("chmod -R 646 /home/conf");
/*
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

*/




} else {
	echo "Login erro";
}
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
<title>Painel de Instalação - brAthena</title>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</head>

<body>

<?php
if ($instalacao_completa == "sim") {
?>
<br><br>
<strong>Sua instalação está completa!!</strong><br>
Acessar painel de controle: <a href="http://<?php echo $ip; ?>/painel/setup">Clique aqui</a>
<?php
} else {
?>
<a href="#" onClick="document.getElementById('instalacao').style.display='block'">Instalar emulador</a>
<div id="instalacao" style="display:none">
<br><br>
Dados do seu VPS:<br><br>
<form method="post" action="?a=instalacao">
<table width="400px">
<tr>
<td>IP</td><td><input name="ip" type="text"></td>
</tr>
<tr>
<td>Sistema Operacional:</td><td>
<select name="so">
<option value="">Selecione</option>
<option value="centos6">CentOS 6</option>
<option value="centos7">CentOS 7</option>
</select>
</td>
</tr>
<tr>
<td>Login VPS (root)</td><td><input name="sshlogin" type="text"></td>
</tr>
<tr>
<td>Senha VPS</td><td><input name="sshsenha" type="password"></td>
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
<td>Crie um usuario (Painel/Jogo->[admin]):</td>
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
<tr>
<td></td><td><input type="submit" value="Instalar"></td>
</tr>
</table>
</form>
</div>
<?php
}
?>
</body>
</html>