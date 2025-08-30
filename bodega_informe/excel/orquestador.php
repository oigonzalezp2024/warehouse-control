<?php
// Carga el autoloader de Composer para que las clases sean reconocidas.
require_once '../../vendor/autoload.php';
include_once '../url_destino.php';
include_once '../controlador/algoritmo/process.php';

use App\Infrastructure\DescargarExcelCorporativo;
use App\Application\BodegaGenerarInformeGestion;
use App\Infrastructure\Repository\BodegaGestionRepository;

function orquestador($pdo, $data_a_enviar, $url_destino, $filtros)
{
    $datos = processData($data_a_enviar, $url_destino);
    $configPath = '../config.php';
    $config = require $configPath;
    $informeGestionExcelRepository = new BodegaGestionRepository($pdo);
    $informeGestionExcelRepository->setData($filtros);
    $datos = $informeGestionExcelRepository->getData();
    /*
    echo "datos de entrada<br>";
    var_dump($filtros);
    echo "<br><br><br>datos de salida<br>";
    var_dump($datos);
    */
    $generarInformeGestion = new BodegaGenerarInformeGestion($config);
    $registros = $generarInformeGestion->run($datos);
    $excelGenerator = new DescargarExcelCorporativo($configPath, $registros);
    $excelGenerator->descargar();
}