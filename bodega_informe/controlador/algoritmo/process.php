<?php

function processData($data_a_enviar, $url_destino)
{
    $datos_json = json_encode($data_a_enviar);

    // Validar que la conversión a JSON fue exitosa
    if ($datos_json === false) {
        die("Error al codificar los datos a JSON.");
    }

    // Configurar la petición cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_destino);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la petición y obtener la respuesta
    $respuesta = curl_exec($ch);

    // Manejar posibles errores de cURL
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        die("Error de cURL: " . $error_msg);
    }

    // Cerrar la sesión cURL
    curl_close($ch);

    $datos = json_decode($respuesta, true);
    return $datos;
}


