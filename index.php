<?php
session_start();
include("db/conexion.php");

if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$pedidos = $conn->prepare("
  SELECT p.id_pedido, p.fecha_compra, e.estado, SUM(d.subtotal) AS total,
         (SELECT COUNT(*) FROM modelo.reseña r WHERE r.id_pedido = p.id_pedido) AS tiene_resena
  FROM modelo.pedido p
  JOIN modelo.estadopedido e ON p.id_estadopedido = e.id_estadopedido
  JOIN modelo.detallepedido d ON p.id_pedido = d.id_pedido
  WHERE p.id_cliente = (
    SELECT id_cliente FROM modelo.usuario WHERE id_usuario = :uid
  )
  GROUP BY p.id_pedido, p.fecha_compra, e.estado
  ORDER BY p.fecha_compra DESC
");
$pedidos->execute([':uid' => $id_usuario]);
$pedidos = $pedidos->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Modal Pedidos -->
<div id="modal-pedidos" class="fixed inset-0 bg-black bg-opacity-40 z-[9998] hidden justify-center items-start pt-20">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl max-h-[80vh] overflow-auto p-6 relative">
    <button onclick="togglePedidos()" class="absolute top-4 right-4 text-gray-500 text-xl">✖</button>
    <h2 class="text-2xl font-bold mb-4">🧾 Pedidos realizados</h2>
    <table class="w-full border text-left text-sm">
      <thead class="bg-slate-200">
        <tr>
          <th class="p-2"># Pedido</th>
          <th class="p-2">Fecha</th>
          <th class="p-2">Estado</th>
          <th class="p-2">Total</th>
          <th class="p-2">Reseña</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($pedidos) === 0): ?>
          <tr><td colspan="5" class="p-4 text-center text-gray-500">No tenés pedidos aún.</td></tr>
        <?php else:
          foreach ($pedidos as $p): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="p-2 font-mono"><?= $p['id_pedido'] ?></td>
              <td class="p-2"><?= $p['fecha_compra'] ?></td>
              <td class="p-2"><?= $p['estado'] ?></td>
              <td class="p-2">₡<?= number_format($p['total'], 2) ?></td>
              <td class="p-2">
                <?php if ($p['estado'] === 'Entregado'): ?>
                  <?php if ($p['tiene_resena'] == 0): ?>
                    <a href="resena.php?id_pedido=<?= $p['id_pedido'] ?>" class="text-blue-600 hover:underline">✍️ Reseñar</a>
                  <?php else: ?>
                    <span class="text-green-600 font-semibold">✅ Ya reseñado</span>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="text-gray-400">--</span>
                <?php endif; ?>
              </td>
            </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>


<!-- Footer -->
<footer class="bg-slate-900 text-slate-400 py-6 text-center mt-10">
  © <?php echo date("Y"); ?> Aurora Boutique. Todos los derechos reservados.
</footer>

<!-- Scripts -->
<script>
  let carrito = [];

  function agregarAlCarrito(id, nombre, precio) {
    const existente = carrito.find(p => p.id === id);
    if (existente) {
      existente.cantidad++;
    } else {
      carrito.push({ id, nombre, precio, cantidad: 1 });
    }
    localStorage.setItem("carrito", JSON.stringify(carrito));
    actualizarCarrito();
  }

  function actualizarCarrito() {
    const contador = document.getElementById("contador-carrito");
    const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
    contador.innerText = totalItems;
    const lista = document.getElementById("lista-carrito");
    const total = document.getElementById("total-general");
    if (!lista || !total) return;
    lista.innerHTML = "";
    let totalGeneral = 0;
    carrito.forEach(item => {
      const totalItem = item.precio * item.cantidad;
      totalGeneral += totalItem;
      lista.innerHTML += `
        <div class='border-b py-2 flex justify-between'>
          <span>${item.nombre} x${item.cantidad}</span>
          <span>₡${totalItem.toFixed(2)}</span>
        </div>`;
    });
    total.innerText = "₡" + totalGeneral.toFixed(2);
  }

  function vaciarCarrito() {
    carrito = [];
    localStorage.setItem("carrito", "[]");
    actualizarCarrito();
  }

  function cargarCarrito() {
    fetch("carrito.php")
      .then(r => r.text())
      .then(html => {
        document.getElementById("contenedor-carrito").innerHTML = html;
        actualizarCarrito();
      });
  }

  function cerrarCarrito() {
    document.getElementById("contenedor-carrito").innerHTML = "";
  }

  function togglePedidos() {
    const modal = document.getElementById("modal-pedidos");
    modal.classList.toggle("hidden");
    modal.classList.toggle("flex");
  }

  window.onload = () => {
    carrito = JSON.parse(localStorage.getItem("carrito") || "[]");
    actualizarCarrito();
  }
</script>

<div id="contenedor-carrito"></div>
</body>
</html>
