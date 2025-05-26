<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'envios') {
  header("Location: ../index.php");
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_empleado = $conn->query("SELECT id_empleado FROM modelo.usuario WHERE id_usuario = $id_usuario")
                    ->fetchColumn();

$pedidos = $conn->prepare("
  SELECT p.id_pedido, p.fecha_compra, p.direccion_detallada, e.estado
  FROM modelo.pedido p
  JOIN modelo.estadopedido e ON p.id_estadopedido = e.id_estadopedido
  WHERE p.id_empleado_responsable = :id
  ORDER BY p.fecha_compra DESC
");
$pedidos->execute([':id' => $id_empleado]);
$pedidos = $pedidos->fetchAll(PDO::FETCH_ASSOC);

$estados = $conn->query("SELECT id_estadopedido, estado FROM modelo.estadopedido")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pedidos asignados - Envíos</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-50 p-6">
  <div class="text-right mb-6">
  <a href="../logout.php" class="text-sm text-blue-600 hover:underline">🔒 Cerrar sesión</a>
</div>
  <h1 class="text-3xl font-bold mb-6">📦 Pedidos asignados</h1>

  <?php foreach ($pedidos as $p): ?>
    <form method="POST" action="actualizar_estado.php" class="bg-white rounded shadow p-4 mb-4 flex justify-between items-center">
      <div>
        <p><strong>Pedido #<?= $p['id_pedido'] ?></strong> - <?= $p['fecha_compra'] ?></p>
        <p>Dirección: <?= htmlspecialchars($p['direccion_detallada']) ?></p>
        <p>Estado actual: <span class="font-medium text-blue-600"><?= $p['estado'] ?></span></p>
      </div>
      <div class="flex items-center gap-4">
        <select name="nuevo_estado" required class="border rounded px-2 py-1">
          <option value="">-- Cambiar estado --</option>
          <?php foreach ($estados as $e): ?>
            <option value="<?= $e['id_estadopedido'] ?>"><?= htmlspecialchars($e['estado']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="hidden" name="id_pedido" value="<?= $p['id_pedido'] ?>">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Actualizar</button>
      </div>
    </form>
  <?php endforeach; ?>
</body>
</html>
