<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id_pedido'])) {
  die("Pedido no especificado.");
}

$id_pedido = $_GET['id_pedido'];
$id_usuario = $_SESSION['id_usuario'];

// Obtener id_cliente
$stmt = $conn->prepare("SELECT id_cliente FROM modelo.usuario WHERE id_usuario = :uid");
$stmt->execute([':uid' => $id_usuario]);
$id_cliente = $stmt->fetchColumn();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $comentario = $_POST['comentario'] ?? '';
  $calificacion = $_POST['calificacion'] ?? 0;

  $stmt = $conn->prepare("INSERT INTO modelo.reseña (id_pedido, id_cliente, comentario, calificacion)
                          VALUES (:pedido, :cliente, :comentario, :calificacion)");
  $stmt->execute([
    ':pedido' => $id_pedido,
    ':cliente' => $id_cliente,
    ':comentario' => $comentario,
    ':calificacion' => $calificacion
  ]);

  header("Location: index.php?mensaje=reseña_ok");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dejar Reseña</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-100 p-8">
  <div class="max-w-xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-4">✍️ Dejar Reseña</h1>
    <form method="POST">
      <label class="block mb-2">Comentario:</label>
      <textarea name="comentario" required class="w-full border rounded p-2 mb-4" rows="4"></textarea>

      <label class="block mb-2">Calificación (1 a 5):</label>
      <select name="calificacion" required class="w-full border rounded p-2 mb-4">
        <option value="">-- Seleccione --</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
      </select>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Enviar reseña</button>
    </form>
  </div>
</body>
</html>
