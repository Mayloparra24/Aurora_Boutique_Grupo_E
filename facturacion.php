<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header("Location: index.php");
  exit;
}

try {
  $facturas = $conn->query("
    SELECT f.*, c.nombre1 || ' ' || c.apellido1 AS cliente
    FROM modelo.factura f
    JOIN modelo.pedido p ON f.id_pedido = p.id_pedido
    JOIN modelo.cliente c ON p.id_cliente = c.id_cliente
    ORDER BY f.fecha_emision DESC
  ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("<h1 style='color:red; font-family:monospace;'>⚠️ Error en facturación:</h1><pre>" . $e->getMessage() . "</pre>");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Facturación - Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-100 p-6">
  <h1 class="text-3xl font-bold mb-6 text-center">📄 Facturación</h1>

  <table class="w-full bg-white shadow-md rounded overflow-hidden text-sm">
    <thead class="bg-slate-200 text-left">
      <tr>
        <th class="p-3">Factura</th>
        <th class="p-3">Cliente</th>
        <th class="p-3">Subtotal</th>
        <th class="p-3">Descuento</th>
        <th class="p-3">Impuesto</th>
        <th class="p-3">Comisión</th>
        <th class="p-3">Total</th>
        <th class="p-3">Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($facturas as $f): ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="p-3 font-mono"><?= $f['id_factura'] ?></td>
          <td class="p-3"><?= htmlspecialchars($f['cliente']) ?></td>
          <td class="p-3">₡<?= number_format($f['subtotal'], 2) ?></td>
          <td class="p-3">₡<?= number_format($f['descuento'], 2) ?></td>
          <td class="p-3">₡<?= number_format($f['impuesto'], 2) ?></td>
          <td class="p-3">₡<?= number_format($f['comision'], 2) ?></td>
          <td class="p-3 font-bold">₡<?= number_format($f['total'], 2) ?></td>
          <td class="p-3"><?= $f['fecha_emision'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="text-right mt-6">
    <a href="admin.php" class="text-blue-600 hover:underline">← Volver al panel admin</a>
  </div>
</body>
</html>
