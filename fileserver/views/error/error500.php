<?php

http_response_code(404);
?>
<h1>Se ha producido un error</h1>
<p>Descripcion del error <?=$msg?>
<?php
die();