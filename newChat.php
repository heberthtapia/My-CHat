<?PHP
	session_start();
	include 'adodb5/adodb.inc.php';
	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();
	$sql = 'SELECT * FROM usuario AS u, empleado AS e ';
	$sql.= 'WHERE u.id_empleado = e.id_empleado ';
	//$sql.= 'AND u.status = "Activo"';
	$srtQuery = $db->Execute($sql);
	$_SESSION["id_admin"] = $id_admin = '6004317';
	$_SESSION["nombre"] =$nombre = 'Heberth';
	$_SESSION["paterno"] =$ap_paterno = 'Tapia';
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.mCustomScrollbar.css">


	<link rel="stylesheet" type="text/css" href="css/newStyle.css">

	<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
	<!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script src="https://js.pusher.com/4.0/pusher.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script type="text/javascript" src="js/push.min.js"></script>

	<title>CHAT en tiempo REAL</title>
</head>
<body>

<audio id="audio"><source src="tono/S_Dew_drops.ogg" type="audio/ogg"></audio>
<audio id="audio1"><source src="tono/Hint.ogg" type="audio/ogg"></audio>
<audio id="audio2"><source src="tono/Time.ogg" type="audio/ogg"></audio>
<audio id="audio3"><source src="tono/Skyline.ogg" type="audio/ogg"></audio>
<audio id="audio4"><source src="tono/Peanut.ogg" type="audio/ogg"></audio>

<p>Quien envia mensaje:</p>
<input type="text=" name="userFrom" id="userFrom" value="6004317">

<aside id="iconChat" class="animation-target">
	<a href="#" onclick="openChat();">
		<div>
			<img src="images/chat4.png" width="45">
		</div>
	</a>
</aside>

<aside id="sidebar_primary" class="tabbed_sidebar ng-scope chat_sidebar animation-target1">
	<div class="popup-head">
		<div class="popup-head-left pull-left">
			<h1>Conectados</h1>
		</div>
		<div class="popup-head-right-online pull-right">
			<button class="chat-header-button" type="button" onclick="closeChat();"><i class="fa fa-minus" aria-hidden="true"></i></button>
		</div>
	</div>
<div id="connect" class="chat_box_wrapper chat_box_small chat_box_active connect mCustomScrollbar">
	<div class="chat_box touchscroll chat_box_colors_a">
		<div class="chat_message_wrapper">
			<div class="chat_user_avatar">
				<ul>
				<?php
					while( $row = $srtQuery->FetchRow() ){
				?>
					<li>
						<a onclick="chatClick(<?=$row['id_empleado']?>);" >
							<?PHP
								  if( $row['foto'] != '' ){
							?>
								<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/thumbnail/<?=($row['foto']);?>&amp;w=35&amp;h=35&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
							<?PHP
								}else{
							?>
								<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../images/sin_imagen.jpg&amp;w=35&amp;h=35&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
							<?PHP
								}
							?>
							<p><?=$row['nombre'].' '.$row['apP'].' '.$row['apM']?></p>
						</a>
					</li>
				<?php
					}
				?>
				</ul>
			</div>
		</div>
	</div>
</div>
</aside>


<div id="sidebar"></div>

<script>
/*Push.create("Bienbenido al CHAT...!!!",{
	body: "Este es el cuerpo de la notificación.",
	icon: "images/logo-elviejoroble.png",
	timeout: 4000,
	onClick: function(){
		this.close();
	}
});*/
/**
 * [openChat description] Abrir chat por medio del boton
 * @return {[type]} [description]
 */
function openChat(){
	$('#sidebar_primary').css({
		display: 'block'
	});
	$('#iconChat').css({
		display: 'none'
	});
	num = 1;
	$('#sidebar aside').each(function (index){
		id = $(this).attr('id');
		r = ((265*num)+2);
		$('aside#'+id).css('right', r+'px');
		num++;
	});
}
/**
 * [closeChat description] Cerrar chat por medio del boton
 * @return {[type]} [description]
 */
