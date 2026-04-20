<?php

return [
    'required'  => 'El campo :attribute es obligatorio.',
    'string'    => 'El campo :attribute debe ser texto.',
    'max'       => ['string' => 'El campo :attribute no puede tener más de :max caracteres.'],
    'min'       => ['string' => 'El campo :attribute debe tener al menos :min caracteres.'],
    'in'        => 'El valor seleccionado en :attribute no es válido.',
    'image'     => 'El campo :attribute debe ser una imagen.',
    'date'      => 'El campo :attribute no es una fecha válida.',
    'unique'    => 'El :attribute ya está en uso.',
    'email'     => 'El campo :attribute debe ser un correo válido.',
    'nullable'  => '',

    'attributes' => [
        'tipo'                  => 'tipo',
        'nombre'                => 'nombre',
        'apellido_paterno'      => 'apellido paterno',
        'apellido_materno'      => 'apellido materno',
        'fotografia'            => 'fotografía',
        'numero_item'           => 'número de ítem',
        'cite_memorandum'       => 'CITE memorandum',
        'cargo'                 => 'cargo',
        'designacion'           => 'designación',
        'fecha_ingreso_aduana'  => 'fecha de ingreso a la Aduana',
        'fecha_inicio_cargo'    => 'fecha de inicio de cargo',
        'contrato_numero'       => 'número de contrato',
        'cargo_consultoria'     => 'cargo de consultoría',
        'fecha_inicio_contrato' => 'fecha de inicio de contrato',
        'fecha_fin_contrato'    => 'fecha de fin de contrato',
        'unidad'                => 'unidad',
        'sub_unidad'            => 'sub-unidad',
    ],
];
