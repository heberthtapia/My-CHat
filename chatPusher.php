<?PHP
	session_start();

	include 'adodb5/adodb.inc.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$sql = 'SELECT * FROM usuario ';
    $srtQuery = $db->Execute($sql);

    $_SESSION["id_admin"] = $id_admin = '9999';
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
		<link rel="stylesheet" type="text/css" href="css/myStyle.css">

		<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
		<!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script src="https://js.pusher.com/4.0/pusher.min.js"></script>

		<title>Programando Brother's</title>
	</head>
	<body>

<p>Quien envia mensaje:</p>
<input type="text=""" name="userFrom" id="userFrom">

<aside id="sidebar_primary" class="tabbed_sidebar ng-scope chat_sidebar">

	<div class="popup-head">
    	<div class="popup-head-left pull-left">
			<h1>Conectados</h1>
		</div>
		<div class="popup-head-right-online pull-right">
            <button class="chat-header-button" type="button"><i class="fa fa-minus" aria-hidden="true"></i>
</button>
			<!--<button class="chat-header-button" type="button"><i class="glyphicon glyphicon-earphone"></i></button>
            <div class="btn-group gurdeepoushan">
				<button class="chat-header-button" data-toggle="dropdown" type="button" aria-expanded="false">
				<i class="glyphicon glyphicon-paperclip"></i> </button>
				<ul role="menu" class="dropdown-menu pull-right">
					<li><a href="#"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Gallery</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Photo</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Video</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span> Audio</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> Location</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Contact</a></li>
				</ul>
			</div>-->

			<button data-widget="remove" id="removeClass" class="chat-header-button pull-right" type="button"><i class="fa fa-remove" aria-hidden="true"></i></button>
        </div>
	</div>


<div id="conectados" class="chat_box_wrapper chat_box_small chat_box_active" style="opacity: 1; display: block; transform: translateX(0px);">
    <div class="chat_box touchscroll chat_box_colors_a">

        <div class="chat_message_wrapper">

            <div class="chat_user_avatar">
                 <ul>
                 <?php
                   while( $row = $srtQuery->FetchRow() ){
                 ?>
                    <li>
                    	<a onclick="chatClick(<?=$row['id_empleado']?>);" >
		                    <img alt="" title=""  src="http://www.webncc.in/images/gurdeeposahan.jpg" class="md-user-image">
		                    <p><?=$row[1].' '.$row[2].' '.$row[3]?></p>
		                </a>
                    </li>
                 <?php
                 	}
                 ?>
                </ul>
            </div>

        </div>
	</div>
	
</aside>

<div id="sidebar"></div>

<script>
function chatClickSend(userTo, e){
	userFrom = $('input#userFrom').val();
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
			url: 'sidebarChat.php',
			type: 'POST',
			async:true,
			data: {userTo: userTo, num: num, userFrom: userFrom}
		})
		.done(function(data) {
			console.log("success");
			//alert(userFrom+'---'+e.userTo);
			if(userFrom == e.userTo){
				$('#sidebar').append(data);				
				var alt = $("#chat"+userFrom+userTo).prop("scrollHeight");				
				$("#chat"+userFrom+userTo).scrollTop(alt);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
			sendMessage(e);
		});
	}
}

function chatClick(userTo){
	userFrom = $('input#userFrom').val();
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
			url: 'sidebarChat.php',
			type: 'POST',
			async:true,
			data: {userTo: userTo, num: num, userFrom: userFrom}
		})
		.done(function(data) {
			console.log("success");
			$('#sidebar').append(data);				
			var alt = $("#chat"+userFrom+userTo).prop("scrollHeight");				
			$("#chat"+userFrom+userTo).scrollTop(alt);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}
}
var pusher = new Pusher('4e89620472fb0a58c62c');
var canal = pusher.subscribe('canal_prueba');

$(function(){

	canal.bind('nuevo_comentario', function(data) {
		/* Act on the event */
		chatClickSend(data.userFrom,data);
		//sendMessage(data);
		//delay(100);
		
	});

});


/*$(function(){

	canal.bind('nuevo_comentario', function(data) {
		/* Act on the event 	
		f = $('div#chat_box_'+data.userTo+data.userFrom+' div.chat_message_wrapper:last').hasClass( "chat_message_right" ).toString();

		if( f == 'true' ){
			t = '<div class="chat_message_wrapper">';
			t+= '<div class="chat_user_avatar">';
			t+= '   <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >';
			t+= '     <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://bootsnipp.com/img/avatars/bcf1c0d13e5500875fdd5a7e8ad9752ee16e7462.jpg" class="md-user-image">';
			t+= '   </a>';
			t+= '</div>';
			t+= '	<ul class="chat_message">';
			t+= '        <li>';
			t+= '            <p>'+data.mensaje+'</p>';
			t+= '        </li>';
			t+= '    </ul> </div>';
			$('div#chat_box_'+data.userTo+data.userFrom).append(t);			
		}else{
			if( $('div#chat_box_'+data.userTo+data.userFrom).is(':empty') ){				
				t = '<div class="chat_message_wrapper">';
				t+= '<div class="chat_user_avatar">';
				t+= '   <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >';
				t+= '     <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://bootsnipp.com/img/avatars/bcf1c0d13e5500875fdd5a7e8ad9752ee16e7462.jpg" class="md-user-image">';
				t+= '   </a>';
				t+= '</div>';
				t+= '	<ul class="chat_message">';
				t+= '        <li>';
				t+= '            <p>'+data.mensaje+'</p>';
				t+= '        </li>';
				t+= '    </ul> </div>';
				$('div#chat_box_'+data.userTo+data.userFrom).append(t);
			}else{
				$('div#chat_box_'+data.userTo+data.userFrom+' div.chat_message_wrapper:last').find('ul').append('<li><p>'+data.mensaje+'</p></li>');
			}
		}
		alt = $("#chat"+data.userTo+data.userFrom).prop("scrollHeight");
		$("#chat"+data.userTo+data.userFrom).scrollTop(alt);	
	});
});*/

