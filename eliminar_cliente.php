<?php
include("db/conexion.php");
$id = $_GET['id'];

$stmt = $conn->prepare("CALL modelo.eliminar_cliente(:id)");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

header("Location: admin.php?mensaje=cliente_eliminado");
exit;
?>
