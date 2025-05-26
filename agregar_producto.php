<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_talla = $_POST['id_talla'];
    $id_color = $_POST['id_color'];
    $id_categoria = $_POST['id_categoria'];

    try {
        $stmt = $conn->prepare("INSERT INTO modelo.producto 
            (nombre, descripcion, precio, stock, id_talla, id_color, id_categoria)
            VALUES (:nombre, :descripcion, :precio, :stock, :talla, :color, :categoria)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':stock' => $stock,
            ':talla' => $id_talla,
            ':color' => $id_color,
            ':categoria' => $id_categoria
        ]);

        header("Location: admin.php?mensaje=producto_ok");
        exit;

    } catch (PDOException $e) {
        echo "<p class='text-red-600 font-bold'>Error al agregar producto: " . $e->getMessage() . "</p>";
    }
}
?>
