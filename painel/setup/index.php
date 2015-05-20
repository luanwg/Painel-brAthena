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
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Setup - brAthena</title>
</head>

<body>
Por favor, preencha todos os campos:
<form method="post" action="">
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
<td><input type="text" name="login"></td>
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