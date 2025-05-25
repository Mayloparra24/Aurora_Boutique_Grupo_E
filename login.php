<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión - Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-slate-100 text-gray-800">
  <form method="POST" action="router.php" class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-slate-800">Iniciar sesión</h2>
    <input type="text" name="usuario" placeholder="Usuario" class="w-full mb-4 p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
    <input type="password" name="clave" placeholder="Contraseña" class="w-full mb-4 p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
    <button type="submit" name="login" class="w-full bg-yellow-400 hover:bg-yellow-300 text-slate-900 p-3 rounded font-semibold shadow">
      Entrar
    </button>
    <p class="mt-4 text-center text-sm">¿No tenés cuenta? <a href="registro.php" class="text-yellow-600 hover:underline">Registrarse</a></p>
  </form>
</body>
</html>