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
$sql->query("GRANT ALL PRIVILEGES ON * . * TO '$login'@'localhost' IDENTIFIED BY '$senha_usuario' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
$sql->query("CREATE DATABASE brAthena_Principal");
$sql->query("CREATE DATABASE brAthena_Logs");
$sql->query("CREATE DATABASE brAthena_DB");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Principal`.* TO '$login'@'localhost' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Logs`.* TO '$login'@'localhost' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_DB`.* TO '$login'@'localhost' WITH GRANT OPTION");

$sql->close();

$confs = file_get_contents("/var/www/html/painel/confs.php");
$txt1c = array('"SQLUSER", ""','"SQLPASS", ""','"SSHIP", ""','"SSHUSER", ""','"SSHPASS", ""');
$txt2c = array('"SQLUSER", "'.$login.'"','"SQLPASS", "'.$senha_usuario.'"','"SSHIP", "'.$ip.'"','"SSHUSER", "'.$ssh_login.'"','"SSHPASS", "'.$ssh_senha.'"');
$escreveconfs = str_replace($txt1c, $txt2c, $confs);
$novoconfs = fopen("/var/www/html/painel/confs.php", "w");
fwrite($novoconfs, $escreveconfs);
fclose($novoconfs);

include_once("../confs.php");

$ssh->exec("mysql -u ".SQLUSER." -p".SQLPASS." brAthena_Principal < /home/emulador/sql/principal.sql");
$ssh->exec("mysql -u ".SQLUSER." -p".SQLPASS." brAthena_Logs < /home/emulador/sql/logs.sql");
if ($versao == "pre") { $vs = "pre-renovacao/pre-renovacao.sql"; } else { $vs = "renovacao/renovacao.sql"; }
$ssh->exec("mysql -u ".SQLUSER." -p".SQLPASS." brAthena_Principal < /home/emulador/sql/".$vs."");

$sql->select_db("brAthena_Principal");
$sql->query("INSERT INTO `login` (userid, userpass, sex, email, group_id) VALUES ('$loginp', '$senha_usuariop', 'M', '$email', '99')");

$sql->query("USE mysql");
$sql->query("DROP DATABASE IF EXISTS test");
$sql->query("DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%'");
$sql->query("DELETE FROM mysql.user WHERE User='' OR User='root'");

if ($ssh->exec("chmod 644 /var/www/html/painel/confs.php")) {
$configurado = "sim";	
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
<td><input type="text" name="ssh_senha"></td>
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
}
?>