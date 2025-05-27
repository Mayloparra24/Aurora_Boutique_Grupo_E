<?php
// editar_cliente.php
include("db/conexion.php");
$id = $_GET['id'];
$cliente = $conn->query("SELECT * FROM modelo.cliente WHERE id_cliente = $id")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre1 = $_POST['nombre1'];
  $nombre2 = $_POST['nombre2'];
  $apellido1 = $_POST['apellido1'];
  $apellido2 = $_POST['apellido2'];

  $stmt = $conn->prepare("UPDATE modelo.cliente SET nombre1=?, nombre2=?, apellido1=?, apellido2=? WHERE id_cliente=?");
  $stmt->execute([$nombre1, $nombre2, $apellido1, $apellido2, $id]);

  header("Location: admin.php?mensaje=cliente_editado");
  exit;
}
?>

<form method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">
  <h2 class="text-2xl font-bold mb-4">✏️ Editar Cliente</h2>
  <input name="nombre1" value="<?= $cliente['nombre1'] ?>" class="border p-2 rounded w-full mb-2" required>
  <input name="nombre2" value="<?= $cliente['nombre2'] ?>" class="border p-2 rounded w-full mb-2">
  <input name="apellido1" value="<?= $cliente['apellido1'] ?>" class="border p-2 rounded w-full mb-2" required>
  <input name="apellido2" value="<?= $cliente['apellido2'] ?>" class="border p-2 rounded w-full mb-4">
  <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">Guardar cambios</button>
</form>