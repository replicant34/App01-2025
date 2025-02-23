<?php
// Define access levels for each field in the order form
return [
    'form_fields' => [
        'admin' => [
            'all' => true  // Admin has access to all fields
        ],
        'ceo' => [
            'view' => ['*'],  // Can view all fields
            'edit' => [
                'cargo_price',
                'rate',
                'insurance_price',
                'total_price_vehicle',
                'comment',
                'contracts',
                // ... other fields CEO can edit
            ]
        ],
        'operator' => [
            'view' => [
                'order_date',
                'shipping_type',
                'cargo_type',
                'contracts',
                // ... fields operator can view
            ],
            'edit' => [
                'shipping_type',
                'vehicle_type',
                'weight',
                'contracts',
                // ... fields operator can edit
            ]
        ],
        'client' => [
            'view' => [
                'order_date',
                'shipping_type',
                'cargo_type',
                'client_contracts',
                // ... fields client can view
            ],
            'edit' => [
                'cargo_type',
                'weight',
                'volume',
                // ... fields client can edit
            ]
        ]
    ]
]; 