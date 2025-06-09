<?php

namespace App\Constants;

class DocumentTypes
{
    const FACTURA = 1;
    const BOLETA = 2;
    const NOTA_CREDITO = 3;
    const NOTA_DEBITO = 4;
    const GUIA_REMISION = 5;
    
    /**
     * Obtener todos los tipos de documento
     */
    public static function all()
    {
        return [
            self::FACTURA => 'Factura',
            self::BOLETA => 'Boleta',
            self::NOTA_CREDITO => 'Nota de Crédito',
            self::NOTA_DEBITO => 'Nota de Débito',
            self::GUIA_REMISION => 'Guía de Remisión',
        ];
    }
    
    /**
     * Obtener el nombre de un tipo de documento
     */
    public static function getName($id)
    {
        $types = self::all();
        return $types[$id] ?? 'Desconocido';
    }
    
    /**
     * Obtener el código/prefijo para la serie según el tipo
     */
    public static function getSeriePrefix($id)
    {
        $prefixes = [
            self::FACTURA => 'F',
            self::BOLETA => 'B',
            self::NOTA_CREDITO => 'FC',
            self::NOTA_DEBITO => 'FD',
            self::GUIA_REMISION => 'T',
        ];
        
        return $prefixes[$id] ?? '';
    }
    
    /**
     * Obtener placeholder para la serie
     */
    public static function getSeriePlaceholder($id)
    {
        $placeholders = [
            self::FACTURA => 'F001',
            self::BOLETA => 'B001',
            self::NOTA_CREDITO => 'FC01',
            self::NOTA_DEBITO => 'FD01',
            self::GUIA_REMISION => 'T001',
        ];
        
        return $placeholders[$id] ?? 'Serie';
    }
    
    /**
     * Obtener los IDs válidos para validación
     */
    public static function getValidIds()
    {
        return array_keys(self::all());
    }
    
    /**
     * Obtener la carpeta de almacenamiento según el tipo
     */
    public static function getStorageFolder($id)
    {
        $folders = [
            self::FACTURA => 'facturas',
            self::BOLETA => 'boletas',
            self::NOTA_CREDITO => 'notas_credito',
            self::NOTA_DEBITO => 'notas_debito',
            self::GUIA_REMISION => 'guias_remision',
        ];
        
        return $folders[$id] ?? 'otros';
    }
}