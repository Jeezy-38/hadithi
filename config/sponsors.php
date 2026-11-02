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
                'Jina lako kwenye «Hadithi ya Leo» (ukurasa wa mwanzo) kwa siku nzima',
                'Ujumbe mfupi na kiungo chako (tovuti au WhatsApp)',
                'Idadi ya waliobofya kiungo chako',
            ],
        ],
        [
            'key' => 'wiki',
            'name' => 'Mdhamini wa Wiki',
            'price' => 40000,
            'period' => 'siku 7',
            'featured' => true,
            'features' => [
                'Jina, logo na ujumbe wako kwenye «Hadithi ya Leo» kwa wiki nzima',
                'Kiungo cha moja kwa moja kwa wateja wako',
                'Ripoti ya idadi ya waliobofya',
            ],
        ],
        [
            'key' => 'mwezi',
            'name' => 'Mdhamini Mkuu',
            'price' => 80000,
            'period' => 'mwezi 1',
            'featured' => false,
            'features' => [
                'Jina, logo na ujumbe wako kwenye «Hadithi ya Leo» kwa mwezi mzima',
                'Chapisho la shukrani kwenye mitandao yetu',
                'Ripoti ya waliobofya ya mwezi na ushauri wa matangazo',
            ],
        ],
    ],
];
