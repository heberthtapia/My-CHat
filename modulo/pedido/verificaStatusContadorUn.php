<?PHP
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');

$db->Connect();

$op = new cnFunction();

$fecha = $op->ToDay();
$hora = $op->Time();

$data = new stdClass();

$data->id = $_POST['res'];

/* POR SI ACASO SI ES NECESARIO ACTUALIZAR ALGUN DATO */
$strSql = "SELECT * FROM pedido WHERE id_pedido = '".$data->id."' ";

$str = $db->Execute($strSql);
$file = $str->FetchRow();

if($file['statusContador'] == 'Ocupado' && $file['statusAlmacen'] == 'Libre'){

  $strPed = "UPDATE pedido SET statusContador = 'Libre' WHERE id_pedido = '".$data->id."' ";
  $sqlPed = $db->Execute($strPed);
  $data->status = 'Ocupado';
  $data->sw = 0;

}else{

  $data->sw = 1;

}

//print_r($data);

if($file)
  echo json_encode($data);
else
  echo 0;
?>