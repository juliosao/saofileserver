<?php

require_once('lib/Page.php');
require_once('lib/Mod.php');

$pagina=new Page('archivos');
$pagina->addMod('fsoExplorer');
$pagina->addMod('fsoPlayer');

$pagina->putHeader();
?>
<body>
	<div id="explorer" class="fso-explorer" />
</body>


