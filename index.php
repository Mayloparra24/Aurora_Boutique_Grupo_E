<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-gray-800 font-sans">

  <!-- Navbar -->
  <nav class="flex justify-between items-center px-8 py-5 bg-slate-900 shadow-md">
    <div class="text-3xl font-bold text-yellow-400 tracking-tight">Aurora Boutique</div>
    <div class="space-x-6">
      <?php if (isset($_SESSION['usuario'])): ?>
        <span class="text-white font-medium">👤 <?php echo $_SESSION['usuario']; ?></span>
        <a href="logout.php" class="text-yellow-400 hover:underline">Cerrar sesión</a>
      <?php else: ?>
        <a href="login.php" class="text-yellow-400 font-semibold hover:underline">Iniciar sesión</a>
      <?php endif; ?>
      <a href="#" class="text-white text-lg hover:text-yellow-300">🛍️ Carrito</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-slate-800 to-slate-700 text-center text-white py-20 px-6">
    <h1 class="text-5xl font-extrabold mb-4">Moda con esencia</h1>
    <p class="text-lg text-slate-200 mb-6">Elegancia, estilo y autenticidad en cada prenda.</p>
    <a href="#coleccion" class="bg-yellow-400 hover:bg-yellow-300 text-slate-900 font-semibold px-6 py-3 rounded-full text-lg transition shadow">
      Ver colección
    </a>
  </section>

  <!-- Colección -->
  <section id="coleccion" class="p-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 bg-slate-100">
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
      <img src="https://via.placeholder.com/300x300.png?text=Vestido+Floral" alt="Vestido Floral" class="rounded-t">
      <div class="p-4">
        <h3 class="text-xl font-semibold text-slate-900 mb-1">Vestido Floral</h3>
        <p class="text-slate-500">₡29,000</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
      <img src="https://via.placeholder.com/300x300.png?text=Blusa+Elegante" alt="Blusa Elegante" class="rounded-t">
      <div class="p-4">
        <h3 class="text-xl font-semibold text-slate-900 mb-1">Blusa Elegante</h3>
        <p class="text-slate-500">₡19,000</p>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
      <img src="https://via.placeholder.com/300x300.png?text=Bolso+de+Mano" alt="Bolso de Mano" class="rounded-t">
      <div class="p-4">
        <h3 class="text-xl font-semibold text-slate-900 mb-1">Bolso de Mano</h3>
        <p class="text-slate-500">₡24,000</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-slate-900 text-slate-400 py-6 text-center">
    © <?php echo date("Y"); ?> Aurora Boutique. Todos los derechos reservados.
  </footer>

</body>
</html>
