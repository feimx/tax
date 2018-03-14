<?php

return [
    'fallback' => 'default',

    'taxes' => [
        'iva' => [
            'default' => 0.16,
            'retention' => -0.16,
        ],
        'isr' => [
            'default' => -0.106667,
        ],
        'ieps' => [
            'default' => 0.08,
            'retention' => -0.08,
            'primary' => 0.11,
            'secondary' => 0.13,
        ],
    ],
];
