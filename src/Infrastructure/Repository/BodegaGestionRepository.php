<?php

namespace App\Infrastructure\Repository;

use PDO;
use PDOException;
use DateTime;

class BodegaGestionRepository
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
 public function setData(array $filters)
    {
        try {
            $sql = 'SELECT
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
                    WHERE 1=1'; // El "1=1" permite agregar condiciones "AND" sin verificar si es el primer filtro.

            $params = [];

            // Construir la consulta de forma dinámica
            if (isset($filters['proveedor']) && $filters['proveedor'] !== '') {
                $sql .= ' AND cli.nombre LIKE :proveedor';
                $params[':proveedor'] = '%' . $filters['proveedor'] . '%';
            }
            if (isset($filters['servicio']) && $filters['servicio'] !== '') {
                $sql .= ' AND ser.servicio_nombre LIKE :servicio';
                $params[':servicio'] = '%' . $filters['servicio'] . '%';
            }
            if (isset($filters['producto']) && $filters['producto'] !== '') {
                $sql .= ' AND pro.producto_nombre LIKE :producto';
                $params[':producto'] = '%' . $filters['producto'] . '%';
            }
            if (isset($filters['cantidad']) && $filters['cantidad'] !== '') {
                $sql .= ' AND b.cantidad LIKE :cantidad';
                $params[':cantidad'] = '%' . $filters['cantidad'] . '%';
            }

            // Filtro por rango de fecha de creación (opcional)
            if (isset($filters['fecha_inicio_cre']) && $filters['fecha_inicio_cre'] !== '' && isset($filters['fecha_fin_cre']) && $filters['fecha_fin_cre'] !== '') {
                $sql .= ' AND b.fecha_creacion BETWEEN :fecha_inicio_cre AND :fecha_fin_cre';
                $params[':fecha_inicio_cre'] = $filters['fecha_inicio_cre'];
                $params[':fecha_fin_cre'] = (new DateTime($filters['fecha_fin_cre']))->modify('+1 day')->format('Y-m-d');
            }

            // Filtro por rango de fecha de modificación (opcional)
            if (isset($filters['fecha_inicio_mod']) && $filters['fecha_inicio_mod'] !== '' && isset($filters['fecha_fin_mod']) && $filters['fecha_fin_mod'] !== '') {
                $sql .= ' AND b.fecha_modificacion BETWEEN :fecha_inicio_mod AND :fecha_fin_mod';
                $params[':fecha_inicio_mod'] = $filters['fecha_inicio_mod'];
                $params[':fecha_fin_mod'] = (new DateTime($filters['fecha_fin_mod']))->modify('+1 day')->format('Y-m-d');
            }else if (isset($filters['fecha_inicio_mod']) && $filters['fecha_inicio_mod'] !== '') {
                $sql .= ' AND b.fecha_modificacion BETWEEN :fecha_inicio_mod AND :fecha_fin_mod';
                $params[':fecha_inicio_mod'] = $filters['fecha_inicio_mod'];
                $params[':fecha_fin_mod'] = (new DateTime($filters['fecha_inicio_mod']))->modify('+1 day')->format('Y-m-d');
            } else {
                // Si no se proporciona el filtro, incluimos los registros con fecha nula.
                $sql .= ' AND (b.fecha_modificacion IS NULL OR b.fecha_modificacion IS NOT NULL)';
            }

            $sql .= ' ORDER BY b.id_item DESC';
            
            $statement = $this->connection->prepare($sql);

            // Vincular parámetros de forma dinámica
            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value, PDO::PARAM_STR);
            }
            
            $statement->execute();
            $this->data = $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Error en la consulta a la base de datos: ' . $e->getMessage());
            $this->data = [];
        }
    }


    function getData(): array
    {
        return $this->data;
    }
}
