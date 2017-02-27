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
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script src="https://js.pusher.com/4.0/pusher.min.js"></script>

		<title>Programando Brother's</title>
	</head>
	<body>
		<div  class="container-fluid">
			<section  style="padding: 15%;">
				<div class="row">
					<h1 class="text-center">Chat: <small>Programando Brother's</small></h1>
					<hr>
				</div>
				<div class="row">
					<form id="formChat" role="form">
						<div class="form-group">
							<label for="user">User</label>
							<input type="text" class="form-control" id="user" name="user" placeholder="Enter User">
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-12" >
									<div id="conversation" style="height:200px; border: 1px solid #CCCCCC; padding: 12px;  border-radius: 5px; overflow-x: hidden;">

									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="message">Message</label>
							<input id="message" name="message" placeholder="Enter Message"  class="form-control" rows="3">
						</div>
						<input type="submit" name="" class="btn btn-primary" value="Enviar">
					</form>
				</div>
			</section>
		</div>

	<!--AQUI ESTA EL CHAT TIPOS whatsapp-->


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

<div id="sidebar">

<!--<aside id="sidebar_secondary" class="tabbed_sidebar ng-scope chat_sidebar">

	<div class="popup-head">
    	<div class="popup-head-left pull-left">
    		<a title="Foto de Perfil" target="_blank" href="">
				<img class="md-user-image" alt="Foto de Perfil" title="Foto de Perfil" src="images/perfil.jpg" >
				<h1>Heberth Tapia</h1><small><br> <i class="fa fa-briefcase" aria-hidden="true"></i> Administrador</small>
			</a>
		</div>
		<div class="popup-head-right pull-right">
            <button class="chat-header-button" type="button"><i class="fa fa-minus" aria-hidden="true"></i>
</button>
			<button class="chat-header-button" type="button"><i class="glyphicon glyphicon-earphone"></i></button>
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
			</div>

			<button data-widget="remove" id="removeClass" class="chat-header-button pull-right" type="button"><i class="fa fa-remove" aria-hidden="true"></i></button>
        </div>
	</div>

<div id="chat" class="chat_box_wrapper chat_box_small chat_box_active" style="opacity: 1; display: block; transform: translateX(0px);">
    <div class="chat_box touchscroll chat_box_colors_a">

        <div class="chat_message_wrapper">

            <div class="chat_user_avatar">
                <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >
                    <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)"  src="http://www.webncc.in/images/gurdeeposahan.jpg" class="md-user-image">
                </a>
            </div>
                <ul class="chat_message">
                    <li>
                        <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio, eum? </p>
                    </li>
                    <li>
                        <p> Lorem ipsum dolor sit amet.<span class="chat_message_time">13:38</span> </p>
                    </li>
                </ul>

        </div>

        <div class="chat_message_wrapper chat_message_right">

            <div class="chat_user_avatar">
                <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >
                    <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://bootsnipp.com/img/avatars/bcf1c0d13e5500875fdd5a7e8ad9752ee16e7462.jpg" class="md-user-image">
                </a>
            </div>
                <ul class="chat_message">
                    <li>
                        <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem delectus distinctio dolor earum est hic id impedit ipsum minima mollitia natus nulla perspiciatis quae quasi, quis recusandae, saepe, sunt totam.
                        <span class="chat_message_time">13:34</span>
                        </p>
                    </li>
                </ul>

        </div>
                            <div class="chat_message_wrapper">
                                <div class="chat_user_avatar">
                                <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >
                                    <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://www.webncc.in/images/gurdeeposahan.jpg" class="md-user-image">
                                </a>
                                </div>
                                <ul class="chat_message">
                                    <li>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque ea mollitia pariatur porro quae sed sequi sint tenetur ut veritatis.https://www.facebook.com/iamgurdeeposahan
                                            <span class="chat_message_time">23 Jun 1:10am</span>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="chat_message_wrapper chat_message_right">
                                <div class="chat_user_avatar">
                                <a href="https://web.facebook.com/iamgurdeeposahan" target="_blank" >
                                    <img alt="Gurdeep Osahan (Web Designer)" title="Gurdeep Osahan (Web Designer)" src="http://bootsnipp.com/img/avatars/bcf1c0d13e5500875fdd5a7e8ad9752ee16e7462.jpg" class="md-user-image">
                                </a>
                                </div>
                                <ul class="chat_message">
                                    <li>
                                        <p> Lorem ipsum dolor sit amet, consectetur. </p>
                                    </li>
                                    <li>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                            <span class="chat_message_time">Friday 13:34</span>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
	<div class="chat_submit_box">
	    <div class="uk-input-group">
	        <div class="gurdeep-chat-box">
	        <span style="vertical-align: sub;" class="uk-input-group-addon">
	        <a href="#"><i class="fa fa-smile-o"></i></a>
	        </span>
	        <input type="text" placeholder="Type a message" id="submit_message" name="submit_message" class="md-input">
	        <span style="vertical-align: sub;" class="uk-input-group-addon">
	        <a href="#"><i class="fa fa-camera"></i></a>
	        </span>
	        </div>

	    <span class="uk-input-group-addon">
	    <a href="#"><i class="glyphicon glyphicon-send"></i></a>
	    </span>
	    </div>
	</div>

</aside>-->

</div>
	<!--AQUI ESTA EL FIN CHAT TIPO whatsapp-->



<script>
	function chatClick(usuario){

		num = $('#sidebar > aside').length;
		sw = 0;

		$('#sidebar aside').each(function (index)
	    {
	        id = $(this).attr('id');
	        if(id == usuario){
	          	sw = 1;
	        }
		});

		if( sw === 0){

			$.ajax({
				url: 'sidebarChat.php',
				type: 'POST',
				data: {usuario: usuario, num: num}
			})
			.done(function(data) {
				console.log("success");
				$('#sidebar').append(data);
				var alt = $("#chat").prop("scrollHeight");
				$("#chat").scrollTop(alt);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		}

		$("#send").on("click", function(e){
			e.preventDefault();
			var frm = $("#formChat").serialize();

			$.ajax({
				url: 'register.php',
				type: 'POST',
				data: frm
			})
			.done(function(info) {
				$("#message").val("");
				var altura = $("#conversation").prop("scrollHeight");
				$("conversation").scrollTop(altura);
				console.log(info);
			});
		});
	}

	$(function(){

		var pusher = new Pusher('4e89620472fb0a58c62c');
		var canal = pusher.subscribe('canal_prueba');

		canal.bind('nuevo_comentario', function(data) {
			/* Act on the event */
			$('#conversation').append('<p><b>'+data.user+'</b> dice: '+data.mensaje+'</p>');
		});

		$('#formChat').submit(function(){
			$.post(
				'ajax.php',
				{ msj : $('#message').val(), user : $('#user').val(), socket_id : pusher.connection.socket_id},
				function(data){
					$('#conversation').append('<p><b>'+data.user+'</b> dice: '+data.mensaje+'</p>');
				},
			'json');

			return false;
		});
	});

</script>
	</body>
</html>