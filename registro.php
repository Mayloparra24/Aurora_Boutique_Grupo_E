<?php include('db/conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link href="src/tailwind.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
  <form method="POST" action="router.php" class="bg-white p-6 rounded shadow-md w-96">
    <h2 class="text-xl font-bold mb-4">Crear cuenta</h2>
    <input type="text" name="nombre1" placeholder="Primer nombre" class="w-full mb-3 p-2 border rounded" required>
    <input type="text" name="apellido1" placeholder="Primer apellido" class="w-full mb-3 p-2 border rounded" required>
    <input type="text" name="usuario" placeholder="Nombre de usuario" class="w-full mb-3 p-2 border rounded" required>
    <input type="password" name="clave" placeholder="Contraseña" class="w-full mb-3 p-2 border rounded" required>
    <button type="submit" name="registro" class="w-full bg-green-600 text-white p-2 rounded">Registrarse</button>
  </form>
</body>
</html>