function closeChat(){
	$('#sidebar_primary').css({
		display: 'none'
	});
	$('#iconChat').css({
		display: 'block'
	});
	num = 1;
	$('#sidebar aside').each(function (index){
		id = $(this).attr('id');
		if( num == 1 ){
			r = ((100*num));
		}else{
			r = ((265*num)) - 165;
		};
		$('aside#'+id).css('right', r+'px');
		num++;
	});
}
/**
 * [minimizar description] minimiza cada chat
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
function minimizar(id){
	$('.'+id).slideToggle();
	var str = id;
	var res = str.split("chat");
	$("#chat_box_"+res[1]).mCustomScrollbar('scrollTo','bottom');
}
/**
 * [remove description] elimina el chat
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
function remove(id){
	//$("aside").remove("#"+id);
	//setTimeout(function() {
    	$("aside").remove("#"+id);
    	num = 1;
		var coordenadas = $("#sidebar_primary").position();
		//alert(coordenadas.top);
		if( coordenadas.top == 0){
			right = 165;
		}else{
			right = -2;
		}
		$('#sidebar aside').each(function (index){
			id = $(this).attr('id');
			r = ((265*num)-right);
			$('aside#'+id).css('right', r+'px');
			num++;
		});
    //}, 1800 );
}
/**
 * [cerrar description] llama a remove();
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
function cerrar(id){
	//$("aside#"+id).removeClass("animation-target3");
	//$("aside#"+id).addClass("animation-target4");
	remove(id);
}
/**
 * [adicionaClass adiciona clase]
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
function adicionaClass(id){
	$('#'+id).addClass("animation-target3");
}
/**
 * [chatClickSend Abre un chat cuando se lo envian]
 * @param  {[type]} userTo [description]
 * @param  {[type]} e      [description]
 * @return {[type]}        [description]
 */
