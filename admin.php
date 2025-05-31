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
  <script>
    function toggleModal(id) {
      document.getElementById(id).classList.toggle('hidden');
      document.getElementById(id).classList.toggle('flex');
    }
  </script>
</head>
<body class="bg-gray-100 p-6">
<div class="text-right mb-6">
  <a href="../logout.php" class="text-sm text-blue-600 hover:underline">🔒 Cerrar sesión</a>
  <a href="facturacion.php" class="ml-4 text-sm text-purple-700 font-semibold hover:underline">📄 Ver facturación</a>
  <a href="ver_resenas.php" class="ml-4 text-sm text-green-700 font-semibold hover:underline">⭐ Ver reseñas</a>
</div>

<div class="mb-6 space-x-4">
  <button onclick="toggleModal('modal-clientes')" class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-2 rounded">👥 CRUD Clientes</button>
  <button onclick="toggleModal('modal-empleados')" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded">👨‍💼 CRUD Empleados</button>
  <button onclick="toggleModal('modal-productos')" class="bg-orange-600 hover:bg-orange-500 text-white px-4 py-2 rounded">🛍️ CRUD Productos</button>
</div>

<h1 class="text-3xl font-bold mb-6">📦 Pedidos en revisión</h1>

<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'empleado_ok'): ?>
  <div class="bg-green-500 text-white px-4 py-2 rounded shadow mb-4">
    ✅ Empleado registrado correctamente.
  </div>
<?php endif; ?>

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
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">✔ Verificar</button>
    </div>
  </form>
<?php endforeach; ?>

<!-- CRUD modals -->
<div id="modal-clientes" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 justify-center items-start overflow-auto pt-10">
  <div class="bg-white max-w-4xl w-full mx-auto p-6 rounded-lg shadow-lg relative">
    <button onclick="toggleModal('modal-clientes')" class="absolute top-3 right-3 text-gray-500 text-2xl">✖</button>
    <h2 class="text-2xl font-bold mb-4">👥 Gestión de Clientes</h2>
    <?php include("crud_clientes.php"); ?>
  </div>
</div>

<div id="modal-empleados" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 justify-center items-start overflow-auto pt-10">
  <div class="bg-white max-w-4xl w-full mx-auto p-6 rounded-lg shadow-lg relative">
    <button onclick="toggleModal('modal-empleados')" class="absolute top-3 right-3 text-gray-500 text-2xl">✖</button>
    <h2 class="text-2xl font-bold mb-4">👨‍💼 Gestión de Empleados</h2>
    <?php include("crud_empleados.php"); ?>
  </div>
</div>

<div id="modal-productos" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 justify-center items-start overflow-auto pt-10">
  <div class="bg-white max-w-4xl w-full mx-auto p-6 rounded-lg shadow-lg relative">
    <button onclick="toggleModal('modal-productos')" class="absolute top-3 right-3 text-gray-500 text-2xl">✖</button>
    <h2 class="text-2xl font-bold mb-4">🛍️ Gestión de Productos</h2>
    <?php include("crud_productos.php"); ?>
  </div>
</div>
</body>
</html>
