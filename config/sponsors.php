<?php

/**
 * Ukurasa wa Wadhamini (/wadhamini): mawasiliano na vifurushi.
 * Badilisha bei na maelezo hapa; ukurasa unajisasisha wenyewe.
 */
return [
    'whatsapp' => env('SPONSOR_WHATSAPP', '255786936886'), // bila +
    'whatsapp_display' => env('SPONSOR_WHATSAPP_DISPLAY', '0786 936 886'),
    'email' => env('SPONSOR_EMAIL', 'baytulhadith@gmail.com'),

    'packages' => [
        [
            'key' => 'siku',
            'name' => 'Mdhamini wa Siku',
            'price' => 20000,
            'period' => 'siku 1',
            'featured' => false,
            'features' => [
                'Jina lako kwenye «Hadith ya Leo» kwa siku nzima',
                'Ujumbe mfupi wa mstari mmoja na kiungo chako',
                'Ripoti fupi ya idadi ya waliobofya',
            ],
        ],
        [
            'key' => 'wiki',
            'name' => 'Mdhamini wa Wiki',
            'price' => 40000,
            'period' => 'siku 7',
            'featured' => true,
            'features' => [
                'Logo na jina lako kwenye «Hadith ya Leo» kwa wiki nzima',
                'Nafasi kwenye ukurasa wa Changia na wa mada',
                'Ujumbe wako kwenye arifa ya hadith ya siku (pale inapowezekana)',
                'Ripoti ya wageni na waliobofya',
            ],
        ],
        [
            'key' => 'mwezi',
            'name' => 'Mdhamini Mkuu',
            'price' => 80000,
            'period' => 'mwezi 1',
            'featured' => false,
            'features' => [
                'Logo yako kwenye ukurasa wa mwanzo na sehemu ya chini ya kurasa zote',
                'Nafasi ya kwanza kwenye «Hadith ya Leo» na kurasa za mada',
                'Chapisho la shukrani kwenye mitandao yetu',
                'Ripoti kamili ya mwezi na ushauri wa matangazo',
            ],
        ],
    ],
];
