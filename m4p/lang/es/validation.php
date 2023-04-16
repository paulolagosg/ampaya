<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'active_url' => 'El campo :attribute no es una URL válida.',
    'after' => 'El campo :attribute debe ser una fecha después de :date.',
    'after_or_equal' => 'El campo :attribute debe ser una fecha después o igual a :date.',
    'alpha' => 'El campo :attribute sólo puede contener letras.',
    'alpha_dash' => 'El campo :attribute sólo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El campo :attribute sólo puede contener letras y números.',
    'array' => 'El campo :attribute debe ser un arreglo.',
    'before' => 'El campo :attribute debe ser una fecha antes de :date.',
    'before_or_equal' => 'El campo :attribute debe ser una fecha antes o igual a :date.',
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'file'    => 'El campo :attribute debe estar entre :min y :max kilobytes.',
        'string'  => 'El campo :attribute debe estar entre :min y :max caracteres.',
        'array'   => 'El campo :attribute debe tener entre :min y :max elementos.',
    ],
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación del campo :attribute no coincide.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El campo :attribute no corresponde con el formato :format.',
    'different' => 'Los campos :attribute y :other deben ser diferentes.',
    'digits' => 'El campo :attribute debe ser de :digits dígitos.',
    'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions' => 'El campo :attribute tiene dimensiones de imagen no válidas.',
    'distinct' => 'El campo :attribute tiene un valor duplicado.',
    'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with' => 'El campo :attribute debe terminar con uno de los siguientes: :values',
    'exists' => 'El campo :attribute seleccionado es inválido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tener un valor.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file' => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor que :value caracteres.',
        'array' => 'El campo :attribute debe tener más de :value elementos.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser mayor o igual que :value.',
        'file' => 'El campo :attribute debe ser mayor o igual que :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor o igual que :value caracteres.',
        'array' => 'El campo :attribute debe tener :value elementos o más.',
    ],
    'image' => 'El campo :attribute debe ser una imagen.',
    'in' => 'El campo :attribute seleccionado es inválido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => 'El campo :attribute debe ser un entero.',
    'ip' => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El campo :attribute debe ser una cadena JSON válida.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file' => 'El campo :attribute debe ser menor que :value kilobytes.',
        'string' => 'El campo :attribute debe ser menor que :value caracteres.',
        'array' => 'El campo :attribute debe tener menos de :value elementos.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser menor o igual que :value.',
        'file' => 'El campo :attribute debe ser menor o igual que :value kilobytes.',
        'string' => 'El campo :attribute debe ser menor o igual que :value caracteres.',
        'array' => 'El campo :attribute no debe tener más de :value elementos.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'file' => 'El campo :attribute no puede ser mayor que :max kilobytes.',
        'string' => 'El campo :attribute no puede ser mayor que :max caracteres.',
        'array' => 'El campo :attribute no puede tener más de :max elementos.',
    ],
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser de al menos :min.',
        'file'    => 'El campo :attribute debe ser de al menos :min kilobytes.',
        'string'  => 'El campo :attribute debe ser de al menos :min carácteres.',
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'not_in' => 'El campo :attribute seleccionado es inválido.',
    'not_regex' => 'El formato del campo :attribute no es válido.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'present' => 'El campo :attribute debe estar presente.',
    'regex' => 'El formato del campo :attribute no es válido.',
    'required' => 'El campo :attribute es requerido.',
    'required_if' => 'El campo :attribute es requerido cuando :other es :value.',
    'required_unless' => 'El campo :attribute es requerido a menos que :other esté en :values.',
    'required_with' => 'El campo :attribute es requerido cuando :values está presente.',
    'required_with_all' => 'El campo :attribute es requerido cuando :values está presente.',
    'required_without' => 'El campo :attribute es requerido cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es requerido cuando ningún :values está presente.',
    'same' => 'Los campos :attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El largo del campo :attribute debe ser :size.',
        'file' => 'El campo :attribute debe tener :size kilobytes.',
        'string' => 'El campo :attribute debe tener :size caracteres.',
        'array' => 'El campo :attribute debe contener :size elementos.',
    ],
    'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes: :values',
    'string' => 'El campo :attribute debe ser una cadena.',
    'timezone' => 'El campo :attribute debe ser una zona válida.',
    'unique' => 'El campo :attribute ya ha sido tomado.',
    'uploaded' => 'El campo :attribute no se pudo cargar.',
    'url' => 'El formato del campo :attribute no es válido.',
    'uuid' => 'El campo :attribute debe ser un UUID válido.',
    //rut val
    'valida_rut' => 'El :attribute ingresado no es válido',
    'valida_rut_institucion' => 'El :attribute ingresado no es válido',
    'cantidadConMax' => 'El campo :attribute tiene demasiados elementos.',
    'cantidad_con_max' => 'El campo :attribute tiene demasiados elementos.',
    'unique_rut_inst'  => 'El :attribute ya existente en sistema',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'pers_nrut' => [
            'unique' => 'El :attribute ya existente en sistema',
        ],
        'sigla_concurso' => [
            'unique' => 'El :attribute ya existente en sistema',
        ],
        'nombre_usuario' => [
            'unique' => 'El :attribute ingresado ya existe en sistema',
        ],
        'gls_rol' => [
            'unique' => 'El :attribute ingresado ya existe en sistema',
        ],
        'email' => [
            'email' => 'El :attribute debe tener el siguiente formato: xxx@xxx.xx',
            'regex' => 'El :attribute debe tener el siguiente formato: xxx@xxx.xx',
            //'unique' => 'El campo :attribute ya existente en sistema',
        ],
        'palabra_busqueda' => [
            'string' => 'Debe ingresar primer nombre y/o apellido paterno',
        ],
        'anio_ive' => [
            'unique' => 'Ya existe un %IVE para el año ingresado',
        ],
        'palabra_busqueda_concurso' =>  [
            'string' => 'Debe ingresar un nombre de concurso',
        ],
        'palabra_busqueda_rbd'                  => [
            'required' => 'Debe ingresar Nombre establecimiento o código RBD'
        ],
        'cod_accion' => [
            'required' => 'Debe seleccionar al menos una Acción',
            'required_with' => 'Debe seleccionar una Acción',
        ],
        'email_persona' => [
            'email' => 'El :attribute debe tener el siguiente formato: xxx@xxx.xx',
            'regex' => 'El :attribute debe tener el siguiente formato: xxx@xxx.xx',
        ],
        'cod_tematica_principal' => [
            'cantidad_con_max' => 'El campo :attribute no puede tener más de 2 elementos.'
        ],
        'email_institucion' => [
            'email' => 'El campo :attribute debe tener el siguiente formato: xxx@xxx.xx',
            'regex' => 'El campo :attribute debe tener el siguiente formato: xxx@xxx.xx'
        ],
        'palabra_busqueda_institucion' =>  [
            'string' =>'Debe ingresar :attribute',
        ],
        'palabra_busqueda_proyecto' =>  [
            'string' =>'Debe ingresar :attribute',
        ],
        'fono_institucion' => [
            'min' => ':attribute debe contener como mínimo 9 caracteres y máximo 11',
            'max' => ':attribute debe contener como mínimo 9 caracteres y máximo 11',
        ],
        'gls_institucion' => [
            'unique' => 'El :attribute ingresado ya existe en sistema',
        ],
        'cod_institucion' => [
            'unique' => 'La :attribute ingresada ya existe en sistema',
        ],
        'tiene_apoyo_red_inst' => [
            'required' => 'Tipo de Asesoría es requerido',
        ],
        'sigla_proyecto' => [
            'unique' => 'La :attribute ingresada ya existe en sistema',
        ],
        'cod_establecimiento' => [
            'unique' => 'El :attribute ingresado, no se encuentra registrado en sistema. Debe ser ingresado en sección RBD',
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'fecha_ini' => 'Fecha Desde',
        'fecha_fin' => 'Fecha Hasta',
        'pers_nrut' => 'Rut/DNI',
        'rut' => 'Rut/DNI',
        'pers_tnombres' => 'Nombres',
        'pers_tpaterno' => 'Apellido Paterno',
        'pers_tmaterno' => 'Apellido Materno',
        'gene_ncod' => 'Género',
        'prev_ncod' => 'Previsión',
        'pers_tcorreo' => 'Correo electrónico',
        'pers_fnacimiento' => 'Fecha de nacimiento',
        'pers_tdireccion' => 'Dirección',
        'pers_nrut_medico' => 'Médico',
        'agen_finicio' => 'Fecha de consulta',
        'agen_ftermino' => 'Fecha de término',
        'agen_hinicio' => 'Hora de consulta',
        'pers_nrut_paciente' => 'Paciente',
        'tiat_ncod' => 'Tipo atención',
        'tipa_ncod' => 'Tipo paciente'
        ,'tido_ncod' => 'Tipo documento'
        ,'tiin_ncod' => 'Tipo informe'
        ,'pein_tinforme' => 'Informe'
        ,'pein_tnombre' => 'Nombre informe'
    ],

];
