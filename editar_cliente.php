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

<form method="POST" class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-lg mt-10 space-y-4">
  <h2 class="text-3xl font-bold text-center text-slate-800">✏️ Editar Cliente</h2>
  <div class="grid grid-cols-2 gap-4">
    <div><label class="block text-sm font-medium">Primer nombre</label>
      <input name="nombre1" value="<?= $cliente['nombre1'] ?>" class="border p-2 rounded w-full" required>
    </div>
    <div><label class="block text-sm font-medium">Segundo nombre</label>
      <input name="nombre2" value="<?= $cliente['nombre2'] ?>" class="border p-2 rounded w-full">
    </div>
    <div><label class="block text-sm font-medium">Primer apellido</label>
      <input name="apellido1" value="<?= $cliente['apellido1'] ?>" class="border p-2 rounded w-full" required>
    </div>
    <div><label class="block text-sm font-medium">Segundo apellido</label>
      <input name="apellido2" value="<?= $cliente['apellido2'] ?>" class="border p-2 rounded w-full">
    </div>
  </div>

  <?php for ($i = 0; $i < 2; $i++): ?>
  <div><label class="block text-sm font-medium">Correo <?= $i+1 ?></label>
    <input name="correo<?= $i ?>" value="<?= $correos[$i]['correo'] ?? '' ?>" class="border p-2 rounded w-full">
  </div>
  <div><label class="block text-sm font-medium">Teléfono <?= $i+1 ?></label>
    <input name="telefono<?= $i ?>" value="<?= $telefonos[$i]['telefono'] ?? '' ?>" class="border p-2 rounded w-full">
  </div>
  <?php endfor; ?>

  <div class="grid grid-cols-2 gap-4 pt-4">
    <div><label class="block text-sm font-medium">Usuario</label>
      <input name="usuario" value="<?= $usuario['nombreusuario'] ?? '' ?>" class="border p-2 rounded w-full">
    </div>
    <div><label class="block text-sm font-medium">Contraseña (dejar en blanco para no cambiar)</label>
      <input name="contrasena" type="password" class="border p-2 rounded w-full">
    </div>
  </div>

  <div class="pt-6">
    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded shadow w-full font-semibold">
      💾 Guardar cambios
    </button>
  </div>
</form>
