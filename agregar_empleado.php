<?php
session_start();
include("db/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre1    = $_POST['nombre1'];
  $nombre2    = $_POST['nombre2'] ?? null;
  $apellido1  = $_POST['apellido1'];
  $apellido2  = $_POST['apellido2'] ?? null;
  $correo1    = $_POST['correo'];
  $correo2    = $_POST['correo2'] ?? null;
  $telefono1  = $_POST['telefono'];
  $telefono2  = $_POST['telefono2'] ?? null;
  $rol        = $_POST['rol'];
  $usuario    = $_POST['usuario'];
  $clave      = $_POST['clave'];

  try {
    // Iniciar transacción
    $conn->beginTransaction();

    // Insertar correos
    $stmt = $conn->prepare("INSERT INTO modelo.correo_empleado (correo) VALUES (:c) RETURNING id_correoempleado");
    $stmt->execute([':c' => $correo1]);
    $id_c1 = $stmt->fetchColumn();

    if ($correo2) {
      $stmt->execute([':c' => $correo2]);
      // Aunque no se use, queda registrado
      $stmt->fetchColumn();
    }

    // Insertar teléfonos
    $stmt = $conn->prepare("INSERT INTO modelo.telefono_empleado (telefono) VALUES (:t) RETURNING id_telefonoempleado");
    $stmt->execute([':t' => $telefono1]);
    $id_t1 = $stmt->fetchColumn();

    if ($telefono2) {
      $stmt->execute([':t' => $telefono2]);
      // Aunque no se use, queda registrado
      $stmt->fetchColumn();
    }

    // Insertar empleado
    $stmt = $conn->prepare("
      INSERT INTO modelo.empleado (
        nombre1, nombre2, apellido1, apellido2,
        id_correoempleado, id_telefonoempleado, id_rol
      ) VALUES (
        :n1, :n2, :a1, :a2,
        :c1, :t1, :rol
      ) RETURNING id_empleado
    ");
    $stmt->execute([
      ':n1' => $nombre1,
      ':n2' => $nombre2,
      ':a1' => $apellido1,
      ':a2' => $apellido2,
      ':c1' => $id_c1,
      ':t1' => $id_t1,
      ':rol' => $rol
    ]);
    $id_empleado = $stmt->fetchColumn();

    // Insertar usuario
    $stmt = $conn->prepare("
      INSERT INTO modelo.usuario (nombreusuario, contrasena, id_empleado)
      VALUES (:usuario, :clave, :id_empleado)
    ");
    $stmt->execute([
      ':usuario' => $usuario,
      ':clave' => $clave,
      ':id_empleado' => $id_empleado
    ]);

    // Confirmar transacción
    $conn->commit();

    header("Location: admin.php?mensaje=empleado_ok");
    exit;

  } catch (PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo "<pre style='background:#fee;color:#b00;padding:1rem'>";
    echo "❌ Error SQL al agregar empleado:\n\n" . $e->getMessage();
    echo "</pre>";
    exit;
  }
}
?>
