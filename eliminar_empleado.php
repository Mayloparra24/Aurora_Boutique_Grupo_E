<?php
// eliminar_empleado.php
include("db/conexion.php");
$id = $_GET['id'];

$conn->prepare("DELETE FROM modelo.empleado WHERE id_empleado = ?")->execute([$id]);
header("Location: admin.php?mensaje=empleado_eliminado");
exit;
?><?php
// eliminar_empleado.php
include("db/conexion.php");
$id = $_GET['id'];

$conn->prepare("DELETE FROM modelo.empleado WHERE id_empleado = ?")->execute([$id]);
header("Location: admin.php?mensaje=empleado_eliminado");
exit;
?>