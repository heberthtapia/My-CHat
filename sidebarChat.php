<?php
session_start();

include 'adodb5/adodb.inc.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$userFrom = $_POST['userFrom'];//$_SESSION["id_admin"];

$userTo = $_POST['userTo'];

$num  = $_POST['num'];

$sql = 'SELECT * FROM usuario WHERE id_empleado = '.$userTo.'';
$srtQuery = $db->Execute($sql);

$row = $srtQuery->FetchRow();

$num ++;

$srtSql = 'SELECT chatID FROM chat WHERE sendFrom = '.$userFrom.' AND sendTo = '.$userTo.'';
$srtQ = $db->Execute($srtSql);

if($srtQ){
	$srtSql = 'SELECT chatID FROM chat WHERE sendFrom = '.$userTo.' AND sendTo = '.$userFrom.'';
	$srtQ = $db->Execute($srtSql);
}

$chatID = $srtQ->FetchRow();

if(!$chatID[0]){
	$chatID[0] = 0;
}

$sqlQuery = 'SELECT * FROM chat WHERE chatID = '.$chatID[0].' ORDER BY (dateSend) ASC';
$query = $db->Execute($sqlQuery);


$html = '
<aside id="'.$userFrom.''.$userTo.'" class="tabbed_sidebar ng-scope chat_sidebar" style="right: '.((280*$num)+30).'px; width: 260px;">

	<div class="popup-head">
    	<div class="popup-head-left pull-left">
    		<a title="Foto de Perfil" target="_blank" href="">
				<img class="md-user-image" alt="Foto de Perfil" title="Foto de Perfil" src="images/perfil.jpg" >
				<h1>'.$row[1].' '.$row[2].' '.$row[3].'</h1><small><br> <i class="fa fa-briefcase" aria-hidden="true"></i> Administrador</small>
			</a>
		</div>
		<div class="popup-head-right pull-right">
            <button class="chat-header-button" type="button" onclick="minimizar(&#39;chat'.$userFrom.''.$userTo.'&#39;)"><i class="glyphicon glyphicon-minus"></i></button>
			<button data-widget="remove" id="removeClass" class="chat-header-button pull-right" type="button" onclick="cerrar(&#39;'.$userFrom.''.$userTo.'&#39;)"><i class="glyphicon glyphicon-remove"></i></button>
        </div>
	</div>
<div class="chat'.$userFrom.''.$userTo.'">
<div id="chat'.$userFrom.''.$userTo.'" class="chat_box_wrapper chat_box_small chat_box_active" style="opacity: 1; display: block; transform: translateX(0px);">
    <div id="chat_box_'.$userFrom.''.$userTo.'" class="chat_box touchscroll chat_box_colors_a">';

      	$m1 = 0;
        $m2 = 0;
        $sw = 0;
		while($reg = $query->FetchRow()){
		while ($sw == 0) {
		if ($reg[2] == $userFrom) {
			$m1++;
$html.='<div class="chat_message_wrapper">';

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
			if ($reg[2] == $userFrom) {

$html.='    <li>
                <p>'.$reg[4].'</p>
            </li>';
    	    }else{
    	    	$m1 = 0;
    	    	break;
    	    }
    	}

$html.='</ul>

		</div>';
		}
		if ($reg[2] == $userTo) {
			$m2++;
$html.='<div class="chat_message_wrapper chat_message_right">';

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
			if ($reg[2] == $userTo) {
$html.='    <li>
                <p>'.$reg[4].'</p>
            </li>';
    	    }else{
    	    	$m2 = 0;
    	    	break;
    	    }
    	}
$html.='</ul>';


$html.='</div>';
		}
		if(!$reg) $sw = 1;
     }
	}

$html.='</div>';
$html.='</div>
	<div class="chat_submit_box">
	    <div class="uk-input-group">
	    	<form id="formChat'.$row[0].'" method="POST">
		        <div class="gurdeep-chat-box">
			        <input type="text" placeholder="Escriba mensaje" id="submit_message'.$row[0].'" name="submit_message'.$row[0].'" class="md-input chatMessage">
		        </div>
			    <span class="uk-input-group-addon">
			    	<a href="#" id = "send'.$row[0].'" onclick = "sendSubmit('.$row[0].')" ><i class="glyphicon glyphicon-send"></i></a>
			    </span>
			</form>
	    </div>
	</div>
</div>
<script type="text/javascript">
	$( "#formChat'.$row[0].'" ).submit(function( event ) {
	  	sendSubmit('.$row[0].');
		event.preventDefault();
	});
</script>
</aside>';

echo $html;

?>
