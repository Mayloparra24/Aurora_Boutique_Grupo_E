<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Envíos</title>
  <link href="../../src/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-3xl font-bold mb-4">Panel de Personal de Envío</h1>
  <div class="space-y-4">
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-xl font-semibold mb-2">Pedido #123</h2>
      <p>Cliente: Juan Pérez</p>
      <p>Estado actual: <strong>Pendiente</strong></p>
      <button class="mt-2 bg-blue-500 text-white px-4 py-1 rounded">Actualizar Estado</button>
    </div>
  </div>
</body>
</html>