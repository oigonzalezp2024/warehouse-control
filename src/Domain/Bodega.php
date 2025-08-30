<?php

namespace App\Domain;

class Bodega
{
    private int $id_item;
    private string $proveedor;
    private string $servicio;
    private string $producto;
    private string $cantidad;
    private string $fecha_creacion;
    private string | null $fecha_modificacion;
    private string | null $usuario;

    public function __construct(
        int $id_item,
        string $proveedor,
        string $servicio,
        string $producto,
        string $cantidad,
        string $fecha_creacion,
        string | null $fecha_modificacion,
        string $usuario
    ) {
        $this->id_item = $id_item;
        $this->proveedor = $proveedor;
        $this->servicio = $servicio;
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->fecha_creacion = $fecha_creacion;
        $this->fecha_modificacion = $fecha_modificacion;
        $this->usuario = $usuario;
    }

    // Getters
    public function getIdItem(): int
    {
        return $this->id_item;
    }

    public function getProveedor(): string
    {
        return $this->proveedor;
    }

    public function getServicio(): string
    {
        return $this->servicio;
    }

    public function getProducto(): string
    {
        return $this->producto;
    }

    public function getCantidad(): string
    {
        return $this->cantidad;
    }

    public function getFechaCreacion(): string
    {
        return $this->fecha_creacion;
    }

    public function getFechaModificacion(): string | null
    {
        return $this->fecha_modificacion;
    }

    public function getUsuario(): string
    {
        return $this->usuario;
    }
}
