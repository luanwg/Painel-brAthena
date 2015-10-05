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
$ip = $_POST['ip'];
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
$comando[17] = "/usr/bin/mysqladmin -u root password '$senha_usuario'";
$comando[18] = "mysql -u root -p".$senha_usuario." -e \"CREATE USER 'brAthena'@'".$_SERVER['SERVER_ADDR']."' IDENTIFIED BY '$senha_usuario'\"";
$comando[19] = "mysql -u root -p".$senha_usuario." -e \"GRANT ALL PRIVILEGES ON * . * TO 'brAthena'@'".$_SERVER['SERVER_ADDR']."' IDENTIFIED BY '$senha_usuario' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0\"";


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



$sql = new MySQLi($ip,'brAthena',$senha_usuario);
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

$ssh->exec("mysql -u root -p".$senha_usuario." brAthena_Principal < /home/emulador/sql/principal.sql");
$ssh->exec("mysql -u root -p".$senha_usuario." brAthena_Logs < /home/emulador/sql/logs.sql");
if ($versao == "pre") { $vs = "pre-renovacao/pre-renovacao.sql"; } else { $vs = "renovacao/renovacao.sql"; }
$ssh->exec("mysql -u root -p".$senha_usuario." brAthena_DB < /home/emulador/sql/".$vs."");

$sql->select_db("brAthena_Principal");
$sql->query("INSERT INTO `login` (userid, userpass, sex, email, group_id) VALUES ('$loginp', '$senha_usuariop', 'M', '$email', '99')");

$sql->query("USE mysql");
$sql->query("DROP DATABASE IF EXISTS test");
$sql->query("DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%'");
$sql->query("DELETE FROM mysql.user WHERE User='' OR User='brAthena' OR (User='root' AND Host!='localhost')");

$ssh->exec("chmod 646 -R /home/emulador/conf");
$instalacao_completa = "sim";


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
Acessar painel de controle:<br>
<form method="post" action="http://<?php echo $ip; ?>/painel/setup/?a=configurar">
<input type="hidden" name="ssh_login" value="<?php echo $sshlogin ?>">
<input type="hidden" name="ssh_senha" value="<?php echo $sshsenha ?>">
<input type="hidden" name="login" value="<?php echo $login ?>">
<input type="hidden" name="senha_usuario" value="<?php echo $senha_usuario ?>">
<input type="hidden" name="loginp" value="<?php echo $loginp ?>">
<input type="hidden" name="senha_usuariop" value="<?php echo $senha_usuariop ?>">
<input type="submit" value="Clique Aqui">
</form>
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