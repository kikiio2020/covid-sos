<?php 
use App\Ask;
use App\Sos;

return [
    'ask' => [
        'status' => [
            Ask::STATUS_PENDING => 'Pending',
            Ask::STATUS_IN_PROGRESS => 'In Progress',
            Ask::STATUS_COMPLETED => 'Completed',
        ],
    ],
    'sos' => [
        'delivery_option' => [
            Sos::DELIVARY_OPTION_DOORFRONT => 'Door Front',
            Sos::DELIVARY_OPTION_INTERCOM => 'Intercom',
            Sos::DELIVARY_OPTION_OTHERS => 'Others',
        ],
        'payment_option' => [
            Sos::PAYMENT_OPTION_CASH => 'Cash',
            Sos::PAYMENT_OPTION_NOT_APPLICABLE => 'Not Applicable',
            Sos::PAYMENT_OPTION_OTHERS => 'Others',
            Sos::PAYMENT_OPTION_PAID_ONLINE => 'Paid Online',
        ],
    ] 
];

?>