<?php

namespace App\Helpers;

class StringHelper
{
    // Función para convertir un array de campos a mayúsculas
    public static function convertToUpperCase(array $data, array $fields)
    {
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data[$field] = strtoupper($data[$field]);
            }
        }

        return $data;
    }

    // Función para convertir descripciones de los items a mayúsculas
    public static function convertItemsToUpperCase(array $items)
    {
        foreach ($items as &$item) {
            if (isset($item['descripcion'])) {
                $item['descripcion'] = strtoupper($item['descripcion']);
            }
        }

        return $items;
    }
}
