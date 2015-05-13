<?php
$a = $_GET['a'];


/*include('Net/SSH2.php');
$ssh = new Net_SSH2('167.114.184.140');
if (!$ssh->login('root', 'S551BFiz')) {
    exit('Login Failed');
}

echo "<pre>".$ssh->exec('dpkg -l | grep subversionx')."</pre>";*/


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Painel de Instalação - brAthena</title>
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.2.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
$('#teste').load('teste.php');
});
</script>
</head>

<body>
<?php
if ($a == "instalacao") {
$ip = $_POST['ip'];
$login = $_POST['login'];
$senha = $_POST['senha'];
if ($ip != "" && $login != "" && $senha != "") {
$Verifica_acesso = fsockopen($ip, 22, $errno, $errstr, 1);
if ($Verifica_acesso) {
?>
<div id="progresso">Progresso 0% [>]<br><br><br></div>
<div id="aviso_vps" style="display: none;">Login efetuado com sucesso!<br>Instalando PHP...<br></div>
<div id="aviso_php" style="display: none;">PHP instalado com sucesso!<br>Instalando SSH2...<br></div>
<div id="aviso_ssh" style="display: none;">SSh2 instalado com sucesso!<br>Instalando Apache...<br></div>
<div id="aviso_apache" style="display: none;">Apache instalado com sucesso!<br>Instalando Database...<br></div>
<div id="aviso_db" style="display: none;">Database instalado com sucesso!<br>Instalando Compiladores...<br></div>
<div id="aviso_compiladores" style="display: none;">Compiladores instalados com sucesso!<br>Atualizando aplicativos...<br></div>
<div id="aviso_atu" style="display: none;">Aplicativos atualizados com sucesso!<br>Baixando phpMyAdmin...<br></div>
<div id="aviso_phpmyadmin" style="display: none;">Download concluído com sucesso!<br>Baixando emulador brAthena...<br></div>
<div id="aviso_bra" style="display: none;">Download concluído com sucesso!<br>Iniciando configurações...<br></div>
<div id="aviso_conf" style="display: none;">Configurações terminadas com sucesso!<br><br></div>
<div id="loading" style="display: none;">Carregando...<br></div>
<script>
$(document).ready(function(){
	
	var ip = '<?php echo $ip; ?>';
	var login = '<?php echo $login; ?>';
	var senha = '<?php echo $senha; ?>';
	
$.ajax({
	type: "POST",
	url: "vps.php",
	data: { ip: ip, login: login, senha: senha },
	cache: false,
	success: function(response) {
		if (response == 1) {
			$('#aviso_vps').css('display', 'block');
			$('#progresso').html('Progresso 5% [->]');
			$('#loading').css('display', 'none');
			instalarphp();
			} else {
			window.document.write('Ocorreu um erro ao conectar-se com o servidor, por favor verifique suas informações!');
			}
	}, beforeSend: function() {
		$('#loading').css('display', 'block');
		}
});

function instalarphp() {
$.ajax({
		type: "POST",
		url: "vps.php?a=php",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_php').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 15% [=->]');
			instalarssh();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function instalarssh() {
$.ajax({
		type: "POST",
		url: "vps.php?a=ssh",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_ssh').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 25% [==->]');
			instalarapache();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function instalarapache() {
$.ajax({
		type: "POST",
		url: "vps.php?a=apache",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_apache').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 35% [===->]');
			instalardb();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function instalardb() {
$.ajax({
		type: "POST",
		url: "vps.php?a=db",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_db').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 45% [====->]');
			instalarsvn();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function instalarsvn() {
$.ajax({
		type: "POST",
		url: "vps.php?a=svn",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_svn').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 55% [=====->]');
			instalarcompiladores();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function instalarcompiladores() {
$.ajax({
		type: "POST",
		url: "vps.php?a=compiladores",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_compiladores').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 65% [======->]');
			atualizar();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function atualizar() {
$.ajax({
		type: "POST",
		url: "vps.php?a=atu",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_atu').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 75% [=======->]');
			downloadphpmyadmin();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function downloadphpmyadmin() {
$.ajax({
		type: "POST",
		url: "vps.php?a=phpmyadmin",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_phpmyadmin').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 85% [========->]');
			downloadbra();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function downloadbra() {
$.ajax({
		type: "POST",
		url: "vps.php?a=bra",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_bra').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 95% [=========->]');
			configurando();
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}

function configurando() {
$.ajax({
		type: "POST",
		url: "vps.php?a=conf",
		data: { ip: ip, login: login, senha: senha },
		cache: false,
		success: function(response) {
			if (response == 1) {
			$('#aviso_conf').css('display', 'block');
			$('#loading').css('display', 'none');
			$('#progresso').html('Progresso 100% [==========]');
			}
		}, beforeSend: function() {
			$('#loading').css('display', 'block');
			}
	});
}


});
</script>
<?php
} else {
echo "Impossível acessar VPS.";
}
} else {
echo "Dados em branco!";	
}
} else {
?>
<a href="#" onClick="document.getElementById('instalacao').style.display='block'">Instalar emulador</a>
<div id="instalacao" style="display: none;">
Dados do seu VPS:<br><br>
<form method="post" action="?a=instalacao">
<table width="400px">
<tr>
<td>IP</td><td><input name="ip" type="text"></td>
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
<div id="teste"></div>
</body>
</html>