function chatClickSend(userTo, e){
	userFrom = $('input#userFrom').val();
	num = $('#sidebar > aside').length;
	var coordenadas = $("#sidebar_primary").position();
	if( coordenadas.top == 0){
		right = 167;
	}else{
		right = 0;
	}
	sw = 0;
	$('#sidebar aside').each(function (index)
	{
		id       = $(this).attr('id');
		userFrom = new String(userFrom);
		userTo   = new String(userTo);
		stringB  = userFrom.concat(userTo);
		if( id == stringB && userFrom == e.userTo ){
			sw = 1;
			sendMessage(e);
			$('#audio4')[0].play();
		}
	});
	if( sw === 0){
		$.ajax({
			url: 'sidebarChat.php',
			type: 'POST',
			async:true,
			data: {userTo: userTo, num: num, userFrom: userFrom, right: right}
		})
		.done(function(data) {
			console.log("success");
			if(userFrom == e.userTo){
				$('#sidebar').append(data);
				$("#chat_box_"+userFrom+userTo).mCustomScrollbar({
					autoHideScrollbar: true
				});
				$('#audio4')[0].play();
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
			$("#chat_box_"+userFrom+userTo).mCustomScrollbar('scrollTo','bottom');
			sendMessage(e);
		});
	}
}
/**
 * [chatClick description]
 * @param  {[type]} userTo [Cuando se hace Click sobre algun conectado]
 * @return {[type]}        [description]
 */
function chatClick(userTo, userFrom){
	userFrom = $('input#userFrom').val();
	num = $('#sidebar > aside').length;
	sw = 0;
	$('#sidebar aside').each(function (index)
	{
		id = $(this).attr('id');
		userFrom	= new String(userFrom);
		userTo		= new String(userTo);
		stringB = userFrom.concat(userTo);
		if(id == stringB){
			sw = 1;
		}
	});
	if( sw === 0){
		$.ajax({
			url: 'sidebarChat.php',
			type: 'POST',
			async:true,
			data: {userTo: userTo, num: num, userFrom: userFrom}
		})
		.done(function(data) {
			console.log("success");
			$('#sidebar').append(data);
			var alt = $("#chat"+userFrom+userTo).prop("scrollHeight");
			$("#chat_box_"+userFrom+userTo).mCustomScrollbar({
				autoHideScrollbar: true
			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
			$("#chat_box_"+userFrom+userTo).mCustomScrollbar('scrollTo','bottom');
			$('#submit_message'+userTo).focus();
		});
	}
}

var pusher = new Pusher('4e89620472fb0a58c62c');
var canal = pusher.subscribe('canal_prueba');

$(function(){
	canal.bind('nuevo_comentario', function(data) {
		/* Act on the event */
		chatClickSend(data.userFrom, data);
	});
	$(".content").mCustomScrollbar();
});


function sendMessage(data){
	f = $('div#chat_box_'+data.userTo+data.userFrom+' div.chat_message_wrapper:last').hasClass( "chat_message_right" ).toString();
	if( f == 'true' ){
		t = '<div class="chat_message_wrapper">';
		t+= '<div class="chat_user_avatar">';
		t+= '<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/thumbnail/'+data.foto+'&amp;w=35&amp;h=35&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';
		t+= '</div>';
		t+= '	<ul class="chat_message">';
		t+= '        <li>';
		t+= '            <p>'+data.mensaje+'</p>';
		t+= '        </li>';
		t+= '    </ul> </div>';
		$('div#chat_box_'+data.userTo+data.userFrom).find('div.mCSB_container').append(t);
		/* Alerta de Nuevo Mensaje */
		$('aside#'+data.userTo+data.userFrom).find('div.popup-head').addClass('parpadea');
		$('aside#'+data.userTo+data.userFrom).find('div.parpadea').removeClass('popup-head');
	}else{
		if( $('div#chat_box_'+data.userTo+data.userFrom).find('div.mCSB_container').is(':empty') ){
			t = '<div class="chat_message_wrapper">';
			t+= '<div class="chat_user_avatar">';
			t+= '     <img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/thumbnail/'+data.foto+'&amp;w=35&amp;h=35&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';
			t+= '</div>';
			t+= '	<ul class="chat_message">';
			t+= '        <li>';
			t+= '            <p>'+data.mensaje+'</p>';
			t+= '        </li>';
			t+= '    </ul> </div>';
			$('div#chat_box_'+data.userTo+data.userFrom).find('div.mCSB_container').append(t);
			/* Alerta de Nuevo Mensaje */
			$('aside#'+data.userTo+data.userFrom).find('div.popup-head').addClass('parpadea');
			$('aside#'+data.userTo+data.userFrom).find('div.parpadea').removeClass('popup-head');
		}else{
			$('div#chat_box_'+data.userTo+data.userFrom+' div.chat_message_wrapper:last').find('ul').append('<li><p>'+data.mensaje+'</p></li>');
			/* Alerta de Nuevo Mensaje */
			$('aside#'+data.userTo+data.userFrom).find('div.popup-head').addClass('parpadea');
			$('aside#'+data.userTo+data.userFrom).find('div.parpadea').removeClass('popup-head')
		}
	}
	$.ajax({
			url: 'saveMessage.php',
			type: 'POST',
			data: {userFrom: data.userTo, userTo: data.userFrom, message: data.mensaje},
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	$("#chat_box_"+data.userTo+data.userFrom).mCustomScrollbar('scrollTo','bottom');
}
function sendSubmit(idTo){
	if($('#submit_message'+idTo).val() != ''){
		userFrom = $('input#userFrom').val();
		r = $('div#chat_box_'+userFrom+idTo+' div.chat_message_wrapper:last').hasClass( "chat_message_right" ).toString();
		if( r == 'true' ){
			$.post(
				'ajax.php',
				{ msj : $('#submit_message'+idTo).val(), userFrom : $('#userFrom').val(), userTo : idTo, socket_id : pusher.connection.socket_id},
				function(data){
					$('div#chat_box_'+data.userFrom+idTo+' div.chat_message_wrapper:last').find('ul').append('<li id="effect"><p>'+data.mensaje+'</p></li>');
				},'json')
				.always(function(data) {
					console.log("complete");
					$("#chat_box_"+data.userFrom+idTo).mCustomScrollbar('scrollTo','bottom');
					$('#submit_message'+idTo).val('');
					limpNMessaje(data.userFrom+idTo);
				});
			return false;
		}else{
			$.post(
				'ajax.php',
				{ msj : $('#submit_message'+idTo).val(), userFrom : $('#userFrom').val(), userTo : idTo, socket_id : pusher.connection.socket_id},
				function(data){
					t = '<div class="chat_message_wrapper chat_message_right">';
					t+= '<div class="chat_user_avatar">';
					t+= '     <img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/thumbnail/'+data.foto+'&amp;w=35&amp;h=35&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';
					t+= '</div>';
					t+= '	<ul class="chat_message">';
					t+= '        <li>';
					t+= '            <p>'+data.mensaje+'</p>';
					t+= '        </li>';
					t+= '    </ul> </div>';
					$('div#chat_box_'+data.userFrom+idTo).find('div.mCSB_container').append(t);
				},
				'json')
				.always(function(data) {
					console.log("complete");
					$("#chat_box_"+data.userFrom+idTo).mCustomScrollbar('scrollTo','bottom');
					$('#submit_message'+idTo).val('');
					limpNMessaje(data.userFrom+idTo);
				});
			return false;
		}
	}else{
		alert('No puede enviar mensajes vacios.')
	}
}
function limpNMessaje(id){
	/* Alerta de Nuevo Mensaje */
	$('aside#'+id).find('div.parpadea').addClass('popup-head');
	$('aside#'+id).find('div.popup-head').removeClass('parpadea')
}
</script>

</body>
</html>
