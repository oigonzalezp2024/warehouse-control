<?php
// config.php
// Este archivo centraliza la configuración para la generación de informes de Excel.
// La lógica principal ahora es flexible y se basa en este mapeo de datos.

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

return [
    // Sección para la información de la empresa en el encabezado.
    'empresa' => [
        'nombre' => 'BODEGA',
        'nombre_archivo' => 'gestion-bodega',
        'subtitulo_informe' => 'Informe de Gestión de Bodega',
        'logo_path' => './recursos/logos/logo_corporativo.png',
        'hoja_estilo' => [
            'nombre_hoja' => 'Informe de Gestión',
        ]
    ],
    // Sección para configurar las dimensiones de filas y columnas.
    'dimensiones' => [
        'logo_alto' => 50,
        'fila_titulo_alto' => 40,
        'fila_subtitulo_alto' => 20,
        'auto_ajuste' => true,
        'ancho_columnas' => [
            'fijos' => [
                'A' => 20,
                'E' => 15,
                'F' => 15,
                'H' => 10,
            ],
            'por_defecto' => 20,
        ],
    ],
    // Sección para definir los estilos de fuente, relleno y bordes.
    'estilos' => [
        'titulo' => [
            'font' => ['bold' => true, 'size' => 20, 'color' => ['argb' => 'FF2A3644']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ],
        'subtitulo' => [
            'font' => ['bold' => false, 'size' => 14, 'color' => ['argb' => 'FF546A76']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ],
        'header_font' => [
            'bold' => true,
            'color' => ['argb' => '000000'],
            'size' => 11,
        ],
        'header_fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => '7aef8d'],
        ],
        'data_row_fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'cfffd3'],
        ],
        'table_borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFBDBDBD'],
            ],
        ],
    ],
    // ** La clave para la flexibilidad **
    // 'campos_datos_tabla' define el mapeo entre los encabezados de las
    // columnas del Excel y las claves del array de datos ($registro).
    'campos_datos_tabla' => [
        [
            'header_text' => 'id_item',
            'data_key' => 'id_item',
        ],
        [
            'header_text' => 'proveedor',
            'data_key' => 'proveedor'
        ],
        [
            'header_text' => 'servicio',
            'data_key' => 'servicio'
        ],
        [
            'header_text' => 'producto',
            'data_key' => 'producto'
        ],
        [
            'header_text' => 'cantidad',
            'data_key' => 'cantidad'
        ],
        [
            'header_text' => 'fecha_creacion',
            'data_key' => 'fecha_creacion'
        ],
        [
            'header_text' => 'fecha_modificacion',
            'data_key' => 'fecha_modificacion'
        ],
        [
            'header_text' => 'usuario',
            'data_key' => 'usuario'
        ],
    ],
    // Configuración para el pie de página.
    'pie_de_pagina' => [
        'texto' => '&L&"Arial"&9Informe Generado el &D&"Arial"&9 &R&"Arial"&9Página &P de &N',
    ],
    'data' => [
        [
            'id_item' => 1,
            'proveedor' => 'Oscar Gonzalezzzzz',
            'servicio' => 'lavado',
            'producto' => 'Color',
            'cantidad' => '1000',
            'fecha_creacion' => '2020-05-05',
            'fecha_modificacion' => '2020-05-06',
            'usuario' => 'Oscar Gonzalez'
        ],
    ]
];
