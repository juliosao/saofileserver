<?php

require_once('lib/Page.php');
require_once('lib/Mod.php');

$pagina=new Page('archivos');
$pagina->addMod('fso');
$pagina->addMod('fsoExplorer');

$pagina->putHeader();
?>
<body>
	<div id="explorer" class="fso-explorer" />
</body>

