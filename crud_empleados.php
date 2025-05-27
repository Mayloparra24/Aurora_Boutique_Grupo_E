<?php
include("db/conexion.php");

$empleados = $conn->query("SELECT id_empleado, nombre1, nombre2, apellido1, apellido2 FROM modelo.empleado ORDER BY id_empleado")->fetchAll(PDO::FETCH_ASSOC);
$roles = [1 => 'Administrador', 2 => 'Personal de Envíos'];
?>

<!-- Botón para mostrar el formulario -->
<div class="mb-4">
  <button onclick="document.getElementById('form-empleado').classList.toggle('hidden')" class="bg-blue-700 text-white px-4 py-2 rounded">
    ➕ Nuevo empleado
  </button>
</div>

<!-- Formulario insertar -->
<form id="form-empleado" method="POST" action="agregar_empleado.php" class="bg-white shadow p-6 rounded grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
  <input name="nombre1" placeholder="Primer nombre" required class="border p-2 rounded">
  <input name="nombre2" placeholder="Segundo nombre" class="border p-2 rounded">
  <input name="apellido1" placeholder="Primer apellido" required class="border p-2 rounded">
  <input name="apellido2" placeholder="Segundo apellido" class="border p-2 rounded">
  <input name="correo" type="email" placeholder="Correo principal" required class="border p-2 rounded">
  <input name="correo2" type="email" placeholder="Correo secundario" class="border p-2 rounded">
  <input name="telefono" type="text" placeholder="Teléfono principal" required class="border p-2 rounded">
  <input name="telefono2" type="text" placeholder="Teléfono secundario" class="border p-2 rounded">
  <select name="rol" required class="border p-2 rounded">
    <option value="">-- Rol --</option>
    <option value="1">Administrador</option>
    <option value="2">PersonalEnvios</option>
  </select>
  <input name="usuario" placeholder="Nombre de usuario" required class="border p-2 rounded">
  <input name="clave" type="password" placeholder="Contraseña" required class="border p-2 rounded">
  <div class="md:col-span-2 text-right">
    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded">Registrar empleado</button>
  </div>
</form>

<!-- Tabla -->
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
    <?php foreach ($empleados as $e): ?>
      <tr class="border-t hover:bg-gray-50">
        <td class="p-2 font-mono"><?= $e['id_empleado'] ?></td>
        <td class="p-2"><?= $e['nombre1'] . " " . $e['nombre2'] ?></td>
        <td class="p-2"><?= $e['apellido1'] . " " . $e['apellido2'] ?></td>
        <td class="p-2 space-x-2">
          <a href="editar_empleado.php?id=<?= $e['id_empleado'] ?>" class="text-blue-600 hover:underline">✏️ Editar</a>
          <a href="eliminar_empleado.php?id=<?= $e['id_empleado'] ?>" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar este empleado?')">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
