#!/bin/bash -p
case $1 in
	'instalar')
	yum install -y php php-mysql php-cli php-gd php-mbstring php-mcrypt php-mhash php-pdo php-xmlrpc php-pear
	pear channel-discover phpseclib.sourceforge.net
	pear install phpseclib/Net_SSH2
	yum install -y httpd
	yum install -y mariadb mariadb-server
	yum install -y gcc gcc-c++ make mysql mysql-server mysql-devel pcre pcre-devel zlib zlib-devel git
	yum -y update
	mkdir /var/www/html/phpMyAdmin
	svn co https://github.com/luanwg/Painel-brAthena/trunk/phpMyAdmin /var/www/html/phpMyAdmin
	mkdir /home/emulador
	svn co https://github.com/brAthena/brAthena/trunk /home/emulador
	chmod +x sysinfogen.sh
	chmod 777 configure
	systemctl enable httpd.service
	systemctl start mariadb.service
	systemctl enable mariadb.service
	systemctl restart httpd.service
;;
	'atualizar')
	clean
	cd /home/emulador
	date > /var/www/html/painel/logs/atualizar.txt &
	svn update >> /var/www/html/painel/logs/atualizar.txt &
	cd /var/www/html/phpMyAdmin
	svn update >> /var/www/html/painel/logs/atualizar.txt &
	cd /var/www/html/painel
	svn update >> /var/www/html/painel/logs/atualizar.txt &
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