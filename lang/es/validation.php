<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'string'   => 'El campo :attribute debe ser texto.',
    'email'    => 'El campo :attribute debe ser un correo electrónico válido.',
    'min'      => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'max'      => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'unique'    => 'El :attribute ya está en uso.',
    'lowercase' => 'El :attribute debe estar en minúsculas.',
    'regex'     => 'El formato del campo :attribute no es válido.',

    'attributes' => [
        'name'                  => 'nombre',
        'email'                 => 'correo electrónico',
        'password'              => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
    ],
];
