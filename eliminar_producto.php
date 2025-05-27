<?php
// eliminar_producto.php
include("db/conexion.php");
$id = $_GET['id'];

$conn->prepare("DELETE FROM modelo.producto WHERE id_producto = ?")->execute([$id]);
header("Location: admin.php?mensaje=producto_eliminado");
exit;
?>