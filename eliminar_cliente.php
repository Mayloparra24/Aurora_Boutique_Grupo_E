<?php
// eliminar_cliente.php
include("db/conexion.php");
$id = $_GET['id'];

$conn->prepare("DELETE FROM modelo.cliente WHERE id_cliente = ?")->execute([$id]);
header("Location: admin.php?mensaje=cliente_eliminado");
exit;
?>
