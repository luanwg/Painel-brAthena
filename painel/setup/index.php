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

$sql->query("CREATE USER '".$login."'@'localhost' IDENTIFIED BY '".$senha_usuario."'; GRANT ALL PRIVILEGES ON *.* TO '".$login."'@'localhost' IDENTIFIED BY '".$senha_usuario."' WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
$sql->query("CREATE DATABASE brAthena_Painel");
$sql->query("CREATE DATABASE brAthena_Principal");
$sql->query("CREATE DATABASE brAthena_Logs");
$sql->query("CREATE DATABASE brAthena_DB");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Painel`.* TO '".$login."'@'%' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Principal`.* TO '".$login."'@'%' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_Logs`.* TO '".$login."'@'%' WITH GRANT OPTION");
$sql->query("GRANT ALL PRIVILEGES ON `brAthena\_DB`.* TO '".$login."'@'%' WITH GRANT OPTION");

$sql->close();
$sql = new MySQLi('localhost','".$login."','".$senha_usuario."');

$sql->query("DROP USER 'root'");

$sql->select_db("brAthena_Painel") or die ("Não foi possivel selecionar o Banco de Dados. Erro: ".$sql->error);
$sql->query("CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(2) NOT NULL,
  `login` varchar(20) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `level` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `usuarios`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;");
$sql->query("CREATE TABLE IF NOT EXISTS `ssh` (
  `ip` varchar(15) NOT NULL,
  `login` varchar(20) NOT NULL,
  `senha` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `ssh`
  ADD PRIMARY KEY (`ip`);");
$sql->query("INSERT INTO `usuarios` (`login`, senha, email) VALUES ('".$loginp."', '".$senha_usuariop."', '".$email."')");
$sql->query("INSERT INTO `ssh` (ip, `login`, senha) VALUES ('".$ip."', '".$ssh_login."', '".$ssh_senha."')");

$sql_principal = fopen("/home/emulador/sql/principal.sql", "r");
$sql->select_db("brAthena_Principal") or die ("Não foi possivel selecionar o Banco de Dados. Erro: ".$sql->error);
$sql->query($sql_principal);
$sql->query("INSERT INTO `login` (userid, userpass, sex, email, group_id) VALUES ('".$loginp."', '".$senha_usuariop."', 'M', '".$email."', '99')");
fclose($sql_principal);
$sql_logs = fopen("/home/emulador/sql/logs.sql", "r");
$sql->select_db("brAthena_Logs") or die ("Não foi possivel selecionar o Banco de Dados. Erro: ".$sql->error);
$sql->query($sql_logs);
fclose($sql_logs);
if ($versao == "pre") { $sql_db = fopen("/home/emulador/sql/pre-renovacao/pre-renovacao.sql", "r"); } else { $sql_db = fopen("/home/emulador/sql/renovacao/renovacao.sql", "r"); }
$sql->select_db("brAthena_DB") or die ("Não foi possivel selecionar o Banco de Dados. Erro: ".$sql->error);
$sql->query($sql_logs);
fclose($sql_db);

$conf = fopen("../conf.php", "w");
$php_conf = '<?php\n
$sql = new MySQLi(\'localhost\', \''.$login.'\', \''.$senha_usuario.'\');\n
?>';
fwrite($conf, $php_conf);
fclose($conf);

header('Location: /painel');
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