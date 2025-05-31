<?php
// editar_producto.php
include("db/conexion.php");
$id = $_GET['id'];
$producto = $conn->query("SELECT * FROM modelo.producto WHERE id_producto = $id")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $descripcion = $_POST['descripcion'];
  $precio = $_POST['precio'];
  $stock = $_POST['stock'];

  $stmt = $conn->prepare("UPDATE modelo.producto SET nombre=?, descripcion=?, precio=?, stock=? WHERE id_producto=?");
  $stmt->execute([$nombre, $descripcion, $precio, $stock, $id]);

  header("Location: admin.php?mensaje=producto_editado");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-100 text-slate-800 font-sans">

  <form method="POST" class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow mt-10 space-y-4">
    <h2 class="text-3xl font-bold text-center">✏️ Editar Producto</h2>

    <div>
      <label class="block text-sm font-medium mb-1">Nombre</label>
      <input name="nombre" value="<?= $producto['nombre'] ?>" class="border p-2 rounded w-full" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Descripción</label>
      <textarea name="descripcion" class="border p-2 rounded w-full" required><?= $producto['descripcion'] ?></textarea>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Precio</label>
      <input name="precio" type="number" step="0.01" value="<?= $producto['precio'] ?>" class="border p-2 rounded w-full" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Stock</label>
      <input name="stock" type="number" value="<?= $producto['stock'] ?>" class="border p-2 rounded w-full" required>
    </div>

    <div class="pt-4">
      <button type="submit" class="bg-orange-600 hover:bg-orange-500 text-white px-6 py-2 rounded w-full font-semibold">
        💾 Guardar cambios
      </button>
    </div>
  </form>

</body>
</html>
