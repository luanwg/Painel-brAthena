<?php
$a = $_GET['a'];
$ip = $_POST['ip'];
$login = $_POST['login'];
$senha = $_POST['senha'];

include('Net/SSH2.php');
if ($a == "") {
$ssh = new Net_SSH2($ip);
if (!$ssh->login($login, $senha)) {
    echo "0";
} else {
	echo "1";
}

} else {
$ssh = new Net_SSH2($ip);
$ssh->login($login, $senha);

if ($a == "php") {
$ssh->exec('yum install -y php php-mysql php-cli php-gd php-mbstring php-mcrypt php-mhash php-pdo php-xmlrpc php-pear > /var/www/html/log.txt &');

} elseif ($a == "ssh") {
$ssh->exec('pear channel-discover phpseclib.sourceforge.net >> /var/www/html/log.txt &');
$ssh->exec('pear install phpseclib/Net_SSH2 >> /var/www/html/log.txt &');

} elseif ($a == "apache") {
$ssh->exec('yum install -y httpd >> /var/www/html/log.txt &');

} elseif ($a == "db") {
$ssh->exec('yum install -y mariadb mariadb-server >> /var/www/html/log.txt &');

} elseif ($a == "svn") {
$ssh->exec('yum install -y subversion >> /var/www/html/log.txt &');

} elseif ($a == "compiladores") {
$ssh->exec('yum install -y gcc gcc-c++ make mysql mysql-server mysql-devel pcre pcre-devel zlib zlib-devel git >> /var/www/html/log.txt &');

} elseif ($a == "atu") {
$ssh->exec('yum -y update >> /var/www/html/log.txt &');

} elseif ($a == "phpmyadmin") {
$ssh->exec('svn co https://github.com/luanwg/Painel-brAthena/trunk /var/www/html >> /var/www/html/log.txt &');

} elseif ($a == "bra") {
$ssh->exec('mkdir /home/emulador >> /var/www/html/log.txt &');
$ssh->exec('svn co https://github.com/brAthena/brAthena/trunk /home/emulador >> /var/www/html/log.txt &');
$ssh->exec('chmod +x sysinfogen.sh >> /var/www/html/log.txt &');
$ssh->exec('chmod 777 configure >> /var/www/html/log.txt &');

} elseif ($a == "conf") {
$ssh->exec('systemctl enable httpd.service >> /var/www/html/log.txt &');
$ssh->exec('systemctl start mariadb.service >> /var/www/html/log.txt &');
$ssh->exec('systemctl enable mariadb.service >> /var/www/html/log.txt &');
$ssh->exec('systemctl restart httpd.service >> /var/www/html/log.txt &');
}

if ($ssh->getExitStatus() == "0") { echo "1"; }

}
?>