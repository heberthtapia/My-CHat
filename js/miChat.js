function minimizar(id){
	$('.'+id).slideToggle();   //la abre o cierra dependiendo de su estado actual
}
function cerrar(id){
	$("aside").remove("#"+id);
	num = 1;
	$('#sidebar aside').each(function (index)
	{
		id = $(this).attr('id');
		r = ((280*num)+30);
		$('aside#'+id).css('right', r+'px');
		num++;
	});
}
Push.create("Bienbenido al CHAT...!!!",{
	body: "Este es el cuerpo de la notificación.",
	icon: "images/logo-elviejoroble.png",
	timeout: 4000,
	onClick: function(){
		this.close();
	}
});
/**
 * [chatClickSend Abre un chat cuando se lo envian]
 * @param  {[type]} userTo [description]
 * @param  {[type]} e      [description]
 * @return {[type]}        [description]
 */
function chatClickSend(userTo, e, userFrom){
	//userFrom = idEmp;
	num = $('#sidebar > aside').length;
	sw = 0;
	$('#sidebar aside').each(function (index)
	{
		id = $(this).attr('id');
		if(id == userFrom+userTo){
			sw = 1;
			sendMessage(e);
		}
	});
	if( sw === 0){
		$.ajax({
			url: 'modulo/chat/sidebarChat.php',
			type: 'POST',
			async:true,
			data: {userTo: userTo, num: num, userFrom: userFrom}
		})
		.done(function(data) {
			console.log("success");
			if(userFrom == e.userTo){
				$('#sidebar').append(data);
				var alt = $("#chat"+userFrom+userTo).prop("scrollHeight");
				$("#chat_box_"+userFrom+userTo).mCustomScrollbar({
					autoHideScrollbar: true
				});
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
	//userFrom = idEmp;
	num = $('#sidebar > aside').length;
	sw = 0;
	$('#sidebar aside').each(function (index)
	{
		id = $(this).attr('id');
		if(id == userFrom+userTo){
			sw = 1;
		}
	});
	if( sw === 0){
		$.ajax({
			url: 'modulo/chat/sidebarChat.php',
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
		chatClickSend(data.userFrom, data, data.userTo);
	});
	$(".content").mCustomScrollbar();
});

function sendMessage(data){
	f = $('div#chat_box_'+data.userTo+data.userFrom+' div.chat_message_wrapper:last').hasClass( "chat_message_right" ).toString();
	if( f == 'true' ){
		t = '<div class="chat_message_wrapper">';
		t+= '<div class="chat_user_avatar">';
		t+= '<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/'+data.foto+'&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';
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
			t+= '     <img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/'+data.foto+'&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';
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
			url: 'modulo/chat/saveMessage.php',
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
	$('#audio4')[0].play();
	$("#chat_box_"+data.userTo+data.userFrom).mCustomScrollbar('scrollTo','bottom');
}

function sendSubmit(idTo, userFrom){
	//userFrom = idEmp;
	r = $('div#chat_box_'+userFrom+idTo+' div.chat_message_wrapper:last').hasClass( "chat_message_right" ).toString();
	if( r == 'true' ){
		$.post(
			'modulo/chat/ajax.php',
			{ msj : $('#submit_message'+idTo).val(), userFrom : userFrom, userTo : idTo, socket_id : pusher.connection.socket_id},
			function(data){
					$('div#chat_box_'+data.userFrom+idTo+' div.chat_message_wrapper:last').find('ul').append('<li id="effect"><p>'+data.mensaje+'</p></li>');
				},
				'json')
			.always(function(data) {
				console.log("complete");
				$("#chat_box_"+data.userFrom+idTo).mCustomScrollbar('scrollTo','bottom');
				$('#submit_message'+idTo).val('');
				limpNMessaje(data.userFrom+idTo);
			});
		return false;
	}else{
		$.post(
			'modulo/chat/ajax.php',
			{ msj : $('#submit_message'+idTo).val(), userFrom : userFrom, userTo : idTo, socket_id : pusher.connection.socket_id},
			function(data){
					t = '<div class="chat_message_wrapper chat_message_right">';
					t+= '<div class="chat_user_avatar">';
					t+= '     <img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/'+data.foto+'&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';
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
}
function limpNMessaje(id){
	/* Alerta de Nuevo Mensaje */
	$('aside#'+id).find('div.parpadea').addClass('popup-head');
	$('aside#'+id).find('div.popup-head').removeClass('parpadea')
}
