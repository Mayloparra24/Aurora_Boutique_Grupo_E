<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header("Location: /index.php");
  exit;
}

$resenas = [];
$error = "";

try {
  $sql = 'SELECT r."id_reseña", r.comentario, r.calificacion, p.id_pedido, c.nombre1 || \' \' || c.apellido1 AS cliente
          FROM modelo."reseña" r
          JOIN modelo.pedido p ON r.id_pedido = p.id_pedido
          JOIN modelo.cliente c ON r.id_cliente = c.id_cliente
          ORDER BY r."id_reseña" DESC';
  $resenas = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reseñas - Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <div class="text-right mb-6">
    <a href="admin.php" class="text-sm text-blue-700 hover:underline">⬅ Volver al Panel</a>
  </div>

  <h1 class="text-3xl font-bold mb-6">⭐ Reseñas de Clientes</h1>

  <?php if ($error): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded shadow mb-4">
      <strong>Error:</strong> <?= htmlspecialchars($error) ?>
    </div>
  <?php elseif (count($resenas) === 0): ?>
    <p class="text-gray-600">No hay reseñas registradas.</p>
  <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($resenas as $r): ?>
        <div class="bg-white shadow rounded p-4">
          <p class="text-sm text-gray-500 mb-1">Pedido #<?= $r['id_pedido'] ?> | Cliente: <?= htmlspecialchars($r['cliente']) ?></p>
          <p class="text-gray-800 italic mb-2">"<?= htmlspecialchars($r['comentario']) ?>"</p>
          <p class="font-semibold text-yellow-600">Calificación: <?= str_repeat('⭐', intval($r['calificacion'])) ?> (<?= $r['calificacion'] ?>/5)</p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</body>
</html>
