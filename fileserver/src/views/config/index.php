<?php
require_once('../../lib/Util.php');

use app\HTMLApp;

class Main extends HTMLApp
{
    function __construct()
	{
		parent::__construct();
		$this->title = 'SAO-Explorer';
		$this->styles[] = 'styles/config.css';
	}

    function body($args)
    {
?>
<h1>Configuracion</h1>
<ul class="w3-ul w3-border sfs-icon-list">
    <li class="w3-padding" onclick="window.open('users/list.php')">
        <div class="sfs-tools"><div class="sfs-icon sfs-config-icon-users"></div></div>
        <div class="sfs-icon-name">Usuarios</div>
    </li>
</ul>
<?php        
    }
}

$m = new Main();
$m->run();
