<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'string'   => 'El campo :attribute debe ser texto.',
    'email'    => 'El campo :attribute debe ser un correo electrónico válido.',
    'min'      => [
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
    ],
    'max'      => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
        'file'   => 'El campo :attribute no puede ser mayor de :max kilobytes.',
    ],
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'unique'    => 'El :attribute ya está en uso.',
    'lowercase' => 'El :attribute debe estar en minúsculas.',
    'regex'     => 'El formato del campo :attribute no es válido.',
    'numeric'   => 'El campo :attribute debe ser un número.',
    'exists'    => 'El :attribute seleccionado no es válido.',
    'image'     => 'El campo :attribute debe ser una imagen.',
    'mimes'     => 'El campo :attribute debe ser un archivo de tipo: :values.',

    'attributes' => [
        'name'                  => 'nombre',
        'email'                 => 'correo electrónico',
        'password'              => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'nombre'                => 'nombre',
        'descripcion'           => 'descripción',
        'precio'                => 'precio',
        'category_id'           => 'categoría',
        'user_id'               => 'propietario',
        'images.*'              => 'imagen',
    ],
];
