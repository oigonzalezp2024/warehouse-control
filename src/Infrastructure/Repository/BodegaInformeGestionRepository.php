<?php

namespace App\Infrastructure\Repository;

use PDO;
use PDOException;

class BodegaInformeGestionRepository
{
    private PDO $connection;
    private array $data = [];

    /**
     * @param PDO $connection La conexión PDO a la base de datos.
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    function setData($data)
    {
        try {
            $sql = '
                SELECT
                    b.id_item,
                    cli.nombre AS proveedor,
                    ser.servicio_nombre AS servicio,
                    pro.producto_nombre AS producto,
                    b.cantidad,
                    b.fecha_creacion,
                    b.fecha_modificacion,
                    usu.nombre AS usuario
                FROM bodega b
                JOIN proveedores cli ON cli.id_proveedor = b.proveedor_id
                JOIN servicios ser ON ser.id_servicio = b.servicio_id
                JOIN productos pro ON pro.id_producto = b.producto_id
                JOIN usuarios usu ON usu.id_usuario = b.usuario_id
                ORDER BY b.id_item DESC
            ';

            $statement = $this->connection->query($sql);

            // FetchAll para obtener todos los resultados como un array de arrays asociativos.
            $this->data = $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Manejo de errores de la base de datos.
            // En un entorno de producción, es recomendable loguear el error en lugar de mostrarlo.
            error_log('Error en la consulta a la base de datos: ' . $e->getMessage());
            $this->data = []; // Aseguramos que el array esté vacío en caso de error.
        }
    }

    function getData(): array
    {
        return $this->data;
    }
}
