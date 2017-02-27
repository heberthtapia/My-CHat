<?php

	require 'lib/Pusher.php';

	$mensaje = $_POST['msj'];
	$user = $_POST['user'];

	$options = array(
    	//'encrypted' => true
	);

	$pusher = new Pusher(
	   '4e89620472fb0a58c62c',
	   '47127d9bfb1a9485ccc2',
	   '306675',
	   $options
	);

	$pusher->trigger(
		'canal_prueba',
		'nuevo_comentario',
		array('mensaje' => $mensaje, 'user' => $user),
		$_POST['socket_id']
	);

	echo json_encode( array('mensaje' => $mensaje, 'user' => $user));
 ?>