<?php session_start(); ?>
<div id="modal-carrito"
     class="fixed top-20 right-8 z-[9999] w-80 bg-white shadow-2xl p-4 rounded border border-slate-200">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-bold text-slate-800">Tu Carrito</h2>
    <button onclick="cerrarCarrito()" class="text-slate-500 text-xl">✖</button>
  </div>
  <div id="lista-carrito" class="mb-4 max-h-60 overflow-y-auto">
    <!-- Productos desde JS -->
  </div>
  <div class="text-right font-bold text-lg text-slate-700 mb-4">
    Total: <span id="total-general">₡0.00</span>
  </div>
  <div class="flex justify-between">
    <button onclick="vaciarCarrito()" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded">
      Vaciar
    </button>
    <button onclick="window.location.href='finalizar_pedido.php'"
            class="bg-green-500 hover:bg-green-400 text-white px-4 py-2 rounded">
      Realizar Pedido
    </button>
  </div>
</div>
