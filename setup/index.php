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
$login = $_POST['login'];
$senha = $_POST['senha'];
$so = $_POST['so'];
if ($ip != "" && $login != "" && $senha != "" && $so != "") {
require_once('Net/SSH2.php');
$ssh = new Net_SSH2($ip);
if ($ssh->login($login, $senha)) {
$comando[0] = "yum install -y subversion";
$comando[1] = "yum install -y httpd";
$comando[2] = "yum install -y php php-mysql php-cli php-gd php-mbstring php-mhash php-pdo php-xmlrpc php-pear";
$comando[3] = "yum install -y gcc gcc-c++ make pcre pcre-devel zlib zlib-devel git";
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
$comando[9] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/phpMyAdmin /var/www/html/phpMyAdmin";
$comando[10] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/painel /var/www/html/painel";
$comando[11] = "yum -y update";
$comando[12] = "chmod +x /home/emulador/sysinfogen.sh | chmod 777 /home/emulador/configure";
if ($so == "centos6") {
$comando[13] = "chkconfig httpd on | chkconfig mysqld on";
$comando[14] = "service httpd start | service mysqld start";
} elseif ($so == "centos7") {
$comando[13] = "systemctl enable httpd.service | systemctl enable mariadb.service";
$comando[14] = "systemctl start mariadb.service | systemctl start httpd.service";
}

if (ob_get_level() == 0) ob_start(); 
set_time_limit(1800);
ob_implicit_flush(true);
echo '<div style="width: 750px; height: 350px; border: 2px solid; border-color: #666; background: #F5F5F5; overflow: auto; padding: 10px;">';
for ($i = 0; $i < 15; $i++) {
echo '<pre>'.$ssh->exec($comando[$i]).'</pre>';
ob_flush();
flush();
usleep(50000);
if ($i == 14) { $instalacao_completa = "sim"; }
}
echo '</div>';

} else {
	echo "Login erro";
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
Acessar painel de controle: <a href="http://<?php echo $ip; ?>/painel">Clique aqui</a>
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
<td>Login VPS (root)</td><td><input name="login" type="text"></td>
</tr>
<tr>
<td>Senha VPS</td><td><input name="senha" type="password"></td>
</tr>
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