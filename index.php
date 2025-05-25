<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-pink-50 font-sans">

  <!-- Navbar -->
  <nav class="flex justify-between items-center px-8 py-4 bg-white shadow-md">
    <div class="text-3xl font-extrabold text-pink-600 tracking-tight">Aurora Boutique</div>
    <div class="space-x-6">
      <?php if (isset($_SESSION['usuario'])): ?>
        <span class="font-medium text-gray-700">👤 <?php echo $_SESSION['usuario']; ?></span>
        <a href="logout.php" class="text-pink-600 hover:underline">Cerrar sesión</a>
      <?php else: ?>
        <a href="login.php" class="text-pink-600 font-semibold hover:underline">Iniciar sesión</a>
      <?php endif; ?>
      <a href="#" class="text-gray-700 text-lg hover:text-pink-500">🛍️ Carrito</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-pink-100 py-20 text-center">
    <h1 class="text-5xl font-extrabold text-gray-800 mb-4">Descubrí tu estilo con elegancia</h1>
    <p class="text-lg text-gray-600 mb-8">Moda exclusiva, diseñada para vos. Explora nuestra colección de ropa, accesorios y más.</p>
    <a href="#coleccion" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-full text-lg font-semibold shadow">
      Ver colección
    </a>
  </section>

  <!-- Colección destacada (placeholder) -->
  <section id="coleccion" class="p-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
      <img src="https://via.placeholder.com/300x300.png?text=Producto+1" alt="Producto 1" class="rounded mb-4">
      <h3 class="text-xl font-bold text-gray-800">Vestido Floral</h3>
      <p class="text-gray-600">₡29,000</p>
    </div>
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
      <img src="https://via.placeholder.com/300x300.png?text=Producto+2" alt="Producto 2" class="rounded mb-4">
      <h3 class="text-xl font-bold text-gray-800">Blusa Elegante</h3>
      <p class="text-gray-600">₡19,000</p>
    </div>
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
      <img src="https://via.placeholder.com/300x300.png?text=Producto+3" alt="Producto 3" class="rounded mb-4">
      <h3 class="text-xl font-bold text-gray-800">Bolso de Mano</h3>
      <p class="text-gray-600">₡24,000</p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white border-t mt-10 py-6 text-center text-gray-500">
    © <?php echo date("Y"); ?> Aurora Boutique. Todos los derechos reservados.
  </footer>

</body>
</html>