<?php
session_start();
include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_pedido = $_POST['id_pedido'];
  $id_empleado = $_POST['id_empleado'];

  $stmt = $conn->prepare("
    UPDATE modelo.pedido
    SET id_empleado_responsable = :emp,
        id_estadopedido = 4 -- Enviado
    WHERE id_pedido = :id
  ");
  $stmt->execute([
    ':emp' => $id_empleado,
    ':id' => $id_pedido
  ]);

  header("Location: admin.php");
  exit;
}
?>
