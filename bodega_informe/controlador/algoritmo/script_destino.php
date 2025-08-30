<?php
header('Content-Type: application/json');

// Inicializa una respuesta en formato de array
$response = [];

// Obtener el contenido crudo (raw) del cuerpo de la petición HTTP
$json_data = file_get_contents('php://input');

// Decodificar la cadena JSON a un array de PHP
$data_recibida = json_decode($json_data, true);

// Verificar si se recibió y decodificó la data correctamente
if ($data_recibida !== null) {
    // Puedes procesar la data aquí (guardar en base de datos, etc.)
    
    // Preparar la respuesta de éxito
    $response['status'] = 'success';
    $response['message'] = '¡Data recibida y procesada con éxito!';
    $response['data_processed'] = $data_recibida; // Opcionalmente, devuelve los datos procesados
} else {
    // Si la decodificación falló, el JSON estaba mal formado o no se envió nada
    $response['status'] = 'error';
    $response['message'] = 'Error: No se recibió ninguna data o el formato JSON es incorrecto.';
}

// Devuelve la respuesta codificada en JSON
echo json_encode($response);
