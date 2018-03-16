<?php

return [
    /*
     * The fallback type determines the type to use when the current one
     * is not available. You may change the value to correspond to any of
     * provided types
     */
    'fallback' => 'default',
    /*
     * List of taxes with their types ans percentages
     * You can add more types and percentages.
     */
    'taxes' => [
        'iva' => [
            'default' => 0.16,
            'retention' => -0.106667,
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
