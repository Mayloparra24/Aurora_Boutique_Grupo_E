<?php
include("db/conexion.php");

$tipo = $_GET['tipo'] ?? '';
$padre = $_GET['padre'] ?? '';

$mapa = [
    'provincia' => ['tabla' => 'modelo.catalogo_provincia', 'col' => 'id_pais', 'id' => 'id_provincia'],
    'canton'    => ['tabla' => 'modelo.catalogo_canton', 'col' => 'id_provincia', 'id' => 'id_canton'],
    'distrito'  => ['tabla' => 'modelo.catalogo_distrito', 'col' => 'id_canton', 'id' => 'id_distrito'],
    'barrio'    => ['tabla' => 'modelo.catalogo_barrio', 'col' => 'id_distrito', 'id' => 'id_barrio'],
];

if (!isset($mapa[$tipo])) {
    http_response_code(400);
    echo json_encode([]);
    exit;
}

$tabla = $mapa[$tipo]['tabla'];
$col = $mapa[$tipo]['col'];
$idcol = $mapa[$tipo]['id'];

$stmt = $conn->prepare("SELECT $idcol AS id, nombre FROM $tabla WHERE $col = :padre ORDER BY nombre");
$stmt->execute([':padre' => $padre]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
