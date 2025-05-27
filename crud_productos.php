<?php
include("db/conexion.php");

$productos = $conn->query("SELECT * FROM modelo.producto ORDER BY id_producto")->fetchAll(PDO::FETCH_ASSOC);
$tallas = $conn->query("SELECT * FROM modelo.catalogo_talla")->fetchAll(PDO::FETCH_ASSOC);
$colores = $conn->query("SELECT * FROM modelo.catalogo_color")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $conn->query("SELECT * FROM modelo.catalogo_categoria")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Botón para mostrar el formulario -->
<div class="mb-4">
  <button onclick="document.getElementById('form-producto').classList.toggle('hidden')" class="bg-blue-700 text-white px-4 py-2 rounded">
    ➕ Nuevo producto
  </button>
</div>

<!-- Formulario insertar -->
<form id="form-producto" method="POST" action="agregar_producto.php" class="bg-white shadow p-6 rounded grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
  <input name="nombre" placeholder="Nombre del producto" required class="border p-2 rounded">
  <textarea name="descripcion" placeholder="Descripción" required class="border p-2 rounded md:col-span-2"></textarea>
  <input name="precio" type="number" step="0.01" placeholder="Precio ₡" required class="border p-2 rounded">
  <input name="stock" type="number" placeholder="Stock" required class="border p-2 rounded">

  <select name="id_talla" required class="border p-2 rounded">
    <option value="">-- Talla --</option>
    <?php foreach ($tallas as $t): ?>
      <option value="<?= $t['id_talla'] ?>"><?= $t['nombre_talla'] ?></option>
    <?php endforeach; ?>
  </select>

  <select name="id_color" required class="border p-2 rounded">
    <option value="">-- Color --</option>
    <?php foreach ($colores as $c): ?>
      <option value="<?= $c['id_color'] ?>"><?= $c['nombre_color'] ?></option>
    <?php endforeach; ?>
  </select>

  <select name="id_categoria" required class="border p-2 rounded">
    <option value="">-- Categoría --</option>
    <?php foreach ($categorias as $cat): ?>
      <option value="<?= $cat['id_categoria'] ?>"><?= $cat['nombre_categoria'] ?></option>
    <?php endforeach; ?>
  </select>

  <div class="md:col-span-2 text-right">
    <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-2 rounded">Agregar producto</button>
  </div>
</form>

<!-- Tabla -->
<table class="min-w-full table-auto border text-sm mt-8">
  <thead class="bg-slate-200">
    <tr>
      <th class="p-2">ID</th>
      <th class="p-2">Nombre</th>
      <th class="p-2">Precio</th>
      <th class="p-2">Stock</th>
      <th class="p-2">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($productos as $p): ?>
      <tr class="border-t hover:bg-gray-50">
        <td class="p-2 font-mono"><?= $p['id_producto'] ?></td>
        <td class="p-2"><?= $p['nombre'] ?></td>
        <td class="p-2">₡<?= number_format($p['precio'], 2) ?></td>
        <td class="p-2"><?= $p['stock'] ?></td>
        <td class="p-2 space-x-2">
          <a href="editar_producto.php?id=<?= $p['id_producto'] ?>" class="text-blue-600 hover:underline">✏️ Editar</a>
          <a href="eliminar_producto.php?id=<?= $p['id_producto'] ?>" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
