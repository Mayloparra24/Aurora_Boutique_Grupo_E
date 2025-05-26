<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['id_usuario'];
$carrito = json_decode($_POST['carrito_json'], true);
$id_barrio = $_POST['barrio'];
$direccion = $_POST['direccion_detallada'];
$metodo = $_POST['metodopago'];

try {
    $conn->beginTransaction();

    // Verificar si aplica descuento
    $stmt = $conn->prepare("
        SELECT SUM(d.subtotal) AS total_compras
        FROM modelo.pedido p
        JOIN modelo.detallepedido d ON p.id_pedido = d.id_pedido
        WHERE p.id_cliente = :cliente
          AND p.fecha_compra >= CURRENT_DATE - INTERVAL '6 months'
    ");
    $stmt->execute([':cliente' => $usuario_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_compras = $result['total_compras'] ?? 0;

    // Calcular total del pedido
    $total = 0;
    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    // Aplicar 15% de descuento si cumple condición
    if ($total_compras >= 200000) {
        $total *= 0.85;
    }

    // Insertar pedido
    $stmt = $conn->prepare("INSERT INTO modelo.pedido (id_cliente, id_empleado_responsable, id_barrio_entrega, id_estadopedido, fecha_compra, direccion_detallada)
                            VALUES (:cliente, NULL, :barrio, 2, CURRENT_DATE, :direccion) RETURNING id_pedido");
    $stmt->execute([
        ":cliente" => $usuario_id,
        ":barrio" => $id_barrio,
        ":direccion" => $direccion
    ]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_pedido = $pedido['id_pedido'];

    // Insertar detalles
    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $stmt = $conn->prepare("INSERT INTO modelo.detallepedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal)
                                VALUES (:pedido, :producto, :cant, :precio, :sub)");
        $stmt->execute([
            ":pedido" => $id_pedido,
            ":producto" => $item['id'],
            ":cant" => $item['cantidad'],
            ":precio" => $item['precio'],
            ":sub" => $subtotal
        ]);
    }

    // Insertar transacción
    $stmt = $conn->prepare("INSERT INTO modelo.transaccion (id_pedido, id_metodopago, montopagado)
                            VALUES (:pedido, :metodo, :total)");
    $stmt->execute([
        ":pedido" => $id_pedido,
        ":metodo" => $metodo,
        ":total" => $total
    ]);

    $conn->commit();

    echo "<script>
      localStorage.removeItem('carrito');
      window.location.href = 'index.php?mensaje=pedido_ok';
    </script>";

} catch (Exception $e) {
    $conn->rollBack();
    echo "<p>Error al procesar el pedido: {$e->getMessage()}</p>";
}
