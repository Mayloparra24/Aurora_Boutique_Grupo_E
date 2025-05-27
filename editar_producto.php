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

<form method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">
  <h2 class="text-2xl font-bold mb-4">✏️ Editar Producto</h2>
  <input name="nombre" value="<?= $producto['nombre'] ?>" class="border p-2 rounded w-full mb-2" required>
  <textarea name="descripcion" class="border p-2 rounded w-full mb-2" required><?= $producto['descripcion'] ?></textarea>
  <input name="precio" type="number" step="0.01" value="<?= $producto['precio'] ?>" class="border p-2 rounded w-full mb-2" required>
  <input name="stock" type="number" value="<?= $producto['stock'] ?>" class="border p-2 rounded w-full mb-4" required>
  <button type="submit" class="bg-orange-600 hover:bg-orange-500 text-white px-4 py-2 rounded">Guardar cambios</button>
</form>