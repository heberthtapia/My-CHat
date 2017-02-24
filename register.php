<?php 
	$bd = "chateje";
	$server = "localhost";
	$user = "root";
	$password = "mysql";

	$conexion = @mysqli_connect($server, $user, $password,$bd);
	if( !conexion ) die("Error de coexion ".mysqli_connect_error());

	$user = $_POST['user'];
	$message = $_POST['message'];

	$sql = "INSERT INTO conversation(usuario, mensaje) VALUES('$user', '$message')";
	$result = mysqli_query($conexion, $sql);

	if($result)
		echo "Mensaje registrado.";
?>