function sendMessage(data){

	f = $('div#chat_box_'+data.userTo+data.userFrom+' div.chat_message_wrapper:last').hasClass( "chat_message_right" ).toString();

	if( f == 'true' ){
		t = '<div class="chat_message_wrapper">';
		t+= '<div class="chat_user_avatar">';
		t+= '   <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >';
		t+= '     <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://bootsnipp.com/img/avatars/bcf1c0d13e5500875fdd5a7e8ad9752ee16e7462.jpg" class="md-user-image">';
		t+= '   </a>';
		t+= '</div>';
		t+= '	<ul class="chat_message">';
		t+= '        <li>';
		t+= '            <p>'+data.mensaje+'</p>';
		t+= '        </li>';
		t+= '    </ul> </div>';
		$('div#chat_box_'+data.userTo+data.userFrom).append(t);			
	}else{
		if( $('div#chat_box_'+data.userTo+data.userFrom).is(':empty') ){				
			t = '<div class="chat_message_wrapper">';
			t+= '<div class="chat_user_avatar">';
			t+= '   <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >';
			t+= '     <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://bootsnipp.com/img/avatars/bcf1c0d13e5500875fdd5a7e8ad9752ee16e7462.jpg" class="md-user-image">';
			t+= '   </a>';
			t+= '</div>';
			t+= '	<ul class="chat_message">';
			t+= '        <li>';
			t+= '            <p>'+data.mensaje+'</p>';
			t+= '        </li>';
			t+= '    </ul> </div>';
			$('div#chat_box_'+data.userTo+data.userFrom).append(t);
		}else{
			$('div#chat_box_'+data.userTo+data.userFrom+' div.chat_message_wrapper:last').find('ul').append('<li><p>'+data.mensaje+'</p></li>');
		}
	}
	alt = $("#chat"+data.userTo+data.userFrom).prop("scrollHeight");
	$("#chat"+data.userTo+data.userFrom).scrollTop(alt);	
}

function sendSubmit(idTo){

	userFrom = $('input#userFrom').val();

	r = $('div#chat_box_'+userFrom+idTo+' div.chat_message_wrapper:last').hasClass( "chat_message_right" ).toString();

	if( r == 'true' ){
		$.post(
			'ajax.php',
			{ msj : $('#submit_message'+idTo).val(), userFrom : $('#userFrom').val(), userTo : idTo, socket_id : pusher.connection.socket_id},
			function(data){
					//$('#conversation').append('<p><b>'+data.user+'</b> dice: '+data.mensaje+'</p>');
					$('div#chat_box_'+data.userFrom+idTo+' div.chat_message_wrapper:last').find('ul').append('<li><p>'+data.mensaje+'</p></li>');
					
					alt = $("#chat"+data.userFrom+idTo).prop("scrollHeight");	
					$("#chat"+data.userFrom+idTo).scrollTop(alt);

					$('#submit_message'+idTo).val('');
				},
				'json');
		return false;
	}else{

		$.post(
			'ajax.php',
			{ msj : $('#submit_message'+idTo).val(), userFrom : $('#userFrom').val(), userTo : idTo, socket_id : pusher.connection.socket_id},
			function(data){
					//$('#conversation').append('<p><b>'+data.user+'</b> dice: '+data.mensaje+'</p>');
					t = '<div class="chat_message_wrapper chat_message_right">';
					t+= '<div class="chat_user_avatar">';
					t+= '   <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >';
					t+= '     <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://bootsnipp.com/img/avatars/bcf1c0d13e5500875fdd5a7e8ad9752ee16e7462.jpg" class="md-user-image">';
					t+= '   </a>';
					t+= '</div>';
					t+= '	<ul class="chat_message">';
					t+= '        <li>';
					t+= '            <p>'+data.mensaje+'</p>';
					t+= '        </li>';
					t+= '    </ul> </div>';
					
					$('div#chat_box_'+data.userFrom+idTo).append(t);
					
					alt = $("#chat"+data.userFrom+idTo).prop("scrollHeight");
					$("#chat"+data.userFrom+idTo).scrollTop(alt);

					$('#submit_message'+idTo).val('');
				},
				'json');
		return false;
	}

}
</script>
<form ></form>
	</body>
</html>