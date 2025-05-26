<?php
session_start();
include("db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_pedido = $_POST['id_pedido'];
  $nuevo_estado = $_POST['nuevo_estado'];

  $stmt = $conn->prepare("
    UPDATE modelo.pedido
    SET id_estadopedido = :estado
    WHERE id_pedido = :id
  ");
  $stmt->execute([
    ':estado' => $nuevo_estado,
    ':id' => $id_pedido
  ]);

  header("Location: envios.php");
  exit;
}
?>
