<?php session_start(); include("db/conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
  <script>
    let carrito = [];

    function agregarAlCarrito(id, nombre, precio) {
      const existente = carrito.find(p => p.id === id);
      if (existente) {
        existente.cantidad++;
      } else {
        carrito.push({ id, nombre, precio, cantidad: 1 });
      }
      actualizarCarrito();
    }

    function actualizarCarrito() {
      const carritoIcon = document.getElementById("contador-carrito");
      const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
      carritoIcon.innerText = totalItems;

      const lista = document.getElementById("lista-carrito");
      lista.innerHTML = "";

      let totalGeneral = 0;
      carrito.forEach(prod => {
        const total = prod.precio * prod.cantidad;
        totalGeneral += total;
        const item = `<div class='border-b py-2 flex justify-between'>
                        <span>${prod.nombre} x${prod.cantidad}</span>
                        <span>₡${total.toFixed(2)}</span>
                      </div>`;
        lista.innerHTML += item;
      });

      document.getElementById("total-general").innerText = "₡" + totalGeneral.toFixed(2);
    }

    function toggleCarrito() {
      const carritoVentana = document.getElementById("ventana-carrito");
      carritoVentana.classList.toggle("hidden");
    }

    function vaciarCarrito() {
      carrito = [];
      actualizarCarrito();
    }
  </script>
</head>
<body class="bg-slate-50 text-gray-800 font-sans">
  <nav class="flex justify-between items-center px-8 py-4 bg-slate-900 shadow-md relative">
    <div class="text-3xl font-bold text-yellow-400 tracking-tight">Aurora Boutique</div>
    <div class="space-x-6">
      <?php if (isset($_SESSION['usuario'])): ?>
        <span class="text-white font-medium">👤 <?php echo $_SESSION['usuario']; ?></span>
        <a href="logout.php" class="text-yellow-400 hover:underline">Cerrar sesión</a>
      <?php else: ?>
        <a href="login.php" class="text-yellow-400 font-semibold hover:underline">Iniciar sesión</a>
      <?php endif; ?>
      <button onclick="toggleCarrito()" class="text-white text-lg hover:text-yellow-300 relative">
        🛍️ Carrito <span id="contador-carrito" class="ml-1 inline-block bg-yellow-400 text-slate-900 px-2 py-0.5 rounded-full text-sm">0</span>
      </button>
    </div>

    <!-- Ventana carrito (reposicionada) -->
    <div id="ventana-carrito" class="absolute top-16 right-8 w-80 bg-white shadow-lg z-50 p-4 rounded hidden">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-slate-800">Tu Carrito</h2>
        <button onclick="toggleCarrito()" class="text-slate-500 text-xl">✖</button>
      </div>
      <div id="lista-carrito" class="mb-4 max-h-60 overflow-y-auto"></div>
      <div class="text-right font-bold text-lg text-slate-700 mb-4">Total: <span id="total-general">₡0.00</span></div>
      <div class="flex justify-between">
        <button onclick="vaciarCarrito()" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded">Vaciar</button>
        <button class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded">Realizar Pedido</button>
      </div>
    </div>
  </nav>

  <section class="bg-gradient-to-r from-slate-800 to-slate-700 text-center text-white py-20 px-6">
    <h1 class="text-5xl font-extrabold mb-4">Moda con esencia</h1>
    <p class="text-lg text-slate-200 mb-6">Elegancia, estilo y autenticidad en cada prenda.</p>
    <a href="#coleccion" class="bg-yellow-400 hover:bg-yellow-300 text-slate-900 font-semibold px-6 py-3 rounded-full text-lg transition shadow">
      Ver colección
    </a>
  </section>

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

  <footer class="bg-slate-900 text-slate-400 py-6 text-center">
    © <?php echo date("Y"); ?> Aurora Boutique. Todos los derechos reservados.
  </footer>
</body>
</html>

