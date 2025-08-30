<?php

namespace App\Application;

use App\Infrastructure\Repository\BodegaInformeGestionRepository;
use App\Domain\Bodega;

/**
 * Clase que simula la lógica de negocio para generar un informe de gestión
 * de bodega, mapeando datos de origen a una estructura de objetos.
 */
class BodegaGenerarInformeGestion
{
    /**
     * @var string La clave del campo a usar como identificador único.
     */
    private $id_key;

    /**
     * Constructor de la clase.
     *
     * @param array $config La configuración de la aplicación, que debe ser un array.
     */
    public function __construct(array $config)
    {
        // Esta línea es la que causaba el error si $config no era un array.
        // Se accede correctamente al valor del array de configuración.
        $this->id_key = $config['campos_datos_tabla'][0]['data_key'];
    }

    /**
     * Ejecuta el proceso de generación de registros a partir de datos de simulación.
     *
     * @param array $datosSimulacion Un array de arrays, donde cada uno es un registro de datos.
     * @return array Un array de registros procesados.
     */
    function run(array $datosSimulacion)
    {
        $registros = [];

        foreach ($datosSimulacion as $datos) {
            // Se usa la clave obtenida del constructor para acceder dinámicamente al ID.
            $id_item = (int) $datos[$this->id_key];
            
            $informeGestion = new Bodega(
                $id_item,
                $datos['proveedor'],
                $datos['servicio'],
                $datos['producto'],
                $datos['cantidad'],
                $datos['fecha_creacion'],
                $datos['fecha_modificacion'],
                $datos['usuario']
            );
            
            $registro = [
                $this->id_key => $informeGestion->getIdItem(),
                'proveedor' => $informeGestion->getProveedor(),
                'servicio' => $informeGestion->getServicio(),
                'producto' => $informeGestion->getProducto(),
                'cantidad' => $informeGestion->getCantidad(),
                'fecha_creacion' => $informeGestion->getFechaCreacion(),
                'fecha_modificacion' => $informeGestion->getFechaModificacion(),
                'usuario' => $informeGestion->getUsuario()
            ];
            
            array_push($registros, $registro);
        }
        
        return $registros;
    }
}
