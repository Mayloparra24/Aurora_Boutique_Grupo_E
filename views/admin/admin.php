<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Admin</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-3xl font-bold mb-4">Panel de Administrador</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="#" class="p-4 bg-white rounded shadow hover:bg-blue-100">➕ Agregar Producto</a>
    <a href="#" class="p-4 bg-white rounded shadow hover:bg-blue-100">➕ Agregar Empleado</a>
    <a href="#" class="p-4 bg-white rounded shadow hover:bg-blue-100">📦 Verificar Pedidos</a>
  </div>
</body>
</html>