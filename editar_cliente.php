<?php
session_start();
include("db/conexion.php");

$id = $_GET['id'];

// Obtener datos del cliente principal
$cliente = $conn->query("SELECT * FROM modelo.cliente WHERE id_cliente = $id")->fetch(PDO::FETCH_ASSOC);

// Obtener correos y teléfonos
$correos = $conn->query("SELECT id_correocliente, correo FROM modelo.correo_cliente WHERE id_correocliente IN (
  SELECT id_correocliente FROM modelo.cliente WHERE id_cliente = $id)")->fetchAll(PDO::FETCH_ASSOC);

$telefonos = $conn->query("SELECT id_telefonocliente, telefono FROM modelo.telefono_cliente WHERE id_telefonocliente IN (
  SELECT id_telefonocliente FROM modelo.cliente WHERE id_cliente = $id)")->fetchAll(PDO::FETCH_ASSOC);

// Obtener usuario asociado
$usuario = $conn->query("SELECT * FROM modelo.usuario WHERE id_cliente = $id")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Datos personales
  $nombre1 = $_POST['nombre1'];
  $nombre2 = $_POST['nombre2'];
  $apellido1 = $_POST['apellido1'];
  $apellido2 = $_POST['apellido2'];

  $stmt = $conn->prepare("UPDATE modelo.cliente SET nombre1=?, nombre2=?, apellido1=?, apellido2=? WHERE id_cliente=?");
  $stmt->execute([$nombre1, $nombre2, $apellido1, $apellido2, $id]);

  // Correos (hasta 2)
  for ($i = 0; $i < 2; $i++) {
    $campoCorreo = $_POST["correo" . $i] ?? null;
    if (isset($correos[$i])) {
      $conn->prepare("UPDATE modelo.correo_cliente SET correo = ? WHERE id_correocliente = ?")
           ->execute([$campoCorreo, $correos[$i]['id_correocliente']]);
    }
  }

  // Teléfonos (hasta 2)
  for ($i = 0; $i < 2; $i++) {
    $campoTelefono = $_POST["telefono" . $i] ?? null;
    if (isset($telefonos[$i])) {
      $conn->prepare("UPDATE modelo.telefono_cliente SET telefono = ? WHERE id_telefonocliente = ?")
           ->execute([$campoTelefono, $telefonos[$i]['id_telefonocliente']]);
    }
  }

  // Usuario
  $nuevoUsuario = $_POST['usuario'];
  $nuevaContrasena = $_POST['contrasena'];
  if ($usuario) {
    $conn->prepare("UPDATE modelo.usuario SET nombreusuario = ? WHERE id_usuario = ?")
         ->execute([$nuevoUsuario, $usuario['id_usuario']]);

    if (!empty($nuevaContrasena)) {
      $conn->prepare("UPDATE modelo.usuario SET contrasena = ? WHERE id_usuario = ?")
           ->execute([$nuevaContrasena, $usuario['id_usuario']]);
    }
  }

  header("Location: admin.php?mensaje=cliente_editado");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cliente</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-100 text-slate-800 font-sans">

<form method="POST" class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow space-y-6 mt-10">
  <h2 class="text-3xl font-bold text-slate-800 text-center mb-6">✏️ Editar Cliente</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Primer nombre</label>
      <input name="nombre1" value="<?= $cliente['nombre1'] ?>" class="w-full border border-slate-300 rounded p-2" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Segundo nombre</label>
      <input name="nombre2" value="<?= $cliente['nombre2'] ?>" class="w-full border border-slate-300 rounded p-2">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Primer apellido</label>
      <input name="apellido1" value="<?= $cliente['apellido1'] ?>" class="w-full border border-slate-300 rounded p-2" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Segundo apellido</label>
      <input name="apellido2" value="<?= $cliente['apellido2'] ?>" class="w-full border border-slate-300 rounded p-2">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php for ($i = 0; $i < 2; $i++): ?>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Correo <?= $i+1 ?></label>
        <input name="correo<?= $i ?>" value="<?= $correos[$i]['correo'] ?? '' ?>" class="w-full border border-slate-300 rounded p-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono <?= $i+1 ?></label>
        <input name="telefono<?= $i ?>" value="<?= $telefonos[$i]['telefono'] ?? '' ?>" class="w-full border border-slate-300 rounded p-2">
      </div>
    <?php endfor; ?>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Usuario</label>
      <input name="usuario" value="<?= $usuario['nombreusuario'] ?? '' ?>" class="w-full border border-slate-300 rounded p-2" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña (dejar en blanco para no cambiar)</label>
      <input name="contrasena" type="password" class="w-full border border-slate-300 rounded p-2">
    </div>
  </div>

  <div class="pt-6">
    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-400 text-slate-900 font-bold py-2 px-4 rounded-lg shadow">
      💾 Guardar cambios
    </button>
  </div>
</form>

</body>
</html>
