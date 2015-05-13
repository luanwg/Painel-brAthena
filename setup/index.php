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
include('Net/SSH2.php');
$ssh = new Net_SSH2($ip);
if ($ssh->login($login, $senha)) {
$comando[0] = "mkdir /var/www/html/phpMyAdmin | mkdir /var/www/html/painel | mkdir /home/emulador";
$comando[1] = "yum install -y subversion";
$comando[2] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/Comandos /usr/bin";
$comando[3] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/phpMyAdmin /var/www/html/phpMyAdmin";
$comando[4] = "svn co https://github.com/luanwg/Painel-brAthena/trunk/painel /var/www/html/painel";
$comando[5] = "svn co https://github.com/brAthena/brAthena/trunk /home/emulador";
$comando[6] = "yum install -y php php-mysql php-cli php-gd php-mbstring php-mhash php-pdo php-xmlrpc php-pear";
$comando[7] = "pear channel-discover phpseclib.sourceforge.net";
$comando[8] = "pear install phpseclib/Net_SSH2";
$comando[9] = "yum install -y httpd";
$comando[10] = "yum install -y mariadb mariadb-server";
$comando[11] = "yum install -y gcc gcc-c++ make mysql mysql-server mysql-devel pcre pcre-devel zlib zlib-devel git";
$comando[12] = "yum -y update";
$comando[13] = "chmod 777 /usr/bin/sv | chmod +x /home/emulador/sysinfogen.sh | chmod 777 /home/emulador/configure";
$comando[14] = "systemctl enable httpd.service | systemctl enable mariadb.service";
$comando[15] = "systemctl start mariadb.service | systemctl start httpd.service";

if (ob_get_level() == 0) ob_start(); 
set_time_limit(1800);
ob_implicit_flush(true);
echo '<div style="width: 750px; height: 350px; border: 2px solid; border-color: #666; background: #F5F5F5; overflow: auto; padding: 10px;">';
for ($i = 0; $i < 16; $i++) {
echo '<pre>'.$ssh->exec($comando[$i]).'</pre>';
ob_flush();
flush();
usleep(50000);
if ($i == 15) { $instalacao_completa = "sim"; }
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
Dados do seu VPS:<br><br>
<form method="post" action="?a=instalacao">
<table width="400px">
<tr>
<td>IP</td><td><input name="ip" type="text"></td>
</tr>
<tr>
<td>Sistema Operacional:</td><td>
<select name="so">
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