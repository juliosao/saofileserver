<?php

require_once('../../../lib/Util.php');

class Index extends app\HTMLApp
{
	public function __construct()
	{
		parent::__construct(1);
		
		$this->title='Propiedades del usuario';
		$this->addStyle('../../../styles/main/main.css');
		$this->addScript('../../../js/User.js');

		$usr=isset($_REQUEST['id']) ? $_REQUEST['id'] : \auth\Auth::get()->id ;
		$this->addOnload("loadUsers()");
		
	}

	public function putBody()
	{
		?>
		<script type="text/javascript">
			function ok()
			{

			}

			function loadUsers()
			{

			}
		</script>
		<h1>Usuarios</h1>
			
		<?php
	}
}

$index = new Index();
$index->run();

