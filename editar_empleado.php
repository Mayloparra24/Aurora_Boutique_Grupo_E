<?php
session_start();
include("db/conexion.php");

$id = $_GET['id'];

$empleado = $conn->query("SELECT * FROM modelo.empleado WHERE id_empleado = $id")->fetch(PDO::FETCH_ASSOC);

$correos = $conn->query("SELECT * FROM modelo.correo_empleado WHERE id_correoempleado IN (
  SELECT id_correoempleado FROM modelo.empleado WHERE id_empleado = $id)")->fetchAll(PDO::FETCH_ASSOC);

$telefonos = $conn->query("SELECT * FROM modelo.telefono_empleado WHERE id_telefonoempleado IN (
  SELECT id_telefonoempleado FROM modelo.empleado WHERE id_empleado = $id)")->fetchAll(PDO::FETCH_ASSOC);

$usuario = $conn->query("SELECT * FROM modelo.usuario WHERE id_empleado = $id")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre1 = $_POST['nombre1'];
  $nombre2 = $_POST['nombre2'];
  $apellido1 = $_POST['apellido1'];
  $apellido2 = $_POST['apellido2'];

  $stmt = $conn->prepare("UPDATE modelo.empleado SET nombre1=?, nombre2=?, apellido1=?, apellido2=? WHERE id_empleado=?");
  $stmt->execute([$nombre1, $nombre2, $apellido1, $apellido2, $id]);

  for ($i = 0; $i < 2; $i++) {
    $correoNuevo = $_POST['correo' . $i] ?? null;
    if (isset($correos[$i])) {
      $conn->prepare("UPDATE modelo.correo_empleado SET correo = ? WHERE id_correoempleado = ?")
           ->execute([$correoNuevo, $correos[$i]['id_correoempleado']]);
    }

    $telefonoNuevo = $_POST['telefono' . $i] ?? null;
    if (isset($telefonos[$i])) {
      $conn->prepare("UPDATE modelo.telefono_empleado SET telefono = ? WHERE id_telefonoempleado = ?")
           ->execute([$telefonoNuevo, $telefonos[$i]['id_telefonoempleado']]);
    }
  }

  $usuarioNuevo = $_POST['usuario'];
  $contrasenaNueva = $_POST['contrasena'];
  if ($usuario) {
    $conn->prepare("UPDATE modelo.usuario SET nombreusuario = ? WHERE id_usuario = ?")
         ->execute([$usuarioNuevo, $usuario['id_usuario']]);

    if (!empty($contrasenaNueva)) {
      $conn->prepare("UPDATE modelo.usuario SET contrasena = ? WHERE id_usuario = ?")
           ->execute([$contrasenaNueva, $usuario['id_usuario']]);
    }
  }

  header("Location: admin.php?mensaje=empleado_editado");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Empleado</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-100 text-slate-800 font-sans">

<form method="POST" class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow mt-10 space-y-6">
  <h2 class="text-3xl font-bold text-center">✏️ Editar Empleado</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Primer nombre</label>
      <input name="nombre1" value="<?= $empleado['nombre1'] ?>" class="border rounded p-2 w-full" required>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Segundo nombre</label>
      <input name="nombre2" value="<?= $empleado['nombre2'] ?>" class="border rounded p-2 w-full">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Primer apellido</label>
      <input name="apellido1" value="<?= $empleado['apellido1'] ?>" class="border rounded p-2 w-full" required>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Segundo apellido</label>
      <input name="apellido2" value="<?= $empleado['apellido2'] ?>" class="border rounded p-2 w-full">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php for ($i = 0; $i < 2; $i++): ?>
    <div>
      <label class="block text-sm font-medium mb-1">Correo <?= $i+1 ?></label>
      <input name="correo<?= $i ?>" value="<?= $correos[$i]['correo'] ?? '' ?>" class="border rounded p-2 w-full">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Teléfono <?= $i+1 ?></label>
      <input name="telefono<?= $i ?>" value="<?= $telefonos[$i]['telefono'] ?? '' ?>" class="border rounded p-2 w-full">
    </div>
    <?php endfor; ?>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Usuario</label>
      <input name="usuario" value="<?= $usuario['nombreusuario'] ?? '' ?>" class="border rounded p-2 w-full">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Contraseña (dejar en blanco para no cambiar)</label>
      <input name="contrasena" type="password" class="border rounded p-2 w-full">
    </div>
  </div>

  <div class="pt-4">
    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 w-full rounded">
      💾 Guardar cambios
    </button>
  </div>
</form>

</body>
</html>
