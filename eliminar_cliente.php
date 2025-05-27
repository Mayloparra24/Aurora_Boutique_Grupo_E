<?php
include("db/conexion.php");
$id = $_GET['id'];

// Llamar al procedimiento almacenado
$stmt = $conn->prepare("SELECT modelo.eliminar_cliente_completo(:id)");
$stmt->execute([':id' => $id]);

header("Location: admin.php?mensaje=cliente_eliminado");
exit;
?>
