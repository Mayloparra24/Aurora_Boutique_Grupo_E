<?php
include("db/conexion.php");

$id = $_GET['id'];

try {
    // Activar excepciones para errores PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Intentar eliminar el cliente
    $stmt = $conn->prepare("DELETE FROM modelo.cliente WHERE id_cliente = ?");
    $stmt->execute([$id]);

    // Redirigir si fue exitoso
    header("Location: admin.php?mensaje=cliente_eliminado");
    exit;

} catch (PDOException $e) {
    // Mostrar mensaje de error si ocurre excepción
    echo "<div style='color: red; padding: 20px; font-family: monospace;'>";
    echo "<strong>❌ Error al eliminar el cliente:</strong><br>";
    echo $e->getMessage();
    echo "</div>";
}
?>
