<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Envíos - Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen p-8">
  <h1 class="text-4xl font-bold mb-6 text-slate-800">Panel del Personal de Envío</h1>

  <div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow border border-slate-200 hover:border-yellow-400 transition">
      <h2 class="text-xl font-semibold text-slate-800 mb-2">Pedido #123</h2>
      <p class="text-slate-600">Cliente: Juan Pérez</p>
      <p class="text-slate-600 mb-4">Estado actual: <strong class="text-yellow-600">Pendiente</strong></p>
      <button class="bg-yellow-400 hover:bg-yellow-300 text-slate-900 px-4 py-2 rounded font-semibold shadow">
        Actualizar Estado
      </button>
    </div>

    <!-- Repetir para más pedidos -->
  </div>
</body>
</html>