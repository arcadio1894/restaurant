<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de SUNAT
    |--------------------------------------------------------------------------
    |
    | Aquí se definen los parámetros necesarios para la conexión con SUNAT.
    |
    */

    'ruc' => env('SUNAT_RUC', '20123456789'),  // Reemplaza con el RUC de la empresa
    'razon_social' => env('SUNAT_RAZON_SOCIAL', 'Mi Empresa S.A.C.'),
    'nombre_comercial' => env('SUNAT_NOMBRE_COMERCIAL', 'Mi Tienda de Pizzas'),

    'usuario_sol' => env('SUNAT_USERNAME', 'MODDATOS'),
    'clave_sol' => env('SUNAT_PASSWORD', 'MODDATOS'),

    'certificado' => storage_path(env('SUNAT_CERT_PATH', 'certs/certificado.pfx')),
    'cert_password' => env('SUNAT_CERT_PASSWORD', ''), // Agregamos la contraseña del certificado

    /*
    |--------------------------------------------------------------------------
    | Entorno de SUNAT
    |--------------------------------------------------------------------------
    |
    | Define si el sistema se conecta al entorno de producción o beta.
    | Valores posibles: "production" o "beta".
    |
    */

    'env' => env('SUNAT_ENV', 'beta'),
];
