<?php include('db/conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - Aurora Boutique</title>
  <link href="css/tailwind.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center text-slate-800">

  <form method="POST" action="router.php" class="bg-white p-8 rounded-lg shadow-xl w-full max-w-xl space-y-6 border border-slate-200">
    <h2 class="text-3xl font-bold text-center text-slate-800 mb-2">Crear una cuenta</h2>
    <p class="text-center text-slate-500 text-sm mb-6">Completá tus datos para registrarte en Aurora Boutique</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-semibold mb-1">Primer nombre *</label>
        <input type="text" name="nombre1" required class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1">Segundo nombre</label>
        <input type="text" name="nombre2" class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1">Primer apellido *</label>
        <input type="text" name="apellido1" required class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1">Segundo apellido</label>
        <input type="text" name="apellido2" class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-semibold mb-1">Teléfono 1 *</label>
        <input type="tel" name="telefono1" required class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1">Teléfono 2 (opcional)</label>
        <input type="tel" name="telefono2" class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1">Correo 1 *</label>
        <input type="email" name="correo1" required class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1">Correo 2 (opcional)</label>
        <input type="email" name="correo2" class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
    </div>

    <hr class="my-4 border-slate-200">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-semibold mb-1">Usuario *</label>
        <input type="text" name="usuario" required class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1">Contraseña *</label>
        <input type="password" name="clave" required class="w-full p-3 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
    </div>

    <div class="pt-4">
      <button type="submit" name="registro" class="w-full bg-yellow-400 hover:bg-yellow-300 text-slate-900 font-bold p-3 rounded shadow">
        Registrarse
      </button>
    </div>
  </form>

</body>
</html>
