<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: login.php");
    exit;
}
include("db/conexion.php");
$paises = $conn->query("SELECT id_pais, nombre FROM modelo.catalogo_pais")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Finalizar Pedido</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-100 text-gray-800 p-6">
  <h1 class="text-3xl font-bold mb-6 text-center">Confirmar y Finalizar Pedido</h1>

  <form action="procesar_pedido.php" method="POST" class="max-w-3xl mx-auto bg-white p-6 rounded shadow space-y-6">
    <h2 class="text-xl font-semibold mb-4">Dirección de entrega</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <select name="pais" id="pais" required class="p-3 border rounded">
        <option value="">Seleccioná país</option>
        <?php foreach ($paises as $p): ?>
          <option value="<?= $p['id_pais'] ?>"><?= $p['nombre'] ?></option>
        <?php endforeach; ?>
      </select>
      <select name="provincia" id="provincia" required class="p-3 border rounded"><option value="">Provincia</option></select>
      <select name="canton" id="canton" required class="p-3 border rounded"><option value="">Cantón</option></select>
      <select name="distrito" id="distrito" required class="p-3 border rounded"><option value="">Distrito</option></select>
      <select name="barrio" id="barrio" required class="p-3 border rounded"><option value="">Barrio</option></select>
      <input required name="direccion_detallada" placeholder="Dirección detallada" class="p-3 border rounded col-span-1 md:col-span-2">
    </div>

    <h2 class="text-xl font-semibold mt-8 mb-4">Método de pago</h2>
    <select name="metodopago" required class="w-full p-3 border rounded">
      <option value="">Seleccioná un método</option>
      <option value="1">Tarjeta de crédito</option>
      <option value="2">Tarjeta de débito</option>
      <option value="3">Transferencia</option>
      <option value="4">Sinpe Móvil</option>
      <option value="5">Paypal</option>
    </select>

    <h2 class="text-xl font-semibold mt-8 mb-4">Resumen del carrito</h2>
    <div id="resumen-carrito" class="bg-gray-50 p-4 rounded border"></div>

    <input type="hidden" name="carrito_json" id="carrito_json">
    <div class="mt-6 text-right">
      <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-3 rounded font-bold">Pagar</button>
    </div>
  </form>

  <script>
    const carrito = JSON.parse(localStorage.getItem("carrito") || "[]");
    document.getElementById("carrito_json").value = JSON.stringify(carrito);
    const resumen = document.getElementById("resumen-carrito");
    let total = 0;
    resumen.innerHTML = carrito.map(p => {
      total += p.precio * p.cantidad;
      return `<div class='flex justify-between border-b py-2'>
                <span>${p.nombre} x${p.cantidad}</span>
                <span>₡${(p.precio * p.cantidad).toFixed(2)}</span>
              </div>`;
    }).join('') + `<div class='text-right font-bold mt-2'>Total: ₡${total.toFixed(2)}</div>`;

    function cargar(destino, origen, tipo) {
      origen.addEventListener("change", () => {
        fetch(`direccion_api.php?tipo=${tipo}&padre=${origen.value}`)
          .then(r => r.json())
          .then(data => {
            destino.innerHTML = `<option value="">Seleccioná</option>`;
            data.forEach(op => {
              destino.innerHTML += `<option value="${op.id}">${op.nombre}</option>`;
            });
          });
      });
    }

    cargar(document.getElementById("provincia"), document.getElementById("pais"), "provincia");
    cargar(document.getElementById("canton"), document.getElementById("provincia"), "canton");
    cargar(document.getElementById("distrito"), document.getElementById("canton"), "distrito");
    cargar(document.getElementById("barrio"), document.getElementById("distrito"), "barrio");
  </script>
</body>
</html>
