<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
  <form method="POST" action="router.php" class="bg-white p-6 rounded shadow-md w-96">
    <h2 class="text-xl font-bold mb-4">Iniciar sesión</h2>
    <input type="text" name="usuario" placeholder="Usuario" class="w-full mb-3 p-2 border rounded" required>
    <input type="password" name="clave" placeholder="Contraseña" class="w-full mb-3 p-2 border rounded" required>
    <button type="submit" name="login" class="w-full bg-blue-500 text-white p-2 rounded">Entrar</button>
    <p class="mt-4 text-center">¿No tenés cuenta? <a href="registro.php" class="text-blue-600">Registrarse</a></p>
  </form>
</body>
</html>