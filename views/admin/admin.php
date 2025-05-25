<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrador - Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen p-8">
  <h1 class="text-4xl font-bold mb-8 text-slate-800">Panel del Administrador</h1>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-xl transition border border-slate-200 hover:border-yellow-400">
      <h2 class="text-xl font-semibold text-slate-800 mb-2">➕ Agregar Producto</h2>
      <p class="text-slate-500 text-sm">Registrar nuevo producto en el catálogo.</p>
    </a>
    <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-xl transition border border-slate-200 hover:border-yellow-400">
      <h2 class="text-xl font-semibold text-slate-800 mb-2">➕ Agregar Empleado</h2>
      <p class="text-slate-500 text-sm">Crear cuenta para personal nuevo.</p>
    </a>
    <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-xl transition border border-slate-200 hover:border-yellow-400">
      <h2 class="text-xl font-semibold text-slate-800 mb-2">📦 Verificar Pedidos</h2>
      <p class="text-slate-500 text-sm">Aprobar, cancelar o revisar pedidos realizados.</p>
    </a>
  </div>
</body>
</html>