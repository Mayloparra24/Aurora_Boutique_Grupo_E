<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aurora Boutique</title>
  <link href="src/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <nav class="flex justify-between items-center p-4 bg-white shadow">
    <div class="text-2xl font-bold">Aurora Boutique</div>
    <div class="space-x-4">
      <?php if (isset($_SESSION['usuario'])): ?>
        <span class="font-semibold">👤 <?php echo $_SESSION['usuario']; ?></span>
        <a href="logout.php" class="text-blue-600">Cerrar sesión</a>
      <?php else: ?>
        <a href="login.php" class="text-blue-600">Iniciar sesión</a>
      <?php endif; ?>
      <a href="#" class="text-gray-800">🛒 Carrito</a>
    </div>
  </nav>
  <main class="p-6 text-center">
    <h1 class="text-4xl font-bold mb-4">Bienvenidos a Aurora Boutique</h1>
    <p class="text-gray-600">Explorá nuestra colección de moda.</p>
  </main>
</body>
</html>