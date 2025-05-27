<?php
// eliminar_cliente.php
include("db/conexion.php");
$id = $_GET['id'];

// Primero eliminar registros relacionados (en orden correcto)
$conn->prepare("DELETE FROM modelo.usuario WHERE id_cliente = ?")->execute([$id]);
$conn->prepare("DELETE FROM modelo.reseña WHERE id_cliente = ?")->execute([$id]);
$conn->prepare("DELETE FROM modelo.pedido WHERE id_cliente = ?")->execute([$id]);

// Luego eliminar correo y teléfono si están referenciados
$conn->prepare("DELETE FROM modelo.correo_cliente WHERE id_correocliente = (
  SELECT id_correocliente FROM modelo.cliente WHERE id_cliente = ?
)")->execute([$id]);

$conn->prepare("DELETE FROM modelo.telefono_cliente WHERE id_telefonocliente = (
  SELECT id_telefonocliente FROM modelo.cliente WHERE id_cliente = ?
)")->execute([$id]);

// Finalmente, eliminar el cliente
$conn->prepare("DELETE FROM modelo.cliente WHERE id_cliente = ?")->execute([$id]);

header("Location: admin.php?mensaje=cliente_eliminado");
exit;
?>
