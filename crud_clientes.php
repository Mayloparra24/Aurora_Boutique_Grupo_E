<?php
include("db/conexion.php");

$clientes = $conn->query("SELECT * FROM modelo.cliente ORDER BY id_cliente")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Tabla de clientes -->
<table class="min-w-full table-auto border text-sm mt-8">
  <thead class="bg-slate-200">
    <tr>
      <th class="p-2">ID</th>
      <th class="p-2">Nombre</th>
      <th class="p-2">Apellido</th>
      <th class="p-2">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clientes as $c): ?>
      <tr class="border-t hover:bg-gray-50">
        <td class="p-2 font-mono"><?= $c['id_cliente'] ?></td>
        <td class="p-2"><?= $c['nombre1'] . " " . $c['nombre2'] ?></td>
        <td class="p-2"><?= $c['apellido1'] . " " . $c['apellido2'] ?></td>
        <td class="p-2 space-x-2">
          <a href="editar_cliente.php?id=<?= $c['id_cliente'] ?>" class="text-blue-600 hover:underline">✏️ Editar</a>
          <a href="eliminar_cliente.php?id=<?= $c['id_cliente'] ?>" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar este cliente?')">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
