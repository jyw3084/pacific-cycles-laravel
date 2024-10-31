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

    'accepted'             => '必須接受此欄位',
    'active_url'           => '此欄位必須是可使用的URL地址',
    'after'                => '此欄位必須是在 :date 之後的日期',
    'alpha'                => '此欄位隻能包含英文字母',
    'alpha_dash'           => '此欄位隻能包含英文字母，數字和-',
    'alpha_num'            => '此欄位隻能包含英文字母和數字',
    'array'                => '此欄位必須是陣列',
    'before'               => '此欄位必須是在 :date. 之前的日期',
    'between'              => [
        'numeric' => '此欄位必須介於 :min 至 :max 之間',
        'file'    => '此欄位大小必須介於 :min kb 至 :max kb 之間',
        'string'  => '此欄位長度必須介於 :min 至 :max 之間',
        'array'   => '此欄位包含的長度必須介於 :min 至 :max 個之間',
    ],
    'boolean'              => '此欄位必須是 true 或 false',
    'confirmed'            => '此欄位必須一緻',
    'date'                 => '此欄位不是有效的日期',
    'date_format'          => '此欄位必須符合格式 :format',
    'different'            => '此欄位與 :other 必須不同',
    'digits'               => '此欄位必須是 :digits 位數',
    'digits_between'       => '此欄位的位數必須介於 :min 與 :max 之間',
    'distinct'             => '此欄位已存在',
    'email'                => '此欄位必須是有效的電子郵件位址',
    'exists'               => '此欄位須存在',
    'filled'               => '此欄位為必填',
    'image'                => '此欄位必須是圖片',
    'in'                   => '此欄位不是有效值',
    'in_array'             => '此欄位不存在於 :other',
    'integer'              => '此欄位必須是整數',
    'ip'                   => '此欄位必須是有效的 IP 位址',
    'json'                 => '此欄位必須是有效的 JSON 字串',
    'max'                  => [
        'numeric' => '此欄位不能大於 :max',
        'file'    => '此欄位的大小不能超過 :max kb',
        'string'  => '此欄位不能超過 :max 個字元',
        'array'   => '此欄位不能包含超過 :max 個',
    ],
    'mimes'                => '此欄位必須是一個 :values 檔案',
    'min'                  => [
        'numeric' => '此欄位不能小於 :min',
        'file'    => '此欄位的大小不能小於 :min kb',
        'string'  => '此欄位必須至少 :min 個字元',
        'array'   => '此欄位必須至少有 :min 個',
    ],
    'not_in'               => '此欄位是無效值',
    'numeric'              => '此欄位必須是數字',
    'present'              => '此欄位必須出現',
    'regex'                => '此欄位格式無效',
    'required'             => '此欄位為必填',
    'required_if'          => '當 :other 是 :value時，此欄位為必填',
    'required_unless'      => '除非 :other 在 :values 之中，此欄位為必填',
    'required_with'        => '當 :values 出現時，此欄位為必填',
    'required_with_all'    => '當 :values 出現時，此欄位為必填',
    'required_without'     => '當 :values 沒有出現時，此欄位為必填',
    'required_without_all' => '當 :values 沒有出現時，此欄位為必填',
    'same'                 => '此欄位與 :other 須相符',
    'size'                 => [
        'numeric' => '此欄位必須是 :size',
        'file'    => '此欄位必須是 :size kb',
        'string'  => '此欄位必須有 :size 個字元',
        'array'   => '此欄位必須包含 :size 個',
    ],
    'string'               => '此欄位必須是字串',
    'timezone'             => '此欄位必須是有效的時區',
    'unique'               => '此資料已存在',
    'uploaded'             => ':attribute 上傳失敗',
    'url'                  => '此欄位必須是有效的url',
    'uuid'                 => '此欄位必須是一個有效的 UUID.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
