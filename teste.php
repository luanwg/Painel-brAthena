<?php
include('Net/SSH2.php');
$ssh = new Net_SSH2('167.114.184.140');
if (!$ssh->login('root', 'S551BFiz')) {
    echo "0";
} else {
$teste = $ssh->exec('ps');
}
?>
<script>
window.document.write('ok');
</script>