<?php


return [
    'ADMINISTRATOR' => [
        'code' => '001',
        'description' => 'Administrador',
        'sub_modules' => [
            'AGENTS' => [
                'code' => '01',
                'description' => 'Agentes',
                'sub_modules' => [
                    'AGENTS_VIEW' => [
                        'code' => '01',
                        'description' => 'Listar'
                    ],
                    'AGENTS_EDIT' => [
                        'code' => '02',
                        'description' => 'Editar'
                    ],
                    'AGENTS_CREATE' => [
                        'code' => '03',
                        'description' => 'Nuevo'
                    ],
                    'AGENTS_DELETE' => [
                        'code' => '04',
                        'description' => 'Borrar'
                    ],
                ],
            ],
        ],
    ],
];

