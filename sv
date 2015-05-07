#!/bin/bash -p
case $1 in
	'instalar')
	yum install -y php php-mysql php-cli php-gd php-mbstring php-mcrypt php-mhash php-pdo php-xmlrpc php-pear > /var/www/html/log.txt &
	pear channel-discover phpseclib.sourceforge.net >> /var/www/html/log.txt &
	pear install phpseclib/Net_SSH2 >> /var/www/html/log.txt &
	yum install -y httpd >> /var/www/html/log.txt &
	yum install -y mariadb mariadb-server >> /var/www/html/log.txt &
	yum install -y gcc gcc-c++ make mysql mysql-server mysql-devel pcre pcre-devel zlib zlib-devel git >> /var/www/html/log.txt &
	yum -y update >> /var/www/html/log.txt &
	mkdir /var/www/html/phpMyAdmin
	svn co https://github.com/luanwg/Painel-brAthena/tree/master/phpMyAdmin /var/www/html/phpMyAdmin >> /var/www/html/log.txt &
	mkdir /home/emulador
	svn co https://github.com/brAthena/brAthena/trunk /home/emulador >> /var/www/html/log.txt &
	chmod +x sysinfogen.sh >> /var/www/html/log.txt &
	chmod 777 configure >> /var/www/html/log.txt &
	systemctl enable httpd.service >> /var/www/html/log.txt &
	systemctl start mariadb.service >> /var/www/html/log.txt &
	systemctl enable mariadb.service >> /var/www/html/log.txt &
	systemctl restart httpd.service >> /var/www/html/log.txt &
;;
    'start')
	clear
	cd /home/emulador;
	date > /var/www/html/painel/logs/login.txt &
	date > /var/www/html/painel/logs/char.txt &
	date > /var/www/html/painel/logs/map.txt &
	sh ./login-server.sh start >> /var/www/html/painel/logs/login.txt &
	sh ./char-server.sh start >> /var/www/html/painel/logs/char.txt &
	sh ./map-server.sh start >> /var/www/html/painel/logs/map.txt &
;;
    'stop')
	clear
	ps ax | grep -E "login-server|char-server|map-server" | awk '{print $1}' | xargs kill
;;
    'restart')
	clear
	ps ax | grep -E "login-server|char-server|map-server" | awk '{print $1}' | xargs kill
	clear
	cd /home/emulador;
	date > /var/www/html/painel/logs/login.txt &
	date > /var/www/html/painel/logs/char.txt &
	date > /var/www/html/painel/logs/map.txt &
	sh ./login-server.sh start >> /var/www/html/painel/logs/login.txt &
	sh ./char-server.sh start >> /var/www/html/painel/logs/char.txt &
	sh ./map-server.sh start >> /var/www/html/painel/logs/map.txt &
;;
	'recompilar')
	clear
	ps ax | grep -E "login-server|char-server|map-server" | awk '{print $1}' | xargs kill
	date > /var/www/html/painel/logs/recompilar.txt &
	cd /home/emulador;
	./configure >> /var/www/html/painel/logs/recompilar.txt &
	make clean >> /var/www/html/painel/logs/recompilar.txt &
	make sql >> /var/www/html/painel/logs/recompilar.txt &
;;
esac