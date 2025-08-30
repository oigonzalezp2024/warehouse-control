<?php
namespace App\Infrastructure;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Clase para generar y descargar informes de Excel de manera corporativa.
 *
 * Utiliza la biblioteca PhpSpreadsheet para crear un archivo XLSX con un
 * formato predefinido, incluyendo encabezado corporativo, tabla de datos
 * dinámica y estilos personalizados, basados en un archivo de configuración.
 */
class DescargarExcelCorporativo
{
    /** @var array La configuración del informe, cargada desde un archivo. */
    private array $config;

    /** @var Spreadsheet El objeto principal de la hoja de cálculo. */
    private Spreadsheet $spreadsheet;

    /** @var int La fila inicial de la tabla de datos. */
    private int $dataStartRow = 5;

    /**
     * Constructor de la clase.
     *
     * @param string $configPath La ruta al archivo de configuración.
     * @param array $registros Los datos a exportar a Excel.
     */
    public function __construct(string $configPath, array $registros)
    {
        $this->config = require $configPath;
        $this->spreadsheet = new Spreadsheet();
        $this->generarHojaCalculo($registros);
    }

    /**
     * Genera la hoja de cálculo completa con todos los componentes.
     */
    private function generarHojaCalculo(array $registros): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle($this->config['empresa']['hoja_estilo']['nombre_hoja']);

        $this->anadirEncabezadoCorporativo($sheet);
        $this->generarTablaDeDatos($sheet, $registros);
        $this->aplicarEstilosTabla($sheet);
        $this->ajustarAnchoColumnas($sheet);
        $this->anadirPieDePagina($sheet);
    }

    /**
     * Añade el encabezado corporativo al documento (centrado con merge).
     */
    private function anadirEncabezadoCorporativo($sheet): void
    {
        // Calcular última columna según la cantidad de campos configurados
        $totalCampos = count($this->config['campos_datos_tabla']);
        $ultimaColumna = Coordinate::stringFromColumnIndex($totalCampos);

        // Logo
        $logoPath = $this->config['empresa']['logo_path'];
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo Corporativo');
            $drawing->setPath($logoPath);
            $drawing->setHeight($this->config['dimensiones']['logo_alto']);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }

        // Título
        $sheet->setCellValue('A1', $this->config['empresa']['nombre']);
        $sheet->mergeCells("A1:{$ultimaColumna}1");
        $sheet->getStyle("A1:{$ultimaColumna}1")->applyFromArray($this->config['estilos']['titulo']);
        $sheet->getStyle("A1:{$ultimaColumna}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Subtítulo
        $sheet->setCellValue('A2', $this->config['empresa']['subtitulo_informe']);
        $sheet->mergeCells("A2:{$ultimaColumna}2");
        $sheet->getStyle("A2:{$ultimaColumna}2")->applyFromArray($this->config['estilos']['subtitulo']);
        $sheet->getStyle("A2:{$ultimaColumna}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Alturas de filas
        $sheet->getRowDimension(1)->setRowHeight($this->config['dimensiones']['fila_titulo_alto']);
        $sheet->getRowDimension(2)->setRowHeight($this->config['dimensiones']['fila_subtitulo_alto']);
    }

    /**
     * Genera la tabla de datos a partir de los registros.
     */
    private function generarTablaDeDatos($sheet, array $registros): void
    {
        $header = array_column($this->config['campos_datos_tabla'], 'header_text');
        $sheet->fromArray($header, null, 'A' . $this->dataStartRow);

        $dataForExcel = [];
        foreach ($registros as $registro) {
            $rowData = [];
            foreach ($this->config['campos_datos_tabla'] as $campo) {
                $rowData[] = $registro[$campo['data_key']];
            }
            $dataForExcel[] = $rowData;
        }

        $sheet->fromArray($dataForExcel, null, 'A' . ($this->dataStartRow + 1));
    }

    /**
     * Aplica estilos de formato a la tabla de datos.
     */
    private function aplicarEstilosTabla($sheet): void
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();
        $headerRange = 'A' . $this->dataStartRow . ':' . $highestColumn . $this->dataStartRow;
        $dataRange = 'A' . $this->dataStartRow . ':' . $highestColumn . $highestRow;

        // Encabezado
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => $this->config['estilos']['header_font'],
            'fill' => $this->config['estilos']['header_fill'],
            'borders' => $this->config['estilos']['table_borders'],
        ]);

        // Filas alternas
        for ($row = $this->dataStartRow + 1; $row <= $highestRow; $row++) {
            if ($row % 2 !== 0) {
                $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
                    'fill' => $this->config['estilos']['data_row_fill'],
                ]);
            }
        }

        // Bordes + alineación
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => $this->config['estilos']['table_borders'],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    /**
     * Configura el ancho de las columnas.
     */
    private function ajustarAnchoColumnas($sheet): void
    {
        $highestColumn = $sheet->getHighestColumn();
        if ($this->config['dimensiones']['auto_ajuste']) {
            foreach (range('A', $highestColumn) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        } else {
            foreach (range('A', $highestColumn) as $columnID) {
                $ancho = $this->config['dimensiones']['ancho_columnas']['fijos'][$columnID]
                    ?? $this->config['dimensiones']['ancho_columnas']['por_defecto'];
                $sheet->getColumnDimension($columnID)->setWidth($ancho);
            }
        }
    }

    /**
     * Añade el pie de página.
     */
    private function anadirPieDePagina($sheet): void
    {
        $sheet->getHeaderFooter()->setOddFooter($this->config['pie_de_pagina']['texto']);
    }

    /**
     * Descarga el archivo de Excel.
     */
    public function descargar(): void
    {
        $fileNameBase = $this->config['empresa']['nombre_archivo'] ?? 'informe_excel';
        $fileName = $fileNameBase . '-' . date('Y-m-d') . '.xlsx';

        header('Content-Type: Application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        if (ob_get_length() > 0) {
            ob_clean();
        }

        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
