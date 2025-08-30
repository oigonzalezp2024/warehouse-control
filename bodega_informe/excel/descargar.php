<?php
require_once './conn.php';
require_once './orquestador.php';

$proveedor = $_GET['proveedor'];
$servicio = $_GET['servicio'];
$producto = $_GET['producto'];
$cantidad = $_GET['cantidad'];
$fecha_inicio_cre = $_GET['fecha_inicio_cre'];
$fecha_fin_cre = $_GET['fecha_fin_cre'];
$fecha_inicio_mod = $_GET['fecha_inicio_mod'];
$fecha_fin_mod = $_GET['fecha_fin_mod'];

$filtros = [
        'proveedor' => $proveedor,
        'servicio' => $servicio,
        'producto' => $producto,
        'cantidad' => $cantidad,
        'fecha_inicio_cre' => $fecha_inicio_cre,
        'fecha_fin_cre' => $fecha_fin_cre,
        'fecha_inicio_mod' => $fecha_inicio_mod,
        'fecha_fin_mod' => $fecha_fin_mod
];

$data_a_enviar = [];

$pdo = conn();
orquestador($pdo, $data_a_enviar, $url_destino, $filtros);