<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Последующие языковые строки содержат сообщения по-умолчанию, используемые
    | классом, проверяющим значения (валидатором). Некоторые из правил имеют
    | несколько версий, например, size. Вы можете поменять их на любые
    | другие, которые лучше подходят для вашего приложения.
    |
    */


    "accepted"       => "Вы должны принять :attribute.",
    "accepted_if"    => "The :attribute field must be accepted when :other is :value.",
    "active_url"     => "Поле :attribute недействительный URL.",
    "after"          => "Поле :attribute должно быть датой после :date.",
    "after_or_equal" => "The :attribute field must be a date after or equal to :date.",
    "alpha"          => "Поле :attribute может содержать только буквы.",
    "alpha_dash"     => "Поле :attribute может содержать только буквы, цифры и дефис.",
    "alpha_num"      => "Поле :attribute может содержать только буквы и цифры.",
    "array"          => "Поле :attribute должно быть массивом.",
    "ascii"          => "The :attribute field must only contain single-byte alphanumeric characters and symbols.",


    "before"          => "Поле :attribute должно быть датой перед :date.",
    'before_or_equal' => 'The :attribute field must be a date before or equal to :date.',
    "between"         => [
        "array"   => "Поле :attribute должно содержать :min - :max элементов.",
        "file"    => "Размер :attribute должен быть от :min до :max Килобайт.",
        "numeric" => "Поле :attribute должно быть между :min и :max.",
        "string"  => "Длина :attribute должна быть от :min до :max символов.",
    ],
    "boolean" => "The :attribute field must be true or false.",

    "confirmed"        => "Поле :attribute не совпадает с подтверждением.",
    'can'              => 'The :attribute field contains an unauthorized value.',
    'current_password' => 'The password is incorrect.',

    "date"              => "Поле :attribute не является датой.",
    "date_equals"       => "The :attribute field must be a date equal to :date.",
    "date_format"       => "Поле :attribute не соответствует формату :format.",
    "decimal"           => "The :attribute field must have :decimal decimal places.",
    "declined"          => "The :attribute field must be declined.",
    "declined_if"       => "The :attribute field must be declined when :other is :value.",
    "different"         => "Поля :attribute и :other должны различаться.",
    "digits"            => "Длина цифрового поля :attribute должна быть :digits.",
    "digits_between"    => "Длина цифрового поля :attribute должна быть между :min и :max.",
    "dimensions"        => "The :attribute field has invalid image dimensions.",
    "distinct"          => "The :attribute field has a duplicate value.",
    "doesnt_end_with"   => "The :attribute field must not end with one of the following: :values.",
    "doesnt_start_with" => "The :attribute field must not start with one of the following: :values.",

    "email"      => "Поле :attribute должно быть действительным электронным адресом.",
    "exists"     => "Выбранное значение для :attribute уже существует.",
    'ends_with'  => 'The :attribute field must end with one of the following: :values.',
    'enum'       => 'The selected :attribute is invalid.',
    "extensions" => "Поле :attribute должно иметь одно из расширений: :values.",

    'file'   => 'The :attribute field must be a file.',
    'filled' => 'The :attribute field must have a value.',

    'gt' => [
        'array'   => 'The :attribute field must have more than :value items.',
        'file'    => 'The :attribute field must be greater than :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than :value.',
        'string'  => 'The :attribute field must be greater than :value characters.',
    ],
    'gte' => [
        'array'   => 'The :attribute field must have :value items or more.',
        'file'    => 'The :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than or equal to :value.',
        'string'  => 'The :attribute field must be greater than or equal to :value characters.',
    ],

    "image"    => "Поле :attribute должно быть изображением.",
    "in"       => "Выбранное значение для :attribute ошибочно.",
    'in_array' => 'The :attribute field must exist in :other.',
    "integer"  => "Поле :attribute должно быть целым числом.",
    "ip"       => "Поле :attribute должно быть действительным IP-адресом.",
    'ipv4'     => 'The :attribute field must be a valid IPv4 address.',
    'ipv6'     => 'The :attribute field must be a valid IPv6 address.',

    'json' => 'The :attribute field must be a valid JSON string.',

    'lowercase' => 'The :attribute field must be lowercase.',
    'lt' => [
        'array'   => 'The :attribute field must have less than :value items.',
        'file'    => 'The :attribute field must be less than :value kilobytes.',
        'numeric' => 'The :attribute field must be less than :value.',
        'string'  => 'The :attribute field must be less than :value characters.',
    ],
    'lte' => [
        'array'   => 'The :attribute field must not have more than :value items.',
        'file'    => 'The :attribute field must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be less than or equal to :value.',
        'string'  => 'The :attribute field must be less than or equal to :value characters.',
    ],

    'mac_address' => 'The :attribute field must be a valid MAC address.',
    "max"              => [
        "array"   => "Поле :attribute должно содержать не более :max элементов.",
        "file"    => "Поле :attribute должно быть не больше :max Килобайт.",
        "numeric" => "Поле :attribute должно быть не больше :max.",
        "string"  => "Поле :attribute должно быть не длиннее :max символов.",
    ],
    'max_digits' => 'The :attribute field must not have more than :max digits.',
    "mimes"      => "Поле :attribute должно быть файлом одного из типов: :values.",
    'mimetypes'  => 'The :attribute field must be a file of type: :values.',
    "min"              => [
        "array"   => "Поле :attribute должно содержать не менее :min элементов.",
        "file"    => "Поле :attribute должно быть не менее :min Килобайт.",
        "numeric" => "Поле :attribute должно быть не менее :min.",
        "string"  => "Поле :attribute должно быть не короче :min символов.",
    ],
    'min_digits'       => 'The :attribute field must have at least :min digits.',
    'missing'          => 'The :attribute field must be missing.',
    'missing_if'       => 'The :attribute field must be missing when :other is :value.',
    'missing_unless'   => 'The :attribute field must be missing unless :other is :value.',
    'missing_with'     => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'multiple_of'      => 'The :attribute field must be a multiple of :value.',

    "not_in"    => "Выбранное значение для :attribute ошибочно.",
    'not_regex' => 'The :attribute field format is invalid.',
    "numeric"   => "Поле :attribute должно быть числом.",

    'password' => [
        'letters'       => 'The :attribute field must contain at least one letter.',
        'mixed'         => 'The :attribute field must contain at least one uppercase and one lowercase letter.',
        'numbers'       => 'The :attribute field must contain at least one number.',
        'symbols'       => 'The :attribute field must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present'           => 'The :attribute field must be present.',
    'prohibited'        => 'The :attribute field is prohibited.',
    'prohibited_if'     => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits'         => 'The :attribute field prohibits :other from being present.',

    "regex"                => "Поле :attribute имеет ошибочный формат.",
    "required"             => "Поле :attribute обязательно для заполнения.",
    "required_array_keys"  => "The :attribute field must contain entries for: :values.",
    "required_if"          => "Поле :attribute обязательно для заполнения, когда :other равно :value.",
    "required_if_accepted" => "The :attribute field is required when :other is accepted.",
    "required_unless"      => "The :attribute field is required unless :other is in :values.",
    "required_with"        => "Поле :attribute обязательно для заполнения, когда :values указано.",
    "required_with_all"    => "The :attribute field is required when :values are present.",
    "required_without"     => "Поле :attribute обязательно для заполнения, когда :values не указано.",
    "required_without_all" => "The :attribute field is required when none of :values are present.",

    "same" => "Значение :attribute должно совпадать с :other.",
    "size" => [
        "array"   => "Количество элементов в поле :attribute должно быть :size.",
        "numeric" => "Поле :attribute должно быть :size.",
        "file"    => "Поле :attribute должно быть :size Килобайт.",
        "string"  => "Поле :attribute должно быть длиной :size символов.",
    ],
    "starts_with" => "The :attribute field must start with one of the following: :values.",
    "string"      => "The :attribute field must be a string.",

    "timezone"    => "The :attribute field must be a valid timezone.",

    "unique"      => "Такое значение поля :attribute уже существует.",
    "uploaded"    => "The :attribute failed to upload.",
    "uppercase"   => "The :attribute field must be uppercase.",
    "url"         => "Поле :attribute должно быть действительным URL-адресом.",
    "ulid"        => "The :attribute field must be a valid ULID.",
    "uuid"        => "The :attribute field must be a valid UUID.",



    /*
    |--------------------------------------------------------------------------
    | Собственные языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Здесь Вы можете указать собственные сообщения для атрибутов, используя
    | соглашение именования строк "attribute.rule". Это позволяет легко указать
    | свое сообщение для заданного правила атрибута.
    |
    | http://laravel.com/docs/validation#custom-error-messages
    |
    */

    'custom' => [
        'phone_number' => [
            'required'  => 'Нам нужен ваш номер телефона!',
            'incorrect' => 'Введён неправильный номер телефона!'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Собственные названия атрибутов
    |--------------------------------------------------------------------------
    |
    | Последующие строки используются для подмены программных имен элементов
    | пользовательского интерфейса на удобочитаемые. Например, вместо имени
    | поля "email" в сообщениях будет выводиться "электронный адрес".
    |
    | Пример использования
    |
    |   'attributes' => [
    |       'email' => 'электронный адрес',
    |   ]
    |
    */

    'attributes' => [
        'phone_number' => 'номер телефона',
    ],

];
