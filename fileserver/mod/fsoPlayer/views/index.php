
<?php

require_once('../../../lib/Page.php');

$pagina=new Page('player');
$pagina->putHeader();
?>
<body>
	<h1><?=urldecode($_REQUEST['file'])?></h1>
	<video width="100%" controls>
		<source src="../api/play.php?path=<?=$_REQUEST['file']?>">
	</video>
</body>

