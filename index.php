<?php session_start(); include("db/conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
  <style>
    html, body {
      overflow: visible !important;
      position: static !important;
    }
  </style>
</head>
<body class="bg-slate-50 text-gray-800 font-sans">

<!-- Navbar -->
<nav class="flex justify-between items-center px-8 py-4 bg-slate-900 shadow-md relative">
  <div class="text-3xl font-bold text-yellow-400 tracking-tight">Aurora Boutique</div>
  <div class="space-x-4 flex items-center">
    <?php if (isset($_SESSION['usuario'])): ?>
      <span class="text-white font-medium">👤 <?php echo $_SESSION['usuario']; ?></span>
      <button onclick="togglePedidos()"
              class="bg-white text-slate-900 font-semibold px-3 py-1 rounded hover:bg-yellow-300 transition">
        🧾 Ver mis pedidos
      </button>
      <a href="logout.php" class="text-yellow-400 hover:underline">Cerrar sesión</a>
    <?php else: ?>
      <a href="login.php" class="text-yellow-400 font-semibold hover:underline">Iniciar sesión</a>
    <?php endif; ?>
    <div class="inline-block relative">
      <button id="carrito-toggle" onclick="cargarCarrito()" class="text-white text-lg hover:text-yellow-300 relative">
        🛍️ Carrito
        <span id="contador-carrito" class="absolute top-[-8px] right-[-10px] bg-yellow-400 text-slate-900 w-5 h-5 text-xs flex items-center justify-center rounded-full">0</span>
      </button>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="bg-gradient-to-r from-slate-800 to-slate-700 text-center text-white py-20 px-6">
  <h1 class="text-5xl font-extrabold mb-4">Moda con esencia</h1>
  <p class="text-lg text-slate-200 mb-6">Elegancia, estilo y autenticidad en cada prenda.</p>
  <a href="#coleccion" class="bg-yellow-400 hover:bg-yellow-300 text-slate-900 font-semibold px-6 py-3 rounded-full text-lg transition shadow">
    Ver colección
  </a>
</section>

<!-- Colección -->
<section id="coleccion" class="p-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 bg-slate-100">
  <?php
  $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock,
                 t.nombre_talla, c.nombre_color, g.nombre_categoria
          FROM modelo.producto p
          JOIN modelo.catalogo_talla t ON p.id_talla = t.id_talla
          JOIN modelo.catalogo_color c ON p.id_color = c.id_color
          JOIN modelo.catalogo_categoria g ON p.id_categoria = g.id_categoria";
  $stmt = $conn->query($sql);
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
  ?>
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
      <img src="https://via.placeholder.com/300x300.png?text=<?php echo urlencode($row['nombre']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>" class="rounded-t">
      <div class="p-4">
        <h3 class="text-xl font-semibold text-slate-900 mb-1"><?php echo htmlspecialchars($row['nombre']); ?></h3>
        <p class="text-sm text-slate-500 mb-1"><?php echo htmlspecialchars($row['descripcion']); ?></p>
        <p class="text-slate-600">Talla: <?php echo $row['nombre_talla']; ?> | Color: <?php echo $row['nombre_color']; ?></p>
        <p class="text-slate-600 mb-2">Categoría: <?php echo $row['nombre_categoria']; ?></p>
        <p class="text-lg font-bold text-yellow-600 mb-3">₡<?php echo number_format($row['precio'], 2); ?></p>
        <button onclick="agregarAlCarrito(<?php echo $row['id_producto']; ?>, '<?php echo addslashes($row['nombre']); ?>', <?php echo $row['precio']; ?>)"
                class="bg-yellow-400 hover:bg-yellow-300 text-slate-900 font-semibold px-4 py-2 rounded shadow">
          Agregar al carrito
        </button>
      </div>
    </div>
  <?php endwhile; ?>
</section>

<!-- Toast mensaje -->
<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'pedido_ok'): ?>
  <div id="toast" class="fixed top-6 right-6 bg-green-500 text-white px-6 py-3 rounded shadow-lg transition-opacity">
    ✅ Tu pedido ha sido realizado
  </div>
  <script>
    setTimeout(() => {
      document.getElementById('toast').style.opacity = '0';
    }, 3000);
  </script>
<?php endif; ?>

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
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($_SESSION['id_usuario'])):
          $stmt = $conn->prepare("
            SELECT p.id_pedido, p.fecha_compra, e.estado, SUM(d.subtotal) AS total
            FROM modelo.pedido p
            JOIN modelo.estadopedido e ON p.id_estadopedido = e.id_estadopedido
            JOIN modelo.detallepedido d ON p.id_pedido = d.id_pedido
            WHERE p.id_cliente = (
              SELECT id_cliente FROM modelo.usuario WHERE id_usuario = :uid
            )
            GROUP BY p.id_pedido, p.fecha_compra, e.estado
            ORDER BY p.fecha_compra DESC
          ");
          $stmt->execute([':uid' => $_SESSION['id_usuario']]);
          $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (count($pedidos) === 0): ?>
            <tr><td colspan="4" class="p-4 text-center text-gray-500">No tenés pedidos aún.</td></tr>
          <?php else:
            foreach ($pedidos as $p): ?>
              <tr class="border-t hover:bg-gray-50">
                <td class="p-2 font-mono"><?= $p['id_pedido'] ?></td>
                <td class="p-2"><?= $p['fecha_compra'] ?></td>
                <td class="p-2"><?= $p['estado'] ?></td>
                <td class="p-2">₡<?= number_format($p['total'], 2) ?></td>
              </tr>
        <?php endforeach; endif; endif; ?>
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
