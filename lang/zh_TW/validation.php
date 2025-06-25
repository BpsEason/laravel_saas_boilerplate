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

    'accepted'             => ':attribute 必須被接受。',
    'accepted_if'          => '當 :other 為 :value 時，:attribute 必須被接受。',
    'active_url'           => ':attribute 不是一個有效的網址。',
    'after'                => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal'       => ':attribute 必須是 :date 之後或相同的日期。',
    'alpha'                => ':attribute 只能包含字母。',
    'alpha_dash'           => ':attribute 只能包含字母、數字、破折號和底線。',
    'alpha_num'            => ':attribute 只能包含字母和數字。',
    'array'                => ':attribute 必須是一個陣列。',
    'ascii'                => ':attribute 只能包含單字節字母數字字符和符號。',
    'before'               => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal'      => ':attribute 必須是 :date 之前或相同的日期。',
    'between'              => [
        'array'   => ':attribute 必須在 :min 和 :max 之間有 :min 個項目。',
        'file'    => ':attribute 必須在 :min 和 :max KB 之間。',
        'numeric' => ':attribute 必須在 :min 和 :max 之間。',
        'string'  => ':attribute 必須在 :min 和 :max 個字元之間。',
    ],
    'boolean'              => ':attribute 字段必須為 true 或 false。',
    'can'                  => ':attribute 字段包含未經授權的值。',
    'confirmed'            => ':attribute 確認不匹配。',
    'current_password'     => '密碼不正確。',
    'date'                 => ':attribute 不是一個有效的日期。',
    'date_equals'          => ':attribute 必須是等於 :date 的日期。',
    'date_format'          => ':attribute 與格式 :format 不匹配。',
    'decimal'              => ':attribute 必須有 :decimal 位小數。',
    'declined'             => ':attribute 必須被拒絕。',
    'declined_if'          => '當 :other 為 :value 時，:attribute 必須被拒絕。',
    'different'            => ':attribute 和 :other 必須不同。',
    'digits'               => ':attribute 必須是 :digits 位數字。',
    'digits_between'       => ':attribute 必須在 :min 和 :max 位數字之間。',
    'dimensions'           => ':attribute 的圖像尺寸無效。',
    'distinct'             => ':attribute 字段有重複值。',
    'doesnt_contain'       => ':attribute 字段不能包含其中一個值。',
    'doesnt_start_with'    => ':attribute 字段不能以以下其中一個開頭。',
    'email'                => ':attribute 必須是有效的電子郵件地址。',
    'ends_with'            => ':attribute 必須以以下其中一個結尾: :values。',
    'enum'                 => '所選的 :attribute 無效。',
    'exists'               => '所選的 :attribute 無效。',
    'file'                 => ':attribute 必須是一個檔案。',
    'filled'               => ':attribute 字段必須有值。',
    'gt'                   => [
        'array'   => ':attribute 必須有超過 :value 個項目。',
        'file'    => ':attribute 必須大於 :value KB。',
        'numeric' => ':attribute 必須大於 :value。',
        'string'  => ':attribute 必須多於 :value 個字元。',
    ],
    'gte'                  => [
        'array'   => ':attribute 必須有 :value 個或更多項目。',
        'file'    => ':attribute 必須大於或等於 :value KB。',
        'numeric' => ':attribute 必須大於或等於 :value。',
        'string'  => ':attribute 必須多於或等於 :value 個字元。',
    ],
    'image'                => ':attribute 必須是圖像。',
    'in'                   => '所選的 :attribute 無效。',
    'in_array'             => ':attribute 字段在 :other 中不存在。',
    'integer'              => ':attribute 必須是一個整數。',
    'ip'                   => ':attribute 必須是有效的 IP 地址。',
    'ipv4'                 => ':attribute 必須是有效的 IPv4 地址。',
    'ipv6'                 => ':attribute 必須是有效的 IPv6 地址。',
    'json'                 => ':attribute 必須是有效的 JSON 字串。',
    'lowercase'            => ':attribute 必須是小寫。',
    'lt'                   => [
        'array'   => ':attribute 必須有少於 :value 個項目。',
        'file'    => ':attribute 必須小於 :value KB。',
        'numeric' => ':attribute 必須小於 :value。',
        'string'  => ':attribute 必須少於 :value 個字元。',
    ],
    'lte'                  => [
        'array'   => ':attribute 必須有 :value 個或更少項目。',
        'file'    => ':attribute 必須小於或等於 :value KB。',
        'numeric' => ':attribute 必須小於或等於 :value。',
        'string'  => ':attribute 必須少於或等於 :value 個字元。',
    ],
    'mac_address'          => ':attribute 必須是有效的 MAC 地址。',
    'max'                  => [
        'array'   => ':attribute 最多有 :max 個項目。',
        'file'    => ':attribute 最多 :max KB。',
        'numeric' => ':attribute 最多 :max。',
        'string'  => ':attribute 最多 :max 個字元。',
    ],
    'max_digits'           => ':attribute 必須最多有 :max 位數字。',
    'mimes'                => ':attribute 必須是類型為: :values 的檔案。',
    'mimetypes'            => ':attribute 必須是類型為: :values 的檔案。',
    'min'                  => [
        'array'   => ':attribute 至少有 :min 個項目。',
        'file'    => ':attribute 至少 :min KB。',
        'numeric' => ':attribute 至少 :min。',
        'string'  => ':attribute 至少 :min 個字元。',
    ],
    'min_digits'           => ':attribute 必須至少有 :min 位數字。',
    'missing'              => ':attribute 字段必須缺失。',
    'missing_if'           => '當 :other 為 :value 時，:attribute 字段必須缺失。',
    'missing_unless'       => '除非 :other 為 :value，否則 :attribute 字段必須缺失。',
    'missing_with'         => '存在 :values 時，:attribute 字段必須缺失。',
    'missing_with_all'     => '存在所有 :values 時，:attribute 字段必須缺失。',
    'multiple_of'          => ':attribute 必須是 :value 的倍數。',
    'not_in'               => '所選的 :attribute 無效。',
    'not_regex'            => ':attribute 格式無效。',
    'numeric'              => ':attribute 必須是數字。',
    'password'             => [
        'letters'       => ':attribute 必須至少包含一個字母。',
        'mixed'         => ':attribute 必須至少包含一個大寫字母和一個小寫字母。',
        'numbers'       => ':attribute 必須至少包含一個數字。',
        'symbols'       => ':attribute 必須至少包含一個符號。',
        'uncompromised' => '給定的 :attribute 出現在資料洩露中。請選擇不同的 :attribute。',
    ],
    'present'              => ':attribute 字段必須存在。',
    'prohibited'           => ':attribute 字段被禁止。',
    'prohibited_if'        => '當 :other 為 :value 時，:attribute 字段被禁止。',
    'prohibited_unless'    => '除非 :other 在 :values 中，否則 :attribute 字段被禁止。',
    'prohibits'            => ':attribute 字段禁止 :other 存在。',
    'regex'                => ':attribute 格式無效。',
    'required'             => ':attribute 欄位是必填的。',
    'required_array_keys'  => ':attribute 字段必須包含以下條目：:values。',
    'required_if'          => '當 :other 為 :value 時，:attribute 欄位是必填的。',
    'required_if_accepted' => '當 :other 被接受時，:attribute 欄位是必填的。',
    'required_unless'      => '除非 :other 在 :values 中，否則 :attribute 欄位是必填的。',
    'required_with'        => '存在 :values 時，:attribute 欄位是必填的。',
    'required_with_all'    => '存在所有 :values 時，:attribute 欄位是必填的。',
    'required_without'     => '不存在 :values 時，:attribute 欄位是必填的。',
    'required_without_all' => '不存在任何 :values 時，:attribute 欄位是必填的。',
    'same'                 => ':attribute 和 :other 必須匹配。',
    'size'                 => [
        'array'   => ':attribute 必須包含 :size 個項目。',
        'file'    => ':attribute 必須是 :size KB。',
        'numeric' => ':attribute 必須是 :size。',
        'string'  => ':attribute 必須是 :size 個字元。',
    ],
    'starts_with'          => ':attribute 必須以以下其中一個開頭: :values。',
    'string'               => ':attribute 必須是一個字串。',
    'timezone'             => ':attribute 必須是有效的時區。',
    'unique'               => ':attribute 已經存在。',
    'uploaded'             => ':attribute 上傳失敗。',
    'uppercase'            => ':attribute 必須是大寫。',
    'url'                  => ':attribute 必須是有效的網址。',
    'uuid'                 => ':attribute 必須是有效的 UUID。',

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
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more readable such as "E-Mail Address" instead of "email".
    | This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => '名稱',
        'email' => '電子郵件',
        'password' => '密碼',
        'password_confirmation' => '密碼確認',
        'tenant_name' => '租戶名稱',
        'tenant_domain' => '租戶域名',
        'price' => '價格',
        'stock' => '庫存',
        'customer_name' => '客戶名稱',
        'status' => '狀態',
        'items' => '訂單項目',
        'items.*.product_id' => '產品 ID',
        'items.*.quantity' => '數量',
    ],
];
