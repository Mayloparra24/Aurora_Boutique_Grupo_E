<?php
session_start();
include("db/conexion.php");

// LOGIN
if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    try {
        $stmt = $conn->prepare("SELECT * FROM modelo.validar_login(:usuario, :clave)");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':clave', $clave);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && $resultado['id_usuario'] && $resultado['tipo']) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['id_usuario'] = $resultado['id_usuario'];
            $_SESSION['tipo'] = $resultado['tipo'];

            switch ($resultado['tipo']) {
    case 'cliente':
        header("Location: index.php");
        exit;
    case 'admin':
        header("Location: admin.php");
        exit;
    case 'envios':
        header("Location: envios.php");
        exit;
    default:
        echo "Tipo de usuario desconocido.";
        session_destroy();
        exit;
}
        } else {
            echo "<p style='color:red;'>❌ Usuario o contraseña incorrectos.</p>";
        }

    } catch (PDOException $e) {
        echo "Error en login: " . $e->getMessage();
    }
}

// REGISTRO
if (isset($_POST['registro'])) {
    $usuario     = $_POST['usuario'];
    $clave       = $_POST['clave'];
    $nombre1     = $_POST['nombre1'];
    $nombre2     = $_POST['nombre2'] ?? null;
    $apellido1   = $_POST['apellido1'];
    $apellido2   = $_POST['apellido2'] ?? null;
    $telefono1   = $_POST['telefono1'];
    $telefono2   = $_POST['telefono2'] ?? null;
    $correo1     = $_POST['correo1'];
    $correo2     = $_POST['correo2'] ?? null;

    try {
        $stmt = $conn->prepare("CALL modelo.registrar_cliente_usuario(
            :usuario, :clave, :nombre1, :nombre2, :apellido1, :apellido2,
            :telefono1, :telefono2, :correo1, :correo2
        )");

        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':clave', $clave);
        $stmt->bindParam(':nombre1', $nombre1);
        $stmt->bindParam(':nombre2', $nombre2);
        $stmt->bindParam(':apellido1', $apellido1);
        $stmt->bindParam(':apellido2', $apellido2);
        $stmt->bindParam(':telefono1', $telefono1);
        $stmt->bindParam(':telefono2', $telefono2);
        $stmt->bindParam(':correo1', $correo1);
        $stmt->bindParam(':correo2', $correo2);

        $stmt->execute();

        echo "<p style='color:green;'>✅ Registro exitoso. Ya podés iniciar sesión.</p>";
        header("Refresh:2; url=login.php");

    } catch (PDOException $e) {
        echo "Error al registrar: " . $e->getMessage();
    }
}
?>
