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

try {
  $id_pedido = $_GET['id_pedido'];
  $id_usuario = $_SESSION['id_usuario'];

  // Obtener id_cliente
  $stmt = $conn->prepare("SELECT id_cliente FROM modelo.usuario WHERE id_usuario = :uid");
  $stmt->execute([':uid' => $id_usuario]);
  $id_cliente = $stmt->fetchColumn();

  if (!$id_cliente) {
    throw new Exception("No se pudo determinar el cliente desde el usuario.");
  }

  // Verificar si ya existe reseña para este pedido y cliente
  $stmt = $conn->prepare("SELECT COUNT(*) FROM modelo.reseña WHERE id_pedido = :pedido AND id_cliente = :cliente");
  $stmt->execute([':pedido' => $id_pedido, ':cliente' => $id_cliente]);
  $yaTiene = $stmt->fetchColumn();

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($yaTiene > 0) {
      header("Location: index.php?mensaje=reseña_existente");
      exit;
    }

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
} catch (PDOException $e) {
  echo "<div style='color: red; padding: 1rem;'>Error de base de datos: " . $e->getMessage() . "</div>";
} catch (Exception $e) {
  echo "<div style='color: red; padding: 1rem;'>Error: " . $e->getMessage() . "</div>";
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

    <?php if (isset($yaTiene) && $yaTiene > 0): ?>
      <div class="mb-4 text-red-600 font-semibold">
        Ya dejaste una reseña para este pedido.
      </div>
    <?php else: ?>
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
    <?php endif; ?>
  </div>
</body>
</html>
