<?php
session_start();

include 'adodb5/adodb.inc.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$id_admin = $_SESSION["id_admin"];

$user = $_POST['usuario'];
$num  = $_POST['num'];

$sql = 'SELECT * FROM usuario WHERE id_empleado = '.$user.'';
$srtQuery = $db->Execute($sql);

$row = $srtQuery->FetchRow();

$num ++;

$srtSql = 'SELECT chatID FROM chat WHERE sendFrom = '.$id_admin.' AND sendTo = '.$user.'';
$srtQ = $db->Execute($srtSql);

if($srtQ){
	echo $srtSql = 'SELECT chatID FROM chat WHERE sendFrom = '.$user.' AND sendTo = '.$id_admin.'';
	$srtQ = $db->Execute($srtSql);
}

$chatID = $srtQ->FetchRow();

if(!$chatID[0]){
	$chatID[0] = 0;
}

$sqlQuery = 'SELECT * FROM chat WHERE chatID = '.$chatID[0].' ORDER BY (dateSend) ASC';
$query = $db->Execute($sqlQuery);


$html = '
<aside id="'.$row[0].'" class="tabbed_sidebar ng-scope chat_sidebar" style="right: '.((320*$num)+30).'px; width: 300px;">

	<div class="popup-head">
    	<div class="popup-head-left pull-left">
    		<a title="Foto de Perfil" target="_blank" href="">
				<img class="md-user-image" alt="Foto de Perfil" title="Foto de Perfil" src="images/perfil.jpg" >
				<h1>'.$row[1].' '.$row[2].' '.$row[3].'</h1><small><br> <i class="fa fa-briefcase" aria-hidden="true"></i> Administrador</small>
			</a>
		</div>
		<div class="popup-head-right pull-right">
            <button class="chat-header-button" type="button"><i class="fa fa-minus" aria-hidden="true"></i></button>
			<button data-widget="remove" id="removeClass" class="chat-header-button pull-right" type="button"><i class="fa fa-remove" aria-hidden="true"></i></button>
        </div>
	</div>

<div id="chat" class="chat_box_wrapper chat_box_small chat_box_active" style="opacity: 1; display: block; transform: translateX(0px);">
    <div class="chat_box touchscroll chat_box_colors_a">';

      	$m1 = 0;
        $m2 = 0;
        $sw = 0;
		while($reg = $query->FetchRow()){
		while ($sw == 0) {

$html.='<div class="chat_message_wrapper">';

		//print_r($reg);


		if ($reg[2] == $id_admin) {
			$m1++;
			if($m1 == 1){

$html.='<div class="chat_user_avatar">
            <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >
                <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)"  src="http://www.webncc.in/images/gurdeeposahan.jpg" class="md-user-image">
            </a>
        </div>';
$html.='<ul class="chat_message">';
$html.='    <li>
                <p>'.$reg[4].'</p>
            </li>';
    		}

		while($reg = $query->FetchRow()){
			//print_r($reg);
			if ($reg[2] == $id_admin) {

$html.='    <li>
                <p>'.$reg[4].'</p>
            </li>';
    	    }else{
    	    	$m1 = 0;
    	    	break;
    	    }
    	}

$html.='</ul>';
        }

$html.='</div>

        <div class="chat_message_wrapper chat_message_right">';

        if ($reg[2] == $user) {
			$m2++;
			if($m2 == 1){

$html.='<div class="chat_user_avatar">
            <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >
                <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)"  src="http://www.webncc.in/images/gurdeeposahan.jpg" class="md-user-image">
            </a>
        </div>';
$html.='<ul class="chat_message">';
$html.='    <li>
                <p>'.$reg[4].'</p>
            </li>';
    		}

		while($reg = $query->FetchRow()){
			if ($reg[2] == $user) {
$html.='    <li>
                <p>'.$reg[4].'</p>
            </li>';
    	    }else{
    	    	$m2 = 0;
    	    	break;
    	    }
    	}
$html.='</ul>';
     	}
     	if(!$reg) $sw = 1;
$html.='</div>';
     }
	}

$html.='</div>';
$html.='</div>
	<div class="chat_submit_box">
	    <div class="uk-input-group">
	        <div class="gurdeep-chat-box">
		        <input type="text" placeholder="Escriba mensaje" id="submit_message" name="submit_message" class="md-input">
	        </div>
		    <span class="uk-input-group-addon">
		    	<a href="#"><i class="glyphicon glyphicon-send"></i></a>
		    </span>
	    </div>
	</div>

</aside>
';

echo $html;

?>