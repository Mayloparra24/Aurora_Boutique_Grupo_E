<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
  header("Location: /index.php");
  exit;
}

// Pedidos en revisión
$pedidos = $conn->query("
  SELECT p.id_pedido, p.fecha_compra, c.nombre1 || ' ' || c.apellido1 AS cliente, p.direccion_detallada
  FROM modelo.pedido p
  JOIN modelo.cliente c ON p.id_cliente = c.id_cliente
  WHERE p.id_estadopedido = 2
  ORDER BY p.fecha_compra DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Personal de envío
$empleados = $conn->query("
  SELECT id_empleado, nombre1 || ' ' || apellido1 AS nombre
  FROM modelo.empleado
  WHERE id_rol = 2
")->fetchAll(PDO::FETCH_ASSOC);

// Catálogos para productos
$tallas = $conn->query("SELECT id_talla, nombre_talla FROM modelo.catalogo_talla")->fetchAll(PDO::FETCH_ASSOC);
$colores = $conn->query("SELECT id_color, nombre_color FROM modelo.catalogo_color")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $conn->query("SELECT id_categoria, nombre_categoria FROM modelo.catalogo_categoria")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Admin - Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
<div class="text-right mb-6">
  <a href="../logout.php" class="text-sm text-blue-600 hover:underline">🔒 Cerrar sesión</a>
</div>

<h1 class="text-3xl font-bold mb-6">📦 Pedidos en revisión</h1>

<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'empleado_ok'): ?>
  <div class="bg-green-500 text-white px-4 py-2 rounded shadow mb-4">
    ✅ Empleado registrado correctamente.
  </div>
<?php endif; ?>

<!-- Lista de pedidos -->
<?php foreach ($pedidos as $p): ?>
  <form method="POST" action="verificar_pedido.php" class="bg-white rounded shadow p-4 mb-4 flex justify-between items-center">
    <div>
      <p><strong>Pedido #<?= $p['id_pedido'] ?></strong> - <?= $p['fecha_compra'] ?></p>
      <p>Cliente: <?= htmlspecialchars($p['cliente']) ?></p>
      <p>Dirección: <?= htmlspecialchars($p['direccion_detallada']) ?></p>
    </div>
    <div class="flex items-center gap-4">
      <select name="id_empleado" required class="border rounded px-2 py-1">
        <option value="">-- Personal de envío --</option>
        <?php foreach ($empleados as $e): ?>
          <option value="<?= $e['id_empleado'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="id_pedido" value="<?= $p['id_pedido'] ?>">
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
        ✔ Verificar
      </button>
    </div>
  </form>
<?php endforeach; ?>

<!-- Agregar nuevo empleado -->
<h2 class="text-2xl font-bold mt-10 mb-4">➕ Agregar nuevo empleado</h2>
<form method="POST" action="agregar_empleado.php" class="bg-white shadow p-6 rounded grid grid-cols-1 md:grid-cols-2 gap-4">
  <input name="nombre1" placeholder="Primer nombre" required class="border p-2 rounded">
  <input name="nombre2" placeholder="Segundo nombre" class="border p-2 rounded">
  <input name="apellido1" placeholder="Primer apellido" required class="border p-2 rounded">
  <input name="apellido2" placeholder="Segundo apellido" class="border p-2 rounded">
  <input name="correo" type="email" placeholder="Correo" required class="border p-2 rounded">
  <input name="telefono" type="text" placeholder="Teléfono" required class="border p-2 rounded">
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

<!-- Agregar nuevo producto -->
<h2 class="text-2xl font-bold mt-10 mb-4">🛍️ Agregar nuevo producto</h2>
<form method="POST" action="agregar_producto.php" class="bg-white shadow p-6 rounded grid grid-cols-1 md:grid-cols-2 gap-4">
  <input name="nombre" placeholder="Nombre del producto" required class="border p-2 rounded">
  <textarea name="descripcion" placeholder="Descripción" required class="border p-2 rounded md:col-span-2"></textarea>
  <input name="precio" type="number" placeholder="Precio ₡" step="0.01" required class="border p-2 rounded">
  <input name="stock" type="number" placeholder="Cantidad en stock" required class="border p-2 rounded">
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
</body>
</html>
