<?php
require_once('../../../lib/Util.php');
use auth\Auth;
use app\HTMLApp;

Auth::checkSession();
$id=isset($_GET['id']) ? $_GET['id'] : Auth::$current->id;
$imAdmin = Auth::$current->isFromGroup('admin');

?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('Usuario actual'); ?>		
        <script type="text/javascript" src="../js/user.js"></script>
        <script type="text/javascript" src="../../groups/js/group.js"></script>
        <script rel="stylesheet" href="../../../styles/main/main.css"></script>
		<script type="text/javascript">

        let usr = null;

		async function loadUser()
		{
            try
            {
                usr=await User.get("<?=$id?>");
                document.getElementById('name').value=usr.name;
                document.getElementById('mail').value=usr.mail;
                document.getElementById('pw').value="";
                document.getElementById('pw2').value="";
                putGroups(usr);
            }
            catch(ex)
            {
                alert(""+ex);
            }
        }
        
        async function putGroups(usr)
        {
			let grp = UI.clear('groups-editor');
            
<?php if($imAdmin) { ?>         
            let row = document.createElement('tr');
            let cell = document.createElement('td');
            let input = document.createElement('select');

            input.id="new_group";
            input.classList.add('w3-select');
            groupsCombo(input);

			cell.appendChild(input);
            row.appendChild(cell);
            
            let btn = UI.button(UI.image("../../../styles/toolbar/plus.svg"));
            btn.classList.add('w3-button');

            row.appendChild(UI.cell(btn));
            grp.appendChild(row);
<?php } ?>
            let grps = await usr.getGroups();
            for(g of grps)
            {
                row = document.createElement('tr');
                
                row.appendChild(UI.cell(g.name));
<?php if($imAdmin) { ?> 
                
                btn = UI.button(UI.image("../../../styles/toolbar/delete.svg"));
                btn.classList.add('w3-button');
                row.appendChild(UI.cell(btn));
<?php } ?>
                grp.appendChild(row);
            }
        }

<?php if($imAdmin) { ?>
        async function groupsCombo(input)
        {
            let grps = await Group.list();
            for(let grp of grps)
            {
                let option = UI.option(grp.name,grp.id);
                input.appendChild(option);
            }
        }
<?php } ?>

        async function save()
        {
            try
            {
                usr.mail=document.getElementById('mail').value;
				let pw = document.getElementById('pw').value;
				let pw2 = document.getElementById('pw2').value;
				
				if(pw!=pw2)
				{
					alert("Passwords doesnt match!")
					return;
				}

				if(pw2!='')
                	usr.pw2=pw2;

				if(pw!='')
                	usr.pw=pw;


                await usr.save();
                alert("Usuario guardado");
            }
            catch(ex)
            {
                alert(""+ex);
            }
        }
		</script>
	</head>
	<body onload="loadUser()">
		<h1>Configuracion de usuario</h1>
		<div class="w3-container">
            <input type="hidden" id="id" value="<?=Auth::$current->id?>">
            <div class="form">
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="name">Nombre</label>
                    <div class="w3-col m5"><input class="w3-input" id="name" readonly="true" ></div>
                </div>
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="mail">Mail</label>
                    <div class="w3-col m5"><input class="w3-input" id="mail"  type="email"></div>
                </div>
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="cpwd">Password (Nuevo)</label>
                    <div class="w3-col m5"><input class="w3-input" id="pw" value="" type="password"></div>
                </div>
                <div class="w3-row w3-margin">
                    <label class="w3-col m3" for="cpwd">Password (Nuevo,Repetir)</label>
                    <div class="w3-col m5"><input class="w3-input" id="pw2" value="" type="password"></div>
                </div>
                <div class="w3-row w3-margin">
                    <button class="w3-btn w3-blue" onclick=save()>Guardar</button>
                </div>
            </div>
            <h2>Grupos</h2>
            <div class="w3-responsive">
                <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                    <thead>
                        <tr>
                        <th>Group</th>
<?php if($imAdmin) { ?><th>Action</th><?php } ?>
                        </tr>
                    </thead>
                    <tbody id="groups-editor">

                    </tbody>
                </table>
            </div>
        </div>
	</body>
</html>