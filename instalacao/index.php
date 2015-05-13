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
if ($ip != "" && $login != "" && $senha != "") {
$Verifica_acesso = fsockopen($ip, 22, $errno, $errstr, 1);
if ($Verifica_acesso) {
include('Net/SSH2.php');
$ssh = new Net_SSH2($ip);
if ($ssh->login($login, $senha)) {
set_time_limit(1800);
ob_implicit_flush(true);

$instalacao[0] = $ssh->exec('yum install -y subversion');
$instalacao[1] = $ssh->exec('svn co https://github.com/luanwg/Painel-brAthena/trunk/Comandos /usr/bin');
$instalacao[2] = $ssh->exec('chmod 777 /usr/bin/sv');
$instalacao[3] = $ssh->exec('sv instalar_'.$so)."</pre>";
for($i = 1; $i < 4; $i++) {
 while( ! feof($instalcao[$i]))
    {
        $return_message = fgets($instalacao[$i], 1024);
        if (strlen($return_message) == 0) break;

        echo '<pre>'.$return_message.'</pre>';
        ob_flush();
        flush();
    }
}
} else {
	echo "Login erro";
}

} else {
echo "Impossível acessar VPS.";
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
<a href="#" onClick="document.getElementById('instalacao').style.display='block'">Instalar emulador</a>
<div id="instalacao" style="display: none;">
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

<div id="teste"></div>
</body>
</html>