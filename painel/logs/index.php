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
// | Descrição: Leitura dos aquivos de logs.                    |
// |------------------------------------------------------------|
// | Changelog:                                                 |
// | 1.0 Criação [Chuck]                                        |
// |------------------------------------------------------------|
// | - Anotações                                                |
// \____________________________________________________________/
?>
<title>:: Logs - brAthena ::</title>
<link rel="stylesheet" href="style.css" />
<?php
$log = $_GET['log'];
if ($log == "map-server") {
$arquivo = "map.txt";	
} elseif ($log == "char-server") {
$arquivo = "char.txt";
} elseif ($log == "login-server") {
$arquivo = "login.txt";
} elseif ($log == "compilacao") {
$arquivo = "compilacao.txt";
} elseif ($log == "atualizacao") {
$arquivo = "atualizacao.txt";
}
$text = fopen($arquivo, "r");
    while(!feof($texto))
    {
        $return_message = fgets($text, 51200);
        if (strlen($return_message) == 0) break;
		$i = $i + 1;
		if ($i > 1 && $i  < 14 && ($log != "compilacao" && $log != "atualizacao")) {
		echo "<pre>".$i.": <span class=\"sucesso\">".$return_message."</span></pre>";
		} else {
		if ($i == 21 && ($log != "compilacao" && $log != "atualizacao")) { $return_message = "[Info]: Flags ao compilar: Várias.. =p"; }
		
		$normal = array("[Sucesso]:", "[Info]:", "[Aviso]:", "[Conf]:", "[Depurar]:", "[SQL]:", "[Noticia]:", "[Erro]:", "[NPC]:");
		$css   = array("<span class=\"sucesso\">[Sucesso]:</span>", "<span class=\"info\">[Info]:</span>", "<span class=\"aviso\">[Aviso]:</span>", "<span class=\"conf\">[Conf]:</span>", "<span class=\"Depurar\">[Depurar]:</span>", "<span class=\"sql\">[SQL]:</span>", "<span class=\"noticia\">[Notícia]:</span>", "<span class=\"erro\">[Erro]:</span>", "<span class=\"npc\">[NPC]:</span>");
		
		$exibe = str_replace($normal, $css, $return_message);
		echo "<pre>".$i.": ".$exibe."</pre>";
		ob_flush();
        flush();
		
		}
	}
?>