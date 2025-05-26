<?php
session_start();
include("db/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre1 = $_POST['nombre1'];
  $nombre2 = $_POST['nombre2'] ?? null;
  $apellido1 = $_POST['apellido1'];
  $apellido2 = $_POST['apellido2'] ?? null;
  $correo = $_POST['correo'];
  $telefono = $_POST['telefono'];
  $rol = $_POST['rol'];
  $usuario = $_POST['usuario'];
  $clave = $_POST['clave'];

  try {
    // Insertar correo
    $stmt = $conn->prepare("INSERT INTO modelo.correo_empleado (correo) VALUES (:correo) RETURNING id_correoempleado");
    $stmt->execute([':correo' => $correo]);
    $id_correoe = $stmt->fetchColumn();

    // Insertar teléfono
    $stmt = $conn->prepare("INSERT INTO modelo.telefono_empleado (telefono) VALUES (:telefono) RETURNING id_telefonoempleado");
    $stmt->execute([':telefono' => $telefono]);
    $id_telefonoe = $stmt->fetchColumn();

    // Insertar empleado
    $stmt = $conn->prepare("
      INSERT INTO modelo.empleado 
        (nombre1, nombre2, apellido1, apellido2, id_correoempleado, id_telefonoempleado, id_rol)
      VALUES 
        (:n1, :n2, :a1, :a2, :correo, :telefono, :rol)
      RETURNING id_empleado
    ");
    $stmt->execute([
      ':n1' => $nombre1,
      ':n2' => $nombre2,
      ':a1' => $apellido1,
      ':a2' => $apellido2,
      ':correo' => $id_correoe,
      ':telefono' => $id_telefonoe,
      ':rol' => $rol
    ]);
    $id_empleado = $stmt->fetchColumn();

    // Insertar usuario asociado
    $stmt = $conn->prepare("
      INSERT INTO modelo.usuario (nombreusuario, contrasena, id_empleado)
      VALUES (:usuario, :clave, :id_empleado)
    ");
    $stmt->execute([
      ':usuario' => $usuario,
      ':clave' => $clave,
      ':id_empleado' => $id_empleado
    ]);

    header("Location: admin.php?mensaje=empleado_ok");
    exit;

  } catch (PDOException $e) {
    die("Error al agregar empleado: " . $e->getMessage());
  }
}
?>

