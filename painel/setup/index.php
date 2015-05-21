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

if (file_exists("../conf.php")) { echo "Por favor, exclua este diretório!!";  } else {
$a = $_GET['a'];
if ($a == "configurar") {
$senha_root = $_POST['senha_root'];
$csenha_root = $_POST['csenha_root'];
$login = $_POST['login'];
$senha_usuario = $_POST['senha_usuario'];
$csenha_usuario = $_POST['csenha_usuario'];
$loginp = $_POST['loginp'];
$senha_usuariop = $_POST['senha_usuariop'];
$csenha_usuariop = $_POST['csenha_usuariop'];
if ($senha_root != "" && $csenha_root != "" && $login != "" && $senha_usuario != "" && $csenha_usuario != "" && $loginp != "" && $senha_usuariop != "" && $csenha_usuariop != "") {
if ($senha_root == $csenha_root && $senha_usuario == $csenha_usuario && $senha_usuariop == $csenha_usuariop) {
$con_sql = mysql_connect("localhost", "root", "") or die ("Servidor não responde");
mysql_query("SET PASSWORD FOR 'root'@'localhost' = PASSWORD('".$senha_root."')");
mysql_query("DROP USER 'root'@'127.0.0.1'");
mysql_query("DROP USER 'teste'@'::1'");
mysql_query("DROP USER 'teste'@'%'");
mysql_query("DROP DATABASE test");
mysql_query("CREATE USER '".$login."'@'%' IDENTIFIED BY '".$senha_usuario."';GRANT ALL PRIVILEGES ON *.* TO '".$login."'@'%' IDENTIFIED BY '".$senha_usuario."' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
mysql_query("CREATE DATABASE brAthena_Painel");
mysql_query("CREATE DATABASE brAthena_Principal");
mysql_query("CREATE DATABASE brAthena_Logs");
mysql_query("CREATE DATABASE brAthena_DB");
mysql_query("GRANT ALL PRIVILEGES ON `brAthena\_Painel`.* TO '".$login."'@'%' WITH GRANT OPTION");
mysql_query("GRANT ALL PRIVILEGES ON `brAthena\_Principal`.* TO '".$login."'@'%' WITH GRANT OPTION");
mysql_query("GRANT ALL PRIVILEGES ON `brAthena\_Logs`.* TO '".$login."'@'%' WITH GRANT OPTION");
mysql_query("GRANT ALL PRIVILEGES ON `brAthena\_DB`.* TO '".$login."'@'%' WITH GRANT OPTION");
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
Por favor, preencha todos os campos:
<form method="post" action="?a=configurar">
<table>
<tr>
<td>Defina Senha root (MySql):</td>
<td><input type="password" name="senha_root"></td>
</tr>
<tr>
<td>Digite novamente:</td>
<td><input type="password" name="csenha_root"></td>
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
<td>Crie um usuario (Painel):</td>
<td><input type="text" name="loginp"></td>
</tr>
<tr>
<td>Defina Senha para o usuario:</td>
<td><input type="password" name="senha_usuariop"></td>
</tr>
<tr>
<td>Digite novamente:</td>
<td><input type="password" name="csenha_usuariop"></td>
</tr>
</table>
</form>
</body>
</html>
<?php
}