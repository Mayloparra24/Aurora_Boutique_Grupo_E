<?php
// editar_empleado.php
include("db/conexion.php");
$id = $_GET['id'];
$empleado = $conn->query("SELECT * FROM modelo.empleado WHERE id_empleado = $id")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre1 = $_POST['nombre1'];
  $nombre2 = $_POST['nombre2'];
  $apellido1 = $_POST['apellido1'];
  $apellido2 = $_POST['apellido2'];

  $stmt = $conn->prepare("UPDATE modelo.empleado SET nombre1=?, nombre2=?, apellido1=?, apellido2=? WHERE id_empleado=?");
  $stmt->execute([$nombre1, $nombre2, $apellido1, $apellido2, $id]);

  header("Location: admin.php?mensaje=empleado_editado");
  exit;
}
?>

<form method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">
  <h2 class="text-2xl font-bold mb-4">✏️ Editar Empleado</h2>
  <input name="nombre1" value="<?= $empleado['nombre1'] ?>" class="border p-2 rounded w-full mb-2" required>
  <input name="nombre2" value="<?= $empleado['nombre2'] ?>" class="border p-2 rounded w-full mb-2">
  <input name="apellido1" value="<?= $empleado['apellido1'] ?>" class="border p-2 rounded w-full mb-2" required>
  <input name="apellido2" value="<?= $empleado['apellido2'] ?>" class="border p-2 rounded w-full mb-4">
  <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded">Guardar cambios</button>
</form>