
<?php

require_once('../../../lib/Page.php');

$file=urldecode($_REQUEST['file']);
$ext=substr($file,-4);
$pagina=new Page('player');
$pagina->putHeader();
?>
<body>
	<h1><?=htmlentities($file)?></h1>
	<?php if( $ext == '.mp4' ){ ?>
	<video width="100%" controls>
		<source src="../api/play.php?path=<?=$_REQUEST['file']?>">
	</video>
	<?php } else if ($ext=='.mp3' || $ext='.ogg') { ?>
	<audio width="100%" controls>
		<source src="../api/play.php?path=<?=$_REQUEST['file']?>">
	</audio>
	<?php } ?>
</body>